<?php

require("config.php");
require("func.php");

if (isset($_REQUEST['img']))
{
    $fname = $_REQUEST['img'];
    $fname = sanitize($dir, $fname);
    
    $type = $_REQUEST['type'];

    switch ($type)
    {
        case 'thumb':
            $tname = $fname.'.'.$type;
            $sizew = $thumbw;
            $sizeh = $thumbh;
            break;
        case 'small':
            $tname = $fname.'.'.$type;
            $sizew = $smallw;
            $sizeh = $smallh;
            break;
        case 'full':
            $full = true;
            break;
        default:
            die('undefined type');
    }

    if (isset($full))
    {
        header('Content-Type: image/jpeg');
        readfile($dir.'/'.$fname);
        exit;
    }

    #$file = $tempdir . $fname;
    $thumb = $tempdir . $tname;

    $info = pathinfo($thumb);
    $tdir = $info['dirname'];
    #$thumb = $tdir.'/'.$tname;
    
    header('Content-Type: image/jpeg');
    if (file_exists($thumb))
    {
        readfile($thumb);
    }
    else
    {
        if (!file_exists($tdir))
        {
            mkdir($tdir, 0777, true);
        }
        createThumb($dir, $thumb, $fname, $sizew, $sizeh, 1);
    }
}

?>
