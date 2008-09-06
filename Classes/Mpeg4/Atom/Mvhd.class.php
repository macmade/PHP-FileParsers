<?php

final class Mpeg4_Atom_Mvhd extends Mpeg4_FullBox
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
    protected $_type = 'mvhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 ); // Value is 64bits!!!
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 ); // Value is 64bits!!!
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 24 ); // Value is 64bits!!!
            $data->rate              = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 32 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 36 );
            $data->matrix            = $this->_decodeMatrix( 48 );
            $data->next_track_ID     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 108 );
            
        } else {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 16 );
            $data->rate              = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 20 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 24 );
            $data->matrix            = $this->_decodeMatrix( 36 );
            $data->next_track_ID     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 96 );
        }
        
        return $data;
    }
}
