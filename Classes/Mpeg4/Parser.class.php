<?php

class Mpeg4_Parser
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected static $_errorLevel = false;
    protected $_fileHandle        = NULL;
    protected $_mpeg4File         = NULL;
    protected $_warnings          = array();
    
    public function __construct( $file )
    {
        if( !file_exists( $file ) ) {
            
            throw new Exception( 'The requested file ' . $file . ' does not exist.' );
        }
        
        if( !is_readable( $file ) ) {
            
            throw new Exception( 'The requested file ' . $file . ' is not readable.' );
        }
        
        $this->_fileHandle = fopen( $file, 'rb' );
        
        if( !$this->_fileHandle ) {
            
            throw new Exception( 'Cannot open requested file ' . $file . '.' );
        }
        
        $this->_mpeg4File = new Mpeg4_File();
        $this->_parseFile();
        
        fclose( $this->_fileHandle );
    }
    
    private function _parseFile( $bytes = 0, $level = 0, $parent = NULL )
    {
        $bytesRead = 0;
        
        while( $chunk = fread( $this->_fileHandle, 8 ) ) {
            
            $atomLength     = unpack( 'N', substr( $chunk, 0, 4 ) );
            $atomLength     = $atomLength[ 1 ];
            $atomType       = substr( $chunk, 4 );
            $atomDataLength = $atomLength - 8;
            $atomObject     = NULL;
            
            if( $atomType === 'xml ' ) {
                
                $className = 'Mpeg4_Atom_Xml';
                
            } else {
                
                $className = 'Mpeg4_Atom_' . ucfirst( $atomType );
            }
            
            if( $level == 0 ) {
                
                if( $this->_mpeg4File->validChildType( $atomType ) && class_exists( $className ) ) {
                    
                    $atomObject = $this->_mpeg4File->addChild( $atomType );
                    
                } else {
                    
                    $this->_warnings[] = array(
                        'atomType'   => $atomType,
                        'atomLength' => $atomLength,
                        'fileOffset' => ftell( $this->_fileHandle ) - 8,
                        'level'      => $level,
                        'hierarchy'  => ''
                    );
                }
                
            } elseif( $parent && $parent->validChildType( $atomType ) && class_exists( $className ) ) {
                
                $atomObject = $parent->addChild( $atomType );
                
            } else {
                
                $this->_warnings[] = array(
                    'atomType'   => $atomType,
                    'atomLength' => $atomLength,
                    'fileOffset' => ftell( $this->_fileHandle ) - 8,
                    'level'      => $level,
                    'hierarchy'  => ( $parent ) ? implode( ' / ', $parent->getHierarchy() ) : ''
                );
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
    
    public function getMpeg4File()
    {
        return $this->_mpeg4File;
    }
    
    public function getWarnings()
    {
        return $this->_warnings;
    }
}
