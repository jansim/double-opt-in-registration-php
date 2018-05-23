-- Registration table

CREATE TABLE Registration (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email varchar(255),
    name varchar(255),
    confirmationCode varchar(32),
    confirmed tinyint(10),
    unsubscribed tinyint(10)
);