# comments
/* comments */
-- comments

# Host: host    Database: fitnessTracker

 DROP DATABASE IF EXISTS 'fitnessTracker'
 CREATE DATABASE 'fitnessTracker'
 USE 'fitnessTracker';

-- Source for table user
 DROP TABLE IF EXISTS 'User';
 CREATE TABLE 'User'(
    'Username'	VARCHAR(15) NOT NULL,
	'Password'	VARCHAR(15) NOT NULL,
	PRIMARY KEY ('Username', 'Password')
 );