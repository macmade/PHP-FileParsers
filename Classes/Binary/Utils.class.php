<?php

/**
 * Binary utilities
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @version         0.1
 */
final class Binary_Utils
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
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The dividers values for the fixed point methods
     */
    private static $_dividers = array(
        2  => 4,            // 1 << 2  - (2 ** 2)  - For the 30.2 fixed point numbers
        8  => 256,          // 1 << 8  - (2 ** 8)  - For the 8.8 fixed point numbers
        16 => 65536,        // 1 << 16 - (2 ** 16) - For the 16.16 fixed point numbers
        30 => 1073741824    // 1 << 30 - (2 ** 30) - For the 2.30 fixed point numbers
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return NULL
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
     * @throws  Singleton_Exception Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Singleton_Exception( 'Class ' . __CLASS__ . ' cannot be cloned' );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  object  The unique instance of the class
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Unpacks data to the specified format
     * 
     * @param   string  The unpack format (see function unpack())
     * @param   string  The data from which to read (passed by reference)
     * @param   int     The offset from which to read the data
     * @return  int     The unpacked data in the specified format
     */
    private function _unpackData( $format, &$data, $dataOffset )
    {
        // Checks the unpack format
        if( $format === 'c' || $format === 'C' ) {
            
            // Number of bytes to read from the data
            $readByte = 1;
            
        } else if( $format === 's' || $format === 'S' || $format === 'n' || $format === 'v' ) {
            
            // Number of bytes to read from the data
            $readByte = 2;
            
        } else if( $format === 'l' || $format === 'L' || $format === 'N' || $format === 'V' ) {
            
            // Number of bytes to read from the data
            $readByte = 4;
        }
        
        // Unpacks the data
        $unpackData = unpack( $format, substr( $data, $dataOffset, $readByte ) );
        
        // Returns the processed data
        return array_shift( $unpackData );
    }
    
    /**
     * Gets a signed char
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed char
     */
    public function signedChar( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'c', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned char
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned char
     */
    public function unsignedChar( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'C', $data, $dataOffset );
    }
    
    /**
     * Gets a signed short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed short
     */
    public function signedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 's', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned short
     */
    public function unsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'S', $data, $dataOffset );
    }
    
    /**
     * Gets a big endian unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The big endian unsigned short
     */
    public function bigEndianUnsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'n', $data, $dataOffset );
    }
    
    /**
     * Gets a little endian unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The little endian unsigned short
     */
    public function littleEndianUnsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'v', $data, $dataOffset );
    }
    
    /**
     * Gets a signed long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed long
     */
    public function signedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'l', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned long
     */
    public function unsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'L', $data, $dataOffset );
    }
    
    /**
     * Gets a big endian unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The big endian unsigned long
     */
    public function bigEndianUnsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'N', $data, $dataOffset );
    }
    
    /**
     * Gets a little endian unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The little endian unsigned long
     */
    public function littleEndianUnsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'V', $data, $dataOffset );
    }
    
    /**
     * Gets a fixed point number
     * 
     * Actually, only 8.8, 16.16, 30.2 and 2.30 fixed point formats are supported.
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     The number of bits for the integer part (2, 8, 16 or 30)
     * @param   int     The number of bits for the fractional part (2, 8, 16 or 30)
     * @param   int     An optionnal offset from which to read the data
     * @return  float   The fixed point number
     */
    public function bigEndianFixedPoint( &$data, $integerLength, $fractionalLength, $dataOffset )
    {
        // Checks if the fixed point number is expressed on 16 or 32 bits
        if( $integerLength + $fractionalLength === 16 ) {
            
            // Unsigned short - big endian
            $unpackFormat   = 'n';
            
            // Mask for the fractional part
            $fractionalMask = 0x00FF;
            
        } else {
            
            // Unsigned long - big endian
            $unpackFormat   = 'N';
            
            // Mask for the fractional part
            $fractionalMask = 0x0000FFFF;
        }
        
        // Gets the decimal value for the fixed point number from the data
        $unpackData = $this->_unpackData( $unpackFormat, $data, $dataOffset );
        
        // Computes the integer part
        $integer    = $unpackData >> $fractionalLength;
        
        // Computes the fractional part
        $fractional = ( $unpackData & $fractionalMask ) / self::$_dividers[ $fractionalLength ];
        
        // Returns the fixed point number
        return $integer + $fractional;
    }
    
    /**
     * Gets an ISO-639-2 language code
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  string  The ISO-639-2 language code
     */
    public function bigEndianIso639Code( &$data, $dataOffset )
    {
        // Gets an big endian unsigned short (16 bits) from the given data offset
        $unpackData = $this->_unpackData( 'n', $data, $dataOffset );
        
        // Gets letters (each letter is coded on 5 bits)
        $letter1 = ( $unpackData & 0x7C00 ) >> 10;  // Mask is 0111 1100 0000 0000
        $letter2 = ( $unpackData & 0x03E0 ) >> 5;   // Mask is 0000 0011 1110 0000
        $letter3 = ( $unpackData & 0x001F );        // Mask is 0000 0000 0001 1111
        
        // Returns the language code as a string
        // 0x60 - 96 is added to each letter, as the language codes are lowercase letters
        return chr( $letter1 + 0x60 ) . chr( $letter2 + 0x60 ) . chr( $letter3 + 0x60 );
    }
}
