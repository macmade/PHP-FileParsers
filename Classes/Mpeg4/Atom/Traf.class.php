<?php

final class Mpeg4_Atom_Traf extends Mpeg4_ContainerAtom
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
    protected $_type = 'traf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'tfhd':
                
                return true;
            
            case 'trun':
                
                return true;
            
            case 'sdtp':
                
                return true;
            
            case 'sbgp':
                
                return true;
            
            case 'subs':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
