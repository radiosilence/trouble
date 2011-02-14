drop table agents;
drop table games;
drop table weapons;
drop table kills;
drop table sessions;
drop table tempaliases;
drop table codes;
drop table authentication;

create table authentication
(
    id SERIAL PRIMARY KEY,
    user_id integer,
    hash varchar,
    salt varchar
);

create table agents
(
    id SERIAL PRIMARY KEY,
    fullname varchar,
    alias varchar UNIQUE,
    email varchar UNIQUE,
    phone varchar,
    address text,
    course varchar,
    societies varchar,
    clubs varchar,
    timetable text,
    imagefile varchar UNIQUE
);

create table games
(
    id serial primary key,
    name varchar,
    start_date timestamp with time zone,
    end_date timestamp with time zone,
    signup_date timestamp with time zone,
    location text,
    victor integer,
    description text,
    state integer,
    invite_only integer,
    entry_fee integer,
    local_currency integer,
    temp_aliases integer
);

create table weapons
(
    id serial primary key,
    name varchar,
    class varchar,
    ord integer,
    description text,
    link varchar
);
create table kills
(
    id serial primary key,
    weapon integer references weapons(id),
    description text,
    assassin integer references agents(id),
    target integer references agents(id),
    when_happened timestamp with time zone,
    game integer
);

CREATE TABLE sessions
(
  id serial primary key,
  sid character varying(256),
  tok character varying(256),
  remote_addr character varying(15),
  data text,
  latest timestamp with time zone NOT NULL
);

create table codes
(
    id serial primary key,
    code character varying(64),
    active integer,
    type integer,
    game integer references games(id)
);

create table tempaliases
(
    id serial primary key,
    game int references games(id),
    agent int references agents(id),
    alias varchar
);

create table articles
(
    id serial primary key,
    title varchar,
    body text,
    posted_on timestamp with time zone,
    author integer references agents(id),
    custom_url varchar
);