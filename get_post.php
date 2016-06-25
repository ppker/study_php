<?php
/**
 * Des: 描述
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 2016/6/25
 * Time: 18:34
 */

//fsocket模拟post提交
$url = "http://localhost:10022/response.php?site=nowamagic.net";
// print_r(parse_url($url));

//echo $query;
// sock_get($url,"user=gonn");
//sock_get($url, $query);

//fsocket模拟get提交
function sock_get($url, $query)
{
    $data = array(
        'foo'=>'bar',
        'baz'=>'boom',
        'site'=>'www.nowamagic.net',
        'name'=>'nowa magic');

    $query_str = http_build_query($data);

    $info = parse_url($url);
    $fp = fsockopen($info["host"], $info["port"], $errno, $errstr, 3);
    //$head = "GET ".$info['path']."?".$info["query"]." HTTP/1.0\r\n";
    $head = "GET ".$info['path']."?".$query_str." HTTP/1.0\r\n";
    $head .= "Host: ".$info['host']."\r\n";
    $head .= "\r\n";
    $write = fputs($fp, $head);
    while (!feof($fp))
    {
        $line = fread($fp,4096);
        echo $line;
    }
}

sock_post($url,"user=gonn");

function sock_post($url, $query)
{
    $info = parse_url($url);
    $fp = fsockopen($info["host"], $info["port"], $errno, $errstr, 3);
    $head = "POST ".$info['path']."?".$info["query"]." HTTP/1.0\r\n";
    $head .= "Host: ".$info['host']."\r\n";
    $head .= "Referer: http://".$info['host'].$info['path']."\r\n";
    $head .= "Content-type: application/x-www-form-urlencoded\r\n";
    $head .= "Content-Length: ".strlen(trim($query))."\r\n";
    $head .= "\r\n";
    $head .= trim($query);
    $write = fputs($fp, $head);
    while (!feof($fp))
    {
        $line = fread($fp,4096);
        echo $line;
    }
}