<?php

final class Fp_Mpeg4_Atom_Stsz extends Fp_Mpeg4_FullBox
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
    protected $_type = 'stsz';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->sample_size = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        $data->entries     = array();
        
        for( $i = 16; $i < $this->_dataLength; $i += 4 ) {
            
            $entry             = new stdClass();
            $entry->entry_size = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $data->entries[]   = $entry;
        }
        
        return $data;
    }
}
