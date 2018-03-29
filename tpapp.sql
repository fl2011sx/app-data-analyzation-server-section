CREATE TABLE IF NOT EXISTS users(
    userid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) UNIQUE,
    note VARCHAR(20)
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

CREATE TABLE IF NOT EXISTS operation_type(
    operartion_type VARCHAR(50) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS operation(
    operatingid INT AUTO_INCREMENT PRIMARY KEY,
    operatingtime DOUBLE(16, 2),
    userid INT REFERENCES users(userid),
    operation_type VARCHAR(20) REFERENCES operation_type(operartion_type)
);

INSERT users VALUES(1, "user1");
INSERT users VALUES(2, "user2");

INSERT operation_type VALUES("login");
