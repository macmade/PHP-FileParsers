<?php

/**
 * MPEG-4 atom abstract
 * 
 * This abstract class is the base class for all MPEG-4 atom classes.
 * 
 * SDL from ISO-14496-12:
 * 
 * aligned( 8 ) class Box ( unsigned int( 32 ) boxtype, optional unsigned int( 8 )[ 16 ] extended_type )
 * { 
 *      unsigned int( 32 ) size;
 *      unsigned int( 32 ) type = boxtype;
 *      
 *      if( size == 1 ) {
 *          
 *          unsigned int( 64 ) largesize;
 *          
 *      } elseif( size == 0 ) {
 *          
 *          // Box extends to end of file
 *      }
 *      
 *      if( boxtype == 'uuid') {
 *          
 *          unsigned int( 8 )[ 16 ] usertype = extended_type;
 *      }
 * }
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Mpeg4
 * @version         0.1
 */
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
    
    // Abstract methods
    abstract public function __toString();
    abstract public function getLength();
    
    // Dividers values for the fixed point methods
    private static $_dividers = array(
        2  => 4,            // 1 << 2  - (2 ** 2)  - For the 30.2 fixed point numbers
        8  => 256,          // 1 << 8  - (2 ** 8)  - For the 8.8 fixed point numbers
        16 => 65536,        // 1 << 16 - (2 ** 16) - For the 16.16 fixed point numbers
        30 => 1073741824    // 1 << 30 - (2 ** 30) - For the 2.30 fixed point numbers
    );
    
    // Atom type
    protected $_type         = '';
    
    // Extended state
    protected $_extended     = false;
    
    // Parent atom, if any
    protected $_parent       = NULL;
    
    /**
     * Gets a big endian unsigned long from the atom data
     * 
     * @param   int     $dataOffset The beginning of the field in the atom data
     * @return  float   The big endian unsigned long
     */
    protected function _bigEndianUnsignedLong( $dataOffset )
    {
        // Gets the unsigned long - big endian from the atom data
        $unpackData = unpack( 'N', substr( $this->_data, $dataOffset, 4 ) );
        
        // Return the big endian unsigned long
        return $unpackData[ 1 ];
    }
    
    /**
     * Gets a big endian unsigned short from the atom data
     * 
     * @param   int     $dataOffset The beginning of the field in the atom data
     * @return  float   The big endian unsigned short
     */
    protected function _bigEndianUnsignedShort( $dataOffset )
    {
        // Gets the unsigned long - big endian from the atom data
        $unpackData = unpack( 'n', substr( $this->_data, $dataOffset, 2 ) );
        
        // Return the big endian unsigned long
        return $unpackData[ 1 ];
    }
    
    /**
     * Gets a fixed point number from the atom data
     * 
     * Actually, only 8.8, 16.16, 30.2 and 2.30 fixed point formats are supported.
     * 
     * @param   int     $dataOffset         The beginning of the fixed point number field in the atom data
     * @param   int     $integerLength      The number of bits for the integer part (2, 8, 16 or 30)
     * @param   int     $fractionalLength   The number of bits for the fractional part (2, 8, 16 or 30)
     * @return  float   The fixed point number
     */
    protected function _bigEndianFixedPoint( $dataOffset, $integerLength, $fractionalLength )
    {
        // Checks if the fixed point number is expressed on 16 or 32 bits
        if( $integerLength + $fractionalLength === 16 ) {
            
            // Unsigned short - big endian
            $unpackFormat   = 'n';
            
            // Mask for the fractional part
            $fractionalMask = 0x00FF;
            
            // Length of the data to read
            $dataLength     = 2;
            
        } else {
            
            // Unsigned long - big endian
            $unpackFormat   = 'N';
            
            // Mask for the fractional part
            $fractionalMask = 0x0000FFFF;
            
            // Length of the data to read
            $dataLength     = 4;
        }
        
        // Gets the decimal value for the fixed point number from the atom data
        $unpackData = unpack( $unpackFormat, substr( $this->_data, $dataOffset, $dataLength ) );
        
        // Computes the integer part
        $integer    = $unpackData[ 1 ] >> $fractionalLength;
        
        // Computes the fractional part
        $fractional = ( $unpackData[ 1 ] & $fractionalMask ) / self::$_dividers[ $fractionalLength ];
        
        // Returns the fixed point number
        return $integer + $fractional;
    }
    
    /**
     * Gets an ISO-639-2 language code from the atom data
     * 
     * @param   int     $dataOffset The beginning of the language code field in the atom data (pad bit included, so 16 bits!)
     * @return  string  The ISO-639-2 language code
     */
    protected function _bigEndianIso639Code( $dataOffset )
    {
        // Gets an big endian unsigned short (16 bits) from the given data offset
        $unpackData = unpack( 'n', substr( $this->_data, $dataOffset, 2 ) );
        
        // Gets letters (each letter is coded on 5 bits)
        $letter1 = ( $unpackData[ 1 ] & 0x7C00 ) >> 10;  // Mask is 0111 1100 0000 0000
        $letter2 = ( $unpackData[ 1 ] & 0x03E0 ) >> 5;   // Mask is 0000 0011 1110 0000
        $letter3 = ( $unpackData[ 1 ] & 0x001F );        // Mask is 0000 0000 0001 1111
        
        // Returns the language code as a string
        // 0x60 - 96 is added to each letter, as the language codes are lowercase letters
        return chr( $letter1 + 0x60 ) . chr( $letter2 + 0x60 ) . chr( $letter3 + 0x60 );
    }
    
    /**
     * Marks the atom as extended (length on 64 bits), or not
     * 
     * @param   boolean $value  True if the atom is extended, otherwise false
     * @return  boolean
     */
    public function setExtended( $value = true )
    {
        // Sets the extended state
        $this->_extended = ( boolean )$value;
        
        return true;
    }
    
    /**
     * Checks if the atom is extended (length on 64 bits)
     * 
     * @return  boolean
     */
    public function isExtended()
    {
        // Returns the extended state
        return $this->_extended;
    }
    
    /**
     * Gets the atom type
     * 
     * @return  string  The atom type (4 chars)
     */
    public function getType()
    {
        // Returns the atom type
        return $this->_type;
    }
    
    /**
     * Gets the atom hierarchy
     * 
     * @return  array  An array with every parent atom to the current one (included)
     */
    public function getHierarchy()
    {
        // Checks for a parent atom
        if( is_object( $this->_parent ) ) {
            
            // Gets the hierarchy from the parent
            $hierarchy   = $this->_parent->getHierarchy();
            
            // Adds the current atom to the hierarchy
            $hierarchy[] = $this->_type;
            
        } else {
            
            // Top level atom
            $hierarchy = array( $this->_type );
        }
        
        // Return the atom hierarchy
        return $hierarchy;
    }
}
