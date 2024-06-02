CREATE TABLE users(
    Id int PRIMARY KEY AUTO_INCREMENT,
    Username varchar(200),
    Email varchar(200),
    staff_id int(4),
    Password varchar(200)
);


INSERT INTO users (Username, Email, Staff_id, Password) 
VALUES ('staff', 'staff@gmail.com', 1158, 'staff123');
