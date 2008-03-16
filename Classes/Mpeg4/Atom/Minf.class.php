<?php

final class Mpeg4_Atom_Minf extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'minf';
    
    public function validChildType( $type ) {
        
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
