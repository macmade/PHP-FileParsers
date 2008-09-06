<?php

/**
 * Exception class for the Png package
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Png_Exception extends Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_BAD_SIGNATURE = 0x01;
    const EXCEPTION_BAD_CRC       = 0x02;
    const EXCEPTION_INVALID_CHUNK = 0x03;
}
