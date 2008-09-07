<?php

/**
 * PNG sBIT chunk (significant bits)
 * 
 * To simplify decoders, PNG specifies that only certain sample depths may be
 * used, and further specifies that sample values should be scaled to the full
 * range of possible values at the sample depth. The sBIT chunk defines the
 * original number of significant bits (which can be less than or equal to the
 * sample depth). This allows PNG decoders to recover the original data
 * losslessly even if the data had a sample depth not  directly supported
 * by PNG.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Sbit extends Png_Chunk
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
    protected $_type = 'sBIT';
    
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
        $data                                        = new stdClass();
        $data->colourType0                           = new stdClass();
        $data->colourType2                           = new stdClass();
        $data->colourType3                           = new stdClass();
        $data->colourType4                           = new stdClass();
        $data->colourType6                           = new stdClass();
        
        // Gets the significant bits for the coulour type 0
        $data->colourType0->significantGreyscaleBits = self::$_binUtils->unsignedChar( $this->_data, 0 );
        
        // Gets the significant bits for the coulour type 2
        $data->colourType2->significantRedBits       = self::$_binUtils->unsignedChar( $this->_data, 1 );
        $data->colourType2->significantGreenBits     = self::$_binUtils->unsignedChar( $this->_data, 2 );
        $data->colourType2->significantBlueBits      = self::$_binUtils->unsignedChar( $this->_data, 3 );
        
        // Colour type 3 is the same as coulour type 2
        $data->colourType3->significantRedBits       = $data->colourType2->significantRedBits;
        $data->colourType3->significantGreenBits     = $data->colourType2->significantGreenBits;
        $data->colourType3->significantBlueBits      = $data->colourType2->significantBlueBits;
        
        // Gets the significant bits for the coulour type 4
        $data->colourType4->significantGreyscaleBits = self::$_binUtils->unsignedChar( $this->_data, 4 );
        $data->colourType4->significantAlphaBits     = self::$_binUtils->unsignedChar( $this->_data, 5 );
        
        // Gets the significant bits for the coulour type 6
        $data->colourType6->significantRedBits       = self::$_binUtils->unsignedChar( $this->_data, 6 );
        $data->colourType6->significantGreenBits     = self::$_binUtils->unsignedChar( $this->_data, 7 );
        $data->colourType6->significantBlueBits      = self::$_binUtils->unsignedChar( $this->_data, 8 );
        $data->colourType6->significantAlphaBits     = self::$_binUtils->unsignedChar( $this->_data, 9 );
        
        // Returns the processed data
        return $data;
    }
}
