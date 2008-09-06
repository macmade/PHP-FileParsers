<?php

/**
 * MPEG-4 XML atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class XMLBox extends FullBox( 'xml ', version = 0, 0 )
 * {
 *      string xml;
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.2
 */
final class Mpeg4_Atom_Xml extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.2';
    const DEVEL_STATE    = 'alpha';
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'xml ';
    
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
        $data = parent::getProcessedData();
        
        // Tries the get the byte order mark
        $bom  = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        
        // Checks for the byte order mark
        if( ( $bom & 0xFEFF ) === $bom ) {
            
            // UTF-16 XML
            $data->xml = new SimpleXMLElement( substr( $this->_data, 6, -1 ) );
            
        } else {
            
            // UTF-8 XML
            $data->xml = new SimpleXMLElement( substr( $this->_data, 4, -1 ) );
        }
        
        // Return the processed data
        return $data;
    }
}
