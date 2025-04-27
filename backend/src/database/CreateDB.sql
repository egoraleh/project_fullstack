CREATE TABLE users (
    id bigserial PRIMARY KEY ,
    name varchar(50),
    surname varchar(70),
    role char(5) default 'basic' NOT NULL ,
    email varchar(50) UNIQUE NOT NULL ,
    avatar_path varchar,
    password varchar(30) NOT NULL ,
    phone_number char(12)
);

CREATE TABLE ads (
    id bigserial PRIMARY KEY ,
    user_id bigint NOT NULL REFERENCES users (id) ,
    title varchar(100) NOT NULL ,
    description text NOT NULL ,
    category_id bigint NOT NULL REFERENCES categories (id) ,
    price int NOT NULL ,
    address varchar NOT NULL
);

CREATE TABLE categories (
    id bigserial PRIMARY KEY ,
    name varchar(100) NOT NULL UNIQUE
)