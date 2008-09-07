<?php

/**
 * PNG tEXt chunk (textual data)
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Png_Chunk_Text extends Png_Chunk
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
    protected $_type = 'tEXt';
    
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
        
        // Position of the null separator
        $null             = strpos( $this->_data, chr( 0 ) );
        
        // Gets the keyword
        $data->keyword    = substr( $this->_data, 0, $null );
        
        // Gets the text string
        $data->textString = substr( $this->_data, $null + 1 );
        
        // Returns the processed data
        return $data;
    }
}
