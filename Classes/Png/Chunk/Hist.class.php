<?php

/**
 * PNG hIST chunk
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Hist extends Png_Chunk
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
     * The chunk type
     */
    protected $_type = 'hIST';
    
    /**
     * 
     */
    public function getProcessedData()
    {
        // Storage
        $data            = new stdClass();
        $data->frequency = array();
        
        // Process each frequency
        for( $i = 0; $i < $this->_dataLength; $i += 2 ) {
            
            // Adds the current frequency
            $data->frequency[] = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i );
        }
        
        // Returns the processed data
        return $data;
    }
}
