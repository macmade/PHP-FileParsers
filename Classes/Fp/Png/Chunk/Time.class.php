<?php

/**
 * PNG tIMe chunk (image last-modification time)
 * 
 * The tIME chunk gives the time of the last image modification (not the time
 * of initial image creation).
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png/Chunk
 * @version         0.1
 */
class Fp_Png_Chunk_Time extends Fp_Png_Chunk
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
    protected $_type = 'tIMe';
    
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
        $data         = new stdClass();
        
        // Gets the date informations
        $data->year   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 0 );
        $data->month  = self::$_binUtils->unsignedChar( $this->_data, 2 );
        $data->day    = self::$_binUtils->unsignedChar( $this->_data, 3 );
        $data->hour   = self::$_binUtils->unsignedChar( $this->_data, 4 );
        $data->minute = self::$_binUtils->unsignedChar( $this->_data, 5 );
        $data->second = self::$_binUtils->unsignedChar( $this->_data, 6 );
        
        // Creates a timestamp
        $data->tstamp = mktime(
            $data->hour,
            $data->minute,
            $data->second,
            $data->month,
            $data->day,
            $data->year
        );
        
        // Returns the processed data
        return $data;
    }
}
