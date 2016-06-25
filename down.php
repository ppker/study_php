<?php
/**
 * Des: 描述
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 2016/6/25
 * Time: 19:14
 */

# Socket 模拟HTTP协议传输文件
# Http是应用层协议使用端口80
#
$hostname = 'www.nowamagic.net';
$port = '80';
# 建立连接
$fp = fsockopen($hostname,$port,$errno,$errstr);
//set_socket_blocking($fp,false);
//stream_set_blocking($fp,0);
stream_set_blocking($fp, true);
if(!$fp)
{
    echo "$errno : $errstr<br/>";
}
else
{
    # 发送一个HTTP请求信息头
    $request_header="GET /librarys/webapp/Snow.zip HTTP/1.1\n";
    # 起始行
    # 头域
    $request_header.="Host: $hostname\n";
    # 再一个回车换行表示头信息结束
    $request_header.="\n";

    # 发送请求到服务器
    fputs($fp,$request_header);
    # 接受响应
    $fp2=fopen('Snow.zip','w');
    while (!feof($fp))
    {
        $line = fputs($fp2,fgets($fp,128));
        //echo $line;
    }
    # 关闭
    fclose($fp2);
    fclose($fp);
}