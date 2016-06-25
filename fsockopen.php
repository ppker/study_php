<?php
/**
 * Des: 描述
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 2016/6/25
 * Time: 17:46
 */

$fp = fsockopen("www.cnblogs.com", 80, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET / HTTP/1.1\r\n";
    $out .= "HOST: www.cnblogs.com\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwirte($fp, $out);
    $content = '';
    while (!feof($fp)) {
        $content .= fgets($fp, 128);
    }
    echo $content;
    fclose($fp);
}
