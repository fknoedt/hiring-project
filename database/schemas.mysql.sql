-- I've opted for a MySQL solution as I already have this service running locally
-- foreign keys were added to emails and usage_log tables
-- `created_at` and `updated_at` columns were used - instead of `date` - to store distinct insert and update times

CREATE DATABASE `sss`
CHARACTER SET 'utf8mb4'
COLLATE 'utf8mb4_general_ci';

USE `strivven`;

CREATE TABLE users(
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `fName` varchar(500) NULL,
  `lName` varchar(500) NULL,
  `username` varchar(256) NULL,
  `password` varchar(256) NULL,
  `age` bigint NULL,
  `created_at` datetime(3) NULL, -- replaced single `date` column
  `updated_at` datetime(3) NULL
);

CREATE TABLE emails(
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `userId` int NOT NULL, -- column added to hold the FK to the users table
  `email` varchar(500) NULL,
  `primary` int NULL,
  `created_at` datetime(3) NULL, -- replaced single `date` column
  `updated_at` datetime(3) NULL,
  FOREIGN KEY(`userId`) REFERENCES users(`id`) -- added to enforce constraint
);


CREATE TABLE `usage_log` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `userId` INTEGER(11) NOT NULL,
  `sessionId` VARCHAR(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login` DATETIME(3) DEFAULT NULL,
  `last_movement` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `logout` DATETIME(3) DEFAULT NULL,
  PRIMARY KEY USING BTREE (`id`),
  KEY `userId` USING BTREE (`userId`),
  KEY `usage_log_idx1` USING BTREE (`sessionId`),
  CONSTRAINT `usage_log_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB
AUTO_INCREMENT=6 ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci'
;


CREATE TABLE user_admin(
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `userId` int NOT NULL,
  FOREIGN KEY(`userId`) REFERENCES users(`id`) -- added to enforce constraint
);


-- Assessments Structure & Data --

CREATE TABLE `areas` (
  `id` INTEGER(11) NOT NULL,
  `name` VARCHAR(512) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY USING BTREE (`id`),
  UNIQUE KEY `name` USING BTREE (`name`)
) ENGINE=InnoDB
ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci'
;

CREATE TABLE `assessments` (
  `id` INTEGER(11) NOT NULL,
  `name` VARCHAR(512) COLLATE utf8mb4_general_ci NOT NULL,
  `areaId` INTEGER(11) NOT NULL,
  PRIMARY KEY USING BTREE (`id`),
  KEY `assessments_fk1` USING BTREE (`areaId`),
  CONSTRAINT `assessments_fk1` FOREIGN KEY (`areaId`) REFERENCES `areas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB
ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';


CREATE TABLE `user_assessment` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `userId` INTEGER(11) NOT NULL,
  `assessmentId` INTEGER(11) NOT NULL,
  `created_or_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY USING BTREE (`id`),
  KEY `user_assessment_fk1` USING BTREE (`userId`),
  KEY `user_assessment_fk2` USING BTREE (`assessmentId`),
  CONSTRAINT `user_assessment_fk1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_assessment_fk2` FOREIGN KEY (`assessmentId`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB
ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci'
;


-- // DATA // --


INSERT INTO `users` (`id`, `fName`, `lName`, `age`, `created_at`, `updated_at`, `username`, `password`) VALUES
  (1,'Filipe','Knoedt',38,'2019-10-25 01:19:20.949',NULL,'fknoedt','$2y$10$zTYwsvj3QLbvQj8J99ZTWuMN3gq5tvcLYbNx4CTxmVZyOmWHDWxZW'),
  (2,'James','Frisella',NULL,'2019-10-25 01:19:30.639',NULL,'james','$2y$10$zTYwsvj3QLbvQj8J99ZTWuMN3gq5tvcLYbNx4CTxmVZyOmWHDWxZW'),
  (3,'Woody','Allen',NULL,'2019-10-25 01:58:26.248',NULL,NULL,NULL);


INSERT INTO `emails` (`id`, `userId`, `email`, `primary`, `created_at`, `updated_at`) VALUES
  (1,1,'fknoedt@gmail.com',1,'2019-10-25 01:51:15.857',NULL),
  (2,1,'fknoedt.secondary@gmail.com',0,'2019-10-25 01:52:05.087',NULL),
  (3,2,'james@strivven.com',1,'2019-10-25 01:52:23.461',NULL);


insert into areas (id, name) values
(1,	'Agriculture, Food & Natural Resources'),
(2,	'Architecture & Construction'),
(3,	'Arts, A/V Technology & Communications'),
(4,	'Business Management & Administration'),
(5,	'Education & Training'),
(6,	'Finance'),
(7,	'Government & Public Administration'),
(8,	'Health Science'),
(9,	'Hospitality & Tourism'),
(10, 'Human Services'),
(11, 'Information Technology'),
(12, 'Law, Public Safety, Corrections & Security'),
(13, 'Manufacturing'),
(14, 'Marketing, Sales & Service'),
(15, 'Science, Technology, Engineering & Mathematics'),
(16, 'Transportation, Distribution & Logistics');

insert into assessments (id, name, areaId) values
(207, 'Learning how things grow and stay alive',1),
(208, 'Making the best use of the earth\'s natural resources',1),
(209, 'Hunting or fishing',1),
(210, 'Protecting the environment or taking care of animals',1),
(211, 'Being outdoors',1),
(212, 'Planning, budgeting, and keeping records',1),
(213, 'Operating and/or repairing machines',1),
(214, 'Reading and following blueprints and/or instructions',2),
(215, 'Picturing in my mind what a finished product will look like',2),
(216, 'Working with my hands',2),
(217, 'Performing work that requires precise steps',2),
(218, 'Solving technical problems',2),
(219, 'Visiting and learning from historical or beautiful buildings',2),
(220, 'Following logical step-by-step procedures',2),
(221, 'Using my imagination to communicate new information to others',3),
(222, 'Performing in front of others',3),
(223, 'Reading and writing',3),
(224, 'Playing a musical instrument',3),
(225, 'Performing creative, artistic activities',3),
(226, 'Using video and recording technology',3),
(227, 'Designing brochures and posters',3),
(228, 'Performing routine, organized activities but can be flexible',4),
(229, 'Working with numbers and detailed information',4),
(230, 'Being the leader in a group',4),
(231, 'Making business contact with people',4),
(232, 'Working with computer programs',4),
(233, 'Creating reports and communicating ideas',4),
(234, 'Planning my work and follow instructions without close supervision',4),
(235, 'Communicating with different types of people',5),
(236, 'Help others with their homework or to learn new things',5),
(237, 'Going to school',5),
(238, 'Directing and planning activities for others',5),
(239, 'Handling several responsibilities at once',5),
(240, 'Acquiring new information',5),
(241, 'Helping people overcome their challenges',5),
(242, 'Working with numbers',6),
(243, 'Working to meet a deadline',6),
(244, 'Making predictions based on existing facts',6),
(245, 'Having a framework of rules by which to operate',6),
(246, 'Analyzing financial information and interpret it to others',6),
(247, 'Handling money with accuracy and reliability',6),
(248, 'Taking pride in the way I dress and look',6),
(249, 'Being involved in politics',7),
(250, 'Negotiating, defending, and debating ideas and topics',7),
(251, 'Planning activities and working cooperatively with others',7),
(252, 'Working with details',7),
(253, 'Performing a variety of duties that may change often',7),
(254, 'Analyzing information and interpreting it to others',7),
(255, 'Traveling and seeing things that are new to me',7),
(256, 'Working under pressure',8),
(257, 'Helping sick people and animals',8),
(258, 'Making decisions based on logic and information',8),
(259, 'Participating in health and science classes',8),
(260, 'Responding quickly and calmly in emergencies',8),
(261, 'Working as a member of a team',8),
(262, 'Following guidelines precisely and meet strict standards of accuracy',8),
(263, 'Investigating new places and activities',9),
(264, 'Working with all ages and types of people',9),
(265, 'Organizing activities in which other people enjoy themselves',9),
(266, 'Having a flexible schedule',9),
(267, 'Helping people make up their minds',9),
(268, 'Communicating easily, tactfully, and courteously',9),
(269, 'Learning about other cultures',9),
(270, 'Caring about people, their needs, and their problems',10),
(271, 'Participating in community services and/or volunteering',10),
(272, 'Listening to other people\'s viewpoints',10),
(273, 'Working with people from preschool age to old age',10),
(274, 'Thinking of new ways to do things',10),
(275, 'Making friends with different kinds of people',10),
(316, 'Being socially aware or advocating for the rights of others',10),
(276, 'Reasoning clearly and logically to solve complex problems',11),
(277, 'Using machines, techniques, and processes',11),
(278, 'Reading technical materials and/or diagrams',11),
(279, 'Solving technical problems',11),
(280, 'Adapting to change',11),
(281, 'Playing video games and figure out how they work',11),
(282, 'Concentrating for long periods without being distracted',11),
(283, 'Working under pressure or in the face of danger',12),
(284, 'Making decisions based on my own observations',12),
(285, 'Interacting with other people',12),
(286, 'Respecting rules and regulations',12),
(287, 'Debating and winning arguments',12),
(288, 'Observing and analyzing people\'s behavior',12),
(317, 'Helping people in need or standing up for the rights of others',12),
(289, 'Working with my hands',13),
(290, 'Putting things together',13),
(291, 'Doing routine, organized and accurate work',13),
(292, 'Performing activities that produce tangible results',13),
(293, 'Applying math to work out solutions',13),
(294, 'Using hand and power tools and operate equipment/machinery',13),
(295, 'Visualizing objects in three dimensions from flat drawings',13),
(296, 'Shopping and going to the mall',14),
(297, 'Being in charge',14),
(298, 'Making displays and promote ideas',14),
(299, 'Giving presentations and enjoy public speaking',14),
(300, 'Persuading people to buy products or to participate in activities',14),
(301, 'Communicating my ideas to other people',14),
(302, 'Taking advantage of opportunities to make extra money',14),
(303, 'Interpreting formulas',15),
(304, 'Finding the answers to questions',15),
(305, 'Working in a laboratory',15),
(306, 'Figuring out how things work and investigate new things',15),
(307, 'Exploring new technology',15),
(308, 'Experimenting to find the best way to do something',15),
(309, 'Paying attention to details and help things be precise',15),
(310, 'Traveling',16),
(311, 'Seeing well and having quick reflexes',16),
(312, 'Designing efficient processes',16),
(313, 'Anticipating needs and preparing to meet them',16),
(314, 'Driving or riding',16),
(315, 'Moving things from one place to another',16),
(318, 'Working with machines or automotive technology',16);


