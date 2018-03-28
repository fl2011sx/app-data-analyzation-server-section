<?php

function db_query($query) {
    global $db;
    return $db -> query($query);
}

function addUser($username) {
    resetUserIdIterator();
    db_query("INSERT INTO users(username) VALUES(\"$username\")");
}

function resetUserIdIterator() {
    $result = db_query("SELECT MAX(userid) FROM users");
    $max_id = $result -> fetch_assoc()['max(userid)'] + 1;
    db_query("alter table users auto_increment=".$max_id);
}

function addUserPropertyValue($username, $property, $value) {
    $userid = getUserId($username);
    db_query("INSERT INTO user_pro_val(userid, user_property, user_pro_value) VALUSE($userid, \"$property\", \"$value\")");
}

function isExistUser($username) {
    $result = db_query("SELECT count(*) FROM users WHERE username=".$username);
    $count = $result -> fetch_assoc()['count(*)'];
    return count;
}

function removeUser($userid) {
    db_query("DELETE FROM users WHERE userid=$userid");
}

function addApp($appid, $appname) {
    db_query("INSER INTO apps(appid, appname) VALUES($appid, \"$appname\")");
}

function addOperation($operatingtime, $userid, $appid) {
    db_query("INSERT INTO operation(operatingtime, userid, appid) VALUES($operatingtime, $userid, $appid)");
}

function removeOperation($operatingid) {
    db_query("DELETE FROM operation WHERE operatingid = $operatingid");
}

function removeApp($appid) {
    db_query("DELETE FROM apps WHERE appid = \"$appid\"");
}

function count_operation_for_app($appid) {
    $result = db_query("SELECT count(*) FROM operation WHERE appid = $appid");
    return $result -> fetch_assoc()['count(*)'];
}

function getUserProperties() {
    $result = db_query("SELECT DISTINCT user_property FROM user_properties");
    $re_str = "{\"properties\":[";
    $isFirst = true;
    while ($row = $result -> fetch_assoc()["user_property"]) {
        if (!$isFirst) {
            $re_str = $re_str.",";
        }
        $re_str = $re_str."\"".$row."\"";
        $isFirst = false;
    }
    $re_str = $re_str."]}";
    return $re_str;
}
 
function getUserId($username) {
    $result = db_query("SELECT userid FROM users WHERE username=".$username);
    return $result -> fetch_assos()['userid'];
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
    addUser($_GET["username"]);
    break;
case "addUserPropertyValue":
    addUserPropertyValue($_GET["username"], $_GET["property"], $_GET["value"]);
    break;
case "getUserProperties":
    echo getUserProperties();
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

