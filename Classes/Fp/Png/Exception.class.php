<?php

/**
 * Exception class for the Png package
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Fp_Png_Exception extends Fp_Exception_Base
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
    const EXCEPTION_BAD_SIGNATURE = 0x01;
    const EXCEPTION_BAD_CRC       = 0x02;
    const EXCEPTION_INVALID_CHUNK = 0x03;
}
