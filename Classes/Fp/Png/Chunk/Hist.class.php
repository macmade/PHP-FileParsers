<?php

# $Id$

/**
 * PNG hIST chunk (image histogram)
 * 
 * The hIST chunk gives the approximate usage frequency of each colour in the
 * palette. A histogram chunk can appear only when a PLTE chunk appears. If a
 * viewer is unable to provide all the colours listed in the palette,
 * the histogram may help it decide how to choose a subset of the colours
 * for display.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Fp_Png_Chunk_Hist extends Fp_Png_Chunk
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
