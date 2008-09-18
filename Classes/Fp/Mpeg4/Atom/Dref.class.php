<?php

# $Id$

final class Fp_Mpeg4_Atom_Dref extends Fp_Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'alpha';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'dref';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entries     = array();
        
        for( $i = 8; $i < $this->_dataLength; $i++ ) {
            
            $entryLength = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entryType   = substr( $this->_data, $i + 4, 4 );
            
            if( $entryType === 'urn ' ) {
                
                $entry = $this->_dataEntryUrnBox( substr( $this->_data, $i + 8, $entryLength - 8 ) );
                
            } elseif( $entryType === 'url ' ) {
                
                $entry = $this->_dataEntryUrlBox( substr( $this->_data, $i + 8, $entryLength - 8 ) );
                
            } else {
                
                $entry = new stdClass();
            }
            
            $data->entries[] = $entry;
            $i              += $entryLength;
        }
        
        return $data;
    }
    
    protected function _dataEntryUrnBox( $rawData )
    {
        $data       = $this->_dataEntryBox( $rawData );
        $data->type = 'urn ';
        
        if( strlen( $rawData ) > 4 ) {
            
            $sep            = strpos( $rawData, chr( 0 ) );
            $data->name     = substr( $rawData, 4, $sep - 4 );
            $data->location = substr( $rawData, $sep + 1, -1 );
        }
        
        return $data;
    }
    
    protected function _dataEntryUrlBox( $rawData )
    {
        $data       = $this->_dataEntryBox( $rawData );
        $data->type = 'url ';
        
        if( strlen( $rawData ) > 4 ) {
            
            $data->location = substr( $rawData, 4 );
        }
        
        return $data;
    }
    
    protected function _dataEntryBox( $rawData )
    {
        $data                        = new stdClass();
        $version                     = unpack( 'N', str_pad( substr( $rawData, 0, 1 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        $flags                       = unpack( 'N', str_pad( substr( $rawData, 1, 3 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        $data->version               = $version[ 1 ];
        $data->flags                 = new stdClass();
        $data->flags->self_reference = $flags & 0x000001;
        
        return $data;
    }
}
