<?php

# $Id$

final class Fp_Mpeg4_Atom_Co64 extends Fp_Mpeg4_DataAtom
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
    protected $_type = 'co64';
    
    public function getProcessedData()
    {
        return new stdClass();
    }
}
