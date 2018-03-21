<?php

function addUser($userid, $username) {
    global $db;
    $query = "INSERT INTO users(userid, username) VALUES($userid, \"$username\")";
    // echo $query;
    $db -> query($query);
}

function removeUser($userid) {
    global $db;
    $query = "DELETE FROM users WHERE userid=$userid";
    $db -> query($query);
}

function addApp($appid, $appname) {
    global $db;
    $query = "INSER INTO apps(appid, appname) VALUES($appid, \"$appname\")";
    $db -> query($query);
}

function addOperation($operatingtime, $userid, $appid) {
    global $db;
    $query = "INSERT INTO operation(operatingtime, userid, appid) VALUES($operatingtime, $userid, $appid)";
    $db -> query($query);
}

function removeOperation($operatingid) {
    global $db;
    $query = "DELETE FROM operation WHERE operatingid = $operatingid";
    $db -> query($query);
}

function removeApp($appid) {
    global $db;
    $query = "DELETE FROM apps WHERE appid = \"$appid\"";
    $db -> query($query);
}

function count_operation_for_app($appid) {
    global $db;
    $query = "SELECT count(*) FROM operation WHERE appid = $appid";
    $result = $db -> query($query);
    return $result -> fetch_assoc()['count(*)'];

}
 

$db = new mysqli('127.0.0.1', 'root', 'FLZdown1km$mysql!');
if (mysqli_connect_errno()) {
    echo "connect error!";
    exit;
}
$db -> select_db('tpapp');

$command = $_GET["command"];
switch ($command) {
case "addUser":
    addUser($_GET["userid"], $_GET["username"]);
    break;
case "removeUser":
    removeUser($_GET["userid"]);
    break;
case "addApp":
    addApp($_GET["appid"], $_GET["appname"]);
    break;
case "removeApp":
    removeApp($_GET["appname"]);
    break;
case "addOperation":
    addOperation(/*$_GET["operatingid"], */$_GET["operatingtime"], $_GET["userid"], $_GET["appid"]);
    break;
case "removeOperation":
    removeOperation($_GET["opreatingid"]);
    break;
case "countOperationForApp":
    echo count_operation_for_app($_GET["appid"]);
    break;
default:
    echo "command undefined.";
    break;
}

