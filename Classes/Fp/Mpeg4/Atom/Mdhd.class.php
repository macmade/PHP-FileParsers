<?php

final class Fp_Mpeg4_Atom_Mdhd extends Fp_Mpeg4_FullBox
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
    protected $_type = 'mdhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 24 );
            $data->language          = self::$_binUtils->bigEndianIso639Code( $this->_data, 32 );
            
        } else {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 16 );
            $data->language          = self::$_binUtils->bigEndianIso639Code( $this->_data, 20 );
        }
        
        return $data;
    }
}
