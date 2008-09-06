<?php
    require_once( './init.inc.php' );
?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>File Parser</title>
    <meta name="generator" content="BBEdit 8.7" />
    <link rel="stylesheet" href="Css/base.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
    <h1>File Parser</h1>
    <div class="upload">
        <form method="post" enctype="multipart/form-data" id="upload-form">
            <label for="file">The file to upload:</label><br />
            <input name="file" id="file" type="file" /><br />
            <input name="submit" id="submit" type="submit" value="Analyze the file" />
        </form>
    </div>
    <?php
        if( isset( $_POST[ 'submit' ] ) && isset( $_FILES[ 'file' ] ) && is_array( $_FILES[ 'file' ] ) && count( $_FILES[ 'file' ] ) ) {
            
            $file = $_FILES[ 'file' ];
            
            switch( $file[ 'type' ] ) {
                
                case 'video/mp4':
                    
                    try {
                        
                        $parser    = new Mpeg4_Parser( $file[ 'tmp_name' ], true );
                        $mpeg4File = $parser->getMpeg4File();
                        $dataArray = $mpeg4File->getProcessedData();
                        $warnings  = $parser->getWarnings();
                        
                        if( count( $warnings ) ) {
                            
                            print '<h2>Warnings</h2>';
                            print '<pre>';
                            print_r( $warnings );
                            print '</pre>';
                        }
                        
                        print '<h2>Parsed data</h2>';
                        print '<pre>';
                        print_r( $dataArray );
                        print '</pre>';
                        
                        file_put_contents( 'Ressources/Movies/debug.mp4', $mpeg4File );
                        
                    } catch( Exception $e ) {
                        
                        print '<div class="error">Exception: ' . $e->getMessage() .'</div>';
                    }
                    
                    break;
                
                case 'image/gif':
                    
                    try {
                        
                        $parser    = new Gif_Parser( $file[ 'tmp_name' ] );
                        
                        print '<h2>Parsed data</h2>';
                        print '<pre>';
                        print_r( $parser->getInfos() );
                        print '</pre>';
                        
                    } catch( Exception $e ) {
                        
                        print '<div class="error">Exception: ' . $e->getMessage() .'</div>';
                    }
                    
                    break;
                
                case 'image/png':
                    
                    try {
                        
                        $parser    = new Png_Parser( $file[ 'tmp_name' ] );
                        
                        print '<h2>Parsed data</h2>';
                        print '<pre>';
                        print_r( $parser->getInfos() );
                        print '</pre>';
                        
                    } catch( Exception $e ) {
                        
                        print '<div class="error">Exception: ' . $e->getMessage() .'</div>';
                    }
                    
                    break;
                
                default:
                    
                    print '<div class="error">Sorry, the file type is not recognized or supported</div>';
                    break;
            }
        }
    ?>
</body>
</html>
