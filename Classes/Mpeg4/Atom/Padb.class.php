<?php

/**
 * MPEG-4 PADB atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class PaddingBitsBox extends FullBox( 'padb', version = 0, 0 )
 * {
 *      unsigned int( 32 ) sample_count;
 *      int i;
 *      
 *      for( i = 0; i < ( ( sample_count + 1 ) / 2 ); i++ ) {
 *          
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad1;
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad2;
 *      }
 * }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Padb extends Mpeg4_FullBox
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
    protected $_type = 'padb';
    
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
        $data->entries = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) ) {
                    
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each priority
        for( $i = 4; $i < ( $stsz->entry_count + 1 ) / 2; $i += 2 ) {
            
            // Storage for the current entry
            $entry = new stdClass();
            
            // Gets the raw data for the entry
            $entryData = $this->_bigEndianUnsignedShort( $i - 1 );
            
            // Process the entry data
            $entry->pad1 = $entryData & 0x0070; // Mask is 0000 0000 0111 0000 
            $entry->pad2 = $entryData & 0x0007; // Mask is 0000 0000 0000 0111
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
