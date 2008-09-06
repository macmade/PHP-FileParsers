<?php

/**
 * Abstract for the PNG chunks
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
abstract class Png_Chunk
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'alpha';
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function getProcessedData();
    
    /**
     * The chunk type
     */
    protected $_type       = '';
    
    /**
     * The chunk data
     */
    protected $_data       = '';
    
    /**
     * The chunk data length
     */
    protected $_dataLength = 0;
    
    /**
     * 
     */
    protected function __toString()
    {
        $length = pack( 'N', $this->_dataLength );
        
        return $length . $this->_type . $this->_data;
    }
    
    /**
     * 
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * 
     */
    public function getDataLength()
    {
        return $this->_dataLength;
    }
    
    /**
     * 
     */
    public function setRawData( $data )
    {
        $this->_data       = $data;
        $this->_dataLength = strlen( $data );
    }
}
