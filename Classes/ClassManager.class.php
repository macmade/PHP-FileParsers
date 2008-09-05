<?php

/**
 * MPEG-4 package initialization class
 * 
 * This class will handle every request to a class form the MPEG-4 package,
 * by automatically loading the class file (thanx to the SPL). In other words,
 * the only thing you have to care about if you want to use the MPEG-4 package
 * is to load this file. Then, every class from this package will be available.
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4
 * @version         0.1
 */
final class Mpeg4_Init
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    // Unique instance of the class (singleton)
    private static $_instance = NULL;
    
    // Loaded classes from this package
    private $_loadedClasses   = array();
    
    // The directory which contains this file (and the other files from this package)
    private $_dir             = '';
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    private function __construct()
    {
        // Stores the directory containing this file
        $this->_dir = substr( dirname( __FILE__ ), 0, -5 );
    }
    
    /**
     * Class cloning
     * 
     * This method disallows the cloning of this class (singleton).
     */
    public function __clone()
    {
        trigger_error( 'The class ' . __CLASS__ . '  cannot be cloned, as it\'s a singleton. Please call the getInstance() method in order to get the unique instance of this class.' );
    }
    
    /**
     * Gets the unique instance of this class (singleton)
     * 
     * @return  object  The unique instance of this class
     */
    public static function getInstance()
    {
        // Checks if the instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * SPL autoload method
     * 
     * @param   string  $className  The name of the class to load
     * @return  boolean
     * @see     getInstance
     * @see     _loadClass
     */
    public static function autoLoad( $className )
    {
        // Instance of this class
        static $instance = NULL;
        
        // Checks if the instance of the class has already been fetched
        if( !is_object( $instance ) ) {
            
            // Gets the instance of this class
            $instance = self::getInstance();
        }
        
        // Checks if the requested class belongs to this package
        if( substr( $className, 0, 6 ) === 'Mpeg4_' ) {
            
            // Loads the class
            return $instance->_loadClass( $className );
            
        }
        
        // The requested class does not belong to this package
        return false;
    }
    
    /**
     * Loads a class from this package
     * 
     * @param   string  $className  The name of the class to load
     * @return  boolean
     */
    private function _loadClass( $className )
    {
        // Gets the class path
        $classPath = $this->_dir . str_replace( '_', DIRECTORY_SEPARATOR, $className ) . '.class.php';
        
        // Checks if the class file exists
        if( file_exists( $classPath ) ) {
            
            // Includes the class file
            require_once( $classPath );
            
            // Checks the minimal PHP version required (eval() is required as late static bindings are implemented only in PHP 5.3)
            eval( '$phpCompatible = ' . $className . '::PHP_COMPATIBLE;' );
            
            if( version_compare( PHP_VERSION, $phpCompatible, '<' ) ) {
                
                // PHP version is too old
                trigger_error( 'Class ' . $className . ' requires PHP version ' . $phpCompatible . ' (actual version is ' . PHP_VERSION . ')' , E_USER_ERROR );
            }
            
            // Adds the class to the loaded classes array
            $this->_loadedClasses[ $className ] = $classPath;
            
            // Class was successfully loaded
            return true;
        }
        
        // Class file was not found
        return false;
    }
    
    /**
     * Gets the loaded classes from this package
     * 
     * @return  array   An array with the loaded classes
     */
    public function getLoadedClasses()
    {
        // Returns the loaded classes from this package
        return $this->_loadedClasses;
    }
}

// Checks the PHP version required for this file
if( version_compare( PHP_VERSION, Mpeg4_Init::PHP_COMPATIBLE, '<' ) ) {
    
    // PHP version is too old
    trigger_error( 'Class Mpeg4_Init requires PHP version ' . Mpeg4_Init::PHP_COMPATIBLE . ' (actual version is ' . PHP_VERSION . ')' , E_USER_ERROR );
}

// Registers a SPL autoload method to use to load the classes form this package
spl_autoload_register( array( 'Mpeg4_Init', 'autoLoad' ) );
