<?php

class Mpeg4_Atom_Co64 extends Mpeg4_DataAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'co64';
    
    public function getProcessedData()
    {
        return new stdClass();
    }
}
