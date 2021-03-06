<?php

# $Id$

/**
 * Abstract for the parser classes
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Parser
 * @version         0.3
 */
abstract class Fp_Parser_Base
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
     * The abstract method used to parse the file
     */
    abstract protected function _parseFile();
    
    /**
     * The instance of the binary utilities class
     */
    protected static $_binUtils  = NULL;
    
    /**
     * Wether the static variables are set or not
     */
    protected static $_hasStatic = false;
    
    /**
     * The PHP file handler
     */
    protected $_fileHandle       = NULL;
    
    /**
     * The file path
     */
    protected $_filePath         = '';
    
    /**
     * Class constructor
     * 
     * @param   string              The location of the file to parse
     * @return  NULL
     * @throws  Fp_Parser_Exception If the file does not exist
     * @throws  Fp_Parser_Exception If the file is not readable
     * @throws  Fp_Parser_Exception If PHP isn't able to open a file handle
     */
    public function __construct( $file )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Checks if the requested file exists
        if( !file_exists( $file ) ) {
            
            // File does not exist
            throw new Fp_Parser_Exception( 'The requested file ' . $file . ' does not exist.', Fp_Parser_Exception::EXCEPTION_NO_FILE );
        }
        
        // Checks if the requested file can be read
        if( !is_readable( $file ) ) {
            
            // Unreadable file
            throw new Fp_Parser_Exception( 'The requested file ' . $file . ' is not readable.', Fp_Parser_Exception::EXCEPTION_UNREADABLE );
        }
        
        // Opens a binary file hander
        $this->_fileHandle = fopen( $file, 'rb' );
        
        // Checks the file handler
        if( !$this->_fileHandle ) {
            
            // Invalid file handler
            throw new Fp_Parser_Exception( 'Cannot open requested file ' . $file . '.', Fp_Parser_Exception::EXCEPTION_INVALID_HANDLER );
        }
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Parses the file
        $this->_parseFile();
        
        // Closes the file handle
        fclose( $this->_fileHandle );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    protected static function _setStaticVars()
    {
        // Gets the instance of the binary utilities class
        self::$_binUtils  = Fp_Binary_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Reads bytes from the file handler
     * 
     * @param   int     The number of bytes to read
     * @return  string  The bytes from the file
     */
    protected function _read( $length )
    {
        return fread( $this->_fileHandle, $length );
    }
}
