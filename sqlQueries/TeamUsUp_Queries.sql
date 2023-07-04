
CREATE TABLE Account (
    AccountID int NOT NULL auto_increment,
    FirstName varchar(30) NOT NULL,
    LastName varchar(30) NOT NULL,
    Dob date NOT NULL,
    Password varchar(40) NOT NULL,
    Gender char(1) NOT NULL,
    Role varchar(10) NOT NULL,
    Email varchar(40) NOT NULL,
    Status varchar(15) NOT NULL Default 'Active',
    CONSTRAINT Account_pk PRIMARY KEY (AccountID),
    CONSTRAINT Gender CHECK (Gender IN ('M', 'F')),
    CONSTRAINT ValidDOB CHECK (DOB <= sysdate()),
    CONSTRAINT Role CHECK (Role IN ('Admin', 'Student')),
    CONSTRAINT ValidEmail CHECK (Email LIKE '%@%.murdoch.%'),
    CONSTRAINT AccountStatus CHECK (Status IN ('Active', 'Deactivated'))
);

CREATE TABLE Course (
    CourseID int NOT NULL auto_increment,
    Name varchar(100) NOT NULL,
    `Desc` varchar(500) NULL,
    Status varchar(10) NOT NULL Default 'Available',
    CONSTRAINT Course_pk PRIMARY KEY (CourseID),
    CONSTRAINT CourseStatus CHECK(Status IN ('Available', 'Removed', 'Hidden'))
);
CREATE TABLE Unit (
    UnitID char(6) NOT NULL,
    Name varchar(100) NOT NULL,
    `Desc` varchar(500) NULL,
    Credit int NOT NULL,
    Status varchar(10) NOT NULL Default 'Available',
    CONSTRAINT Unit_pk PRIMARY KEY (UnitID),
	CONSTRAINT UnitStatus CHECK(Status IN ('Available', 'Removed', 'Hidden')),
    CONSTRAINT UnitIDFormat CHECK(REGEXP_LIKE (UnitID,'[A-Z][A-Z][A-Z][0-9][0-9][0-9]'))
);

CREATE TABLE CourseUnit (
    CourseID int NOT NULL,
    UnitID varchar(10) NOT NULL,
    CONSTRAINT CourseUnit_pk PRIMARY KEY (CourseID,UnitID),
    CONSTRAINT CourseUnit_Course_fk FOREIGN KEY (CourseID) 
							REFERENCES Course(CourseID) 
							ON DELETE CASCADE,
	CONSTRAINT CourseUnit_Unit_fk FOREIGN KEY (UnitID) 
							REFERENCES Unit(UnitID) 
							ON DELETE CASCADE
);

CREATE TABLE Offering (
    OffID int NOT NULL auto_increment,
    Semester char(5) NOT NULL,
    UnitID varchar(10) NOT NULL,
    Class char(1) NOT NULL,
    CONSTRAINT Offering_pk PRIMARY KEY (OffID),
    CONSTRAINT Offering_Unit_FK FOREIGN KEY(UnitID)
							REFERENCES Unit(UnitID) 
							ON DELETE CASCADE,
	CONSTRAINT ClassValue CHECK(REGEXP_LIKE (Class, '[A-Z]')),
    CONSTRAINT SemesterValue CHECK((Left(Semester,3) IN ('TJA', 'TMA','TSA')) and REGEXP_LIKE (right(Semester,2), '[0-9][0-9]'))
);

CREATE TABLE Student (
    StudNo int NOT NULL,
    AccountID int NOT NULL,
    CourseID int NOT NULL,
    EnrolDate date NOT NULL,
    Biography varchar(1000) NULL,
    OverallRating double(3,2) NOT NULL Default 0.00,
    Type char(2) NOT NULL,
    CONSTRAINT Student_pk PRIMARY KEY (StudNo),
    CONSTRAINT Student_Account_fk FOREIGN KEY(AccountID)
								REFERENCES Account(AccountID)
                                ON DELETE CASCADE,
	CONSTRAINT Student_Course_fk FOREIGN KEY(CourseID)
								REFERENCES Course(CourseID)
                                ON DELETE CASCADE,
	CONSTRAINT ValidEnrolDate CHECK (EnrolDate <= sysdate()),
    CONSTRAINT TypeValue CHECK (Type IN('FT', 'PT'))
);

CREATE TABLE Enrolment (
    StudNo int NOT NULL,
    OffID int NOT NULL,
    Status varchar(15) NOT NULL,
    Grade varchar(5) NOT NULL,
    CONSTRAINT Enrolment_pk PRIMARY KEY (StudNo,OffID),
    CONSTRAINT Enrolment_Stud_FK FOREIGN KEY(StudNo)
							REFERENCES Student(StudNo) 
							ON DELETE CASCADE,
	CONSTRAINT Enrolment_Off_FK FOREIGN KEY(OffID)
							REFERENCES Offering(OffID) 
							ON DELETE CASCADE,
	CONSTRAINT EnrolmentStatus CHECK(Status IN ('Completed', 'DISCONTIN', 'DUPLICATE', 'ENROLLED', 'INVALID', 'UNCONFIRMED')),
    CONSTRAINT EnrolmentGrade CHECK(Grade IN('HD', 'ASD', 'D', 'ASC', 'C', 'ASP', 'P', 'DNS', 'AS', 'N', 'Q', 'SA', 'SX', 'NA', 'W0', 'WD', 'NS', 'G'))
);

CREATE TABLE Assignment (
    AssID int NOT NULL auto_increment,
    OffID int NOT NULL,
    Name varchar(100) NOT NULL,
    `Desc` varchar(500) NULL,
    CONSTRAINT Assignment_pk PRIMARY KEY (AssID),
    CONSTRAINT Assignment_Off_FK FOREIGN KEY (OffID)
							REFERENCES Offering(OffID) 
							ON DELETE CASCADE
);

CREATE TABLE `Group` (
    GroupID int NOT NULL auto_increment,
    AssID int NOT NULL,
    Name varchar(30) NOT NULL,
    CONSTRAINT Group_pk PRIMARY KEY (GroupID),
    CONSTRAINT Group_Ass_FK FOREIGN KEY (AssID)
						REFERENCES Assignment(AssID)
                        ON DELETE CASCADE
);

CREATE TABLE GroupMember (
    GroupID int NOT NULL,
    StudNo int NOT NULL,
    Role varchar(20) NOT NULL,
    RoleDesc varchar(500) NULL,
    CONSTRAINT GroupMember_pk PRIMARY KEY (GroupID,StudNo),
    CONSTRAINT GroupMember_Group_FK FOREIGN KEY (GroupID)
						REFERENCES `Group`(GroupID)
                        ON DELETE CASCADE,
    CONSTRAINT GroupMember_Stud_FK FOREIGN KEY (StudNo)
						REFERENCES Student(StudNo)
                        ON DELETE CASCADE                   
);

CREATE TABLE Review (
    RevID int NOT NULL auto_increment,
    ByStudNo int NOT NULL,
    ToStudNo int NOT NULL,
    Comment varchar(1000) NOT NULL,
    Rating double(3,2) NOT NULL,
    GroupID int NOT NULL,
    Display varchar(10) NOT NULL default 'Pending',
    CONSTRAINT Review_pk PRIMARY KEY (RevID),
    CONSTRAINT Review_ByStud_FK FOREIGN KEY (ByStudNo)
						REFERENCES Student(StudNo)
                        ON DELETE CASCADE,
    CONSTRAINT Review_ToStud_FK FOREIGN KEY (ToStudNo)
						REFERENCES Student(StudNo)
                        ON DELETE CASCADE,  
	CONSTRAINT Review_Group_FK FOREIGN KEY (GroupID)
						REFERENCES `Group`(GroupID)
                        ON DELETE CASCADE,
	CONSTRAINT ReviewDisplay CHECK (Display IN ('Accepted', 'Pending', 'Reported', 'Hidden'))
);

CREATE TABLE Notification (
    NotID int NOT NULL auto_increment,
    RequesterID int NOT NULL,
    RecipientID int NULL,
    Action varchar(20) NOT NULL,
    Message varchar(2000) NOT NULL,
    ApproverID int NULL,
    SubjectID int NULL,
    Status varchar(10) NOT NULL,
    CONSTRAINT Notification_pk PRIMARY KEY (NotID),
    CONSTRAINT Notificiation_RequesterID_FK FOREIGN KEY (RequesterID)
						REFERENCES Account(AccountID)
                        ON DELETE CASCADE,
	CONSTRAINT Notificiation_RecipientID_FK FOREIGN KEY (RecipientID)
						REFERENCES Account(AccountID)
                        ON DELETE CASCADE,
	CONSTRAINT Notificiation_ApproverID_FK FOREIGN KEY (ApproverID)
						REFERENCES Account(AccountID)
                        ON DELETE CASCADE,
	CONSTRAINT NotificationStatus CHECK (Status IN ('Approved','Pending', 'Rejected', 'NA'))
);

delimiter $$
CREATE TRIGGER StudentDOBTrigger
  BEFORE INSERT ON Student
  FOR EACH ROW
BEGIN
declare msg varchar(128);
  IF( new.EnrolDate < (select account.dob from account where account.accountid = new.accountid) )
  THEN
    set msg = concat('MyTriggerError: Trying to insert an enrolment date before date of birth');
        signal sqlstate '45000' set message_text = msg;
  END IF;
END$$ 
delimiter $$
CREATE TRIGGER ComputeRating
  AFTER INSERT ON Review
  FOR EACH ROW
BEGIN
declare totalRating_ double(100,2);
declare AverageRating_ double(3,2);
  Select Sum(Rating) into totalRating_ from review where ToStudNo = new.ToStudNo;
  Select totalRating_/(Select count(*) from review where toStudNo = new.toStudNo) into AverageRating_ ;
  Update student set overallRating = AverageRating_ where studNo = new.toStudNo;
END$$ 

delimiter $$
CREATE TRIGGER ComputeRatingOnDelete
  AFTER DELETE 
  ON Review FOR EACH ROW
BEGIN
declare totalRating_ double(100,2);
declare AverageRating_ double(3,2);
  Select Sum(Rating) into totalRating_ from review where ToStudNo = old.ToStudNo;
  Select totalRating_/(Select count(*) from review where toStudNo = old.toStudNo) into AverageRating_ ;
  Update student set overallRating = AverageRating_ where studNo = old.toStudNo;
END$$ 

insert into account values(1, 'Eren', 'Yeager', STR_TO_DATE('30-03-835', '%d-%m-%Y'), 'dc647eb65e6711e155375218212b3964', 'M', 'Student', '34356789@student.murdoch.edu.au', 'Active');
insert into account values(2, 'Mikasa', 'Ackerman', STR_TO_DATE('10-02-834', '%d-%m-%Y'), '2ac9cb7dc02b3c0083eb70898e549b63', 'F', 'Student', '34356790@student.murdoch.edu.au', 'Active');
insert into account values(3, 'Levi', 'Ackerman', STR_TO_DATE('25-12-820', '%d-%m-%Y'), '6f9dff5af05096ea9f23cc7bedd65683', 'M', 'Student', '34356791@student.murdoch.edu.au', 'Active');
insert into account values(4, 'jean', 'kirstein', STR_TO_DATE('07-04-835', '%d-%m-%Y'), '874fcc6e14275dde5a23319c9ce5f8e4', 'M', 'Student', '34356792@student.murdoch.edu.au', 'Active');
insert into account values(5, 'sasha', 'braus', STR_TO_DATE('26-07-834', '%d-%m-%Y'), '874fcc6e14275dde5a23319c9ce5f8e4', 'M', 'Student', '34356793@student.murdoch.edu.au', 'Active');

insert into account values(8, 'a', 'b', STR_TO_DATE('30-03-835', '%d-%m-%Y'), 'dc647eb65e6711e155375218212b3964', 'M', 'Admin', 'abc@admin.murdoch.edu.au', 'Active');

insert into Course values(1, 'Computer Science', 'To learn all about programming', 'Available');
insert into Course values(2, 'Animal Health', 'To learn all about health of animals', 'Available');
insert into Course values(3, 'Asian Studies', 'To study about asians', 'Hidden');
insert into Course values(4, 'Law', 'To Learn everything needed to be a lawyer', 'Available');
insert into Course values(5, 'Nursing', 'To nurture your passions to care for people to become a registered nurse', 'Available');

Insert into Unit Values('ICT302', 'IT Professional Practice Project', 'To simulate and teach students how to solve real world IT problems', 3, 'Available');
Insert into Unit Values('ICT285', 'Database', 'To learn how to create and do things in databases', 3, 'Available');
Insert into Unit Values('ART604', 'Advanced Research Methods', 'Learn about constructic thesis, referencing and bibliographis',3,'Available');
Insert into Unit Values('LLB260', 'Contract Law', 'Introduction to contract law',6,'Available');
Insert into Unit Values('ANS221', 'Animal Structure and Function', 'This unit integrates the anatomy and physiology of farm animals, in particular cattle, sheep, pigs and horses', 3, 'Available');
Insert into Unit Values('NUR236', 'Mental Health Nursing', 'To enhance knowledge of contemporary mental health nursing', 3, 'Available');
Insert into Unit Values('BRD209', 'Creativity and Innovation', 'For students to learn to think outside the box', 3, 'Available');

Insert into courseunit Values(1, 'ICT302');
Insert into courseunit Values(1, 'ICT285');
Insert into courseunit Values(2, 'ANS221');
Insert into courseunit Values(3, 'ART604');
Insert into courseunit Values(4, 'LLB260');
Insert into courseunit Values(5, 'NUR236');
Insert into courseunit Values(1, 'BRD209');
Insert into courseunit Values(4, 'BRD209');

Insert into Offering Values(1, 'TJA21', 'ICT302', 'A');
Insert into Offering Values(2, 'TMA20', 'ICT285', 'A');
Insert into Offering Values(3, 'TMA20', 'ICT285', 'B');
Insert into Offering Values(4, 'TJA20', 'ART604', 'A');
Insert into Offering Values(5, 'TSA20', 'LLB260', 'A');
Insert into Offering Values(6, 'TSA19', 'ANS221', 'A');
Insert into Offering Values(7, 'TSA19', 'ANS221', 'B');
Insert into Offering Values(8, 'TSA19', 'ANS221', 'C');
Insert into Offering Values(9, 'TSA19', 'NUR236', 'A');
Insert into Offering Values(10, 'TSA20', 'ICT285', 'A');
Insert into Offering Values(11, 'TSA20', 'BRD209', 'A');


Insert into Student Values(34356789, 1, 1, STR_TO_DATE('1-01-2020', '%d-%m-%Y'),'Some stuff about myself', 0,'FT');
Insert into Student Values(34356790, 2, 1, STR_TO_DATE('1-01-2020', '%d-%m-%Y'),'-', 0,'FT');
Insert into Student Values(34356791, 3, 5, STR_TO_DATE('3-05-2019', '%d-%m-%Y'),'Not writing anything', 0,'FT');
Insert into Student Values(34356792, 4, 4, STR_TO_DATE('30-09-2019', '%d-%m-%Y'),'Too lazy to think of anything', 0,'FT');
Insert into Student Values(34356793, 5, 2, STR_TO_DATE('1-01-2020', '%d-%m-%Y'),'MMMM potato', 0,'PT');

Insert into Enrolment Values(34356789, 1, 'Enrolled','NA');
Insert into Enrolment Values(34356789, 2, 'Completed', 'D');
Insert into Enrolment Values(34356790, 1, 'Enrolled','NA');
Insert into Enrolment Values(34356790, 2, 'Completed', 'HD');
Insert into Enrolment Values(34356791, 4, 'Completed', 'HD');
Insert into Enrolment Values(34356789, 11, 'Completed', 'D');
Insert into Enrolment Values(34356790, 11, 'Completed', 'HD');
Insert into Enrolment Values(34356792, 11, 'Completed', 'HD');
Insert into Enrolment Values(34356792, 5, 'Completed', 'C');
Insert into Enrolment Values(34356793, 7, 'Completed', 'P');

Insert into Assignment Values(1, 1, 'Professional Practice Project', 'The requirements and analysis document that the group has formed for the project');
Insert into Assignment Values(2, 11, 'Individual Assignment 1', 'For students to work individually to submit a report of their innovation');
Insert into Assignment Values(3, 11, 'Group Assignment 1', 'Students are to group up and present their solution to a problem, and would be marked by creativity');
Insert into Assignment Values(4, 6, 'Assignment 1', 'To gauge the understanding of students regarding the structure of animals');

Insert into `Group` Values(1, 1, 'Group 1');
Insert into `Group` Values(2, 1, 'Group 2');
Insert into `Group` Values(3, 3, 'Group 1');
Insert into `Group` Values(4, 3, 'Group 2');
Insert into `Group` Values(5, 3, 'Group 3');
Insert into `Group` Values(6, 4, 'Group 1');

Insert into GroupMember Values(1, 34356789, 'Leader', 'Leader of the group');
Insert into GroupMember Values(1, 34356790, 'Member', 'A Member of the group');
Insert into GroupMember Values(3, 34356790, 'Leader', 'Leader of the group');
Insert into GroupMember Values(3, 34356789, 'Member', 'A Member of the group');
Insert into GroupMember Values(3, 34356792, 'Member', 'A Member of the group');

Insert into Review Values(1, 34356789, 34356790, 'Fantastic teammate to work with', 5, 1, 'Accepted'); 
Insert into Review Values(2, 34356790, 34356789, 'Best teammate ever', 5, 1, 'Accepted');
Insert into Review Values(3, 34356790, 34356789, 'Still the best teammate to work with', 4, 3, 'Accepted');
Insert into Review Values(4, 34356789, 34356790, 'Great to have you as a teammate again, fantastic work!', 5, 3, 'Pending'); 
Insert into Review Values(5, 34356792, 34356789, 'Alright teammate', 5, 3, 'Accepted'); 
Insert into Review Values(6, 34356792, 34356790, 'Good teammate', 5, 3, 'Accepted'); 

Insert into Notification (NotID, RequesterID, RecipientID, Action, Message, SubjectID, Status) Values(1, 1, 2, 'Gave Review', 'A user has written a review to you, and is now pending', 1,'NA');
Insert into Notification (NotID, RequesterID, RecipientID, Action, Message, SubjectID, Status) Values(2, 2, 1, 'Gave Review', 'A user has written a review to you, and is now pending',2,'NA');
Insert into Notification (NotID, RequesterID, RecipientID, Action, Message, SubjectID, Status) Values(3, 3, 1, 'Gave Review', 'A user has written a review to you, and is now pending',3,'NA');
Insert into Notification (NotID, RequesterID, RecipientID, Action, Message, SubjectID, Status) Values(4, 3, 2, 'Gave Review', 'A user has written a review to you, and is now pending',4,'NA');
Insert into Notification (NotID, RequesterID, RecipientID, Action, Message, SubjectID, Status) Values(5, 3, 2, 'Edit Review', 'A user has editted one of the reviews they have given you',4,'NA');
