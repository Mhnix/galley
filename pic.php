<?php

require("config.php");
require("func.php");

if (isset($_REQUEST['img']))
{
    $fname = $_REQUEST['img'];
  
    $fname = sanitize($dir, $fname);

    createHeader($albumname.$fname);

    $path = dirname($fname);
    if ($path == '/')
    {
        $path = '';
    }
    $file = basename($fname);
    $realpath = $dir.'/'.$path;

    echo getParentString($fname, $albumname)."\n";

    if (false !== ($dir = opendir( $realpath )))
    {
        $files = array();
        while ($rawfiles[] = readdir($dir));
        $files = array_filter($rawfiles, 'filterImageType');
        sort($files);
        closedir($dir);
        
        for($i = 0; $i < sizeof($files); $i++)
        {
            if ($files[$i] == $file)
            {
                echo "<div id=\"pic\">\n";
                echo "<div id=\"small\"><a href=\"".rewriteUrl("image/full{$path}/{$files[$i]}")."\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i]}")."\" /><a></div>\n";
                if ($i > 0)
                {
                    echo "<div class=\"preload\" id=\"prevpreload\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i-1]}")."\" /></div>\n";
                    echo "<a href=\"".rewriteUrl("view{$path}/{$files[$i-1]}")."\"><div id=\"prev\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i-1]}")."\" /></div></a>\n";
                }
                if ($i < sizeof($files) - 1)
                {
                    echo "<div class=\"preload\" id=\"nextpreload\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i+1]}")."\" /></div>\n";
                    echo "<a href=\"".rewriteUrl("view{$path}/{$files[$i+1]}")."\"><div id=\"next\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i+1]}")."\" /></div></a>\n";
                }
                echo "</div>\n";
                $found = true;
                break;
            }
        }
        if (!isset($found))
        {
            echo "invalid path";
        }
    }
    else
    {
        echo "bad path: {$realpath}";
    }
    createFooter();
}

?>
