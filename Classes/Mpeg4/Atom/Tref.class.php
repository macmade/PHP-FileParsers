<?php

/**
 * MPEG-4 TREF atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class TrackReferenceBox extends Box( 'tref' )
 * {}
 * 
 * aligned( 8 ) class TrackReferenceTypeBox ( unsigned int( 32 ) reference_type ) extends Box( reference_type )
 * { 
 *      unsigned int( 32 ) track_IDs[]; 
 * }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Tref extends Mpeg4_DataAtom
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
    protected $_type = 'tref';
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Data storage
        $data          = new stdClass();
        $data->entries = array();
        
        // Offset for the entries
        $entriesOffset = 0;
        
        // Process each entry
        while( $entriesOffset < $this->_dataLength ) {
            
            // Length of the current entry
            $entryLength           = $this->_bigEndianUnsignedLong( $entriesOffset );
            
            // Storage for the current entry
            $entry                 = new stdClass();
            
            // Reference type
            $entry->reference_type = substr( $this->_data, $entriesOffset + 4, 4 );
            
            // Storage for the track IDs
            $entry->track_IDs      = array();
            
            // Process each track ID
            for( $i = 8; $i < $entryLength; $i +=4 ) {
                
                // Gets the track ID
                $entry->track_IDs[] = $this->_bigEndianUnsignedLong( $entriesOffset + $i );
            }
            
            // Stores the current entry
            $data->entries[] = $entry;
            
            // Updates the entries offset
            $entriesOffset  += $entryLength;
        }
        
        // Returns the processed data
        return $data;
    }
}
