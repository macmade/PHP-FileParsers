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
