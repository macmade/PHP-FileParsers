<?php

abstract class Mpeg4_ContainerAtom extends Mpeg4_Atom implements Iterator
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function validChildType( $type );
    
    protected $_type                 = '';
    protected $_extended             = false;
    protected $_childrenByNames      = array();
    protected $_childrenByNamesCount = array();
    protected $_children             = array();
    protected $_childrenCount        = 0;
    protected $_iteratorIndex        = 0;
    
    public function __toString()
    {
        $childrenData   = '';
        $childrenLength = 0;
        
        foreach( $this->_children as $childAtom ) {
            
            $childrenLength += $childAtom->getLength();
            $childrenData   .= ( string )$childAtom;
        }
        
        if( $this->_extended ) {
            
            $length = $childrenLength + 16;
            
            $length32 = $length & 0x00001111;
            $length64 = $length >> 32;
            
            $length = pack( 'N/N', $length64, $length32 );
            
            return pack( 'N', 1 ) . $this->_type . $length . $childrenData;
            
        } else {
            
            $length = pack( 'N', $childrenLength + 8 );
            
            return $length . $this->_type . $childrenData;
        }
    }
    
    public function rewind()
    {
        $this->_iteratorIndex = 0;
    }
    
    public function current()
    {
        return $this->_children[ $this->_iteratorIndex ];
    }
    
    public function key()
    {
        return $this->_children[ $this->_iteratorIndex ]->getType();
    }
    
    public function next()
    {
        $this->_iteratorIndex++;
    }
    
    public function valid()
    {
        return $this->_iteratorIndex < $this->_childrenCount;
    }
    
    public function getProcessedData()
    {
        $data = array();
        
        foreach( $this->_children as $childAtom ) {
            
            $childData = new stdClass();
            
            $childData->type     = $childAtom->getType();
            $childData->extended = $childAtom->isExtended();
            
            if( is_subclass_of( $childAtom, 'Mpeg4_DataAtom' ) ) {
                
                $childData->final      = $childAtom->isFinal();
                $childData->dataLength = $childAtom->getDataLength();
                $childData->data       = $childAtom->getProcessedData();
                
            } else {
                
                $childData->children = $childAtom->getProcessedData();
            }
            
            $data[] = $childData;
        }
        
        return $data;
    }
    
    public function addChild( $childType )
    {
        if( !$this->validChildType( $childType ) ) {
            
            throw new Exception( 'Atom of type ' . $childType . ' cannot be contained in ' . $this->_type );
        }
        
        $className         = 'Mpeg4_Atom_' . ucfirst( $childType );
        $atom              = new $className;
        $atom->_parent     = $this;
        $this->_children[] = $atom;
        $this->_childrenCount++;
        
        if( !isset( $this->_childrenNames[ $childType ] ) ) {
            
            $this->_childrenNames[ $childType ] = array();
        }
        
        $this->_childrenNames[ $childType ][] = key( $this->_children );
        
        return $atom;
    }
    
    public function getLength()
    {
        $length = 0;
        
        foreach( $this->_children as $childAtom ) {
            
            $length += $childAtom->getLength();
        }
        
        if( $this->_extended ) {
            
            return $length + 16;
            
        } else {
            
            return $length + 8;
        }
    }
    
    public function hasChildren()
    {
        return $this->_childrenCount > 0;
    }
    
    public function getNumberOfChildren()
    {
        return $this->_childrenCount;
    }
}

