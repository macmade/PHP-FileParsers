<?php

final class Mpeg4_Atom_Trak extends Mpeg4_ContainerAtom
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
    protected $_type = 'trak';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'tkhd':
                
                return true;
            
            case 'tref':
                
                return true;
            
            case 'edts':
                
                return true;
            
            case 'mdia':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
