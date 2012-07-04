<?php
require("config.php");

header("Content-type: text/css");
?>

@charset "UTF-8";

.preload {
    display: none;
}

.thumb {
    float: left;
    height: <?php echo $thumbh; ?>px; 
    width: <?php echo $thumbw; ?>px; 
    line-height: <?php echo $thumbh; ?>px;
    text-align: center;
}

#pic {
    height: <?php echo $smallh; ?>px; 
    width: <?php echo $smallw; ?>px; 
    position: relative;
    text-align: center;
}

#small {
    width: <?php echo $smallw; ?>px; 
}

#prev {
    left: 0.5em;
    top: 0.5em;
    position: absolute;
}

#next {
    position: absolute;
    right: 0.5em;
    top: 0.5em;
}

#next:hover, #prev:hover {
    opacity: 1;
}

#prev, #next, #small {
    line-height: <?php echo $smallh; ?>px;
}

#prev, #next {
    transition: opacity linear .5s;
    -webkit-transition: opacity linear .5s;
    -moz-transition: opacity linear .5s;
    -o-transition: opacity linear .5s;
    -ms-transition: opacity linear .5s;
    opacity: 0;
    width: <?php echo $thumbw; ?>px; 
    text-align: center;
}

.thumb img, #prev img, #next img, #small img {
    vertical-align: middle;
}

#prev img, #next img {
    border-radius: 5px;
    padding: 0.3em;
    background-color: white;
}

.thumb, #prev, #next {
    margin: 0.3em;
}

.thumb img {
    border-radius: 5px;
}

#grid {
}
