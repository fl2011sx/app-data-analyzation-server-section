CREATE TABLE IF NOT EXISTS users(
    userid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) UNIQUE
);

CREATE TABLE IF NOT EXISTS user_properties(
    user_property VARCHAR(20) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS user_pro_val(
    userid INT REFERENCES users(userid),
    user_property VARCHAR(20) REFERENCES user_properties(user_property),
    user_pro_value VARCHAR(20),
    PRIMARY KEY(userid, user_property)
);

CREATE TABLE IF NOT EXISTS apps(
    appid INT PRIMARY KEY,
    appname VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS operation(
    operatingid INT AUTO_INCREMENT PRIMARY KEY,
    operatingtime DOUBLE(16, 5),
    userid INT REFERENCES users(userid),
    appid INT REFERENCES apps(appid)
);

INSERT users VALUES(1, "user1");
INSERT users VALUES(2, "user2");

INSERT apps VALUES(1, "app1");
INSERT apps VALUES(2, "app2");

INSERT operation VALUES(1, 1, 1, 1);
INSERT operation VALUES(2, 2, 2, 1);