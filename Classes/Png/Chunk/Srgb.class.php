<?php

/**
 * PNG sRGB chunk
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Srgb extends Png_Chunk
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
     * Rendering intent values
     */
    const RGB_PERCEPTUAL            = 0x0;
    const RGB_RELATIVE_COLORIMETRIC = 0x1;
    const RGB_SATURATION            = 0x2;
    const RGB_ABSOLUTE_COLORIMETRIC = 0x3;
    
    /**
     * The chunk type
     */
    protected $_type = 'sRGB';
    
    /**
     * 
     */
    public function getProcessedData()
    {
        // Storage
        $data                  = new stdClass();
        
        // Gets the rendering intent
        $data->renderingIntent = self::$_binUtils->unsignedChar( $this->_data );
        
        // Stores the human readable rendering intent
        $data->perceptual           = ( $data->renderingIntent === self::RGB_PERCEPTUAL )            ? true : false;
        $data->relativeColorimetric = ( $data->renderingIntent === self::RGB_RELATIVE_COLORIMETRIC ) ? true : false;
        $data->saturation           = ( $data->renderingIntent === self::RGB_SATURATION )            ? true : false;
        $data->absoluteColorimetric = ( $data->renderingIntent === self::RGB_ABSOLUTE_COLORIMETRIC ) ? true : false;
        
        // Returns the processed data
        return $data;
    }
}
