<?php

// Sets the error reporting level to the highest possible value
error_reporting ( E_ALL | E_STRICT );

// Includes the class manager
require_once( './Classes/ClassManager.class.php' );

// Checks the PHP version required for this file
if( version_compare( PHP_VERSION, ClassManager::PHP_COMPATIBLE, '<' ) ) {
    
    // PHP version is too old
    trigger_error( 'Class ClassManager requires PHP version ' . ClassManager::PHP_COMPATIBLE . ' (actual version is ' . PHP_VERSION . ')' , E_USER_ERROR );
}

// Registers a SPL autoload method to use to load the classes form this package
spl_autoload_register( array( 'ClassManager', 'autoLoad' ) );
