<?php

final class Fp_Mpeg4_Atom_Mdia extends Fp_Mpeg4_ContainerAtom
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
    protected $_type = 'mdia';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'mdhd':
                
                return true;
            
            case 'hdlr':
                
                return true;
            
            case 'minf':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
