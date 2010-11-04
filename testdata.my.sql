CREATE TABLE agents (
  id int(11) NOT NULL AUTO_INCREMENT,
  fullname varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(255) NOT NULL,
  address text NOT NULL,
  course varchar(255) NOT NULL,
  societies text NOT NULL,
  clubs text NOT NULL,
  timetable text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO agents (id, fullname, alias, email, phone, address, course, societies, clubs, timetable) VALUES
(1, 'James Cleveland', 'radiosilence', 'jamescleveland@example.com', '567567567', '38 MANCHESTER LANE\r\n NOWHERE', 'Computer Science & Cybernetics (BSc)', 'Indie Society\r\nVideo Game Society', 'Iguana\r\nUpin Arms', 'Blahh'),
(2, 'Ruth Sullivan', 'littlespy', 'littlespy@example.com', '02223382', 'Adasdasd', 'History at Wexham', 'None', 'Pavs\r\n', 'Dogs');

CREATE TABLE codes (
  id int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  active tinyint(4) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE games (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  start_date datetime NOT NULL,
  end_date datetime NOT NULL,
  signup_date datetime NOT NULL,
  location text NOT NULL,
  victor int(11) NOT NULL,
  description text NOT NULL,
  state tinyint(4) NOT NULL,
  entry_fee float NOT NULL,
  local_currency tinyint(4) NOT NULL,
  code_required tinyint(4) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO games (id, `name`, start_date, end_date, signup_date, location, victor, description, state, entry_fee, local_currency, code_required) VALUES
(1, 'Test Game', '2010-11-13 13:59:17', '2010-11-30 13:59:22', '2010-11-12 13:59:28', 'Reading, UK', 0, 'Blahhh', 1, 3, 0, 0),
(2, 'Stupid Game', '2011-01-01 01:54:06', '3010-11-30 01:54:08', '2010-11-01 01:54:11', 'Hull', 0, 'Worst game ever', 0, 555, 0, 0);

CREATE TABLE kills (
  id int(11) NOT NULL AUTO_INCREMENT,
  weapon int(11) NOT NULL,
  description text NOT NULL,
  assassin int(11) NOT NULL,
  target int(11) NOT NULL,
  when_happened datetime NOT NULL,
  game int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO kills (id, weapon, description, assassin, target, when_happened, game) VALUES
(1, 8, 'Blah bla', 2, 1, '2010-11-13 17:09:09', 1),
(2, 2, 'Bleop', 1, 2, '2010-11-04 17:09:33', 1);

CREATE TABLE sessions (
  id int(11) NOT NULL,
  sid varchar(255) NOT NULL,
  tok varchar(255) NOT NULL,
  remote_addr varchar(15) NOT NULL,
  `data` text NOT NULL,
  latest timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO sessions (id, sid, tok, remote_addr, `data`, latest) VALUES
(0, '8641db366f3b4824398db5273b627b29934ebe00', 'adbaea125e0fd7c18a7ae6fe739fe0516d2263e6', '192.168.1.10', '[]', '0000-00-00 00:00:00'),
(0, 'c2289f6ebc2df038c69edfe834eb3c909f0c1653', '6aa207f442bd4ec2993ca41166bdaee35e84ac08', '192.168.1.10', '[]', '0000-00-00 00:00:00'),
(0, 'c273f10061188a4dd35a0f67187b068bb4e96084', '7955f23b584cfb94b63b8398cfa6402d505d2e38', '192.168.1.10', '[]', '0000-00-00 00:00:00'),
(0, '4ea5d78c255ea5ecc5326004bc88c7e65d108768', 'acd6e2b9567bb5debb48fdc273ae7ff7c4afc48f', '82.21.65.254', '[]', '0000-00-00 00:00:00'),
(0, '39f3ba9cdf475a690565adb8495dff2f63d5b400', 'c9518918a37d5c0430e6234ae3a7bb128b2a8899', '82.21.65.254', '[]', '0000-00-00 00:00:00'),
(0, 'b4d20815d8217b18276059ed75fe20778cd927d5', 'e709beefa775e7dfdc12f4c4559afce5c667ba9a', '82.21.65.254', '[]', '0000-00-00 00:00:00'),
(0, 'e74e8e901f1b98d503b8ef3ce58648ac0a4d06b6', 'e9ea61f763cc3129d30014f1a727e817103ca490', '94.194.229.31', '[]', '0000-00-00 00:00:00'),
(0, '00b8466232f457cc9928297249204db8a0bbc972', '9051a9a62446a2e2004723466515c9ffc1b5e711', '192.168.1.10', '[]', '0000-00-00 00:00:00'),
(0, '7039a3f2d5e47a66c91f29809713354a7916a4ce', '6af6609c05fff6ecb811837c2e6b91b76d23b98c', '217.171.129.74', '[]', '0000-00-00 00:00:00'),
(0, 'bccd6e72da726fd28b10af3fb7eb5d14a4f44455', '3e673f481fe998f1a9be1758abb19f5e54be0e63', '217.171.129.72', '[]', '0000-00-00 00:00:00'),
(0, '68f2e8e827fb2b53a8e900796cf701ccf23c644d', 'bfc918a65899852fe2887a458f9eba3e9b95cabb', '86.144.190.133', '[]', '0000-00-00 00:00:00');

CREATE TABLE tempaliases (
  game int(11) NOT NULL,
  agent int(11) NOT NULL,
  alias varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE weapons (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  class varchar(255) NOT NULL,
  ord int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO weapons (id, `name`, class, ord) VALUES
(1, 'Nerf N-Strike Maverick', 'Ranged', 0),
(2, 'Nerf N-Strike Longshot CS6', 'Ranged', 0),
(3, 'Nerf N-Strike Buzzsaw', 'Ranged', 0),
(4, 'Nerf N-Strike Secret Strike AS-1', 'Ranged', 0),
(5, 'Nerf N-Strike Raider Rapid Fire CS-35', 'Ranged', 0),
(6, 'Nerf N-Strike Firefly Blaster', 'Ranged', 0),
(7, 'Nerf N-Strike Nite Finder EX-3', 'Ranged', 0),
(8, 'Nerf N-Strike Vulcan EBF-25', 'Ranged', 0),
(9, 'Nerf N-Strike Switch Shot EX-3', 'Ranged', 0),
(10, 'Nerf N-Strike Recon CS-6', 'Ranged', 0),
(11, 'Other Ranged', 'Ranged', 1),
(12, 'Other Melee', 'Melee', 1),
(13, 'Poisoned (Food)', 'Poison', 0),
(14, 'Poisoned (Drink)', 'Poison', 0),
(15, 'Other Special', 'Special', 1),
(16, 'Poisoned (Contact)', 'Poison', 0),
(17, 'Nerf N-ForceThunder Fury', 'Melee', 0),
(18, 'Nerf (Other)', 'Ranged', 0),
(19, 'Fridge', 'Special', 0),
(20, 'Garrotting', 'Melee', 0),
(21, 'Sword', 'Melee', 0),
(22, 'Dagger', 'Melee', 0),
(23, 'Stiletto', 'Melee', 0),
(24, 'Hammer', 'Melee', 0),
(25, 'Grenade', 'Special', 0),
(26, 'Broken Neck', 'Melee', 0),
(27, 'Kiss of Death', 'Poison', 0),
(28, 'Beating', 'Melee', 0),
(29, 'Drive By', 'Special', 0),
(30, 'Letter Bomb', 'Special', 0),
(31, 'Electrocution', 'Special', 0),
(32, 'AIDS', 'Special', 0);

