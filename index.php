<?php

require("config.php");
require("func.php");

function listDir( $pathToImages, $path )
{
    $realpath = $pathToImages.'/'.$path;
    // open the directory
    if (false !== ($dir = opendir( $realpath )))
    {
        $files = array();
        while ($files[] = readdir($dir));
        sort($files);
        $images = array_filter($files, 'filterImageType');
        $subdirs = array_filter($files, function($fname) use ($realpath) {
            return (is_dir($realpath .'/'.$fname) and $fname and $fname[0] != '.'); 
        });
        closedir($dir);        
        // loop through it, looking for any/all JPG files:
        echo "<div id=\"index\">\n";
        foreach ($subdirs as $fname)
        {
            echo "<div class=\"subdir\">Album: <a href=\"".rewriteUrl("album$path/$fname")."\">$fname</a></div>\n"; 
        }
        echo "</div>\n";
        echo "<div id=\"grid\">\n";
        foreach ($images as $fname)
        #while (false !== ($fname = readdir( $dir )))
        {
            $file = $realpath . '/' . $fname;
            // parse path for the extension
            $info = pathinfo($file);
            // continue only if this is a JPEG image
            $relpath = $path . '/' . $fname;
            $src = rewriteUrl("image/thumb{$relpath}");
            echo "<div class=\"thumb\"><a href=\"".rewriteUrl("view{$relpath}")."\"><img src=\"{$src}\" /></a></div>\n";
        }
        echo "</div>\n";
    }
    // close the directory
   # closedir( $dir );
}

if (isset($_REQUEST['path']))
{
    $path = $_REQUEST['path'];
    $path = sanitize($dir, $path);
}
else
{
    $path = '';
}

createHeader($albumname.$path);
echo getParentString($path, $albumname)."\n";
listDir($dir, $path);
createFooter();
?>
