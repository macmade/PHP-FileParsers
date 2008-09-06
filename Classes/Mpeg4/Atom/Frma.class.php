<?php

/**
 * MPEG-4 FRMA atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class OriginalFormatBox( codingname ) extends Box ( 'frma' )
 * {
 *      unsigned int( 32 ) data_format = codingname;
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
final class Mpeg4_Atom_Frma extends Mpeg4_DataAtom
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
    protected $_type = 'frma';
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Data storage
        $data = new stdClass();
        
        // Data format
        $data->data_format = substr( $this->_data, 0, 4 );
        
        // Returns the processed data
        return $data;
    }
}
