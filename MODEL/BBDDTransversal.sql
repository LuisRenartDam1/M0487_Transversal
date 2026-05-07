
create database BBDDTransversal;
use BBDDTransversal;

create table users(
	username varchar(30) primary key,
    password varchar(100) 
);

create table product(
    id_product int auto_increment not null,
    name varchar(50),
    price double not null,
    amount int not null,
    stock boolean
);


