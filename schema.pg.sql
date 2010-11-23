create table agents
(
	id SERIAL PRIMARY KEY,
	fullname varchar,
	alias varchar,
	email varchar,
	phone varchar,
	address text,
	course varchar,
	societies varchar,
	clubs varchar,
	timetable varchar
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
	local_currency integer
);

create table kills
(
	id serial primary key,
	weapon integer references weapon(id),
	description text,
	assassin integer references agent(id),
	target integer references agent(id),
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

create table weapons
(
	id serial primary key,
	name varchar,
	class varchar,
	ord integer,
	description text,
	link varchar
);

create table codes
(
    id serial primary key,
    code character varying(64),
    active integer,
    type integer,
    game integer references game(id)
);

create table tempaliases
(
    id serial primary key,
    game int references game(id),
    agent int references agent(id),
    alias varchar
);

