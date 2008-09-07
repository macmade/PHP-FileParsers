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
        $data     = new stdClass();
        
        // Gets the IHDR chunk
        $ihdr     = $this->_pngFile->IHDR;
        
        // Process the IHDR data
        $ihdrData = $ihdr->getProcessedData();
        
        // Checks the data length
        switch( $ihdrData->colourType ) {
            
            // Greyscale
            case 0:
                
                // Gets the sample value
                $data->greySampleValue = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 0 );
                break;
            
            // RGB
            case 2:
                
                // Gets the sample values
                $data->redSampleValue   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 0 );
                $data->greenSampleValue = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 2 );
                $data->blueSampleValue  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
                break;
            
            // Indexed color
            case 3:
                
                // Storage
                $data->alphasForPaletteIndexes = array();
                
                // Process the chunk data till the end
                for( $i = 0; $i < $this->_dataLength; $i++ ) {
                    
                    // Gets the alpha for the current palette index
                    $data->alphasForPaletteIndexes[] = self::$_binUtils->unsignedChar( $this->_data, $i );
                }
                
                break;
        }
        
        // Returns the processed data
        return $data;
    }
}
