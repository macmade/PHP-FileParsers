<?php

final class Mpeg4_Atom_Stsc extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'stsc';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        $data->entries     = array();
        
        for( $i = 8; $i < $this->_dataLength; $i += 12 ) {
            
            $entry                           = new stdClass();
            $entry->first_chunk              = $this->_bigEndianUnsignedLong( $i );
            $entry->samples_per_chunk        = $this->_bigEndianUnsignedLong( $i + 4 );
            $entry->sample_description_index = $this->_bigEndianUnsignedLong( $i + 8 );
            $data->entries[]                 = $entry;
        }
        
        return $data;
    }
}
