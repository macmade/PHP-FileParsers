<?php

/**
 * MPEG-4 unknown atom
 * 
 * This class is used for the MPEG-4 atoms that are not part of ISO-14496-12.
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4
 * @version         0.1
 */
final class Fp_Mpeg4_UnknownAtom extends Fp_Mpeg4_DataAtom
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
    protected $_type = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The atom type
     * @return  NULL
     */
    public function __construct( $type )
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Sets the atom type
        $this->_type =substr( $type, 0, 4 );
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        return new stdClass();
    }
}
