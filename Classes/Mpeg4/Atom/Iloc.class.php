<?php

/**
 * MPEG-4 ILOC atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class ItemLocationBox extends FullBox( 'iloc', version = 0, 0 )
 * {
 *      unsigned int( 4 ) offset_size;
 *      unsigned int( 4 ) length_size;
 *      unsigned int( 4 ) base_offset_size;
 *      unsigned int( 4 ) reserved;
 *      unsigned int( 16 ) item_count;
 *      
 *      for( i = 0; i < item_count; i++ ) {
 *          
 *          unsigned int( 16 ) item_ID;
 *          unsigned int( 16 ) data_reference_index;
 *          unsigned int( base_offset_size * 8 ) base_offset;
 *          unsigned int( 16 )  extent_count;
 *          
 *          for ( j = 0; j < extent_count; j++ ) {
 *              
 *              unsigned int( offset_size * 8 ) extent_offset;
 *              unsigned int( length_size *8 ) extent_length;
 *          }
 *      }
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Iloc extends Mpeg4_FullBox
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
    protected $_type = 'iloc';
    
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
        $data                   = parent::getProcessedData();
        
        // Offset related data
        $offset                 = $this->_bigEndianUnsignedShort( 4 );
        
        // Process the atom data
        $data->offset_size      = $offset & 0xF000; // Mask is 1111 0000 0000 0000
        $data->length_size      = $offset & 0x0F00; // Mask is 0000 1111 0000 0000
        $data->base_offset_size = $offset & 0x00F0; // Mask is 0000 0000 1111 0000
        $data->item_count       = $this->_bigEndianUnsignedShort( 6 );
        
        // Storage for items
        $data->items            = array();
        
        // Data offset for the items
        $itemOffset             = 8;
        
        // Process each item
        for( $i = 0; $i < $data->item_count; $i++ ) {
            
            // Storage for the current item
            $item = new stdClass();
            
            // Process the current item data
            $item->item_ID              = $this->bigEndianUnsignedShort( $itemOffset );
            $item->data_reference_index = $this->bigEndianUnsignedShort( $itemOffset + 2 );
            
            // Stores the current item
            $data->items[]              = $item;
        }
        
        // Returns the processed data
        return $data;
    }
}
