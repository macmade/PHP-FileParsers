<?php

/**
 * MPEG-4 fullbox atom abstract
 * 
 * This abstract class is the base class for all MPEG-4 atom classes based on the fullbox model.
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class FullBox( unsigned int( 32 ) boxtype, unsigned int( 8 ) v, bit( 24 ) f ) extends Box( boxtype )
 * { 
 *      unsigned int( 8 ) version = v;
 *      bit( 24 ) flags = f;
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4
 * @version         0.1
 */
abstract class Mpeg4_FullBox extends Mpeg4_DataAtom
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
     * The abstract method used to process the atom flags
     */
    abstract protected function _processFlags( $rawFlags );
    
    /**
     * Process the atom data
     * 
     * This method will only take care of the first 32 bits of the atom data.
     * 8 first bits are for the atom version, and the last 24 ones are for the
     * atom class. Child classes must call this method (parent::getProcessedData)
     * in order to gets the version and the flags. The remaining data has to be
     * processed by the child class
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Storage for the atom data
        $data = new stdClass();
        
        // Gets the first 32 bits from the atom data
        $unpackData = self::$_binUtils->bigEndianUnsignedLong( $this->_data );
        
        // Atom version - 8 first bits - Used to know how to handle the data
        $data->version = $unpackData & 0xFF000000;
        
        // Atom flags - 24 last bits
        $flags         = $unpackData & 0x00FFFFFF;
        
        // Process te atom flags - The method is called from the child class
        $data->flags   = $this->_processFlags( $flags );
        
        // Return the data object
        return $data;
    }
}
