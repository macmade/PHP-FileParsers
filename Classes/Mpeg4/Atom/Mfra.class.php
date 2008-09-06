<?php

final class Mpeg4_Atom_Mfra extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'mfra';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'tfra':
                
                return true;
            
            case 'mfro':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
