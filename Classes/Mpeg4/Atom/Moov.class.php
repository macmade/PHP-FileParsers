<?php

final class Mpeg4_Atom_Moov extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'moov';
    
    public function validChildType( $type ) {
        
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
