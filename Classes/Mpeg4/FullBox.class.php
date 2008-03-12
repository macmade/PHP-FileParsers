<?php

abstract class Mpeg4_FullBox extends Mpeg4_DataAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract protected function _processFlags( $rawFlags );
    
    public function getProcessedData()
    {
        $data = new stdClass();
        
        $version = unpack( 'N', str_pad( substr( $this->_data, 0, 1 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        $flags   = unpack( 'N', str_pad( substr( $this->_data, 1, 3 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        
        $data->version = $version[ 1 ];
        $data->flags   = $this->_processFlags( $flags[ 1 ] );
        
        return $data;
    }
}
