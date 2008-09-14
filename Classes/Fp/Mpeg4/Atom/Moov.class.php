<?php

final class Fp_Mpeg4_Atom_Moov extends Fp_Mpeg4_ContainerAtom
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
    protected $_type = 'moov';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'mvhd':
                
                return true;
            
            case 'trak':
                
                return true;
            
            case 'mvex':
                
                return true;
            
            case 'ipmc':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
