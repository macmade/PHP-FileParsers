<?php

final class Mpeg4_Atom_Hdlr extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'hdlr';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data               = parent::getProcessedData();
        $data->handler_type = substr( $this->_data, 8, 4 );
        $data->name         = substr( $this->_data, 24, -1 );
        
        return $data;
    }
}
