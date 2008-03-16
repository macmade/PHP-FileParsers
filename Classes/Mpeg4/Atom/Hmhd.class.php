<?php

final class Mpeg4_Atom_Hmhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'hmhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data             = parent::getProcessedData();
        $data->maxPDUsize = $this->_bigEndianUnsignedShort( 4 );
        $data->avgPDUsize = $this->_bigEndianUnsignedShort( 6 );
        $data->maxbitrate = $this->_bigEndianUnsignedLong( 8 );
        $data->avgbitrate = $this->_bigEndianUnsignedLong( 12 );
        
        return $data;
    }
}
