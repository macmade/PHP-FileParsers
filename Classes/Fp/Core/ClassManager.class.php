<?php

# $Id$

/**
 * Class manager
 * 
 * This class will handle every request to a class from this project,
 * by automatically loading the class file (thanx to the SPL).
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Oop/Core
 * @version         0.1
 */
final class Fp_Core_ClassManager
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
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The loaded classes from this project
     */
    private $_loadedClasses   = array();
    
    /**
     * The available top packages
     */
    private $_packages        = array();
    
    /**
     * The directory which contains the classes
     */
    private $_classDir        = '';
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return NULL
     */
    private function __construct()
    {
        // Stores the directory containing the classes
        $this->_classDir = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' ) . DIRECTORY_SEPARATOR;
        
        // Creates a directory iterator in the directory containing this file
        $dirIterator     = new DirectoryIterator( $this->_classDir );
        
        // Process each directory
        foreach( $dirIterator as $file ) {
            
            // Checks if the file is a PHP class file
            if( substr( $file, strlen( $file ) - 10 ) === '.class.php' ) {
                
                // Stores the file name, with it's full path
                $this->_packages[ ( string )$file ] = $file->getPathName();
                
                // Process the next file
                continue;
            }
            
            // Checks if the file is a directory
            if( !$file->isDir() ) {
                
                // File - Process the next file
                continue;
            }
            
            // Checks if the directory is hidden
            if( substr( $file, 0, 1 ) === '.' ) {
                
                // Hidden - Process the next file
                continue;
            }
            
            // Stores the directory name, with it's full path
            $this->_packages[ ( string )$file ] = $file->getPathName();
        }
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
     * @throws  Fp_Core_Singleton_Exception Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Fp_Core_Singleton_Exception( 'Class ' . __CLASS__ . ' cannot be cloned', Fp_Core_Singleton_Exception::EXCEPTION_CLONE );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Fp_Core_ClassManager    The unique instance of the class
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
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
     * When registered with the spl_autoload_register() function, this method
     * will be called each time a class cannot be found, and will try to
     * load it.
     * 
     * @param   string  The name of the class to load
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
        
        // Checks if the class belongs to the 'Oop' package
        if( substr( $className, 0, 3 ) === 'Fp_' ) {
            
            // Gets the class root package
            $rootPkg = substr( $className, 3, strpos( $className, '_', 3 ) - 3 );
            
            // Checks if the requested class belongs to this project
            if( isset( $instance->_packages[ $rootPkg ] ) || isset( $instance->_packages[ $className . '.class.php' ] ) ) {
                
                // Loads the class
                return $instance->_loadClass( $className );
                
            }
        }
        
        // The requested class does not belong to this project
        return false;
    }
    
    /**
     * Loads a class from this project
     * 
     * @param   string  The name of the class to load
     * @return  boolean
     */
    private function _loadClass( $className )
    {
        // Gets the class path
        $classPath = $this->_classDir . str_replace( '_', DIRECTORY_SEPARATOR, substr( $className, 3 ) ) . '.class.php';
        
        // Checks if the class file exists
        if( file_exists( $classPath ) ) {
            
            // Includes the class file
            require_once( $classPath );
        
            // Checks if the class is defined
            if( !class_exists( $className ) ) {
                
                // Error message
                $errorMsg = 'The class ' . $className . ' is not defined in file ' . $classPath;
                
                // The class is not defined
                trigger_error( $errorMsg, E_USER_ERROR );
                
                // Prints the error message and exits the script, as Drupal will intercept the error message
                print $errorMsg;
                exit();
            }
            
            // Checks if the PHP_COMPATIBLE constant is defined
            if( !defined( $className . '::PHP_COMPATIBLE' ) ) {
                
                // Error message
                $errorMsg = 'The requested constant PHP_COMPATIBLE is not defined in class ' . $className;
                
                // Class does not respect the project conventions
                trigger_error( $errorMsg, E_USER_ERROR );
                
                // Prints the error message and exits the script, as Drupal will intercept the error message
                print $errorMsg;
                exit();
            }
            
            // Gets the minimal PHP version required (eval() is required as late static bindings are implemented only in PHP 5.3)
            eval( '$phpCompatible = ' . $className . '::PHP_COMPATIBLE;' );
            
            // Checks the PHP version
            if( version_compare( PHP_VERSION, $phpCompatible, '<' ) ) {
                
                // Error message
                $errorMsg = 'Class ' . $className . ' requires PHP version ' . $phpCompatible . ' (actual version is ' . PHP_VERSION . ')';
                
                // PHP version is too old
                trigger_error( $errorMsg, E_USER_ERROR );
                
                // Prints the error message and exits the script, as Drupal will intercept the error message
                print $errorMsg;
                exit();
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
     * Gets the loaded classes from this project
     * 
     * @return  array   An array with the loaded classes
     */
    public function getLoadedClasses()
    {
        // Returns the loaded classes from this project
        return $this->_loadedClasses;
    }
}
