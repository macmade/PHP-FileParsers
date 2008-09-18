<?php

# $Id$

/**
 * MPEG-4 VMHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class VideoMediaHeaderBox extends FullBox( 'vmhd', version = 0, 1 )
 * {
 *      template unsigned int( 16 ) graphicsmode = 0;
 *      template unsigned int( 16 )[ 3 ] opcolor = { 0, 0, 0 };
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Fp_Mpeg4_Atom_Vmhd extends Fp_Mpeg4_FullBox
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
     * The atom type
     */
    protected $_type = 'vmhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
        
        $data->graphicsmode   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        $data->opcolor        = new stdClass();
        $data->opcolor->red   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        $data->opcolor->green = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 8 );
        $data->opcolor->blue  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 10 );
        
        // Return the processed data
        return $data;
    }
}
