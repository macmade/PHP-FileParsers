<?php

final class Mpeg4_Atom_Nmhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'nmhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        return parent::getProcessedData();
    }
}
