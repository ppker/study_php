<?php
/**
 * Des: 描述
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 2016/6/27
 * Time: 9:51
 */

use Workerman\Worker;
require_once "../../Workerman/Autoloader.php";

# 初始化一个worker容器,监听1234端口
$worker = new Worker("websocket://0.0.0.0:1234");
$worker->count = 1;
# worker进程启动后创建一个text Worker打开一个内部通讯端口
$worker->onWorkerStart  = function($worker) {

    # 开启一个内部端口,方便内部系统推送数据，Text协议格式 文本+换行符
    $inner_text_worker = new Worker('text://0.0.0.0:5678');
    $inner_text_worker->onMessage = function($connection, $buffer) {

        $data = json_decode($buffer, true);
        $uid = $data['uid'];
        # 通过workerman，向uid的页面推送数据
        $ret = sendMessageByUid($uid, $buffer);
        # 返回推送结果
        $connection->send($ret ? 'ok' : 'fail');
    };
    # 进行监听
    $inner_text_worker->listen();
};

# 新增一个属性，用来保存uid到connection的映射
$worker->uidConnections = [];
# 当有客户端发来消息时执行的回调函数
$worker->onMessage = function($connection, $data) {
    global $worker;
    if (!isset($connection->uid)) {
        $connection->uid = $data;
        # 保存uid到connection的映射，这样可以方便的通过uid查找connection, 实现针对特定的uid推送数据
        $worker->uidConnections[$connection->uid] = $connection;
        return;
    }
};
# 当有客户端连接断开时
$worker->onClose = function($connection) {

    global $worker;
    if (isset($connection->uid)) {
        unset($worker->uidConnections[$connection->uid]);
    }
};

# 向所有验证的用户推送数据
function broadcast($message) {
    global $worker;
    foreach($worker->uidConnections as $connection) {
        $connection->send($message);
    }
}

# 针对uid推送数据
function sendMessageByUid($uid, $message) {

    global $worker;
    if (isset($worker->uidConnections[$uid])) {
        $connection = $worker->uidConnections[$uid];
        $connection->send($message);
        return true;
    }
    return false;
}

Worker::runAll();