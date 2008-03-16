<?php

final class Mpeg4_Atom_Stbl extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    protected $_type = 'stbl';
    
    public function validChildType( $type ) {
        
        switch( $type ) {
            
            case 'stsd':
                
                return true;
            
            case 'stts':
                
                return true;
            
            case 'ctts':
                
                return true;
            
            case 'stsc':
                
                return true;
            
            case 'stsz':
                
                return true;
            
            case 'stz2':
                
                return true;
            
            case 'stco':
                
                return true;
            
            case 'co64':
                
                return true;
            
            case 'stss':
                
                return true;
            
            case 'stsh':
                
                return true;
            
            case 'padb':
                
                return true;
            
            case 'stdp':
                
                return true;
            
            case 'sdtp':
                
                return true;
            
            case 'sbgp':
                
                return true;
            
            case 'sgpd':
                
                return true;
            
            case 'subs':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
