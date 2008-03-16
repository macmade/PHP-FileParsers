<?php

final class Mpeg4_Atom_Mdhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'mdhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 );
            $data->modification_time = $this->_bigEndianUnsignedLong( 12 );
            $data->timescale         = $this->_bigEndianUnsignedLong( 20 );
            $data->duration          = $this->_bigEndianUnsignedLong( 24 );
            $data->language          = $this->_bigEndianIso639Code( 32 );
            
        } else {
            
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 );
            $data->modification_time = $this->_bigEndianUnsignedLong( 8 );
            $data->timescale         = $this->_bigEndianUnsignedLong( 12 );
            $data->duration          = $this->_bigEndianUnsignedLong( 16 );
            $data->language          = $this->_bigEndianIso639Code( 20 );
        }
        
        return $data;
    }
}
