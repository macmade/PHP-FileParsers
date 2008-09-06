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
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_FILE         = 0x01;
    const EXCEPTION_UNREADABLE      = 0x02;
    const EXCEPTION_INVALID_HANDLER = 0x03;
}
