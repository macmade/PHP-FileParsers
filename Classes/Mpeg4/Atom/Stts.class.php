<?php

/**
 * MPEG-4 STTS atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class TimeToSampleBox extends FullBox( 'stts', version = 0, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      int i;
 *      
 *      for( i = 0; i < entry_count; i++ ) {
 *          
 *          unsigned int( 32 )  sample_count;
 *          unsigned int( 32 )  sample_delta;
 *      }
 * }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Stts extends Mpeg4_FullBox
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
    protected $_type = 'stts';
    
    /**
     * Process the atom flags
     * 
     * @params  string  $rawFlags   The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $flags )
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
        $data              = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        
        // Storage for the entries
        $data->entries     = array();
        
        // Process each entry
        for( $i = 8; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry               = new stdClass();
            
            // Entry data
            $entry->sample_count = $this->_bigEndianUnsignedLong( $i );
            $entry->sample_delta = $this->_bigEndianUnsignedLong( $i + 4 );
            
            // Stores the current entry
            $data->entries[]     = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
