CREATE TABLE users
(
    id           bigserial PRIMARY KEY,
    name         varchar(50),
    surname      varchar(70),
    role         char(5) DEFAULT 'basic' NOT NULL,
    email        varchar(50) UNIQUE      NOT NULL,
    avatar_path  varchar,
    password     varchar(255)            NOT NULL,
    phone_number char(12) UNIQUE
);

CREATE TABLE categories
(
    id   bigserial PRIMARY KEY,
    name varchar(100) NOT NULL UNIQUE
);

CREATE TABLE ads
(
    id          bigserial PRIMARY KEY,
    user_id     bigint       NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    title       varchar(100) NOT NULL,
    description text         NOT NULL,
    category_id bigint       NOT NULL REFERENCES categories (id),
    price       int          NOT NULL,
    address     varchar      NOT NULL
);

CREATE TABLE ad_images
(
    id       bigserial PRIMARY KEY,
    ad_id    bigint   NOT NULL REFERENCES ads (id) ON DELETE CASCADE,
    position smallint NOT NULL,
    url      varchar  NOT NULL
);

CREATE TABLE favorite_ads
(
    id         bigserial PRIMARY KEY,
    user_id    bigint    NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    ad_id      bigint    NOT NULL REFERENCES ads (id) ON DELETE CASCADE,
    created_at timestamp NOT NULL DEFAULT NOW(),
    UNIQUE (user_id, ad_id)
);

CREATE TABLE reviews
(
    id          bigserial PRIMARY KEY,
    author_id   bigint    NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    receiver_id bigint    NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    ad_id       bigint    NOT NULL REFERENCES ads (id) ON DELETE CASCADE,
    created_at  timestamp NOT NULL DEFAULT NOW(),
    text        text      NOT NULL,
    rating      int       NOT NULL CHECK (rating >= 1 AND rating <= 5)
);
