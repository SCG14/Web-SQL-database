
/**********************************************************************
 * NAME: Santiago Calvillo
 * CLASS: CPSC 321
 * DATE: 20/11/2022
 **********************************************************************/


-- TODO: add drop table statements
DROP TABLE IF EXISTS Plan;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS ArtPiece;
DROP TABLE IF EXISTS Museum;
DROP TABLE IF EXISTS City;

-- TODO: add create table statements
-- city table with different city attributes
CREATE TABLE City (
    city_id CHAR(3),
    city_name CHAR(20) NOT NULL,
    city_population INT UNSIGNED NOT NULL,
    country CHAR(20) NOT NULL, 
    language CHAR(20) NOT NULL,
    PRIMARY KEY (city_id)
);

-- museums have different attributes like the type of museum (children, art, science etc) and a city they're in
CREATE TABLE Museum (
    museum_id CHAR(20),
    museum_name CHAR(50) NOT NULL,
    address TEXT NOT NULL,
    museum_type CHAR(20) NOT NULL,
    city_id CHAR(20) NOT NULL,
    PRIMARY KEY (museum_id),
    FOREIGN KEY (city_id) REFERENCES City(city_id)
);

-- art pieces can be paintings sculptures or others and have some author data and creation year
CREATE TABLE ArtPiece (
    piece_id CHAR(20),
    painting_name CHAR(60) NOT NULL,
    author CHAR(20) NOT NULL,
    painting_type ENUM('painting', 'sculpture', 'other') NOT NULL,
    creation_year INT UNSIGNED,
    museum_id CHAR(20),
    PRIMARY KEY (piece_id),
    KEY m_id(museum_id),
    CONSTRAINT m_id FOREIGN KEY (museum_id) REFERENCES Museum(museum_id),
    CONSTRAINT CHECK (creation_year > 0 and creation_year < 2022)
);

-- reviews have an author a rating 1 to 5 and can only be one user at a certain museum
CREATE TABLE Review (
    review_id INTEGER UNSIGNED,
    author CHAR(20) NOT NULL,
    rating  INT UNSIGNED NOT NULL,
    museum_id CHAR(20) NOT NULL,
    PRIMARY KEY (review_id),
    FOREIGN KEY (museum_id) REFERENCES Museum(museum_id),
    CONSTRAINT CHECK (rating <=5)
);

-- plan inherits from the first 3 tables (art, museum, city) and prepares the data to be displayed
CREATE TABLE Plan(
         username CHAR(20),
         id CHAR(20),
         name CHAR(60) NOT NULL,
         inherits CHAR(60),
         type ENUM('city', 'museum', 'art piece') NOT NULL,
         PRIMARY KEY (username, id)
)
