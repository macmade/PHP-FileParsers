<?php

final class Mpeg4_Atom_Ctts extends Mpeg4_FullBox
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
    protected $_type = 'ctts';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entries     = array();
        
        for( $i = 8; $i < $this->_dataLength; $i += 8 ) {
            
            $entry                = new stdClass();
            $entry->sample_count  = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->sample_offset = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            $data->entries[]      = $entry;
        }
        
        return $data;
    }
}
