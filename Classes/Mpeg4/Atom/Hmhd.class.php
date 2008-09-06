<?php

final class Mpeg4_Atom_Hmhd extends Mpeg4_FullBox
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
    protected $_type = 'hmhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data             = parent::getProcessedData();
        $data->maxPDUsize = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        $data->avgPDUsize = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        $data->maxbitrate = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        $data->avgbitrate = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
        
        return $data;
    }
}
