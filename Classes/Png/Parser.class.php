<?php

/**
 * GIF file parser
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Png_Parser
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
     * The PHP file handler for the PNG file
     */
    protected $_fileHandle = NULL;
    
    /**
     * An array that will be filled with the PNG informations
     */
    protected $_pngInfos   = array();
    
    /**
     * The file path
     */
    protected $_filePath   = '';
    
    /**
     * Class constructor
     * 
     * @param   string      The location of the PNG file
     * @return  NULL
     * @throws  Exception   If the file does not exist, is not readable, or if PHP isn't able to open a file handle
     */
    public function __construct( $file )
    {
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
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Parses the file and stores the informations
        $this->_parseFile();
        
        // Closes the file handle
        fclose( $this->_fileHandle );
    }
    
    /**
     * 
     */
    protected function _readBigEndianUnsignedLong()
    {
        $data = unpack( 'N', fread( $this->_fileHandle, 4 ) );
        return array_shift( $data );
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
        if( fread( $this->_fileHandle, 8 ) !== $signature ) {
            
            // Wrong file type
            throw new Exception( 'File ' . $this->_filePath . ' is not a PNG file.' );
        }
        
        // Process the file till the end
        while( !feof( $this->_fileHandle ) ) {
            
            // Storage
            $chunk       = new stdClass();
            
            // Gets the chunk size
            $chunk->size = $this->_readBigEndianUnsignedLong();
            
            // Gets the chunk type
            $chunk->type = fread( $this->_fileHandle, 4 );
            
            // Checks for data
            if( $chunk->size > 0 ) {
                
                // Do not process the data at this time
                fseek( $this->_fileHandle, $chunk->size, SEEK_CUR );
            }
            
            // Gets the cyclic redundancy check
            $chunk->crc = $this->_readBigEndianUnsignedLong();
            
            // Adds the current chunk
            $this->_pngInfos[] = $chunk;
            
            // Checks if the current chunk is the PNG terminator chunk
            if( $chunk->type === 'IEND' ) {
                
                // No more chunks
                break;
            }
        }
    }
    
    /**
     * 
     */
    public function getInfos()
    {
        return $this->_pngInfos;
    }
}
