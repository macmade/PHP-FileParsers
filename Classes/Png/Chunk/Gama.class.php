<?php

/**
 * PNG gAMMA chunk (image gamma)
 * 
 * The gAMA chunk specifies the relationship between the image samples and the
 * desired display output intensity.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Gama extends Png_Chunk
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
    protected $_type = 'gAMMA';
    
    /**
     * Process the chunk data
     * 
     * This method will process the chunk raw data and returns human readable
     * values, stored as properties of an stdClass object. Please take a look
     * at the PNG specification for this specific chunk to see which data will
     * be extracted.
     * 
     * @return  stdClass    The human readable chunk data
     */
    public function getProcessedData()
    {
        // Storage
        $data             = new stdClass();
        
        // Gets the image gamma
        $data->imageGamma = self::$_binUtils->bigEndianUnsignedLong( $this->_data ) / 100000;
        
        // Returns the processed data
        return $data;
    }
}
