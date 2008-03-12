<?php

class Mpeg4_Atom_Tkhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'tkhd';
    
    protected function _processFlags( $rawFlags )
    {
        $flags                   = new stdClass();
        $flags->track_enabled    = $rawFlags & 0x000001;
        $flags->track_in_movie   = $rawFlags & 0x000002;
        $flags->track_in_preview = $rawFlags & 0x000004;
        
        return $flags;
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            #$data->creation_time     = $this->_bigEndianUnsignedLong( 4 ); // Value is 64bits!!!
            #$data->modification_time = $this->_bigEndianUnsignedLong( 12 ); // Value is 64bits!!!
            $data->track_ID          = $this->_bigEndianUnsignedLong( 20 );
            #$data->duration          = $this->_bigEndianUnsignedLong( 28 ); // Value is 64bits!!!
            $data->layer             = $this->_bigEndianUnsignedShort( 44 );
            $data->alternate_group   = $this->_bigEndianUnsignedShort( 46 );
            $data->volume            = $this->_bigEndianFixedPoint( 48, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 52 );
            $data->width             = $this->_bigEndianFixedPoint( 88, 16, 16 );
            $data->height            = $this->_bigEndianFixedPoint( 92, 16, 16 );
            
        } else {
            
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 );
            $data->modification_time = $this->_bigEndianUnsignedLong( 8 );
            $data->track_ID          = $this->_bigEndianUnsignedLong( 12 );
            $data->duration          = $this->_bigEndianUnsignedLong( 20 );
            $data->layer             = $this->_bigEndianUnsignedShort( 32 );
            $data->alternate_group   = $this->_bigEndianUnsignedShort( 34 );
            $data->volume            = $this->_bigEndianFixedPoint( 36, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 40 );
            $data->width             = $this->_bigEndianFixedPoint( 76, 16, 16 );
            $data->height            = $this->_bigEndianFixedPoint( 80, 16, 16 );
        }
        
        return $data;
    }
}
