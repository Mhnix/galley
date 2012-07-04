<?php

require("config.php");
require("func.php");

if (isset($_REQUEST['img']))
{
    $fname = $_REQUEST['img'];
  
    $fname = sanitize($dir, $fname);

    createHeader($albumname.$fname);

    echo "<script type=\"text/javascript\" src=\"".rewriteUrl("jquery.js")."\"></script>";

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

        $curr = 0;
        echo "<script type=\"text/javascript\">\n<!--\n";
        echo "var images=[";
        for($i = 0; $i < sizeof($files); $i++)
        {
            if ($files[$i] == $file)
            {
                $curr = $i;
            }
            echo "\"{$path}/{$files[$i]}\", ";
        }
        echo "];\n";
        echo "var curr=$curr;\n";
        ?>
            nextimg = new Image();
            previmg = new Image();
            var timer;

            function pause() {
                clearTimeout(timer);
                $("#control").attr("href", "javascript:play()");
                $("#control").text("Play");
            }
            
            function play() {
                $("#control").attr("href", "javascript:pause()");
                $("#control").text("Pause");
                if (next())
                {
                    timer = setTimeout("play()",5000);
                } else {
                    pause();
                }
            }
            
            function goPrev() {
                pause();
                prev();
            }

            function goNext() {
                pause();
                next();
            }

            function next() {
                curr++;
                return step();
            }

            function prev() {
                curr--;
                return step();
            }

            function step() {
                if (curr < 0 || curr >= images.length)
                {
                    pause();
                    return false;
                }
                // change images
                $("#small").find("img").attr("src", "<?php echo "$webroot"; ?>image/small"+images[curr]);
                $("#next").find("img").attr("src", "<?php echo "$webroot"; ?>image/thumb"+images[curr+1]);
                $("#prev").find("img").attr("src", "<?php echo "$webroot"; ?>image/thumb"+images[curr-1]);
                // fix links
                $("#small").find("a").attr("href", "<?php echo "$webroot"; ?>image/full"+images[curr]);
                //$("#next").parent().attr("href", "<?php echo "$webroot"; ?>view"+images[curr+1]);
                //$("#prev").parent().attr("href", "<?php echo "$webroot"; ?>view"+images[curr-1]);
                // preload images
                nextimg.src = "<?php echo "$webroot"; ?>image/small"+images[curr+1];
                previmg.src = "<?php echo "$webroot"; ?>image/small"+images[curr-1];

                var state = { curr: curr };
                history.pushState(state, "<?php echo "$albumname"; ?>"+images[curr], "<?php echo "$webroot"; ?>view"+images[curr]);

                return true;
            }

        <?php
        echo "-->\n</script>\n";

        #echo "<a href=\"javascript:prev()\">Prev</a>\n";
        #echo "<a href=\"javascript:play()\">Play</a>\n";
        #echo "<a href=\"javascript:pause()\">Pause</a>\n";
        #echo "<a href=\"javascript:next()\">Next</a>\n";
        echo "<a id=\"control\" href=\"javascript:play()\">Play</a>\n";
        
        for($i = 0; $i < sizeof($files); $i++)
        {
            if ($files[$i] == $file)
            {
                echo "<div id=\"pic\">\n";
                echo "<div id=\"small\"><a href=\"".rewriteUrl("image/full{$path}/{$files[$i]}")."\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i]}")."\" /><a></div>\n";
                if ($i > 0)
                {
                    echo "<div class=\"preload\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i-1]}")."\" /></div>\n";
                    #echo "<a href=\"".rewriteUrl("view{$path}/{$files[$i-1]}")."\"><div id=\"prev\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i-1]}")."\" /></div></a>\n";
                    echo "<a href=\"javascript:goPrev()\"><div id=\"prev\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i-1]}")."\" /></div></a>\n";
                }
                if ($i < sizeof($files) - 1)
                {
                    echo "<div class=\"preload\"><img src=\"".rewriteUrl("image/small{$path}/{$files[$i+1]}")."\" /></div>\n";
                    #echo "<a href=\"".rewriteUrl("view{$path}/{$files[$i+1]}")."\"><div id=\"next\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i+1]}")."\" /></div></a>\n";
                    echo "<a href=\"javascript:goNext()\"><div id=\"next\"><img src=\"".rewriteUrl("image/thumb{$path}/{$files[$i+1]}")."\" /></div></a>\n";
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
