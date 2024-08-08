DROP SCHEMA IF EXISTS lbaw23106 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw23106;
SET search_path TO lbaw23106;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS auction CASCADE;
DROP TABLE IF EXISTS product_images CASCADE;
DROP TABLE IF EXISTS bid CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS user_report CASCADE;
DROP TABLE IF EXISTS auction_report CASCADE;
DROP TABLE IF EXISTS payment CASCADE;
DROP TABLE IF EXISTS ban CASCADE;
DROP TABLE IF EXISTS messages CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS winner_notification CASCADE;
DROP TABLE IF EXISTS end_auction_notification CASCADE;
DROP TABLE IF EXISTS outbid_notification CASCADE;

DROP TYPE IF EXISTS statee;
DROP TYPE IF EXISTS typee;
DROP TYPE IF EXISTS image_typee;

----------------------------------------------------
-- Types
----------------------------------------------------
CREATE TYPE statee AS ENUM('Waiting Payment', 'Occurring', 'Ended- Waiting Exchange', 'Closed');
CREATE TYPE typee AS ENUM('Video Games', 'Board Games', 'Card Games');
CREATE TYPE image_typee AS ENUM('jpg', 'png');

----------------------------------------------------
-- Tables
----------------------------------------------------

-- Note that a plural 'users' name was adopted because user is a reserved word in PostgreSQL.

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT user_username_uk UNIQUE,
    email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    profile_pic VARCHAR NOT NULL DEFAULT 'default.png',
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE auction (
    auction_id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    price INTEGER NOT NULL,
    description TEXT NOT NULL,
    min_price INTEGER NOT NULL,
    location TEXT NOT NULL,
    end_date DATE NOT NULL,
    state statee NOT NULL,
    type typee NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE
);

CREATE TABLE product_images (
    id SERIAL PRIMARY KEY,
    auction_id INTEGER NOT NULL REFERENCES auction (auction_id) ON UPDATE CASCADE,
    image VARCHAR NOT NULL DEFAULT 'default.png'
);

CREATE TABLE bid (
    bid_id SERIAL PRIMARY KEY,
    auction_id INTEGER NOT NULL REFERENCES auction (auction_id) ON UPDATE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE,
    value INTEGER NOT NULL,
    timestamp TIMESTAMP WITH TIME ZONE NOT NULL
);

CREATE TABLE report (
    report_id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE,
    reason TEXT NOT NULL,
    date DATE NOT NULL
);

CREATE TABLE user_report (
    report_id INTEGER NOT NULL REFERENCES report (report_id) ON UPDATE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE
);

CREATE TABLE auction_report (
    report_id INTEGER NOT NULL REFERENCES report (report_id) ON UPDATE CASCADE,
    auction_id INTEGER NOT NULL REFERENCES auction (auction_id) ON UPDATE CASCADE
);

CREATE TABLE payment (
    payment_id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE,
    auction_id INTEGER NOT NULL REFERENCES auction (auction_id) ON UPDATE CASCADE,
    value INTEGER NOT NULL,
    date DATE NOT NULL
);

CREATE TABLE ban (
    ban_id SERIAL PRIMARY KEY,
    reason TEXT NOT NULL,
    date DATE NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE
);

CREATE TABLE messages (
    message_id SERIAL PRIMARY KEY,
    contents TEXT NOT NULL,
    time TIMESTAMP WITH TIME ZONE NOT NULL,
    auction_id INTEGER NOT NULL REFERENCES auction (auction_id) ON UPDATE CASCADE,
    emitter INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE,
    receiver INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE
);

CREATE TABLE notifications (
    notification_id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users (user_id) ON UPDATE CASCADE,
    seen BOOLEAN NOT NULL,
    time TIMESTAMP WITH TIME ZONE NOT NULL,
    date DATE NOT NULL
);

CREATE TABLE winner_notification (
    notification_id INTEGER NOT NULL REFERENCES notifications (notification_id) ON UPDATE CASCADE,
    auction_id INTEGER NOT NULL REFERENCES auction(auction_id) ON UPDATE CASCADE
);

CREATE TABLE end_auction_notification (
    notification_id INTEGER NOT NULL REFERENCES notifications (notification_id) ON UPDATE CASCADE,
    auction_id INTEGER NOT NULL REFERENCES auction(auction_id) ON UPDATE CASCADE
);

CREATE TABLE outbid_notification (
    notification_id INTEGER NOT NULL REFERENCES notifications (notification_id) ON UPDATE CASCADE,
    bid_id INTEGER NOT NULL REFERENCES bid(bid_id) ON UPDATE CASCADE
);


----------------------------------------------------
-- Indexes
----------------------------------------------------

CREATE INDEX index_bid ON bid USING btree (timestamp);
CREATE INDEX users_bids ON bid USING btree (user_id);
CREATE INDEX auctions_bids ON bid USING btree (auction_id);

----------------------------------------------------
-- FTS Indexes
----------------------------------------------------

ALTER TABLE auction
ADD COLUMN tsvectors TSVECTOR;


CREATE FUNCTION auction_search_update()
RETURNS TRIGGER AS
$BODY$
BEGIN
	IF TG_OP = 'INSERT' THEN
		NEW.tsvectors = to_tsvector('english', NEW.name);
	END IF;
	IF TG_OP = 'UPDATE' THEN
		IF(NEW.name <> OLD.name) THEN
			NEW.tsvectors = to_tsvector('english', NEW.name);
		END IF;
	END IF;
	RETURN NEW;	

END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER auction_search_update
	BEFORE INSERT OR UPDATE ON auction
	FOR EACH ROW
	EXECUTE PROCEDURE auction_search_update();


CREATE INDEX auction_search ON auction USING GIN (tsvectors);

----------------------------------------------------

ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;


CREATE FUNCTION user_search_update()
RETURNS TRIGGER AS
$BODY$
BEGIN
	IF TG_OP = 'INSERT' THEN
		NEW.tsvectors = to_tsvector('english', NEW.username);
	END IF;
	IF TG_OP = 'UPDATE' THEN
		IF(NEW.username <> OLD.username) THEN
			NEW.tsvectors = to_tsvector('english', NEW.username);
		END IF;
	END IF;
	RETURN NEW;	

END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER user_search_update
	BEFORE INSERT OR UPDATE ON users
	FOR EACH ROW
	EXECUTE PROCEDURE user_search_update();


CREATE INDEX user_search ON users USING GIN (tsvectors);

----------------------------------------------------
-- TRIGGERS AND UDFs
----------------------------------------------------
/*
CREATE FUNCTION prevent_self_bidding() RETURNS TRIGGER AS 
$BODY$
BEGIN
	IF EXISTS (SELECT auction_id FROM auction WHERE NEW.user_id=(SELECT auction_id FROM bid WHERE user_id=NEW.user_id)) THEN
		RAISE EXCEPTION 'fuction creator cannot bid on their own auction.';
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER check_self_bidding
	BEFORE INSERT OR UPDATE ON bid
	FOR EACH ROW
	EXECUTE PROCEDURE prevent_self_bidding();
	

CREATE FUNCTION enforce_auction_end_date() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF NEW.state = 'Occurring' THEN
        NEW.end_date := CURRENT_TIMESTAMP + INTERVAL '15 days';
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER set_auction_end_date
	BEFORE INSERT OR UPDATE ON auction
	FOR EACH ROW
	EXECUTE PROCEDURE enforce_auction_end_date();


CREATE OR REPLACE FUNCTION validate_profile_pic_format() RETURNS TRIGGER AS 
$BODY$
BEGIN
  IF NEW.image ~ '^[a-zA-Z0-9_]+\.(jpg|jpeg|png)$' THEN
    RETURN TRUE;
  ELSE
    RAISE EXCEPTION 'Invalid picture format. It should be a valid image file format (jpg, jpeg, png).';
    RETURN FALSE;
  END IF;
END;
$BODY$ LANGUAGE plpgsql;


CREATE TRIGGER check_image_data_type
	BEFORE INSERT ON product_images
	FOR EACH ROW
	EXECUTE PROCEDURE validate_profile_pic_format();

/**
CREATE FUNCTION delete_user() RETURNS TRIGGER AS 
$BODY$
DECLARE
	user_id_to_delete INTEGER;
BEGIN
	user_id_to_delete = OLD.user_id;


	-- Update auctions with the user as the creator
	UPDATE users
	SET name = 'deleted user', username = 'deleted user'
	WHERE user_id = user_id_to_delete;


	-- Close auctions created by the user that are not already closed
	UPDATE auction
	SET state = 'Closed'
	WHERE user_id = user_id_to_delete AND state != 'Closed';


	-- Insert closed auctions into end_auction_notification
	INSERT INTO end_auction_notification (auction_id)
	SELECT auction_id
	FROM auction
	WHERE user_id = user_id_to_delete AND state = 'Closed';


	-- Delete user from the users table
	DELETE FROM users WHERE user_id = user_id_to_delete;


	-- Delete related data from other tables
	DELETE FROM product_images WHERE auction_id IN (SELECT auction_id FROM auction WHERE user_id = user_id_to_delete);
	DELETE FROM bid WHERE user_id = user_id_to_delete;
	DELETE FROM report WHERE user_id = user_id_to_delete;
	DELETE FROM user_report WHERE user_id = user_id_to_delete;
	DELETE FROM auction_report WHERE auction_id IN (SELECT auction_id FROM auction WHERE user_id = user_id_to_delete);
	DELETE FROM payment WHERE user_id = user_id_to_delete;
	DELETE FROM ban WHERE user_id = user_id_to_delete;
	DELETE FROM notifications WHERE user_id = user_id_to_delete;


	RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER enforce_user_deletion
	BEFORE INSERT OR UPDATE ON users
	FOR EACH ROW
	EXECUTE PROCEDURE delete_user();
*/

CREATE FUNCTION enforce_auction_payment_date() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF NEW.state = 'Waiting Payment' THEN
        NEW.end_date := CURRENT_TIMESTAMP + INTERVAL '15 days';
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER set_auction_payment_date
	BEFORE INSERT OR UPDATE ON auction
	FOR EACH ROW
	EXECUTE PROCEDURE enforce_auction_payment_date();
*/
/**
----------------------------------------------------
-- TRANSACTIONS
----------------------------------------------------

BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO users(user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (@user_id, @username, @email, @name,  @password, @profile_pic, @is_admin);

END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, typee, user_id)
VALUES(@auction_id, @name, @price, @description, @min_price, @location, @end_date, @state, @typee, @user_id);

END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO bid (auction_id, user_id, value, timestamp)
VALUES (@auction_id, @user_id, @value, @timestamp);

UPDATE auction
SET price= @value
Where auction_id=@auction_id;

END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

UPDATE auction
SET sate='Closed'
WHERE auction_id = @auction_id;

DO $$ 
DECLARE 
    latest_bid_timestamp timestamp;
BEGIN
    SELECT latest_bid_timestamp = MAX(timestamp) FROM bid WHERE auction_id= $auction_id;
END $$;

SELECT auction_id, user_id, MAX(timestamp) as last_bid_timestamp
FROM bid
GROUP BY auction_id;

INSERT INTO notification (user_id, seen, time, date)
SELECT b.user_id, FALSE, NOW(), NOW()
FROM auction a
JOIN bid b ON a.auction_id = b.auction_id
WHERE a.auction_id = @auction_id
  AND b.timestamp = (SELECT MAX(timestamp) FROM bid WHERE auction_id = @auction_id);

INSERT INTO notification (user_id, seen, time, date)
SELECT b.user_id, FALSE, NOW(), NOW()
FROM auction a
JOIN bid b ON a.auction_id = b.auction_id
WHERE a.auction_id = @auction_id
  AND b.timestamp < (SELECT MAX(timestamp) FROM bid WHERE auction_id = @auction_id);

END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

DELETE FROM users
WHERE user_id = @user_id;


END TRANSACTION;


BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

DELETE FROM auction
WHERE auction_id = @auction_id;

DELETE FROM product_images WHERE auction_id = @auction_id;
DELETE FROM bid WHERE auction_id = @auction_id;

END TRANSACTION;*/

----------------------------------------------------
-- Populate the database
----------------------------------------------------

INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (1, 'matifaro', 'matildefarobackup@gmail.com', 'Matilde Faro', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'fG2phWRkbLssUrRegI7qhJ988wcLH4u5VHTRGQRA.jpg', TRUE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (2, 'juanamaia', 'joana.c.maia03@gmail.com', 'Juanita', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'coESftLj9yKYfl6OLbsGiG3eQLZPHO0wE0UkMcCG.jpg', TRUE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (3, 'antonioroberts', 'antonio.roberts@gmail.com', 'Antonio Roberts', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (4, 'estrellathiago', 'estrella.thiago@gmail.com', 'Estrella and Thiago Forever', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (5, 'pablito', 'pabloo@gmail.com', 'Pablo <3', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (6, 'igorandrade', 'igor-m-andrade@hotmail.com', 'Igor', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', '8CS2CxraPHb4adFrg3cKAjZ9Pz6IhkLV7ofunGMP.jpg', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (7, 'derSchwarzeVulkan', 'michelemoutonrally@gmail.com', 'Michèle Mouton', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (8, 'dany', 'dany-ric@gmail.com', 'Daniel Ricciardo', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (9, 'liam', 'liam_f1_lawson@gmail.com', 'Liam Lawson', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (10, 'théo', 'theo_pouchaire@gmail.com', 'Théo Pourchaire', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (11, 'goatifi', 'latifi_nic@gmail.com', 'Nicolas Latifi', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin)
VALUES (12, 'mazespin', 'niki-maze@gmail.com', 'Nikita Mazepin', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE);
INSERT INTO users (user_id, username, email, name,  password, profile_pic, is_admin, is_deleted)
VALUES (13, 'jamiechad', 'chad-jamie@gmail.com', 'Jamie Chadwick', '$2a$10$vKp4piFVHJDJddZLsAsyluwtFsUNjV5A4mN.TRyFOOapuADlwp.9G', 'default.png', FALSE, TRUE);


INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (1, 'Cluedo', 40, 'Cluedo — known as Clue in North America — is a murder mystery game for three to six players, devised by Anthony E. Pratt from Birmingham, England, and currently published by the American game and toy company Hasbro. The object of the game is to determine who murdered the games victim, "Dr. Black", where the crime took place, and which weapon was used. Each player assumes the role of one of the six suspects, and attempts to deduce the correct answer by strategically moving around a game board representing the rooms of a mansion and collecting clues about the circumstances of the murder from the other players. This is the 1980 version (rare).', 50, 'London, UK', '2024-01-01', 'Occurring', 'Board Games', 1);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (2,'Clue', 10, 'In Clue, players move from room to room in a mansion to solve the mystery of: who done it, with what, and where? Players are dealt character, weapon, and location cards after the top card from each card type is secretly placed in the confidential file in the middle of the board. This is the 2009 version!', 35, 'Toronto, Canada', '2023-12-29', 'Occurring', 'Board Games', 2);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (3,'2200 Robux and Game Pass', 11, 'Robux is the in-game currency that gamers use in Roblox. Worth 2200 Robux', 30, 'Porto, Portugal', '2024-01-02', 'Occurring', 'Video Games', 3);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (4,'"NINTENDO WORLD CHAMPIONSHIP" (NES)', 1346, 'These cartridges were specially designed by Nintendo as part of a US promotional and marketing tour. This specially designed cartridge featured three games - Super Mario, Rad Racer and Tetris - with high scores recorded by officials. The competition itself was pretty huge, with prizes offered to players including bonds, money, trophies and highly rare game cartridges, like the ones here. Only 26 of these golden cartridges were made, meaning theyre almost worth their weight in gold.', 17000, 'Ohio, USA', '2024-01-03', 'Occurring', 'Video Games', 1);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (5,'UNO Original Deck', 39, 'UNO is the most famous card game ever! This is one of the ORIGINAL printed copies made in 1971.', 3500, 'Porto, Portugal', '2024-01-04', 'Occurring', 'Card Games', 3);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (6,'Nintendo Super Mario Bros', 25000, 'Super rare Super Mario Bros Vintage', 66000, 'Paris, France', '2024-01-03', 'Occurring', 'Video Games', 2);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (7,'What do you meme', 5, 'Card game to have fun with friends. It does not include any expansion, brand new.', 35, 'Lisboa, Portugal', '2023-12-31', 'Occurring', 'Card Games', 1);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (8,'Deck of cards signed by Lady Di', 2000, 'Normal london deck of cards, signed by princess Diana in 1990', 2470, 'London, UK', '2024-01-02', 'Occurring', 'Card Games', 5);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (9,'Samarcande mini chess set', 1394, 'Magnetic mini chess set in mahogany and cassia wood

- Removable strap in saddle-stitched smooth taurillon leather
- Hand-sculpted pawns in mahogany and cassia wood with Clou de Selle engraved stainless steel weights
- Smart closing mechanism with removable strap
- Leather strap made in France

Once the strap is removed, the lid can be used as a base for the game.

Made in Indonesia

Designed by Hermès Studio

Dimensions: L 12 x H 6 x D 12 cm', 2420, 'Paris, France', '2024-01-03', 'Occurring', 'Board Games', 3);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (10,'Set of 2 Les 4 Mondes bridge', 32, 'Set of 2 decks of bridge playing cards with 54 silver-edged cards

Made in France

Dimensions: L 8.9 x W 5.8 cm', 140, 'Nice, France', '2023-12-31', 'Occurring', 'Card Games', 5);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (11,'Cluedo', 0, 'Board Mystery Game where you have to find who killed the person, with what and when.', 32, 'London, UK', '2024-01-01', 'Waiting Payment', 'Board Games', 1);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (12,'Nintendo Mario', 0, 'Nintendo Video Game', 24, 'London, UK', '2024-01-03', 'Waiting Payment', 'Video Games', 5);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (13,'Nintendo Switch Kirby Game', 76, 'Game about Kirby for Nintendo Switch', 70, 'Manchester, UK', '2023-12-01', 'Closed', 'Video Games', 13);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (14,'Couvertures Nouvelles bridge playing cards', 140, 'Set of 2 decks of bridge playing cards with 54 silver-edged cards

Made in France

Dimensions: L 8.9 x W 5.8 cm', 140, 'Paris, France', '2023-12-11', 'Ended- Waiting Exchange', 'Card Games', 3);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (15,'Playable, Real Game, Jumanji Collector Edition', 1900, 'New!! Interactive Game, Throw The Dice and See how the Riddles Appear, 50 different ones with their 50 challenges, a different game in each game thanks to its random system, win the game and see how JUMANJI appears on the screen. The definitive and most accurate collectors edition for the most demanding fans. Hand-made. A unique product.', 1880, 'Manchester, UK', '2023-12-08', 'Occurring', 'Board Games', 4);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (16,'Sims- Grow Together Expansion Pack', 46, 'The Sims 4 Growing Together is an expansion that focuses primarily on building the gameplay experience and making Sims feels more realistic. While there are new clothing items, furniture and even a new world, its the gameplay that will hook you with this pack.', 40, 'London, UK', '2023-11-06', 'Closed', 'Video Games', 2);
INSERT INTO auction(auction_id, name, price, description, min_price, location, end_date, state, type, user_id)
VALUES (17,'Chess Set with Marble/Walnut/Mosaic Pattern', 146, 'MoleBoutiqueStore - Wooden Chess Board with Metal Chess Figures, Classic Chess Pieces, Chess Set with Marble/Walnut/Mosaic Pattern Chess Board, Chess Sets

- Step into the ultimate chess aficionados paradise at our store, where weve crafted a unique space for enthusiasts to create their perfect chess set. With a diverse selection of wooden chess boards and elegant chess pieces, customers have the delightful opportunity to mix and match to their hearts content. Whether youre drawn to the classic elegance of wooden pieces or the modern sophistication of metal, our customizable selection caters to every taste and playing style. The intuitive design of our page ensures a seamless experience, allowing chess lovers to visualize and assemble their ideal set with ease. Elevate your game and immerse yourself in the centuries-old tradition of chess with a personal touch, only at our store.', 130, 'London, UK', '2023-11-29', 'Closed', 'Board Games', 4);


INSERT INTO product_images(id, auction_id, image)
VALUES (1, 1, 'cluedo.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (2, 2, 'clue.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (3, 3, 'roblox.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (4, 4, 'nes.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (5, 5, 'uno.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (6, 6, 'spb.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (7, 7, 'wdym.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (8, 8, 'cardsdi.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (9, 9, 'chessSet.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (10, 10, 'mondes.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (11, 11, 'cluedo.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (12, 12, 'mario.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (13, 13, 'kirby.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (14, 14, 'couvertures.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (15, 15, 'jumanji.jpg');
INSERT INTO product_images(id, auction_id, image)
VALUES (16, 16, 'sims.jpeg');
INSERT INTO product_images(id, auction_id, image)
VALUES (17, 17, 'chessSetM.jpeg');



INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (1, 1, 2, 20, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (2, 1, 3, 40, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (3, 2, 1, 5, '2023-12-16');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (4, 2, 4, 10, '2023-12-18');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (5, 3, 5, 6, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (6, 3, 6, 11, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (7, 4, 7, 673, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (8, 4, 8, 1346, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (9, 5, 9, 19, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (10, 5, 10, 39, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (11, 6, 11, 20000, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (12, 6, 12, 25000, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (13, 7, 3, 3, '2023-12-17');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (14, 7, 2, 5, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (15, 8, 1, 1500, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (16, 8, 4, 2000, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (17, 9, 5, 700, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (18, 9, 6, 1394, '2023-12-20');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (19, 10, 7, 25, '2023-12-17');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (20, 10, 8, 32, '2023-12-19');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (21, 13, 9, 60, '2023-11-22');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (22, 13, 10, 76, '2023-11-23');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (23, 14, 11, 100, '2023-12-01');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (24, 14, 12, 140, '2023-12-03');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (25, 15, 1, 1500, '2023-11-28');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (26, 15, 2, 1900, '2023-12-01');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (27, 16, 3, 35, '2023-10-30');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (28, 16, 4, 46, '2023-11-05');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (29, 17, 5, 150, '2023-11-21');
INSERT INTO bid(bid_id, auction_id, user_id, value, timestamp)
VALUES (30, 17, 6, 176, '2023-11-27');



ALTER SEQUENCE users_user_id_seq RESTART WITH 1;
UPDATE users SET user_id=nextval('users_user_id_seq');
ALTER SEQUENCE auction_auction_id_seq RESTART WITH 1;
UPDATE auction SET auction_id=nextval('auction_auction_id_seq');
ALTER SEQUENCE product_images_id_seq RESTART WITH 1;
UPDATE product_images SET id=nextval('product_images_id_seq');
ALTER SEQUENCE bid_bid_id_seq RESTART WITH 1;
UPDATE bid SET bid_id=nextval('bid_bid_id_seq');
ALTER SEQUENCE payment_payment_id_seq RESTART WITH 1;
UPDATE payment SET payment_id=nextval('payment_payment_id_seq');