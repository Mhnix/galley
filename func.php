<?php

function filterImageType($value){
    $info = pathinfo($value);
    return (isset($info['extension']) and strtolower($info['extension']) == 'jpg');
}

function sanitize( $dir, $fname )
{
    $path = realpath($dir .'/'. $fname);

#    echo $dir."-".$path."-".str_replace($dir, '', $path)."-".strpos($path, $dir)."-\n";
    $tmp = strpos($path, $dir);
    if(!$path or $tmp === false or $tmp != 0)
    {
        die('Invalid Path');
    }
    return str_replace($dir, '', $path);
}

function createHeader($title)
{
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="<?php echo rewriteUrl("style.php"); ?>" type="text/css" />
    </head>
    <body>
<?php
}

function createFooter()
{
?>
    </body>
</html>
<?php
}

function getParentString($path, $albumname)
{
    $parent = $path;
    $pathstr = '</div>';
    while ('/' != ($parent = dirname($parent)) and $parent != '')
    {
        $pathstr = "<a href=\"".rewriteUrl("album{$parent}")."\">".basename($parent)."</a> / ".$pathstr;
    }
    $pathstr = "<div id=\"parents\"><a href=\"".rewriteUrl("album{$parent}")."\">{$albumname}</a> / ".$pathstr;
    return $pathstr;
}

function rewriteUrl($url)
{
    global $webroot;
    return $webroot.$url;
}

function createThumb( $pathToImages, $savepath, $fname, $new_w, $new_h, $disp )
{
    $file = $pathToImages . $fname;
    // parse path for the extension
    $info = pathinfo($file);
    // continue only if this is a JPEG image
    if ( isset($info['extension']) and strtolower($info['extension']) == 'jpg' )
    {
        $img = imagecreatefromjpeg( $file );

        $width = imageSX($img);
        $height = imageSY($img);

        $ratio = $height/$width;
        $new_r = $new_h/$new_w;

        if ($ratio > $new_r) {        
#        if ($width > $height) {
	        $thumb_h = $new_h;
	        $thumb_w = $width*$new_h/$height;
        }
        elseif ($ratio < $new_r) {
#        elseif ($width < $height) {
	        $thumb_h = $height*$new_w/$width;
	        $thumb_w = $new_w;
        }
        else {
	        $thumb_w = $new_w;
	        $thumb_h = $new_h;
        }

        // create a new temporary image
        $tmp_img = imagecreatetruecolor( $thumb_w, $thumb_h );

        // copy and resize old image into new image
        imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $thumb_w, $thumb_h, $width, $height );

        // save thumbnail into a file
        imagejpeg( $tmp_img, $savepath );

        if ($disp)
        {
            imagejpeg( $tmp_img );
        }
        imagedestroy($tmp_img);
    }
}

?>
