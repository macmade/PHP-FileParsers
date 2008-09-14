<?php

/**
 * PNG pHYs chunk (physical pixel dimensions)
 * 
 * The pHYs chunk specifies the intended pixel size or aspect ratio for
 * display of the image.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Fp_Png_Chunk_Phys extends Fp_Png_Chunk
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
    protected $_type = 'pHYs';
    
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
        $data                = new stdClass();
        
        // Gets the pixel aspect ratio
        $data->pixelPerUnitX = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 0 );
        $data->pixelPerUnitY = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        
        // Gets the unit
        $data->unit          = self::$_binUtils->unsignedChar( $this->_data, 8 );
        
        // Returns the processed data
        return $data;
    }
}
