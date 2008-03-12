<?php

class Mpeg4_Atom_Mvhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'mvhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 ); // Value is 64bits!!!
            $data->modification_time = $this->_bigEndianUnsignedLong( 12 ); // Value is 64bits!!!
            $data->timescale         = $this->_bigEndianUnsignedLong( 20 );
            $data->duration          = $this->_bigEndianUnsignedLong( 24 ); // Value is 64bits!!!
            $data->rate              = $this->_bigEndianFixedPoint( 32, 16, 16 );
            $data->volume            = $this->_bigEndianFixedPoint( 36, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 48 );
            $data->next_track_ID     = $this->_bigEndianUnsignedLong( 108 );
            
        } else {
            
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 );
            $data->modification_time = $this->_bigEndianUnsignedLong( 8 );
            $data->timescale         = $this->_bigEndianUnsignedLong( 12 );
            $data->duration          = $this->_bigEndianUnsignedLong( 16 );
            $data->rate              = $this->_bigEndianFixedPoint( 20, 16, 16 );
            $data->volume            = $this->_bigEndianFixedPoint( 24, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 36 );
            $data->next_track_ID     = $this->_bigEndianUnsignedLong( 96 );
        }
        
        return $data;
    }
}
