<?php

/**
 * MPEG-4 STDP atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class DegradationPriorityBox extends FullBox( 'stdp', version = 0, 0 )
 * {
 *      int i;
 *      
 *      for( i=0; i < sample_count; i++ ) {
 *      
 *          unsigned int( 16 ) priority;
 *      }
 * } 
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Stdp extends Mpeg4_FullBox
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
    protected $_type = 'stdp';
    
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
            
        // Storage for the entries
        $data->priorities = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) ) {
                    
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each priority
        for( $i = 4; $i < $stsz->entry_count; $i += 2 ) {
            
            // Stores the current priority
            $data->priorities[] = $this->_bigEndianUnsignedShort( $i );
        }
        
        // Return the processed data
        return $data;
    }
}
