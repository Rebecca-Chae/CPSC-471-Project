# comments
/* comments */
-- comments

# Host: host    Database: fitnessTracker

-- Source for table user
CREATE TABLE IF NOT EXISTS `User`(
    `Username`	VARCHAR(15) NOT NULL,
	`Password`	VARCHAR(15) NOT NULL,
	PRIMARY KEY (`Username`,`Password`)
);

CREATE TABLE IF NOT EXISTS `Body_Measurement`(
	`Username`	VARCHAR(15)	NOT NULL,
 	`Date`		DATE		NOT NULL,
 	`Weight`	DECIMAL(4,1),
 	`Waist`		DECIMAL(4,1),
 	`Chest`		DECIMAL(4,1),
 	`Hips`		DECIMAL(4,1),
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES `User`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Professional`(
	`Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES `User`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Monitor`(
	`Username`				VARCHAR(15)	NOT NULL,
	`Professional_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Username`, `Professional_Username`),
	FOREIGN KEY (`Username`) REFERENCES `Body_Measurement`(`Username`) ON DELETE CASCADE,
	FOREIGN KEY (`Professional_Username`) REFERENCES `Professional`(`Username`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `Professional’s_Times_Available`(
	`Username`	VARCHAR(15)	NOT NULL,
	`Date`		DATE		NOT NULL,
	`Time`		DECIMAL		NOT NULL,
	PRIMARY KEY (`Username`, `Date`, `Time`),
	FOREIGN KEY (`Username`) REFERENCES `Professional`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Client`(
	`Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES `User`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Friends_With`(
	`Friends_Username`	VARCHAR(15)	NOT NULL,
	`Client_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Friends_Username`, `Client_Username`),
	FOREIGN KEY (`Friends_Username`) REFERENCES `Client`(`Username`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Hires`(
	`Client_Username`		VARCHAR(15)	NOT NULL,
	`Feedback`				VARCHAR(256),
	`Professional_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Client_Username`, `Professional_Username`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`),
	FOREIGN KEY (`Professional_Username`) REFERENCES  `Professional`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Appointments`(
	`Professional_Username`	VARCHAR(15)	NOT NULL,
	`Time`					DECIMAL		NOT NULL,
	`Date`					DATE		NOT NULL,
	`Client_Username`		VARCHAR(15),
	PRIMARY KEY (`Professional_Username`, `Time`, `Date`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`),
	FOREIGN KEY (`Professional_Username`) REFERENCES `Professional`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Exercise`(
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	`Type`				VARCHAR(15),
	`Calories_Burned`	INT,
	PRIMARY KEY (`Exercise_Name`)
);

CREATE TABLE IF NOT EXISTS `Workout_Routine`(
	`Client_Username`	VARCHAR(15)	NOT NULL,
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Client_Username`, `Exercise_Name`),
	FOREIGN KEY (`Client_Username`) REFERENCES `User`(`Username`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise`(`Exercise_Name`)
);

CREATE TABLE IF NOT EXISTS `Personal_Trainer`(
	`Username`			VARCHAR(15)	NOT NULL,
	`Assigns_Routine`	VARCHAR(256),
	`Assigns_To_Client`	VARCHAR(15),
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Assigns_Routine`) REFERENCES `Workout_Routine`(`Exercise_name`),
	FOREIGN KEY (`Assigns_To_Client`) REFERENCES `Workout_Routine`(`Client_Username`)
);

CREATE TABLE IF NOT EXISTS `Other_Professional`(
	`Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES `Professional`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Nutritionist`(
	`Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES Professional(`Username`)
);

CREATE TABLE IF NOT EXISTS `Food_Item`(
	`FoodID`	VARCHAR(32)	NOT NULL,
	`Calories`	INT,
	`Carbs`		DECIMAL,
	`Protein`	DECIMAL,
	`Fat`		DECIMAL,
	PRIMARY KEY (`FoodID`)
);

CREATE TABLE IF NOT EXISTS `Recommended_Meal_Plan`(
	`FoodID`					VARCHAR(32)	NOT NULL,
	`Client_Username`			VARCHAR(15)	NOT NULL,
	`Nutritionists_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`FoodID`, `Client_Username`, `Nutritionists_Username`),
	FOREIGN KEY (`FoodID`) REFERENCES `Food_Item`(`FoodID`),
	FOREIGN KEY (`Client_Username`) REFERENCES `User`(`Username`),
	FOREIGN KEY (`Nutritionists_Username`) REFERENCES `Nutritionist`(`Username`)
);

CREATE TABLE IF NOT EXISTS `Eats`(
	`FoodID`			VARCHAR(32)	NOT NULL,
	`Client_Username`	VARCHAR(15)	NOT NULL,
	`Date`				DATE		NOT NULL,
	PRIMARY KEY (`FoodId`, `Client_Username`),
	FOREIGN KEY (`FoodId`) REFERENCES `Food_Item`(`FoodId`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`)
);
	
CREATE TABLE IF NOT EXISTS `Consists_Of`(
	`FoodID`					VARCHAR(32)	NOT NULL,
	`Client_Username`			VARCHAR(15)	NOT NULL,
	`Nutritionists_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`FoodID`, `Client_Username`, `Nutritionists_Username`),
	FOREIGN KEY (`Client_Username`) REFERENCES `User`(`Username`),
	FOREIGN KEY (`Nutritionists_Username`) REFERENCES `Nutritionist`(`Username`)
);


CREATE TABLE IF NOT EXISTS `Weight_Training`(
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	`Reps`				INT,
	`Weight_in_Pounds`	INT,
	PRIMARY KEY (`Exercise_Name`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise`(`Exercise_Name`)
);

CREATE TABLE IF NOT EXISTS `Cardio`(
	`Exercise_Name`			VARCHAR(15)	NOT NULL,
	`Intensity`				INT,
	`TimePerformedInMins`	INT,
	PRIMARY KEY (`Exercise_Name`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise`(`Exercise_Name`)
);

CREATE TABLE IF NOT EXISTS `Other_Exercise`(
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Exercise_Name`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise`(`Exercise_Name`)
);

CREATE TABLE IF NOT EXISTS `Performs`(
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	`Client_Username`	VARCHAR(15)	NOT NULL,
	`Date`				DATE		NOT NULL,
	PRIMARY KEY (`Exercise_Name`, `Client_Username`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise`(`Exercise_name`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`)
);


CREATE TABLE IF NOT EXISTS `Recommends`(
	`Client_Username`			VARCHAR(15)	NOT NULL,
	`Nutritionists_Username`	VARCHAR(15)	NOT NULL,
	`FoodID`					VARCHAR(32)	NOT NULL,
	PRIMARY KEY (`Client_Username`, `Nutritionists_Username`, `FoodID`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`),
	FOREIGN KEY (`Nutritionists_Username`) REFERENCES `Nutritionist`(`Username`),
	FOREIGN KEY (`FoodID`) REFERENCES `Food_Item`(`FoodID`)
);

CREATE TABLE IF NOT EXISTS `Includes`(
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	`Client_Username`	VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Exercise_Name`, `Client_Username`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Workout_Routine`(`Exercise_name`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Workout_Routine`(`Client_Username`)
);

CREATE TABLE IF NOT EXISTS `Will_Do`(
	`Client_Username`	VARCHAR(15)	NOT NULL,
	`Exercise_Name`		VARCHAR(15)	NOT NULL,
	PRIMARY KEY (`Client_Username`, `Exercise_Name`),
	FOREIGN KEY (`Client_Username`) REFERENCES `Client`(`Username`),
	FOREIGN KEY (`Exercise_Name`) REFERENCES `Workout_Routine`(`Exercise_Name`)
);