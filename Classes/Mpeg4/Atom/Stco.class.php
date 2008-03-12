<?php

class Mpeg4_Atom_Stco extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'stco';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_bigEndianUnsignedLong( 4 );
        $data->entries     = array();
        
        for( $i = 8; $i < $this->_dataLength; $i += 4 ) {
            
            $entry               = new stdClass();
            $entry->chunk_offset = $this->_bigEndianUnsignedLong( $i );
            $data->entries[]     = $entry;
        }
        
        return $data;
    }
}
