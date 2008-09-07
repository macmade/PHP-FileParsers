<?php

/**
 * PNG tRNs chunk (transparency)
 * 
 * The tRNS chunk specifies either alpha values that are associated with
 * palette entries (for indexed-colour images) or a single transparent colour
 * (for greyscale and truecolour images).
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Trns extends Png_Chunk
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
    protected $_type = 'tRNs';
    
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
        $data->colourType3                   = array();
        
        // Gets the value for the coulour type 0
        $data->colourType0->greySampleValue  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 0 );
        
        // Gets the values for the coulour type 2
        $data->colourType0->redSampleValue   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 2 );
        $data->colourType0->greenSampleValue = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        $data->colourType0->blueSampleValue  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        
        // Gets the hexadecimal values
        $redHex                              = dechex( $data->colourType0->redSampleValue );
        $greenHex                            = dechex( $data->colourType0->greenSampleValue );
        $blueHex                             = dechex( $data->colourType0->blueSampleValue );
        
        // Completes each hexadecimal value if needed
        $redHex                              = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
        $greenHex                            = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
        $blueHex                             = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
        
        // Adds the hexadecimal color value to colour type 2
        $data->colourType0->hex              = '#' . strtoupper( $redHex . $greenHex . $blueHex );
        
        // Process the remaining chunk data till the end
        for( $i = 8; $i < $this->_dataLength; $i++ ) {
            
            // Gets the alpha index
            $data->colourType3[] = self::$_binUtils->unsignedChar( $this->_data, $i );
        }
        
        // Returns the processed data
        return $data;
    }
}
