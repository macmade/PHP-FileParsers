<?php

final class Fp_Mpeg4_Atom_Smhd extends Fp_Mpeg4_FullBox
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
    protected $_type = 'smhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data          = parent::getProcessedData();
        $data->balance = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 4 );
        
        return $data;
    }
}
