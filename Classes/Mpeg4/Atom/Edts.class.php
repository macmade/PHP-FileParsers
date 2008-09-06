<?php

final class Mpeg4_Atom_Edts extends Mpeg4_ContainerAtom
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
    protected $_type = 'edts';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'elst':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
