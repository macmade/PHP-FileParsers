<?php

/**
 * Exception class for the Parser package
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Parser
 * @version         0.1
 */
class Parser_Exception extends Exception_Base
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
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_FILE         = 0x01;
    const EXCEPTION_UNREADABLE      = 0x02;
    const EXCEPTION_INVALID_HANDLER = 0x03;
}
