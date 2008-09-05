<?php

/**
 * GIF file parser
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Gif
 * @version         0.1
 */
class Gif_Parser
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
     * The identifiers of the GIF blocks
     */
    const TRAILER                   = 0x3b;
    const IMAGE_DESCRIPTOR          = 0x2c;
    const EXTENSION                 = 0x21;
    const EXTENSION_GRAPHIC_CONTROL = 0xf9;
    const EXTENSION_COMMENT         = 0xfe;
    const EXTENSION_PLAIN_TEXT      = 0x01;
    const EXTENSION_APPLICATION     = 0xff;
    
    /**
     * The PHP file handler for the GIF file
     */
    protected $_fileHandle = NULL;
    
    /**
     * An stdClass object that will be filled with the GIF informations
     */
    protected $_gifInfos   = NULL;
    
    /**
     * The file path
     */
    protected $_filePath   = '';
    
    /**
     * Class constructor
     * 
     * @param   string      $file   The location of the GIF file
     * @return  NULL
     * @throws  Exception   If the file does not exist, is not readable, or if PHP isn't able to open a file handle
     */
    public function __construct( $file )
    {
        // Checks if the requested file exists
        if( !file_exists( $file ) ) {
            
            // File does not exist
            throw new Exception( 'The requested file ' . $file . ' does not exist.' );
        }
        
        // Checks if the requested file can be read
        if( !is_readable( $file ) ) {
            
            // Unreadable file
            throw new Exception( 'The requested file ' . $file . ' is not readable.' );
        }
        
        // Opens a binary file hander
        $this->_fileHandle = fopen( $file, 'rb' );
        
        // Checks the file handler
        if( !$this->_fileHandle ) {
            
            // Invalid file handler
            throw new Exception( 'Cannot open requested file ' . $file . '.' );
        }
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Parses the file and stores the informations
        $this->_gifInfos = $this->_parseFile();
        
        // Closes the file handle
        fclose( $this->_fileHandle );
    }
    
    /**
     * 
     */
    protected function _readLittleEndianUnsignedChar()
    {
        $data  = unpack( 'C', fread( $this->_fileHandle, 1 ) );
        return array_shift( $data );
    }
    
    /**
     * 
     */
    protected function _readLittleEndianUnsignedShort()
    {
        $data = unpack( 'v', fread( $this->_fileHandle, 2 ) );
        return array_shift( $data );
    }
    
    /**
     * 
     */
    protected function _getLogicalScreenDescriptor()
    {
        // Storage
        $lsd                         = new stdClass();
        
        // Gets the image dimensions
        $lsd->width                  = $this->_readLittleEndianUnsignedShort();
        $lsd->height                 = $this->_readLittleEndianUnsignedShort();
        
        // Gets the packed fields
        $packedFields                = $this->_readLittleEndianUnsignedChar();
        
        // Wether to global color table will follow 
        $lsd->globalColorTableFlag   = ( $packedFields & 0x80 ) >> 7;  // Mask is 1000 0000
        
        // The color resolution
        $lsd->colorResolution        = ( $packedFields & 0x70 ) >> 4;  // Mask is 0111 0000
        
        // Wether the global color table is sorted
        $lsd->sortFlag               = ( $packedFields & 0x08 ) >> 3;  // Mask is 0000 1000
        
        // The size of the global color table
        $lsd->sizeOfGlobalColorTable = ( $packedFields & 0x07 );       // Mask is 0000 0111
        
        // Gets the background color index
        $lsd->bgColorIndex           = $this->_readLittleEndianUnsignedChar();
        
        // Gets the pixel aspect ratio
        $lsd->pixelAspectRatio       = $this->_readLittleEndianUnsignedChar();
        
        // Returns the logical screen descriptor
        return $lsd;
    }
    
    /**
     * 
     */
    protected function _getColorTable( $size )
    {
        // Storage
        $table = array();
        
        // Computes the number of colors in the global color table
        $length = pow( 2, $size + 1 );
        
        // Process the global color table
        for( $i = 0; $i < $length; $i++ ) {
            
            // Storage
            $table[ $i ]        = new stdClass();
            
            // Gets the color values
            $red                = $this->_readLittleEndianUnsignedChar();
            $green              = $this->_readLittleEndianUnsignedChar();
            $blue               = $this->_readLittleEndianUnsignedChar();
            
            // Gets the hexadecimal values
            $redHex             = dechex( $red );
            $greenHex           = dechex( $green );
            $blueHex            = dechex( $blue );
            
            // Completes each hexadecimal value if needed
            $redHex             = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
            $greenHex           = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
            $blueHex            = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
            
            // Stores the color values
            $table[ $i ]->red   = $red;
            $table[ $i ]->green = $green;
            $table[ $i ]->blue  = $blue;
            $table[ $i ]->hex   = '#' . strtoupper( $redHex . $greenHex . $blueHex );
        }
        
        // Returns the global color table
        return $table;
    }
    
    /**
     * 
     */
    protected function _getImageSeparator()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the position
        $block->left                  = $this->_readLittleEndianUnsignedShort();
        $block->top                   = $this->_readLittleEndianUnsignedShort();
        
        // Gets the dimensions
        $block->width                 = $this->_readLittleEndianUnsignedShort();
        $block->height                = $this->_readLittleEndianUnsignedShort();
        
        // Gets the packed fields
        $packedFields                 = $this->_readLittleEndianUnsignedChar();
        
        // Wether to local color table will follow 
        $block->localColorTableFlag   = ( $packedFields & 0x80 ) >> 7;  // Mask is 1000 0000
        
        // Wheter the image is interlaced
        $block->interlaceFlag         = ( $packedFields & 0x40 ) >> 6;  // Mask is 0100 0000
        
        // Wether the local color table is sorted
        $block->sortFlag              = ( $packedFields & 0x20 ) >> 5;  // Mask is 0010 0000
        
        // The size of the local color table
        $block->sizeOfLocalColorTable = ( $packedFields & 0x07 );       // Mask is 0000 0111
        
        // Checks if the local color table flag is set
        if( $block->localColorTableFlag ) {
            
            // Local color table follows - Gets its values
            $block->localColorTable   = $this->_getColorTable( $block->sizeOfLocalColorTable  );
        }
        
        // Gets the LZW minimum code size
        $block->lzwMinimumCodeSize    = $this->_readLittleEndianUnsignedChar();
        
        // Gets the image data
        $block->imageData             = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getDataSubBlocks()
    {
        // Storage
        $data = array();
        
        // Gets the next block size
        $blockSize = $this->_readLittleEndianUnsignedChar();
        
        // Process the data blocks until the end of the parent block
        while( $blockSize !== 0x00 ) {
            
            // Storage
            $block       = new stdClass();
            
            // Adds the block size
            $block->size = $blockSize;
            
            // For now, do not process or store the block data
            fseek( $this->_fileHandle, $blockSize, SEEK_CUR );
            
            // Adds the data block
            $data[]      = $block;
            
            // Gets the next block size
            $blockSize   = $this->_readLittleEndianUnsignedChar();
        }
        
        // Returns the data of the sub blocks
        return $data;
    }
    
    /**
     * 
     */
    protected function _getGraphicControlExtension()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the block size
        $block->size                  = $this->_readLittleEndianUnsignedChar();
        
        // Gets the packed fields
        $packedFields                 = $this->_readLittleEndianUnsignedChar();
        
        // The way in which the graphic is to be treated after being displayed
        $block->disposalMethod        = ( $packedFields & 0x1c ) >> 2;  // Mask is 0001 1100
        
        // Whether an user input is expected
        $block->userInputFlag         = ( $packedFields & 0x02 ) >> 1;  // Mask is 0000 0010
        
        // Whether a transparency index is given
        $block->transparentColorFlag  = ( $packedFields & 0x01 );       // Mask is 0000 0001
        
        // Gets the delay time
        $block->delayTime             = $this->_readLittleEndianUnsignedShort();
        
        // Gets the transparent color index
        $block->transparentColorIndex = $this->_readLittleEndianUnsignedChar();
        
        // Block terminator
        fread( $this->_fileHandle, 1 );
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getCommentExtension()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the block size
        $block->size        = $this->_readLittleEndianUnsignedChar();
        
        // Gets the comment data blocks
        $block->commentData = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getPlainTextExtension()
    {
        // Storage
        $block                           = new stdClass();
        
        // Gets the block size
        $block->size                     = $this->_readLittleEndianUnsignedChar();
        
        // Gets the left position of the text grid
        $block->textGridLeftPosition     = $this->_readLittleEndianUnsignedShort();
        
        // Gets the top position of the text grid
        $block->textGridTopPosition      = $this->_readLittleEndianUnsignedShort();
        
        // Gets the width of the text grid
        $block->textGridWidth            = $this->_readLittleEndianUnsignedShort();
        
        // Gets the height of the text grid
        $block->textGridHeight           = $this->_readLittleEndianUnsignedShort();
        
        // Gets the width of the character cell
        $block->characterCellWidth       = $this->_readLittleEndianUnsignedChar();
        
        // Gets the height of the character cell
        $block->characterCellHeight      = $this->_readLittleEndianUnsignedChar();
        
        // Gets the color index for the foreground color
        $block->textForegroundColorIndex = $this->_readLittleEndianUnsignedChar();
        
        // Gets the color index for the background color
        $block->textBackgroundColorIndex = $this->_readLittleEndianUnsignedChar();
        
        // Gets the plain text data blocks
        $block->plainTextData            = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getApplicationExtension()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the block size
        $block->size                      = $this->_readLittleEndianUnsignedChar();
        
        // Gets the application identifier
        $block->applicationIdentifier     = fread( $this->_fileHandle, 8 );
        
        // Gets the application identifier code
        $block->applicationIdentifierCode = fread( $this->_fileHandle, 3 );
        
        // Gets the application data blocks
        $block->applicationData           = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _parseFile()
    {
        // Checks the GIF signature
        if( fread( $this->_fileHandle, 3 ) !== 'GIF' ) {
            
            // Wrong file type
            throw new Exception( 'File ' . $this->_filePath . ' is not a GIF file.' );
        }
        
        // Storage
        $infos                          = new stdClass();
        
        // Gets the GIF version
        $infos->version                 = fread( $this->_fileHandle, 3 );
        
        // Gets the logical screen descriptor
        $infos->logicalScreenDescriptor = $this->_getLogicalScreenDescriptor();
        
        // Checks if the global color table flag is set
        if( $infos->logicalScreenDescriptor->globalColorTableFlag ) {
            
            // Global color table follows - Gets its values
            $infos->globalColorTable    = $this->_getColorTable( $infos->logicalScreenDescriptor->sizeOfGlobalColorTable );
        }
        
        // Gets the identifier of the next block
        $blockId                        = $this->_readLittleEndianUnsignedChar();
        
        // Process the blocks until the trailer (0x3b) is reached
        while( $blockId !== self::TRAILER ) {
            
            // Parses the block
            $this->_parseBlock( $blockId, $infos );
            
            // Gets the identifier of the next block
            $blockId = $this->_readLittleEndianUnsignedChar();
        }
        
        // Returns the informations
        return $infos;
    }
    
    /**
     * 
     */
    protected function _parseBlock( $id, stdClass $infos )
    {
        // Checks the block identifier
        switch( $id ) {
            
            // Image descriptor block
            case self::IMAGE_DESCRIPTOR :
                
                // Checks if the storage array exists
                if( !isset( $infos->images ) ) {
                    
                    // Creates the storage array
                    $infos->images = array();
                }
                
                // Adds the storage object for the current image
                $image = new stdClass();
                
                // Gets the image separator block
                $image->imageSeparator = $this->_getImageSeparator();
                
                // Adds the current image
                $infos->images[] = $image;
                break;
            
            // Extension block
            case self::EXTENSION :
                
                // Gets the extension block identifier
                $extBlockId = $this->_readLittleEndianUnsignedChar();
                
                // Parses the extension block
                $this->_parseExtensionBlock( $extBlockId, $infos );
                break;
            
            // Unknown block
            default:
                
                // Invalid block identifier
                throw new Exception( 'Invalid GIF block identifier: \'0x' . dechex( $id ) . '\'.' );
                break;
        }
    }
    
    /**
     * 
     */
    protected function _parseExtensionBlock( $id, stdClass $infos )
    {
        // Checks the extension block identifier
        switch( $id ) {
            
            // Graphic control extension block
            case self::EXTENSION_GRAPHIC_CONTROL:
                
                // Checks if the storage array exists
                if( !isset( $infos->graphicControlExtension ) ) {
                    
                    // Creates the storage array
                    $infos->graphicControlExtension = array();
                }
                
                // Gets the graphic control extension block
                $infos->graphicControlExtension[] = $this->_getGraphicControlExtension();
                break;
            
            // Comment extension block
            case self::EXTENSION_COMMENT:
                
                // Checks if the storage array exists
                if( !isset( $infos->commentExtension ) ) {
                    
                    // Creates the storage array
                    $infos->commentExtension = array();
                }
                
                // Gets the comment extension block
                $infos->commentExtension[] = $this->_getCommentExtension();
                break;
            
            // Plain text extension block
            case self::EXTENSION_PLAIN_TEXT:
                
                // Checks if the storage array exists
                if( !isset( $infos->plainTextExtension ) ) {
                    
                    // Creates the storage array
                    $infos->plainTextExtension = array();
                }
                
                // Gets the plain text extension block
                $infos->plainTextExtension[] = $this->_getPlainTextExtension();
                break;
            
            // Application extension block
            case self::EXTENSION_APPLICATION:
                
                // Checks if the storage array exists
                if( !isset( $infos->applicationExtension ) ) {
                    
                    // Creates the storage array
                    $infos->applicationExtension = array();
                }
                
                // Gets the application extension block
                $infos->applicationExtension[] = $this->_getApplicationExtension();
                break;
            
            // Unknown sub block
            default:
                
                // Invalid sub block identifier
                throw new Exception( 'Invalid GIF extension block identifier: \'0x' . dechex( $id ) . '\'.' );
                break;
        }
    }
    
    /**
     * 
     */
    public function getInfos()
    {
        return clone( $this->_gifInfos );
    }
}