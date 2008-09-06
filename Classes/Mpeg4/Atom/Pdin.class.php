<?php

/**
 * MPEG-4 PDIN atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class ProgressiveDownloadInfoBox extends FullBox( 'pdin', version = 0, 0 )
 * {
 *      for ( i = 0; ; i++ ) {
 *          
 *          unsigned int( 32 ) rate;
 *          unsigned int( 32 ) initial_delay;
 *      }
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Pdin extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'pdin';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
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
        $data          = parent::getProcessedData();
        
        // Storage for the entries
        $data->entries = array();
        
        // Process each entry
        for( $i = 4; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry                = new stdClass();
            
            // Process the current entry
            $entry->rate          = $this->_bigEndianUnsignedLong( $i );
            $entry->initial_delay = $this->_bigEndianUnsignedLong( $i + 4 );
            
            // Stores the current entry
            $data->entries[]      = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
