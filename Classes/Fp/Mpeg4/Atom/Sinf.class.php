<?php

# $Id$

final class Fp_Mpeg4_Atom_Sinf extends Fp_Mpeg4_ContainerAtom
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
    protected $_type = 'sinf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'frma':
                
                return true;
            
            case 'imif':
                
                return true;
            
            case 'schm':
                
                return true;
            
            case 'schi':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
