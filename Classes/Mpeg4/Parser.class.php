<?php

/**
 * MPEG-4 file parser
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4
 * @version         0.3
 */
class Mpeg4_Parser
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.3';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    // File handler
    protected $_fileHandle           = NULL;
    
    // Instance of Mpeg4_File
    protected $_mpeg4File            = NULL;
    
    // Parsing warnings/errors
    protected $_warnings             = array();
    
    // Allows invalid atom hierarchy (not as in ISO-IEC 14496-12)
    protected $_allowInvalidStucture = false;
    
    // Allows unrecognized atoms (not in ISO-IEC 14496-12)
    protected $_allowUnknownAtoms    = false;
    
    /**
     * Class constructor
     * 
     * @param   string  $file                   The location of the MPEG-4 file
     * @param   boolean $allowInvalidStucture   Allows invalid atom hierarchy (not as in ISO-IEC 14496-12)
     * @param   boolean $allowUnknownAtoms      Allows unrecognized atoms (not in ISO-IEC 14496-12)
     * @return  NULL
     * @throws  Exception   If the file does not exist, is not readable, or if PHP isn't able to open a file handle
     */
    public function __construct( $file, $allowInvalidStucture = false, $allowUnknownAtoms = false )
    {
        // Sets the options for the current instance
        $this->_allowInvalidStucture = $allowInvalidStucture;
        $this->_allowUnknownAtoms    = $allowUnknownAtoms;
        
        // Checks if the requested file exists
        if( !file_exists( $file ) ) {
            
            // File does not exist
            throw new Exception( 'The requested file ' . $file . ' does not exist.' );
        }
        
        // Checks if the requested file can be read
        if( !is_readable( $file ) ) {
            
            // Unreadable file
            throw new Exception( 'The requested file ' . $file . ' is not readable.' );
        }
        
        // Opens a binary file hander
        $this->_fileHandle = fopen( $file, 'rb' );
        
        // Checks the file handler
        if( !$this->_fileHandle ) {
            
            // Invalid file handler
            throw new Exception( 'Cannot open requested file ' . $file . '.' );
        }
        
        // Create a new instance of Mpeg4_File
        $this->_mpeg4File = new Mpeg4_File();
        
        // Parses the file
        $this->_parseFile();
        
        // Closes the file handle
        fclose( $this->_fileHandle );
    }
    
    private function _parseFile( $bytes = 0, $level = 0, $parent = NULL )
    {
        // Number of bytes read in the current parsing level
        $bytesRead = 0;
        
        // Reads 8 bytes of the MPEG-4 files till the end of the file
        // 8 bytes is the atom length and the atom type
        while( $chunk = fread( $this->_fileHandle, 8 ) ) {
            
            // Gets the atom length
            $atomLength     = unpack( 'N', substr( $chunk, 0, 4 ) );
            $atomLength     = $atomLength[ 1 ];
            
            // Gets the atom type
            $atomType       = substr( $chunk, 4 );
            
            // Gets the atom data length
            $atomDataLength = $atomLength - 8;
            
            // Storage for the current atom
            $atomObject     = NULL;
            
            // Special case for the XML atom, as it's only 3 characters
            if( $atomType === 'xml ' ) {
                
                // Name of the atom class to use
                $className = 'Mpeg4_Atom_Xml';
                
            } else {
                
                // Name of the atom class to use
                $className = 'Mpeg4_Atom_' . ucfirst( $atomType );
            }
            
            // Checks the parsing level (top or not)
            if( $level === 0 ) {
                
                // Parent is the file itself for the top-level atoms
                $parent = $this->_mpeg4File;
            }
            
            // Checks if the current atom can be inserted in the parent, and if the atom class exists
            $validAtom          = $parent->validChildType( $atomType );
            
            if( !$validAtom ) {
                
                $errorMsg = ( $level === 0 ) ? 'Atom ' . $atomType . ' cannot be stored as a top-level atom' : 'Atom ' . $atomType . ' cannot be stored in atom ' . $parent->getType();
                
                // Adds a warning
                $this->_warnings[] = array(
                    'atomType'   => $atomType,
                    'atomLength' => $atomLength,
                    'fileOffset' => ftell( $this->_fileHandle ) - 8,
                    'parseLevel'      => $level,
                    'hierarchy'  => ( $level === 0 ) ? '' : implode( ' / ', $parent->getHierarchy() ),
                    'message'    => $errorMsg
                );
                
                if( $this->_allowInvalidStucture ) {
                    
                    $parent->allowAnyChildrenType( true );
                    $atomObject = $parent->addChild( $atomType );
                    $parent->allowAnyChildrenType( false );
                }
                
            } else {
                
                $atomObject = $parent->addChild( $atomType );
            }
            
            if( $atomLength === 0 ) {
                
                return false;
                
            } elseif( $atomLength === 1 ) {
                
                $extendedSize     = unpack( 'Nlength1/Nlenght2', fread( $this->_fileHandle, 8 ) );
                $atomSize         = ( double )( ( $length[ 'length1' ] << 32 ) + $length[ 'length2' ] );
                $atomDataLength   = $atomDataLength + 8;
                
                if( $atomObject ) {
                    
                    $atomObject->setExtended( true );
                }
            }
            
            if( $atomDataLength ) {
                
                if( $atomObject && is_subclass_of( $atomObject, 'Mpeg4_ContainerAtom' ) ) {
                    
                    $this->_parseFile( $atomDataLength, $level + 1, $atomObject );
                    
                } else {
                    
                    $readData       = true;
                    $dataBytesCount = 0;
                    $letters        = array();
                    $binData        = '';
                    $data           = fread( $this->_fileHandle, $atomDataLength );
                    
                    if( $atomObject ) {
                        
                        $atomObject->setRawData( $data );
                    }
                }
            }
                
            $bytesRead += $atomLength;
                    
            if( $bytes > 0 && $bytes == $bytesRead ) {
                
                return false;
            }
        }
    }
    
    /**
     * Gets the Mpeg4_File instance
     * 
     * @return  object  The instance of Mpeg4_File
     */
    public function getMpeg4File()
    {
        return $this->_mpeg4File;
    }
    
    /**
     * Gets the parsing errors/warnings
     * 
     * @return  array   An array with the parsing errors/warnings
     */
    public function getWarnings()
    {
        return $this->_warnings;
    }
}
