<?php

# $Id$

// Checks the PHP version
if( ( double )PHP_VERSION < 5 ) {
    
    // We are running PHP4
    trigger_error( 'PHP version 5 is required to use this script (actual version is ' . PHP_VERSION . ')', E_USER_ERROR );
}

// Checks for the SPL
if( !function_exists( 'spl_autoload_register' ) ) {
    
    // The SPL is unavailable
    trigger_error( 'The SPL (Standard PHP Library) is required to use this script', E_USER_ERROR );
}

// Sets the timezone
date_default_timezone_set( 'Europe/Zurich' );

// Sets the error reporting level to the highest possible value
error_reporting ( E_ALL | E_STRICT );

// Includes the class manager
require_once(
    dirname( __FILE__ )
  . DIRECTORY_SEPARATOR
  . 'Classes'
  . DIRECTORY_SEPARATOR
  . 'Fp'
  . DIRECTORY_SEPARATOR
  . 'Core'
  . DIRECTORY_SEPARATOR
  . 'ClassManager.class.php'
);

// Checks the PHP version required to use the class manager
if( version_compare( PHP_VERSION, Fp_Core_ClassManager::PHP_COMPATIBLE, '<' ) ) {
    
    // PHP version is too old
    trigger_error( 'Class ClassManager requires PHP version ' . Fp_Core_ClassManager::PHP_COMPATIBLE . ' (actual version is ' . PHP_VERSION . ')' , E_USER_ERROR );
}

// Registers an SPL autoload method to use to load the classes form this package
spl_autoload_register( array( 'Fp_Core_ClassManager', 'autoLoad' ) );
