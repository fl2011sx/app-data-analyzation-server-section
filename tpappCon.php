<?php

function db_query($query) {
    global $db;
    return $db -> query($query);
}

function addUser($username, $note = 0) {
    resetUserIdIterator();
    $que = "INSERT INTO users(username) VALUES(\"$username\")";
    if ($note !== 0) {
        $que = "INSERT INTO users(username, note) VALUES(\"$username\", \"$note\")";
    }
    db_query($que);
}

function resetUserIdIterator() {
    $result = db_query("SELECT MAX(userid) FROM users");
    $max_id = $result -> fetch_assoc()['max(userid)'] + 1;
    db_query("alter table users auto_increment=".$max_id);
}

function addUserPropertyValue($username, $property, $value) {
    $userid = getUserId($username);
    db_query("INSERT INTO user_pro_val(userid, user_property, user_pro_value) VALUES($userid, \"$property\", \"$value\")");
}

function isExistUser($username) {
    $result = db_query("SELECT count(*) FROM users WHERE username=\"".$username."\"");
    $count = $result -> fetch_assoc()['count(*)'];
    return $count;
}

function setLoginOperation($username, $operatingtime) {
    $userid = getUserId($username);
    db_query("INSERT INTO operation(operatingtime, userid, operation_type) VALUES($operatingtime, $userid, \"login\")");
}

function removeUser($userid) {
    db_query("DELETE FROM users WHERE userid=$userid");
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
    $result = db_query("SELECT userid FROM users WHERE username=\"".$username."\"");
    return $result -> fetch_assoc()['userid'];
}

function addUserProperty($pro) {
    db_query("INSERT INTO user_properties(user_property) VALUES(\"$pro\")");
}

function batch_registUsers($prename, $count, $properties, $note) {
    for ($i = 0; $i < $count; $i++) {
        $name = $prename."_$i";
        addUser($name, $note);
        foreach ($properties as $pro => $val) {
            addUserPropertyValue($name, $pro, $val);
        }
    }
}

function deleteBatchUsers($note) {
    db_query("DELETE FROM user_pro_val WHERE userid in (SELECT userid FROM users WHERE note = \"$note\")");
    db_query("DELETE FROM users WHERE note = \"$note\"");
}

function init_database() {
    global $db;
    if (isset($db)) {return;}
    $db = new mysqli('127.0.0.1', 'root', 'FLZdown1km$mysql!');
    if (mysqli_connect_errno()) {
        echo "connect error!";
        exit;
    }
    $db -> select_db('tpapp');
}

function processCommand() {
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
    case "login":
        $isSucc = isExistUser($_GET["username"]);
        echo $isSucc;
        if ($isSucc) {
            setLoginOperation($_GET["username"], time());
        }
        break; 
    default:
        echo "command undefined.";
        break;
    }
}

init_database();
processCommand();