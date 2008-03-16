<?php
    require_once( 'Classes/Mpeg4/Init.class.php' );
?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>MPEG-4 Parser</title>
    <meta name="generator" content="BBEdit 8.7" />
    <link rel="stylesheet" href="Css/base.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
    <h1>MPEG-4 Parser</h1>
    <div class="mpeg4-upload">
        <form method="post" enctype="multipart/form-data" id="mpeg4-upload-form">
            <label for="mpeg4-file">MPEG-4 file:</label><br />
            <input name="mpeg4-file" id="mpeg4-file" type="file" /><br />
            <input name="mpeg4-submit" id="mpeg4-submit" type="submit" value="Analyze MPEG-4 file" />
        </form>
    </div>
    <?php
        if( isset( $_POST[ 'mpeg4-submit' ] ) && isset( $_FILES[ 'mpeg4-file' ] ) && is_array( $_FILES[ 'mpeg4-file' ] ) && count( $_FILES[ 'mpeg4-file' ] ) ) {
            
            $file = $_FILES[ 'mpeg4-file' ];
            
            if( $file[ 'type' ] == 'video/mp4' ) {
                
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
                
                file_put_contents( 'Movies/debug.mp4', $mpeg4File );
                
            } else {
                
                print '<div class="error">Sorry, the file is not an MPEG-4 movie</div>';
            }
        }
    ?>
</body>
</html>
