<?php

final class Fp_Mpeg4_Atom_Minf extends Fp_Mpeg4_ContainerAtom
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
    protected $_type = 'minf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'vmhd':
                
                return true;
            
            case 'smhd':
                
                return true;
            
            case 'hmhd':
                
                return true;
            
            case 'nmhd':
                
                return true;
            
            case 'dinf':
                
                return true;
            
            case 'stbl':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
