<?php

/**
 * Exception class for the Gif package
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Gif
 * @version         0.1
 */
class Gif_Exception extends Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NOT_GIF    = 0x01;
    const EXCEPTION_BAD_ID     = 0x02;
    const EXCEPTION_BAD_EXT_ID = 0x03;
}
