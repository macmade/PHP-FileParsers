<?php

final class Fp_Mpeg4_Atom_Meta extends Fp_Mpeg4_ContainerAtom
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
    protected $_type = 'meta';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'hdlr':
                
                return true;
            
            case 'dinf':
                
                return true;
            
            case 'ipmc':
                
                return true;
            
            case 'iloc':
                
                return true;
            
            case 'ipro':
                
                return true;
            
            case 'iinf':
                
                return true;
            
            case 'xml':
                
                return true;
            
            case 'bxml':
                
                return true;
            
            case 'pitm':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
