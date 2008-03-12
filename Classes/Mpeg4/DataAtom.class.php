<?php

abstract class Mpeg4_DataAtom extends Mpeg4_Atom
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function getProcessedData();
    
    protected $_type       = '';
    protected $_data       = '';
    protected $_dataLength = 0;
    protected $_final      = false;
    
    public function __toString()
    {
        if( $this->_final ) {
            
            $length = pack( 'N', 0 );
            
            return $length . $this->_type . $this->_data;
            
        } elseif( $this->_extended ) {
            
            $length = $this->_dataLength + 16;
            
            $length32 = $length & 0x00001111;
            $length64 = $length >> 32;
            
            $length = pack( 'N/N', $length64, $length32 );
            
            return  pack( 'N', 1 ) . $this->_type . $length . $this->_data;
            
        } else {
            
            $length = pack( 'N', $this->_dataLength + 8 );
            
            return $length . $this->_type . $this->_data;
        }
    }
    
    protected function _decodeMatrix( $dataOffset )
    {
        $matrix    = new stdClass();
        $matrix->a = $this->_bigEndianFixedPoint( $dataOffset,      16, 16 );
        $matrix->b = $this->_bigEndianFixedPoint( $dataOffset + 4,  16, 16 );
        $matrix->u = $this->_bigEndianFixedPoint( $dataOffset + 8,   2, 30 );
        $matrix->c = $this->_bigEndianFixedPoint( $dataOffset + 12, 16, 16 );
        $matrix->d = $this->_bigEndianFixedPoint( $dataOffset + 16, 16, 16 );
        $matrix->v = $this->_bigEndianFixedPoint( $dataOffset + 20,  2, 30 );
        $matrix->x = $this->_bigEndianFixedPoint( $dataOffset + 24, 16, 16 );
        $matrix->y = $this->_bigEndianFixedPoint( $dataOffset + 28, 16, 16 );
        $matrix->w = $this->_bigEndianFixedPoint( $dataOffset + 32,  2, 30 );
        
        return $matrix;
    }
    
    public function getRawData()
    {
        return $this->_data;
    }
    
    public function getLength()
    {
        if( $this->_final ) {
            
            return 1;
            
        } elseif( $this->_extended ) {
            
            return $this->_dataLength + 16;
            
        } else {
            
            return $this->_dataLength + 8;
        }
    }
    
    public function getDataLength()
    {
        return $this->_dataLength;
    }
    
    public function getHexData( $chunkSplit = 0, $sep = ' ' )
    {
        if( $chunkSplit ) {
            
            return chunk_split( bin2hex( $this->_data ), ( int )$chunkSplit, ( string )$sep );
        }
        
        return bin2hex( $this->_data );
    }
    
    public function getBinData( $chunkSplit = 0, $sep = ' ' )
    {
        $bin = '';
        
        for( $i = 0; $i < $this->_dataLength; $i++  ) {
            
            $bin .= str_pad( decbin( ord( substr( $this->_data, $i, 1 ) ) ), 8, 0, STR_PAD_LEFT );
        }
        
        if( $chunkSplit ) {
            
            return chunk_split( $bin, ( int )$chunkSplit, ( string )$sep );
        }
        
        return $bin;
    }
    
    public function setRawData( $data )
    {
        $this->_data       = $data;
        $this->_dataLength = strlen( $data );
        
        return true;
    }
    
    public function setFinal( $value = true )
    {
        $this->_final = ( boolean )$value;
        
        return true;
    }
    
    public function isFinal()
    {
        return $this->_final;
    }
}
