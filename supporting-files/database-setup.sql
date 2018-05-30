-- Registration table

CREATE TABLE ? (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email varchar(255),
    name varchar(255),
    confirmationCode varchar(32),
    confirmed tinyint(10),
    unsubscribed tinyint(10),
    registered_on DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_changed_on DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);