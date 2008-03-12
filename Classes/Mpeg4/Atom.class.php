<?php

abstract class Mpeg4_Atom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function __toString();
    abstract public function getLength();
    
    private static $_dividers = array(
        2  => 4,            // 1 << 2  (2**2)
        8  => 256,          // 1 << 8  (2**8)
        16 => 65536,        // 1 << 16 (2**16)
        30 => 1073741824    // 1 << 30 (2**30)
    );
    protected $_type         = '';
    protected $_extended     = false;
    protected $_parent       = NULL;
    
    protected function _bigEndianUnsignedLong( $dataOffset )
    {
        $unpackData = unpack( 'N', substr( $this->_data, $dataOffset, 4 ) );
        
        return $unpackData[ 1 ];
    }
    
    protected function _bigEndianUnsignedShort( $dataOffset )
    {
        $unpackData = unpack( 'n', substr( $this->_data, $dataOffset, 2 ) );
        
        return $unpackData[ 1 ];
    }
    
    protected function _bigEndianFixedPoint( $dataOffset, $integerLength, $fractionalLength )
    {
        if( $integerLength + $fractionalLength === 16 ) {
            
            $unpackFormat   = 'n';
            $fractionalMask = 0x00FF;
            $dataLength     = 2;
            
        } else {
            
            $unpackFormat   = 'N';
            $fractionalMask = 0x0000FFFF;
            $dataLength     = 4;
        }
        
        $unpackData = unpack( $unpackFormat, substr( $this->_data, $dataOffset, $dataLength ) );
        $integer    = $unpackData[ 1 ] >> $fractionalLength;
        $fractional = ( $unpackData[ 1 ] & $fractionalMask ) / self::$_dividers[ $fractionalLength ];
        
        return $integer + $fractional;
    }
    
    protected function _bigEndianIso639Code( $dataOffset )
    {
        $unpackData = unpack( 'n', substr( $this->_data, $dataOffset, 2 ) );
        $part1      = ( $unpackData[ 1 ] & 0x7C00 ) >> 10;  // Mask is 0111 1100 0000 0000
        $part2      = ( $unpackData[ 1 ] & 0x03E0 ) >> 5;   // Mask is 0000 0011 1110 0000
        $part3      = ( $unpackData[ 1 ] & 0x001F );        // Mask is 0000 0000 0001 1111
        
        return chr( $part1 + 0x60 ) . chr( $part2 + 0x60 ) . chr( $part3 + 0x60 );
    }
    
    public function setExtended( $value = true )
    {
        $this->_extended = ( boolean )$value;
        
        return true;
    }
    
    public function isExtended()
    {
        return $this->_extended;
    }
    
    public function getType()
    {
        return $this->_type;
    }
    
    public function getHierarchy()
    {
        if( is_object( $this->_parent ) ) {
            
            $parents   = $this->_parent->getHierarchy();
            $parents[] = $this->_type;
            
        } else {
            
            $parents = array( $this->_type );
        }
        
        return $parents;
    }
}
