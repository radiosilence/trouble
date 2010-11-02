INSERT INTO agents (id, fullname, alias, email, phone, address, course, societies, clubs, timetable) VALUES
(1, 'James Cleveland', 'radiosilence', 'jamescleveland@gmail.com', '07865998857', '30 Wokingham Road\r\nReading\r\nRG6 1JH', 'Computer Science & Cybernetics (BSc)', 'Indie Society\r\nVideo Game Society', 'Iguana\r\nUpin Arms', 'Blahh'),
(2, 'Ruth Sullivan', 'littlespy', 'littlespy87@gmail.com', '02223382', 'Adasdasd', 'History at Wexham', 'None', 'Pavs\r\n', 'Dogs');

INSERT INTO games (id, start_date, end_date, signup_date, location, victor, description, finalised, entry_fee) VALUES
(1, '2010-11-13 13:59:17', '2010-11-30 13:59:22', '2010-11-12 13:59:28', 'Reading, UK', 0, 'Blahhh', 1, 3);


INSERT INTO kills (id, weapon, description, assassin, target,when_happened, game) VALUES
(1, 8, 'Blah bla', 2, 1, '2010-11-13 17:09:09', 1),
(2, 2, 'Bleop', 1, 2, '2010-11-04 17:09:33', 1);
--
-- Dumping data for table weapons
--

INSERT INTO weapons (id, name, class, ord) VALUES
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
