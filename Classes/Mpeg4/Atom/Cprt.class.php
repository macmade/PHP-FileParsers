<?php

/**
 * MPEG-4 CPRT atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class CopyrightBox extends FullBox( 'cprt', version = 0, 0 )
 * {
 *      const bit( 1 ) pad = 0;
 *      unsigned int( 5 )[ 3 ] language;
 *      string notice;
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.2
 */
final class Mpeg4_Atom_Cprt extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.2';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'cprt';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Gets the processed data from the parent (fullbox)
        $data           = parent::getProcessedData();
        
        // Process the atom data
        $data->language = $this->_bigEndianIso639Code( 4 );
        
        // Tries the get the byte order mark
        $noticeBom      = $this->_bigEndianUnsignedShort( 6 );
        
        // Checks for the byte order mark
        if( ( $noticeBom & 0xFEFF ) === $noticeBom ) {
            
            // UTF-16 string
            $data->notice = substr( $this->_data, 8, -1 );
            
        } else {
            
            // UTF-8 string
            $data->notice = substr( $this->_data, 6, -1 );
        }
        
        // Return the processed data
        return $data;
    }
}
