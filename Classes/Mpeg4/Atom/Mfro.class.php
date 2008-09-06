<?php

/**
 * MPEG-4 MFRO atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class MovieFragmentRandomAccessOffsetBox extends FullBox( 'mfro', version, 0 )
 * {
 *      unsigned int( 32 ) size;
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Mfro extends Mpeg4_FullBox
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
    protected $_type = 'mfro';
    
    /**
     * Process the atom flags
     * 
     * @params  string  $rawFlags   The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
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
        $data       = parent::getProcessedData();
        
        // Process the atom data
        $data->size = $this->_bigEndianUnsignedLong( 4 );
        
        // Returns the processed data
        return $data;
    }
}
