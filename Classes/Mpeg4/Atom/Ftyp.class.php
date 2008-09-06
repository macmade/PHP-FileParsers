<?php

final class Mpeg4_Atom_Ftyp extends Mpeg4_DataAtom
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
    protected $_type = 'ftyp';
    
    public function getProcessedData()
    {
        $data                    = new stdClass();
        $data->major_brand       = substr( $this->_data, 0, 4 );
        $data->minor_version     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->compatible_brands = array();
        
        if( $this->_dataLength > 8 ) {
            
            for( $i = 8; $i < $this->_dataLength; $i += 4 ) {
                
                $data->compatible_brands[] = substr( $this->_data, $i, 4 );
            }
        }
        
        return $data;
    }
}
