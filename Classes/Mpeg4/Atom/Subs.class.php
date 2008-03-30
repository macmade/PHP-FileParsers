<?php

/**
 * MPEG-4 SUBS atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class SubSampleInformationBox extends FullBox( 'subs', version, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      int i, j;
 *      
 *      for( i = 0; i < entry_count; i++ ) {
 *          
 *          unsigned int( 32 ) sample_delta;
 *          unsigned int( 16 ) subsample_count;
 *          
 *          if( subsample_count > 0 ) {
 *              
 *              for( j = 0; j < subsample_count; j++ ) {
 *                  
 *                  if( version == 1 ) {
 *                      
 *                      unsigned int( 32 ) subsample_size;
 *                      
 *                  } else {
 *                      
 *                      unsigned int( 16 ) subsample_size;
 *                  }
 *                  
 *                  unsigned int( 8 ) subsample_priority;
 *                  unsigned int( 8 ) discardable;
 *                  unsigned int( 32 ) reserved = 0;
 *              }
 *          }
 *      }
 *  }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Subs extends Mpeg4_FullBox
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
    protected $_type = 'subs';
    
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
        $data              = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        
        // Storage for the entries
        $data->entries     = array();
        
        // Data offset to process the entries
        $entryOffset       = 8;
        
        // Process each entry
        for( $i = 0; $i < $data->entry_count; $i++ ) {
            
            // Storage for the current entry
            $entry                  = new stdClass();
            
            // Process the data for the current entry
            $entry->sample_delta    = $this->_bigEndianUnsignedLong( $entryOffset );
            $entry->subsample_count = $this->_bigEndianUnsignedShort( $entryOffset + 4 );
            $entry->subsamples      = array();
            
            // Updates the data offset
            $entryOffset           += 6;
            
            // Checks for subsamples
            if( $entry->subsample_count > 0 ) {
                
                // Process each subsample
                for( $j = 0; $j < $subsample_count; $j++ ) {
                    
                    // Storage for the current subsample
                    $subSample = new stdClass();
                    
                    // Checks the atom version
                    if( $data->version === 1 ) {
                        
                        // Size of the subsample
                        $subSample->subsample_size = $this->_bigEndianUnsignedLong( $entryOffset );
                        
                        // Updates the data offset
                        $entryOffset              += 4;
                        
                    } else {
                        
                        // Size of the subsample
                        $subSample->subsample_size = $this->_bigEndianUnsignedShort( $entryOffset );
                        
                        // Updates the data offset
                        $entryOffset              += 2;
                    }
                    
                    // Remaining subsample data
                    $subSampleData                 = $this->_bigEndianUnsignedShort( $entryOffset );
                    
                    // Process the remaining data
                    $subSample->subsample_priority = $subSampleData > 8;        // 8 first bits
                    $subSample->discardable        = $subSampleData & 0x00FF;   // 8 last bits
                    
                    // Stores the current subsample
                    $entry->subsamples[]           = $subSample;
                }
            }
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
