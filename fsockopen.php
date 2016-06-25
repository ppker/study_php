<?php
/**
 * Des: 描述
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 2016/6/25
 * Time: 17:46
 */

// $fp = fsockopen("www.workerman.net", 80, $errno, $errstr, 30);
$fp = stream_socket_client("tcp://www.workerman.net:80", $errno, $errstr, 30);

if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET / HTTP/1.1\r\n";
    $out .= "HOST: www.workerman.net\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    $content = '';
    while (!feof($fp)) {
        $content .= fgets($fp, 128);
    }
    echo $content;
    fclose($fp);
}


/*
$fp = fsockopen("udp://127.0.0.1", 13, $errno, $errstr);
if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\n";
} else {
    fwrite($fp, "\n");
    echo fread($fp, 26);
    fclose($fp);
}*/