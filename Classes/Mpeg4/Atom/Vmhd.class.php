<?php

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
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Vmhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    // Atom type
    protected $_type = 'vmhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  $rawFlags   The atom raw flags
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
        
        $data->graphicsmode   = $this->_bigEndianUnsignedShort( 4 );
        $data->opcolor        = new stdClass();
        $data->opcolor->red   = $this->_bigEndianUnsignedShort( 6 );
        $data->opcolor->green = $this->_bigEndianUnsignedShort( 8 );
        $data->opcolor->blue  = $this->_bigEndianUnsignedShort( 10 );
        
        // Return the processed data
        return $data;
    }
}
