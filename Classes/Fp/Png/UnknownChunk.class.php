<?php

# $Id$

/**
 * Placeholder for the unknown PNG chunks
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Fp_Png_UnknownChunk extends Fp_Png_Chunk
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
     * The chunk type
     */
    protected $_type = '';
    
    /**
     * Class constructor
     * 
     * @param   Png_File    The instance of the Png_File class in which the chunk is placed
     * @param   string      The chunk type
     * @return  NULL
     */
    public function __construct( Png_File $pngFile, $type )
    {
        // Sets the chunk type
        $this->_type = substr( $type, 0, 4 );
        
        // Calls the parent constructor
        parent::__construct( $pngFile );
    }
    
    /**
     * Process the chunk data
     * 
     * This method will process the chunk raw data and returns human readable
     * values, stored as properties of an stdClass object. Please take a look
     * at the PNG specification for this specific chunk to see which data will
     * be extracted.
     * 
     * @return  stdClass    The human readable chunk data
     */
    public function getProcessedData()
    {
        return new stdClass();
    }
}
