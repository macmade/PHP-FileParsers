<?php

/**
 * Exception class for the Gif package
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Gif
 * @version         0.1
 */
class Fp_Gif_Exception extends Fp_Exception_Base
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
    const EXCEPTION_NOT_GIF    = 0x01;
    const EXCEPTION_BAD_ID     = 0x02;
    const EXCEPTION_BAD_EXT_ID = 0x03;
}
