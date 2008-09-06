<?php

/**
 * MPEG-4 SBGP atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class SampleToGroupBox extends FullBox( 'sbgp', version = 0, 0 )
 * {
 *      unsigned int( 32 ) grouping_type;
 *      unsigned int( 32 ) entry_count;
 *      
 *      for( i = 1; i <= entry_count; i++ ) {
 *          
 *          unsigned int( 32 ) sample_count;
 *          unsigned int( 32 ) group_description_index;
 *      }
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Sbgp extends Mpeg4_FullBox
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
    protected $_type = 'sbgp';
    
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
        $data                = parent::getProcessedData();
        
        // Process the atom data
        $data->grouping_type = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entry_count   = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        
        // Storage for the entries
        $data->entries       = array();
        
        // Process each entry
        for( $i = 12; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry                          = new stdClass();
            
            // Process the entry data
            $entry->sample_count            = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->group_description_index = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            
            // Stores the current entry
            $data->entries[]                = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
