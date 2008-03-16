<?php

final class Mpeg4_Atom_Skip extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'skip';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'udta':
                
                return true;
            
            case 'cprt':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
