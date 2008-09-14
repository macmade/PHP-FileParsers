<?php

final class Fp_Mpeg4_Atom_Hdlr extends Fp_Mpeg4_FullBox
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
