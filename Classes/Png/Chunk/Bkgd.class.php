<?php

/**
 * PNG bKGd chunk (background colour)
 * 
 * The bKGD chunk specifies a default background colour to present the image
 * against. If there is any other preferred background, either user-specified
 * or part of a larger page (as in a browser), the bKGD chunk should be ignored.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Bkgd extends Png_Chunk
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
    protected $_type = 'bKGd';
    
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
        $data                                = new stdClass();
        $data->colourType0                   = new stdClass();
        $data->colourType2                   = new stdClass();
        $data->colourType3                   = new stdClass();
        $data->colourType4                   = new stdClass();
        $data->colourType6                   = new stdClass();
        
        // Gets the value for the coulour type 0
        $data->colourType0->greyscale        = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 0 );
        
        // Colour type 4 is the same as coulour type 0
        $data->colourType4->greyscale        = $data->colourType0->greyscale;
        
        // Gets the values for the coulour type 2
        $data->colourType2->redSampleValue   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 2 );
        $data->colourType2->greenSampleValue = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        $data->colourType2->blueSampleValue  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        
        // Gets the hexadecimal values
        $redHex                              = dechex( $data->colourType0->redSampleValue );
        $greenHex                            = dechex( $data->colourType0->greenSampleValue );
        $blueHex                             = dechex( $data->colourType0->blueSampleValue );
        
        // Completes each hexadecimal value if needed
        $redHex                              = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
        $greenHex                            = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
        $blueHex                             = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
        
        // Adds the hexadecimal color value to colour type 2
        $data->colourType2->hex              = '#' . strtoupper( $redHex . $greenHex . $blueHex );
        
        // Colour type 6 is the same as coulour type 2
        $data->colourType6->redSampleValue   = $data->colourType2->redSampleValue;
        $data->colourType6->greenSampleValue = $data->colourType2->greenSampleValue;
        $data->colourType6->blueSampleValue  = $data->colourType2->blueSampleValue;
        $data->colourType6->hex              = $data->colourType2->hex;
        
        // Gets the value for the coulour type 0
        $data->colourType3->paletteIndex     = self::$_binUtils->unsignedChar( $this->_data, 8 );
        
        // Returns the processed data
        return $data;
    }
}
