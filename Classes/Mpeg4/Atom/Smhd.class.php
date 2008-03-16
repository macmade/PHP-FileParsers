<?php

final class Mpeg4_Atom_Smhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'smhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data          = parent::getProcessedData();
        $data->balance = $this->_bigEndianFixedPoint( 4, 8, 8 );
        
        return $data;
    }
}
