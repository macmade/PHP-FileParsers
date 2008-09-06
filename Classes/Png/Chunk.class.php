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
    
    /**
     * The abstract method used to get the processed chunk data
     */
    abstract public function getProcessedData();
    
    /**
     * The instance of the binary utilities class
     */
    protected static $_binUtils  = NULL;
    
    /**
     * Wether the static variables are set or not
     */
    protected static $_hasStatic = false;
    
    /**
     * The chunk type
     */
    protected $_type             = '';
    
    /**
     * The chunk data
     */
    protected $_data             = '';
    
    /**
     * The chunk data length
     */
    protected $_dataLength       = 0;
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    public function __construct()
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    protected static function _setStaticVars()
    {
        // Gets the instance of the binary utilities class
        self::$_binUtils  = Binary_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    protected function __toString()
    {
        // Gets the chunk length
        $length = pack( 'N', $this->_dataLength );
        
        // Computes the CRC
        $crc    = crc32( $this->_type . $this->_data );
        
        // Returns the full chunk
        return $length . $this->_type . $this->_data . $crc;
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
