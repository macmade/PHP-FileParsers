<?php

final class Mpeg4_Atom_Elst extends Mpeg4_FullBox
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
    protected $_type = 'elst';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        $data->entries     = array();
        
        if( $data->version === 1 ) {
            
            for( $i = 8; $i < $this->_dataLength; $i += 20 ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = $this->_bigEndianUnsignedLong( $i );
                $entry->media_time       = $this->_bigEndianUnsignedLong( $i + 8 );
                $entry->media_rate       = $this->_bigEndianFixedPoint( $i + 16, 16, 16 );
                $data->entries[]         = $entry;
            }
            
        } else {
            
            for( $i = 8; $i < $this->_dataLength; $i += 12 ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = $this->_bigEndianUnsignedLong( $i );
                $entry->media_time       = $this->_bigEndianUnsignedLong( $i + 4 );
                $entry->media_rate       = $this->_bigEndianFixedPoint( $i + 8, 16, 16 );
                $data->entries[]         = $entry;
            }
        }
        
        return $data;
    }
}
