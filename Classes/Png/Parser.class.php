<?php

/**
 * GIF file parser
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Png_Parser extends Parser_Base
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
     * An instance of the Mpeg4_File class
     */
    protected $Png_File              = NULL;
    
    /**
     * An array that will be filled with the PNG informations
     */
    protected $_pngInfos             = array();
    
    /**
     * Class constructor
     * 
     * @param   string          The location of the PNG file
     * @return  NULL
     */
    public function __construct( $file )
    {
        // Create a new instance of Png_File
        $this->_pngFile = new Png_File();
        
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
            throw new Png_Exception( 'File ' . $this->_filePath . ' is not a PNG file.' );
        }
        
        // Process the file till the end
        while( !feof( $this->_fileHandle ) ) {
            
            // Chunk header data
            $chunkHeaderData = $this->_read( 8 );
            
            // Gets the chunk size
            $chunkSize       = self::$_binUtils->bigEndianUnsignedLong( $chunkHeaderData );
            
            // Gets the chunk type
            $chunkType       = substr( $chunkHeaderData, 4 );
            
            // Adds the chunk
            $chunk           = $this->_pngFile->addChunk( $chunkType );
            
            // Storage for the chunk data
            $chunkData       = '';
            
            // Checks for data
            if( $chunkSize > 0 ) {
                
                // Gets the chunk data
                $chunkData = $this->_read( $chunkSize );
                
                // Stores the raw data
                $chunk->setRawData( $chunkData );
            }
            
            // Gets the cyclic redundancy check
            $crcData = $this->_read( 4 );
            $crc     = self::$_binUtils->bigEndianUnsignedLong( $crcData );
            
            // Checks the CRC
            if( $crc !== crc32( $chunkType . $chunkData ) ) {
                
                // Invalid CRC
                throw new Png_Exception( 'Invalid cyclic redundancy check for chunk ' . $chunkType );
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
}
