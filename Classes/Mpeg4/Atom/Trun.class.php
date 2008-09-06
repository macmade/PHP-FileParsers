<?php

/**
 * MPEG-4 TRUN atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class TrackRunBox extends FullBox( 'trun', 0, tr_flags )
 * {
 *      unsigned int( 32 ) sample_count;
 *      
 *      // The following are optional fields
 *      signed int( 32 ) data_offset;
 *      unsigned int( 32 ) first_sample_flags;
 *      
 *      // All fields in the following array are optional
 *      {
 *          unsigned int( 32 ) sample_duration;
 *          unsigned int( 32 ) sample_size;
 *          unsigned int( 32 ) sample_flags
 *          unsigned int( 32 ) sample_composition_time_offset;
 *      }[ sample_count ]
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Trun extends Mpeg4_FullBox
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
    protected $_type = 'trun';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Storage for the atom flags
        $flags                                          = new stdClass();
        
        // Process the atom flags
        $flags->data_offset_present                     = ( $rawFlags & 0x000001 ) ? true: false;
        $flags->first_sample_flags_present              = ( $rawFlags & 0x000004 ) ? true: false;
        $flags->sample_duration_present                 = ( $rawFlags & 0x000100 ) ? true: false;
        $flags->sample_size_present                     = ( $rawFlags & 0x000200 ) ? true: false;
        $flags->sample_flags_present                    = ( $rawFlags & 0x000400 ) ? true: false;
        $flags->sample_composition_time_offsets_present = ( $rawFlags & 0x000800 ) ? true: false;
        
        // Returns the atom flags
        return $flags;
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Gets the processed data from the parent (fullbox)
        $data               = parent::getProcessedData();
        
        // Sample count
        $data->sample_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        
        // Storage for the samples
        $data->samples      = array();
        
        // Offset for the remaining data
        $dataOffset         = 8;
        
        // Checks for the data offset
        if( $data->flags->data_offset_present ) {
            
            // Data offset
            $data->data_offset = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
            
            // Updates the data offset
            $dataOffset       += 4;
        }
        
        // Checks for the first sample flags
        if( $data->flags->first_sample_flags_present ) {
            
            // First sample flags
            $data->first_sample_flags = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
            
            // Updates the data offset
            $dataOffset              += 4;
        }
        
        // Process each sample
        for( $i = 0; $i < $data->sample_count; $i++ ) {
            
            // Storage for the current sample
            $sample = new stdClass();
            
            // Checks for the sample duration
            if( $data->flags->sample_duration_present ) {
                
                // Sample duration
                $sample->sample_duration = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
                
                // Updates the data offset
                $dataOffset             += 4;
            }
            
            // Checks for the sample size
            if( $data->flags->sample_size_present ) {
                
                // Sample size
                $sample->sample_size = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
                
                // Updates the data offset
                $dataOffset         += 4;
            }
            
            // Checks for the sample flags
            if( $data->flags->sample_flags_present ) {
                
                // Sample flags
                $sample->sample_flags = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
                
                // Updates the data offset
                $dataOffset          += 4;
            }
            
            // Checks for the sample composition tome offset
            if( $data->flags->sample_composition_time_offsets_present ) {
                
                // Sample composition tome offset
                $sample->sample_composition_time_offsets = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $dataOffset );
                
                // Updates the data offset
                $dataOffset                             += 4;
            }
            
            // Stores the current sample
            $data->samples[] = $sample;
        }
        
        // Return the processed data
        return $data;
    }
}
