<?php

/**
 * PNG file
 * 
 * @author          Jean-David Gadina <macmade@eosgarden.com>
 * @copyright       Copyright &copy; 2008
 * @package         Png
 * @version         0.1
 */
class Png_File
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
     * Allows invalid chunk structure (not as in the PNG specification)
     */
    protected $_allowInvalidStucture  = false;
    
    /**
     * THe type of the last chunk added
     */
    protected $_lastChunk             = '';
    
    /**
     * The PNG file signature
     */
    protected $_signature             = '';
    
    /**
     * An array with the PNG chunks
     */
    protected $_chunks                = array();
    
    /**
     * The name and count of the added chunks
     */
    protected $_chunkNames            = array();
    
    /**
     * The valid PNG chunks, as in the PNG specification
     */
    protected $_validChunks           = array(
        
        // Critical chunks
        'IHDR' => true,
        'PLTE' => true,
        'IDAT' => true,
        'IEND' => true,
        
        // Ancillary chunks
        'cHRM' => true,
        'gAMA' => true,
        'iCCP' => true,
        'sBIT' => true,
        'sRGB' => true,
        'bKGD' => true,
        'hIST' => true,
        'tRNS' => true,
        'pHYs' => true,
        'sPLT' => true,
        'tIME' => true,
        'iTXt' => true,
        'tEXt' => true,
        'zTXt' => true
    );
    
    /**
     * The chunks that can be added multiple times in the file
     */
    protected $_allowedMultipleChunks = array(
        'IDAT' => true,
        'sPLT' => true,
        'iTXt' => true,
        'tEXt' => true,
        'zTXt' => true
    );
    
    /**
     * 
     */
    public function __construct()
    {
        // Sets the PNG file signature (\211   P   N   G  \r  \n \032 \n)
        $this->_signature = chr( 137 ) . chr( 80 ) . chr( 78 ) . chr( 71 )
                          . chr( 13 )  . chr( 10 ) . chr( 26 ) . chr( 10 );
    }
    
    /**
     * 
     */
    public function __toString()
    {
        // Starts with the PNG signature
        $data = $this->_signature;
        
        // Process each chunk
        foreach( $this->_chunks as $chunk ) {
            
            // Adds the current chunk
            $data .= ( string )$chunk;
        }
        
        // Returns the PNG data
        return $data;
    }
    
    public function getProcessedData()
    {
        $data = array();
        
        foreach( $this->_chunks as $chunk ) {
            
            $chunkData               = new stdClass();
            
            $chunkData->type         = $chunk->getType();
            $chunkData->size         = $chunk->getDataLength();
            $chunkData->isCritical   = $chunk->isCritical();
            $chunkData->isAncillary  = $chunk->isAncillary();
            $chunkData->isPrivate    = $chunk->isPrivate();
            $chunkData->isSafeToCopy = $chunk->isSafeToCopy();
            $chunkData->data         = $chunk->getProcessedData();
            
            $data[]                   = $chunkData;
        }
        
        return $data;
    }
    
    /**
     * 
     */
    public function addChunk( $chunkType )
    {
        $invalid = $this->isInvalidChunk( $chunkType );
        
        if( $invalid && !$this->_allowInvalidStucture ) {
            
            throw new Png_Exception( $invalid, Png_Exception::EXCEPTION_INVALID_CHUNK );
        }
            
        $className        = 'Png_Chunk_' . ucfirst( strtolower( $chunkType ) );
        
        if( class_exists( $className ) ) {
            
            $chunk = new $className;
            
        } else {
            
            $chunk = new Png_UnknownChunk( $chunkType );
        }
        
        $this->_chunks[]  = $chunk;
            
        $this->_lastChunk = $chunkType;
        
        if( isset( $this->_chunkNames[ $chunkType ] ) ) {
            
            $this->_chunkNames[ $chunkType ]++;
            
        } else {
            
            $this->_chunkNames[ $chunkType ] = 1;
        }
        
        return $chunk;
    }
    
    /**
     * Checks if a chunk type is invalid
     * 
     * @param   string  The chunk type
     * @return  mixed   False if the chunk is valid, otherwise an error message
     */
    public function isInvalidChunk( $type )
    {
        // Checks if the chunk is valid
        if( !isset( $this->_validChunks[ $type ] ) ) {
            
            return 'Chunk ' . $type . ' is not part of the PNG specification';
        }
        
        // Checks if the chunk already exists and if it can be added multiple times
        if( isset( $this->_chunkNames[ $type ] ) && !isset( $this->_allowedMultipleChunks[ $type ] ) ) {
            
            return 'Chunk ' . $type . ' cannot be added more than once';
        }
        
        // The IHDR chunk must be present before any other chunk
        if( $type !== 'IHDR' && !isset( $this->_chunkNames[ 'IHDR' ] ) ) {
            
            return 'Cannot add chunk ' . $type . ' as there is no IHDR chunk';
        }
        
        // IDAT chunks must be consecutives
        if( $type === 'IDAT' && isset( $this->_chunkNames[ 'IDAT' ] ) && $this->_lastChunk != 'IDAT' ) {
            
            return 'IDAT chunks must be consecutives';
        }
        
        // No chunk can be placed if the IEND chunk exists
        if( isset( $this->_chunkNames[ 'IEND' ] ) ) {
            
            return 'Cannot add chunk ' . $type . ' as the IEND chunk is already present';
        }
        
        return false;
    }
}
