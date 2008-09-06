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
     * An array that will be filled with the PNG informations
     */
    protected $_pngInfos   = array();
    
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
            
            // Storage
            $chunk           = new stdClass();
            
            // Chunk header data
            $chunkHeaderData = $this->_read( 8 );
            
            // Gets the chunk size
            $chunk->size     = self::$_binUtils->bigEndianUnsignedLong( $chunkHeaderData );
            
            // Gets the chunk type
            $chunk->type     = substr( $chunkHeaderData, 4 );
            
            // Checks for data
            if( $chunk->size > 0 ) {
                
                // Do not process the data at this time
                fseek( $this->_fileHandle, $chunk->size, SEEK_CUR );
            }
            
            // Gets the CRC data
            $crcData           = $this->_read( 4 );
            
            // Gets the cyclic redundancy check
            $chunk->crc        = self::$_binUtils->bigEndianUnsignedLong( $crcData );
            
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
