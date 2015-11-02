--Authors: Matt Pineau--
--Date: 10/26/15--
--Project: Capping (Database)--
--Professor: Dr. Rivas--

--Drop all stored procedures if they already exist
DROP FUNCTION getReportCredits(integer);

--  --Drop all views if they already exist--
DROP VIEW IF EXISTS CoursesToRequirements;

--Drop all tables if they already exist--
DROP TABLE IF EXISTS CoreRequirements;
DROP TABLE IF EXISTS CountsToward;
DROP TABLE IF EXISTS CoursesTaken;
DROP TABLE IF EXISTS TransfersTo;
DROP TABLE IF EXISTS MaristCourses;
DROP TABLE IF EXISTS InstitutionCourses;
DROP TABLE IF EXISTS ConcentrationSelections;
DROP TABLE IF EXISTS MinorSelections;
DROP TABLE IF EXISTS MinorRequirements;
DROP TABLE IF EXISTS MaristMinors;
DROP TABLE IF EXISTS ConcentrationRequirements;
DROP TABLE IF EXISTS Requirements;
DROP TABLE IF EXISTS MajorConcentrations;
DROP TABLE IF EXISTS MaristMajors;
DROP TABLE IF EXISTS TransferReports;
DROP TABLE IF EXISTS Admins;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Institutions;
DROP TABLE IF EXISTS Addresses;
DROP TABLE IF EXISTS Users;


--Drop all types if they already exist--
DROP TYPE IF EXISTS typeOfDegree;


--Create any necessary enumerations (types)--
CREATE TYPE typeOfDegree as ENUM ('BA', 'BS');


--Create Tables--

CREATE TABLE Users(
    userId         SERIAL    NOT NULL,
    userFirstName  TEXT      NOT NULL,
    userLastName   TEXT      NOT NULL,
    email          TEXT      NOT NULL,
    password       TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY    (userId)
);

CREATE TABLE Addresses(
    addressId      SERIAL    NOT NULL,
    addressStreet  TEXT      NOT NULL,
    addressCity    TEXT      NOT NULL,
    addressZip     TEXT      NOT NULL,
    addressState   TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(addressId)
);
CREATE TABLE Institutions(
    institutionId         SERIAL     NOT NULL,
    institutionAddress    INTEGER    NOT NULL    REFERENCES Addresses (addressId),
    institutionName       TEXT       NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(institutionId)
);

CREATE TABLE Students(
    studentId               INTEGER    NOT NULL    REFERENCES Users (userId),
    studentInstitutionId    INTEGER    NOT NULL    REFERENCES Institutions (institutionId),
    homeAddressId           INTEGER    REFERENCES Addresses (addressId),
    studentPhone            TEXT,
    dateOfBirth             DATE       NOT NULL,
    createdOn               TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated             TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(studentId)
);

CREATE TABLE Admins(
    adminId        INTEGER                        NOT NULL    REFERENCES Users (userId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(adminId)
);


CREATE TABLE TransferReports(
    transferReportId    SERIAL    NOT NULL,
    studentId           INTEGER   NOT NULL    REFERENCES Students (studentId),
    reportName          TEXT      NOT NULL,
    createdOn           TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated         TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(transferReportId)
);

CREATE TABLE MaristMajors(
    majorId        SERIAL          NOT NULL,
    majorName      TEXT            NOT NULL,
    school         TEXT            NOT NULL,
    BAorBS         typeOfDegree    NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(majorId)
);

CREATE TABLE MajorConcentrations(
    concentrationId      SERIAL    NOT NULL,
    majorId              INTEGER   NOT NULL    REFERENCES MaristMajors (majorId),
    concentrationName    TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(concentrationId)
);

CREATE TABLE Requirements(
    requirementId         SERIAL     NOT NULL,
    requirementName       TEXT       NOT NULL,
    numCreditsRequired    INTEGER    NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(requirementId)
);

CREATE TABLE ConcentrationRequirements(
    concentrationRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    concentrationId               INTEGER    NOT NULL    REFERENCES MajorConcentrations (concentrationId),
    createdOn       TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated     TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(concentrationRequirementId)
);
CREATE TABLE MaristMinors(
    minorId    SERIAL    NOT NULL,
    minorName  TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorId)
);

CREATE TABLE MinorRequirements(
    minorRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    minorID               INTEGER    NOT NULL    REFERENCES MaristMinors (minorId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorRequirementId)
);

CREATE TABLE CoreRequirements(
    coreRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(coreRequirementId)
);

CREATE TABLE ConcentrationSelections(
    concentrationId            INTEGER    NOT NULL    REFERENCES MajorConcentrations (concentrationId),
    transferReportId   INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(concentrationId, transferReportId)
);

CREATE TABLE MinorSelections(
    minorID             INTEGER    NOT NULL    REFERENCES MaristMinors (minorId),
    transferReportId    INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorId, transferReportId)
);

CREATE TABLE InstitutionCourses(
    courseId         SERIAL     NOT NULL,
    institutionId    INTEGER    NOT NULL    REFERENCES Institutions (institutionId),
    courseTerm       TEXT       NOT NULL,
    courseTitle      TEXT       NOT NULL,
    courseNum        TEXT       NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(courseId)
);

CREATE TABLE MaristCourses(
    maristCourseId         SERIAL     NOT NULL,
    maristCourseTitle      TEXT       NOT NULL,
    maristCourseNum        TEXT       NOT NULL,
    maristCourseSubject    TEXT       NOT NULL,
    numCredits             INTEGER    DEFAULT 3,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(maristCourseId)
);

CREATE TABLE TransfersTo(
    courseId          INTEGER    NOT NULL    REFERENCES InstitutionCourses (courseId),
    maristCourseId    INTEGER    NOT NULL    REFERENCES MaristCourses (maristCourseId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(courseId, maristCourseId)
);

CREATE TABLE CoursesTaken(
    transferReportId    INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    courseId            INTEGER    NOT NULL    REFERENCES InstitutionCourses (courseId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(transferReportId, courseId)
);

CREATE TABLE CountsToward(
    maristCourseId    INTEGER    NOT NULL    REFERENCES MaristCourses (maristCourseId),
    requirementId     INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(maristCourseId, requirementId)
);


-- Insert test data --

INSERT INTO Users (userFirstName, userLastName, email, password)
VALUES ('Steve', 'Harvey', 'steve.harvey@familyfeud.com', 'nakedGrandma1'),
       ('Jerry', 'Springer', 'jerry.springer@baggage.com', 'imAHorriblePerson');

INSERT INTO Addresses (addressStreet, addressCity, addressZip, addressState)
VALUES ('24 Main Street', 'Orlando', '32801', 'Florida'),
       ('53 Pendell Road', 'Poughkeepsie', '12601', 'New York');

INSERT INTO Institutions (institutionAddress, institutionName)
VALUES (2, 'Dutchess Community College');

INSERT INTO Students (studentId, studentInstitutionId, homeAddressId, studentPhone, dateOfBirth)
VALUES (1, 1, 1, '5555550147', '12 Jul 1996');

INSERT INTO Admins (adminId)
VALUES (2);

INSERT INTO TransferReports (studentId, reportName)
VALUES (1, 'myFirstReport');

INSERT INTO MaristMajors (majorName, school, BAorBS)
VALUES ('Computer Science', 'School of Computer Science and Mathematics', 'BS');

INSERT INTO MajorConcentrations (majorId, concentrationName)
VALUES (1, 'Software Development');

INSERT INTO Requirements (requirementName, numCreditsRequired)
VALUES ('Philosophy', 3), --Core
       ('Ethics', 3), --Core
       ('College Writing', 6), --Core
       ('Literature', 6), --Core
       ('Fine Arts', 3), --Core
       ('Social Science', 6), --Core
       ('Philosophy and Religious Studies', 3), --Core
       ('History', 6), --Core
       ('Natural Science', 6), --Core
       ('Cultural Diversity', 3), --Core
       ('Introduction to Programming', 4), --CompSci(SoftDev)
       ('Software Development 1', 4), --CompSci(SoftDev)
       ('Software Development 2', 4), --CompSci(SoftDev)
       ('Software Systems and Analysis', 4), --CompSci(SoftDev)
       ('Data Communications and Networks', 4), --CompSci(SoftDev)
       ('Database Management', 4), --CompSci(SoftDev)
       ('Internetworking', 4), --CompSci(SoftDev)
       ('System Design', 4), --CompSci(SoftDev)
       ('Computer Organization and Architecture', 4), --CompSci(SoftDev)
       ('Algorithm Analysis and Design', 4), --CompSci(SoftDev)
       ('System Elective Option', 4), --CompSci(SoftDev)
       ('Language Elective Option', 3), --CompSci(SoftDev)
       ('Third Required Elective', 3), --CompSci(SoftDev)
       ('Project 1', 3), --CompSci(SoftDev)
       ('Project 2', 1), --CompSci(SoftDev)
       ('Introduction to Business and Management', 3), --CompSci(SoftDev)
       ('Introduction to Statistics', 3), --CompSci(SoftDev)
       ('Calculus 1', 4), --CompSci(SoftDev)
       ('Discrete Mathematics', 4), --CompSci(SoftDev)
       ('Calculus 1', 4), --Math Minor
       ('Calculus 2', 4), --Math Minor
       ('Calculus 3', 4), --Math Minor
       ('Introduction to Mathematics Reasoning', 3), --Math Minor
       ('Linear Algebra or Discrete Mathematics', 3), --Math Minor
       ('Mathematics', 6); --Core

INSERT INTO ConcentrationRequirements (concentrationRequirementId, concentrationId)
VALUES (11, 1),
       (12, 1),
       (13, 1),
       (14, 1),
       (15, 1),
       (16, 1),
       (17, 1),
       (18, 1),
       (19, 1),
       (20, 1),
       (21, 1),
       (22, 1),
       (23, 1),
       (24, 1),
       (25, 1),
       (26, 1),
       (27, 1),
       (28, 1),
       (29, 1);

INSERT INTO MaristMinors (minorName)
VALUES ('Mathematics');

INSERT INTO MinorRequirements (minorRequirementId, minorID)
VALUES (30, 1),
       (31, 1),
       (32, 1),
       (33, 1),
       (34, 1);

INSERT INTO CoreRequirements (coreRequirementId)
VALUES (1),
      (2),
      (3),
      (4),
      (5),
      (6),
      (7),
      (8),
      (9),
      (10),
      (35);

INSERT INTO ConcentrationSelections (concentrationId, transferReportId)
VALUES (1, 1);

INSERT INTO MinorSelections (minorID, transferReportId)
VALUES (1, 1);

INSERT INTO InstitutionCourses (institutionId, courseTerm, courseTitle, courseNum)
VALUES (1, 'Fall 2014', 'Composition I', 'ENG 101'), --> College Writing I
       (1, 'Fall 2014', 'Social Problems in Today''s World', 'BHS 103'), --> Social Problems
       (1, 'Fall 2014', 'Precalculus Mathematics', 'MAT 185'), --> Pre-Calculus
       (1, 'Fall 2014', 'Analytical Geometry and Calculus I', 'MAT 221'), --> Calculus with Management Applications (Calculus I?)
       (1, 'Fall 2014', 'Introduction to Computer Science and Programming', 'CPS 141'),
       (1, 'Spring 2015', 'Composition II', 'ENG 102'), --> Writing for College
       (1, 'Spring 2015', 'Analytical Geometry and Calculus II', 'MAT 222'), --> Calculus II
       (1, 'Spring 2015', 'Advanced Programming Techniques', 'CPS 142'),
       (1, 'Spring 2015', 'Lifetime Wellness and Fitness', 'WFE 101'), --> Physical Education Elective
       (1, 'Fall 2015', 'Data Structures', 'CPS 231'), --> Software Development I
       (1, 'Fall 2015', 'Discrete Mathematics', 'CPS 214'), --> Discrete Mathematics I
       (1, 'Fall 2015', 'Linear Algebra', 'MAT 215'), --> Linear Algebra
       (1, 'Fall 2015', 'Assembler Language Programming', 'CIS 227'); --> Architecture of Hardware and System Software


INSERT INTO MaristCourses (maristCourseTitle, maristCourseNum, maristCourseSubject, numCredits)
VALUES ('College Writing 1', '116L', 'ENG', 3),
       ('Social Problems', '202L', 'SOC', 3),
       ('Pre-Calculus', '120L', 'MATH', 3),
       ('Calculus with Management Applications', '115L', 'MATH', 3),
       ('Writing for College', '120L', 'ENG', 3),
       ('Calculus 1', '241L', 'MATH', 4),
       ('Calculus 2', '242L', 'MATH', 4),
       ('Physical Education Elective', '801N', 'PHED', 3),
       ('Software Development 1', '202L', 'CMPT', 4),
       ('Discrete Mathematics 1', '205L', 'MATH', 3),
       ('Linear Algebra', '210L', 'MATH', 3),
       ('Architecture of Hardware and System Software', '321L', 'ITS', 3),
       ('Computer Science Course', '901L', 'CMSC', 3),
       ('Computer Science Course', '902L', 'CMSC', 3);

INSERT INTO TransfersTo (courseId, maristCourseId)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (6, 5),
       (7, 7),
       (9, 8),
       (10, 9),
       (11, 10),
       (12, 11),
       (4, 6);

INSERT INTO CoursesTaken (transferReportId, courseId)
VALUES (1, 1),
       (1, 2),
       (1, 3),
       (1, 4),
       (1, 5),
       (1, 6),
       (1, 7),
       (1, 8),
       (1, 9),
       (1, 10),
       (1, 13);

INSERT INTO CountsToward (maristCourseId, requirementId)
VALUES (1, 3),
       (2, 6),
       (3, 35),
       (4, 35),
       (5, 3),
       (6, 35),
       (6, 28),
       (6, 30),
       (7, 35),
       (7, 31),
       (9, 12),
       (10, 35),
       (10, 34),
       (10, 29),
       (11, 35),
       (11, 34);


--Create Views


CREATE VIEW CoursesToRequirements
AS
SELECT MaristCourses.maristCourseTitle courseName,
       MaristCourses.maristCourseSubject sub,
       MaristCourses.maristCourseNum courseNum,
       MaristCourses.numCredits courseCredits,
       Requirements.requirementName reqName,
       Requirements.numCreditsRequired creditsNeeded
FROM
MaristCourses
INNER JOIN CountsToward
ON
MaristCourses.maristCourseId = CountsToward.maristCourseId
INNER JOIN Requirements
ON CountsToward.requirementId = Requirements.requirementId
ORDER BY Requirements.requirementName DESC;


--Create Stored Procedures


CREATE OR REPLACE FUNCTION getReportCredits(ReportId int)
RETURNS TABLE("User First Name" TEXT, "User Last Name" TEXT, "Report Name" TEXT, "Credits Transfered" BIGINT)
AS
$$
BEGIN
RETURN QUERY
SELECT Users.userFirstName AS "User First Name", Users.userLastName AS "User Last Name", TransferReports.reportName AS "Report Name", SUM(MaristCourses.numCredits) AS "Credits Transfered"
FROM Users
INNER JOIN Students
ON Users.userId = Students.studentId
INNER JOIN TransferReports
ON Students.studentId = TransferReports.studentId
INNER JOIN CoursesTaken
ON TransferReports.transferReportId = CoursesTaken.transferReportId
INNER JOIN InstitutionCourses
ON InstitutionCourses.courseId = CoursesTaken.courseId
INNER JOIN TransfersTo
ON InstitutionCourses.courseId = TransfersTo.courseId
INNER JOIN MaristCourses
ON MaristCourses.maristCourseId = TransfersTo.maristCourseId
WHERE ReportId = TransferReports.transferReportId
GROUP BY Users.userFirstName, Users.userLastName, TransferReports.reportName;
END;
$$
LANGUAGE plpgsql;





