<?php

/**
 * MPEG-4 STCO atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class ChunkOffsetBox extends FullBox( 'stco', version = 0, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      
 *      for ( i=1; i <= entry_count; i++ ) {
 *          
 *          unsigned int( 32 )  chunk_offset;
 *      }
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Stco extends Mpeg4_FullBox
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
    protected $_type = 'stco';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
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
        for( $i = 8; $i < $this->_dataLength; $i += 4 ) {
            
            // Storage for the current entry
            $entry               = new stdClass();
            
            // Process the entry data
            $entry->chunk_offset = $this->_bigEndianUnsignedLong( $i );
            
            // Stores the current entry
            $data->entries[]     = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
