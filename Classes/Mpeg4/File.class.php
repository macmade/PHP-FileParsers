<?php

class Mpeg4_File extends Mpeg4_ContainerAtom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    public function __toString()
    {
        $childrenData = '';
        
        foreach( $this->_children as $childAtom ) {
            
            $childrenData .= ( string )$childAtom;
        }
        
        return $childrenData;
    }
    
    public function addChild( $childType )
    {
        $atom          = parent::addChild( $childType );
        $atom->_parent = NULL;
        
        return $atom;
    }
    
    public function getLength()
    {
        $length = 0;
        
        foreach( $this->_children as $childAtom ) {
            
            $length += $childAtom->getLength();
        }
        
        return $length;
    }
    
    public function setExtended( $value = true )
    {
        return false;
    }
    
    public function validChildType( $type ) {
        
        switch( $type ) {
            
            case 'ftyp':
                
                return true;
            
            case 'pdin':
                
                return true;
            
            case 'moov':
                
                return true;
            
            case 'moof':
                
                return true;
            
            case 'mfra':
                
                return true;
            
            case 'mdat':
                
                return true;
            
            case 'free':
                
                return true;
            
            case 'skip':
                
                return true;
            
            case 'meta':
                
                return true;
            
            default:
                
                return false;
        }
    }
}

