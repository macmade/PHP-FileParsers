<?php

/**
 * MPEG-4 TKHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class TrackHeaderBox extends FullBox( 'tkhd', version, flags )
 * { 
 *      if( version == 1 ) {
 *          
 *          unsigned int( 64 ) creation_time;
 *          unsigned int( 64 ) modification_time;
 *          unsigned int( 32 ) track_ID;
 *          const unsigned int( 32 ) reserved = 0;
 *          unsigned int( 64 ) duration;
 *          
 *      } else { // version == 0
 *          
 *          unsigned int( 32 ) creation_time;
 *          unsigned int( 32 ) modification_time;
 *          unsigned int( 32 ) track_ID;
 *          const unsigned int( 32 ) reserved = 0;
 *          unsigned int( 32 ) duration;
 *      } 
 *      const unsigned int( 32 )[ 2 ] reserved = 0;
 *      template int( 16 ) layer = 0;
 *      template int( 16 ) alternate_group = 0;
 *      template int( 16 ) volume = { if track_is_audio 0x0100 else 0 };
 *      const unsigned int( 16 ) reserved = 0;
 *      template int( 32 )[ 9 ] matrix = { 0x00010000, 0, 0, 0, 0x00010000, 0, 0, 0, 0x40000000 };
 *      unsigned int( 32 ) width;
 *      unsigned int( 32 ) height;
 *  }
 * 
 * @author          Stéphane Cherpit <stef@eosgarden.com>
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4/Atom
 * @version         0.1
 */
class Mpeg4_Atom_Tkhd extends Mpeg4_FullBox
{
    /**
     * Class version constants.
     * Holds the version, the developpment state
     * and the PHP lower compatible version.
     */
    const CLASS_VERSION  = '0.1';
    const DEVEL_STATE    = 'beta';
    const PHP_COMPATIBLE = '5.2.0';
    
    // Atom type
    protected $_type = 'tkhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  $rawFlags   The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Storage for the atom flags
        $flags                   = new stdClass();
        
        // Process the atom flags
        $flags->track_enabled    = $rawFlags & 0x000001;
        $flags->track_in_movie   = $rawFlags & 0x000002;
        $flags->track_in_preview = $rawFlags & 0x000004;
        
        // Returns the atom flags
        return $flags;
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
        
        // Checks the atom version
        if( $data->version === 1 ) {
            
            // Process data
            #$data->creation_time     = $this->_bigEndianUnsignedLong( 4 ); // Value is 64bits!!!
            #$data->modification_time = $this->_bigEndianUnsignedLong( 12 ); // Value is 64bits!!!
            $data->track_ID          = $this->_bigEndianUnsignedLong( 20 );
            #$data->duration          = $this->_bigEndianUnsignedLong( 28 ); // Value is 64bits!!!
            $data->layer             = $this->_bigEndianUnsignedShort( 44 );
            $data->alternate_group   = $this->_bigEndianUnsignedShort( 46 );
            $data->volume            = $this->_bigEndianFixedPoint( 48, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 52 );
            $data->width             = $this->_bigEndianFixedPoint( 88, 16, 16 );
            $data->height            = $this->_bigEndianFixedPoint( 92, 16, 16 );
            
        } else {
            
            // Process data
            $data->creation_time     = $this->_bigEndianUnsignedLong( 4 );
            $data->modification_time = $this->_bigEndianUnsignedLong( 8 );
            $data->track_ID          = $this->_bigEndianUnsignedLong( 12 );
            $data->duration          = $this->_bigEndianUnsignedLong( 20 );
            $data->layer             = $this->_bigEndianUnsignedShort( 32 );
            $data->alternate_group   = $this->_bigEndianUnsignedShort( 34 );
            $data->volume            = $this->_bigEndianFixedPoint( 36, 8, 8 );
            $data->matrix            = $this->_decodeMatrix( 40 );
            $data->width             = $this->_bigEndianFixedPoint( 76, 16, 16 );
            $data->height            = $this->_bigEndianFixedPoint( 80, 16, 16 );
        }
        
        // Return the processed data
        return $data;
    }
}
