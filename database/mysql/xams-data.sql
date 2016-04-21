INSERT INTO pm_properties VALUES ('database_structure', '0.0.20');

INSERT INTO pm_preferences (LogLevel, Admin, LogLines, NewVersionCheck,
LastVersionCheck, DefaultLanguage, OnlineNews, LoginWelcome, SpamScore, HighSpamScore)
VALUES (3, 'admin', 13, 'true', '0000-00-00', 'english', 'false', 'Welcome to XAMS', '5', '15');

-- Uncomment the following line to insert a test account to the database (Username: demo / Password: demo)
-- INSERT INTO pm_admins (Name, Password, Added) VALUES ("demo", MD5("demo"), NOW());

