<?php

/**
 * GIF file parser
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Fp_Png_Parser extends Fp_Parser_Base
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'alpha';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * An instance of the Fp_Png_File class
     */
    protected $Png_File              = NULL;
    
    /**
     * Allows invalid chunk structure (not as in the PNG specification)
     */
    protected $_allowInvalidStucture = false;
    
    /**
     * An array that will be filled with the PNG informations
     */
    protected $_pngInfos             = array();
    
    /**
     * The parsing warnings/errors
     */
    protected $_warnings             = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The location of the PNG file
     * @return  NULL
     */
    public function __construct( $file, $allowInvalidStucture = false )
    {
        // Create a new instance of Png_File
        $this->_pngFile              = new Fp_Png_File();
        
        // Sets the options for the current instance
        $this->_allowInvalidStucture = $allowInvalidStucture;
        
        // Calls the parent constructor
        parent::__construct( $file );
    }
    
    /**
     * 
     */
    protected function _parseFile()
    {
        // The PNG file signature (\211   P   N   G  \r  \n \032 \n)
        $signature = chr( 137 ) . chr( 80 ) . chr( 78 ) . chr( 71 )
                   . chr( 13 )  . chr( 10 ) . chr( 26 ) . chr( 10 );
        
        // Checks the GIF signature
        if( $this->_read( 8 ) !== $signature ) {
            
            // Wrong file type
            throw new Fp_Png_Exception( 'File ' . $this->_filePath . ' is not a PNG file.', Fp_Png_Exception::EXCEPTION_BAD_SIGNATURE );
        }
        
        // Process the file till the end
        while( !feof( $this->_fileHandle ) ) {
            
            // Chunk header data
            $chunkHeaderData = $this->_read( 8 );
            
            // Gets the chunk size
            $chunkSize       = self::$_binUtils->bigEndianUnsignedLong( $chunkHeaderData );
            
            // Gets the chunk type
            $chunkType       = substr( $chunkHeaderData, 4 );
            
            // Checks if the chunk is valid or not
            $invalid = $this->_pngFile->isInvalidChunk( $chunkType );
            
            // Checks the invalid state
            if( $invalid ) {
                
                // Adds a warning
                $this->_warnings[] = array(
                    'chunkType'   => $chunkType,
                    'chunkLength' => $chunkSize,
                    'fileOffset'  => ftell( $this->_fileHandle ) - 8,
                    'message'     => $invalid
                );
                
                // Checks if we allows invalid chunks
                if( $this->_allowInvalidStucture ) {
                    
                    // Tells the PNG file class to not complains about bad chunks
                    $this->_pngFile->allowAnyChunkType( true );
                    
                    // Adds the chunk
                    $chunk = $this->_pngFile->addChunk( $chunkType );
                    
                } else {
                    
                    // No invalid chunk is allowed - The current chunk will be skipped
                    $chunk = false;
                }
                
            } else {
                
                // Chunk is valid - Adds it
                $chunk = $this->_pngFile->addChunk( $chunkType );
            }
            
            // Storage for the chunk data
            $chunkData       = '';
            
            // Checks for data
            if( $chunkSize > 0 ) {
                
                // Gets the chunk data
                $chunkData = $this->_read( $chunkSize );
                
                // Checks if the chunk object exists
                if( $chunk ) {
                    
                    // Stores the raw data
                    $chunk->setRawData( $chunkData );
                }
            }
            
            // Gets the cyclic redundancy check
            $crcData = $this->_read( 4 );
            $crc     = self::$_binUtils->bigEndianUnsignedLong( $crcData );
            
            // Checks the CRC
            if( $crc !== crc32( $chunkType . $chunkData ) ) {
                
                // Invalid CRC
                throw new Fp_Png_Exception( 'Invalid cyclic redundancy check for chunk ' . $chunkType, Fp_Png_Exception::EXCEPTION_BAD_CRC );
            }
            
            // Checks if the current chunk is the PNG terminator chunk
            if( $chunkType === 'IEND' ) {
                
                // No more chunks
                break;
            }
        }
    }
    
    /**
     * Gets the Png_File instance
     * 
     * @return  object  The instance of Png_File
     */
    public function getPngFile()
    {
        return $this->_pngFile;
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
