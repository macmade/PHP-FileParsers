<?php

/**
 * MPEG-4 STSD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class SampleDescriptionBox( unsigned int( 32 ) handler_type ) extends FullBox( 'stsd', 0, 0 )
 * {
 *      int i ;
 *      unsigned int( 32 ) entry_count;
 *      
 *      for( i = 1; i <= entry_count; i++ ) {
 *          
 *          switch( handler_type ) {
 *              
 *              case 'soun':
 *                  
 *                  AudioSampleEntry();
 *                  break;
 *              
 *              case 'vide':
 *                  
 *                  VisualSampleEntry();
 *                  break;
 *              
 *              case 'hint':
 *                  
 *                  HintSampleEntry();
 *                  break;
 *          }
 *      }
 * }
 * 
 * aligned( 8 ) abstract class SampleEntry( unsigned int( 32 ) format ) extends Box( format )
 * {
 *      const unsigned int( 8 )[ 6 ] reserved = 0;
 *      unsigned int( 16 ) data_reference_index;
 * }
 * 
 * class HintSampleEntry() extends SampleEntry( protocol )
 * {
 *      unsigned int( 8 ) data[];
 * }
 * 
 * class VisualSampleEntry( codingname ) extends SampleEntry( codingname )
 * {
 *      unsigned int( 16 ) pre_defined = 0;
 *      const unsigned int( 16 ) reserved = 0;
 *      unsigned int( 32 )[ 3 ] pre_defined = 0;
 *      unsigned int( 16 ) width;
 *      unsigned int( 16 ) height;
 *      template unsigned int( 32 ) horizresolution = 0x00480000;
 *      template unsigned int( 32 ) vertresolution  = 0x00480000;
 *      const unsigned int( 32 ) reserved = 0;
 *      template unsigned int( 16 ) frame_count = 1;
 *      string[ 32 ] compressorname;
 *      template unsigned int( 16 ) depth = 0x0018;
 *      int( 16 ) pre_defined = -1;
 * }
 * 
 * class AudioSampleEntry( codingname ) extends SampleEntry( codingname )
 * {
 *      const unsigned int( 32 )[ 2 ] reserved = 0;
 *      template unsigned int( 16 ) channelcount = 2;
 *      template unsigned int( 16 ) samplesize = 16;
 *      unsigned int( 16 ) pre_defined = 0;
 *      const unsigned int( 16 ) reserved = 0 ;
 *      template unsigned int( 32 ) samplerate = { timescale of media } << 16;
 * }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Stsd extends Mpeg4_FullBox
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
    protected $_type = 'stsd';
    
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
     * @see     _audioSampleEntry
     * @see     _visualSampleEntry
     * @see     _hintSampleEntries
     */
    public function getProcessedData()
    {
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        
        // Checks for the HDLR atom
        if( !isset( $this->_parent->_parent->_parent->hdlr ) ) {
            
            // No HDLR atom
            return $data;
        }
        
        // Gets data from HDLR
        $hdlr = $this->_parent->_parent->_parent->hdlr->getProcessedData();
        
        // Checks if the track is a hint track
        if( $hdlr->handler_type === 'hint' ) {
            
            $data->entries = $this->_hintSampleEntries();
            
        } else {
            
            // Storage for the entries            
            $data->entries     = array();
            
            // Process each entry
            for( $i = 0; $i < $data->entry_count; $i++ ) {
                
                // Checks the handler type
                if( $hdlr->handler_type === 'soun' ) {
                    
                    // Returns the atom processed data (audio entry)
                    $data->entries[] = $this->_audioSampleEntry( $i );
                    
                } elseif( $hdlr->handler_type === 'vide' ) {
                    
                    // Returns the atom processed data (visual entry)
                    $data->entries[] = $this->_visualSampleEntry( $i );
                    
                }
            }
        }
        
        // Unrecognized handler type
        return $data;
    }
    
    /**
     * Process the atom data (audio entry)
     * 
     * @param   int     $entryNumber    The current entry number
     * @return  object  The processed atom data
     */
    protected function _audioSampleEntry( $entryNumber )
    {
        // Data storage
        $data                       = new stdClass();
        
        // Gets the start offset for the current entry
        // Each entry is 288 bits
        $startOffset                = 8 + ( $entryNumber * 36 );
        
        // Gets the start offset for the entry data
        // Each entry data is 160 bits
        $dataStartOffset            = $startOffset + 16;
        
        // Process the atom data
        $data->format               = substr( $this->_data, $startOffset + 4 , 4 );
        $data->data_reference_index = $this->_bigEndianUnsignedShort( $startOffset + 14 );
        $data->channelcount         = $this->_bigEndianUnsignedShort( $dataStartOffset + 8 );
        $data->samplesize           = $this->_bigEndianUnsignedShort( $dataStartOffset + 10 );
        $data->samplerate           = $this->_bigEndianFixedPoint( $dataStartOffset + 16, 16, 16 );
        
        // Returns the processed data
        return $data;
    }
    
    /**
     * Process the atom data (visual entry)
     * 
     * @param   int     $entryNumber    The current entry number
     * @return  object  The processed atom data
     */
    protected function _visualSampleEntry( $entryNumber  )
    {
        // Data storage
        $data                       = new stdClass();
        
        // Gets the start offset for the current entry
        // Each entry is 464 bits
        $startOffset                = 8 + ( $entryNumber * 58 );
        
        // Gets the start offset for the entry data
        // Each entry data is 336 bits
        $dataStartOffset            = $startOffset + 16;
        
        // Length of the compressor string
        $compressorNameLength       = ( $this->_bigEndianUnsignedShort( $dataStartOffset + 36, 2 ) && 0xFF00 ) >> 8;
        
        // Process the atom data
        $data->format               = substr( $this->_data, $startOffset + 4 , 4 );
        $data->data_reference_index = $this->_bigEndianUnsignedShort( $startOffset + 14 );
        $data->width                = $this->_bigEndianUnsignedShort( $dataStartOffset + 16 );
        $data->height               = $this->_bigEndianUnsignedShort( $dataStartOffset + 18 );
        $data->horizresolution      = $this->_bigEndianFixedPoint( $dataStartOffset + 20, 16, 16 );
        $data->vertresolution       = $this->_bigEndianFixedPoint( $dataStartOffset + 24, 16, 16 );
        $data->frame_count          = $this->_bigEndianUnsignedShort( $dataStartOffset + 32);
        $data->compressorname       = ( $compressorNameLength > 0 ) ? substr( $this->_data, 37, $compressorNameLength ) : '';
        $data->depth                = $this->_bigEndianUnsignedShort( $dataStartOffset, 40 );
        
        // Returns the processed data
        return $data;
    }
    
    /**
     * Process the atom data (hint entries)
     * 
     * @return  object  The processed atom data
     */
    protected function _hintSampleEntries()
    {
        // Entries storage
        $entries     = array();
        
        // Offset for the entries
        $entryOffset = 8;
        
        while( $entryOffset < $this->_dataLength ) {
            
            // Current entry length
            $entryLength                 = $this->_bigEndianUnsignedLong( $entryOffset );
            
            // Storage for the current entry
            $entry                       = new stdClass();
            
            // Process the current entry
            $entry->protocol             = substr( $this->_data, $entryOffset + 4, 4 );
            $entry->data_reference_index = $this->_bigEndianUnsignedShort( $entryOffset + 14 );
            
            // Storage for the data of the current entry
            $entry->data                 = array();
            
            // Process each data inside the current entry
            for( $i = 16; $i < $entryLength; $i++ ) {
                
                // Adds the current data
                $entry->data[] = ( $this->_bigEndianUnsignedShort( $i - 2, 2 ) && 0x00FF );
            }
            
            // Updates the entry offset
            $entryOffset += $entryLength;
            
            // Stores the current entry
            $entries[]    = $entry;
        }
        
        // Returns the processed data
        return $entries;
    }
}
