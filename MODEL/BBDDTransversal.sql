
create database BBDDTransversal;
use BBDDTransversal;

create table users(
	username varchar(30) primary key not null,
    password varchar(100) not null 
);

create table productos(
    id_product int auto_increment not null,
    name varchar(100) not null,
    price int not null,
    amount int not null,
    stock boolean not null
);


