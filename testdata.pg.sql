INSERT INTO agents (id, fullname, alias, email, phone, address, course, societies, clubs, timetable) VALUES
(1,'James Cleveland', 'radiosilence', 'jamescleveland@example.com', '567567567', '38 MANCHESTER LANE, NOWHERE', 'Computer Science & Cybernetics (BSc)', 'Indie Society, Video Game Society', 'Iguana, Upin Arms', 'Blahh'),
(2, 'Ruth Sullivan', 'littlespy', 'littlespy@example.com', '02223382', 'Adasdasd', 'History at Wexham', 'None', 'Pavs', 'Dogs');


INSERT INTO games (id, name, start_date, end_date, signup_date, location, victor, description, state, entry_fee, local_currency, invite_only) VALUES
(1, 'Test Game', '2010-11-13 13:59:17', '2010-11-30 13:59:22', '2010-11-12 13:59:28', 'Reading, UK', 0, 'Blahhh', 1, 3, 0, 0),
(2, 'Stupid Game', '2011-01-01 01:54:06', '3010-11-30 01:54:08', '2010-11-01 01:54:11', 'Hull', 0, 'Worst game ever', 0, 555, 0, 0);

INSERT INTO kills (weapon, description, assassin, target, when_happened, game) VALUES
(8, 'Blah bla', 2, 1, '2010-11-13 17:09:09', 1),
(2, 'Bleop', 1, 2, '2010-11-04 17:09:33', 1);

INSERT INTO weapons (name, class, ord) VALUES
('Nerf N-Strike Maverick', 'Ranged', 0),
('Nerf N-Strike Longshot CS6', 'Ranged', 0),
('Nerf N-Strike Buzzsaw', 'Ranged', 0),
('Nerf N-Strike Secret Strike AS-1', 'Ranged', 0),
('Nerf N-Strike Raider Rapid Fire CS-35', 'Ranged', 0),
('Nerf N-Strike Firefly Blaster', 'Ranged', 0),
('Nerf N-Strike Nite Finder EX-3', 'Ranged', 0),
('Nerf N-Strike Vulcan EBF-25', 'Ranged', 0),
('Nerf N-Strike Switch Shot EX-3', 'Ranged', 0),
('Nerf N-Strike Recon CS-6', 'Ranged', 0),
('Other Ranged', 'Ranged', 1),
('Other Melee', 'Melee', 1),
('Poisoned (Food)', 'Poison', 0),
('Poisoned (Drink)', 'Poison', 0),
('Other Special', 'Special', 1),
('Poisoned (Contact)', 'Poison', 0),
('Nerf N-ForceThunder Fury', 'Melee', 0),
('Nerf (Other)', 'Ranged', 0),
('Fridge', 'Special', 0),
('Garrotting', 'Melee', 0),
('Sword', 'Melee', 0),
('Dagger', 'Melee', 0),
('Stiletto', 'Melee', 0),
('Hammer', 'Melee', 0),
('Grenade', 'Special', 0),
('Broken Neck', 'Melee', 0),
('Kiss of Death', 'Poison', 0),
('Beating', 'Melee', 0),
('Drive By', 'Special', 0),
('Letter Bomb', 'Special', 0),
('Electrocution', 'Special', 0),
('AIDS', 'Special', 0);
