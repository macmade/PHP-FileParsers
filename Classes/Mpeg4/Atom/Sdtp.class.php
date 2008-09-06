<?php

/**
 * MPEG-4 TKHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class SampleDependencyTypeBox extends FullBox( 'sdtp', version = 0, 0 )
 * { 
 *      for ( i = 0; i < sample_count; i++ ) { 
 *          
 *          unsigned int( 2 ) reserved = 0; 
 *          unsigned int( 2 ) sample_depends_on; 
 *          unsigned int( 2 ) sample_is_depended_on; 
 *          unsigned int( 2 ) sample_has_redundancy; 
 *      } 
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Sdtp extends Mpeg4_FullBox
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
    protected $_type = 'sdtp';
    
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
            
        // Storage for the entries
        $data->entries = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) ) {
                    
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each sample
        for( $i = 0; $i < $stsz->entry_count; $i++ ) {
            
            // Checks if we are reading the first entry
            if( $i === 0 ) {
                
                // Gets the raw data for the current entry
                $entryData = ( self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i ) & 0xFF00 ) >> 8;
                
            } else {
                
                // Gets the raw data for the current entry
                $entryData = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i - 1 ) & 0x00FF;
            }
            
            // Storage for the current sample
            $entry = new stdClass();
            
            // Process the data for the current entry
            $entry->sample_depends_on     = ( $entryData & 0x30 ) >> 4; // Mask is 0011 0000
            $entry->sample_is_depended_on = ( $entryData & 0x0C ) >> 2; // Mask is 0000 1100
            $entry->sample_has_redundancy = $entryData & 0x03;          // Mask is 0000 0011
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
