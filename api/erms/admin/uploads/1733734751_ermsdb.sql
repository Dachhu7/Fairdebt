-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2023 at 09:08 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ermsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `empeducation`
--

CREATE TABLE `empeducation` (
  `Id` int(11) NOT NULL,
  `EmpID` int(10) DEFAULT NULL,
  `CoursePG` varchar(45) DEFAULT NULL,
  `SchoolCollegePG` varchar(45) DEFAULT NULL,
  `YearPassingPG` varchar(45) DEFAULT NULL,
  `PercentagePG` varchar(4) DEFAULT NULL,
  `CourseGra` varchar(45) DEFAULT NULL,
  `SchoolCollegeGra` varchar(45) DEFAULT NULL,
  `YearPassingGra` varchar(45) DEFAULT NULL,
  `PercentageGra` varchar(4) DEFAULT NULL,
  `CourseSSC` varchar(45) DEFAULT NULL,
  `SchoolCollegeSSC` varchar(45) DEFAULT NULL,
  `YearPassingSSC` varchar(45) DEFAULT NULL,
  `PercentageSSC` varchar(4) DEFAULT NULL,
  `CourseHSC` varchar(45) DEFAULT NULL,
  `SchoolCollegeHSC` varchar(45) DEFAULT NULL,
  `YearPassingHSC` varchar(45) DEFAULT NULL,
  `PercentageHSC` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `empeducation`
--

INSERT INTO `empeducation` (`Id`, `EmpID`, `CoursePG`, `SchoolCollegePG`, `YearPassingPG`, `PercentagePG`, `CourseGra`, `SchoolCollegeGra`, `YearPassingGra`, `PercentageGra`, `CourseSSC`, `SchoolCollegeSSC`, `YearPassingSSC`, `PercentageSSC`, `CourseHSC`, `SchoolCollegeHSC`, `YearPassingHSC`, `PercentageHSC`) VALUES
(1, 4, 'NA', 'NA', 'NA', 'NA', 'B.Tech(IT)', 'LPU', '2014', '86%', 'Science', 'ABC Senoir secondary School', '2010', '64%', 'Science', 'abcd', '2008', '98%'),
(2, 2, 'abc', 'ghf', '2016', '89%', 'B.Tech(IT)', 'LPU', '2013', '86%', 'Science', 'DPS Senoir secondary School', '2009', '64%', 'Science', 'DPS Senoir secondary School', '2008', '90%'),
(3, 3, 'Master in charted accountant', 'Bhavi CA college', '2004', '89%', 'Bachelor in charted accountant', 'Bhavi CA college', '1996', '95%', 'Science', 'graimia convent school', '1993', '75%', 'Science', 'graimia convent school', '1991', '89%'),
(4, 7, 'MCA', 'KITE Ghaziabad', '1990', '64 %', 'BCA', 'TVN', '1997', '68 %', 'Science', 'TVN', '1992', '76 %', 'Science', 'TVN', '2010', '89 %'),
(5, 12, 'NA', 'NA', 'NA', 'NA', 'B.Tech', 'VIT', '1996', '75%', 'Science', 'GHI convent school', '1993', '66%', 'Science', 'GHI convent school', '1990', '65%'),
(6, 13, 'MBA', 'SMU', '2018', '70', 'B.Tech', 'LPU', '2015', '80', 'PCM', 'Test', '2010', '74', 'PCM', 'ABC', '2008', '85'),
(7, 1, 'NA', 'NA', 'NA', 'NA', 'B.Tech', 'ABC', '2012', '75', 'PCM', 'XYZ', '2008', '67', '10th', 'HGHH', '2006', '89'),
(8, 14, 'M.Tech', 'ABC College', '2014', '65', 'B.Tech', 'XYZ', '2012', '70', 'PCM', 'ABC', '2008', '56', 'High School', 'XYZ', '2006', '85');

-- --------------------------------------------------------

--
-- Table structure for table `empexpireince`
--

CREATE TABLE `empexpireince` (
  `ID` int(11) NOT NULL,
  `EmpID` varchar(5) DEFAULT NULL,
  `Employer1Name` varchar(75) DEFAULT NULL,
  `Employer1Designation` varchar(50) DEFAULT NULL,
  `Employer1CTC` varchar(50) DEFAULT NULL,
  `Employer1WorkDuration` varchar(11) DEFAULT NULL,
  `Employer2Name` varchar(75) DEFAULT NULL,
  `Employer2Designation` varchar(50) DEFAULT NULL,
  `Employer2CTC` varchar(50) DEFAULT NULL,
  `Employer2WorkDuration` varchar(11) DEFAULT NULL,
  `Employer3Name` varchar(75) DEFAULT NULL,
  `Employer3Designation` varchar(50) DEFAULT NULL,
  `Employer3CTC` varchar(50) DEFAULT NULL,
  `Employer3WorkDuration` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `empexpireince`
--

INSERT INTO `empexpireince` (`ID`, `EmpID`, `Employer1Name`, `Employer1Designation`, `Employer1CTC`, `Employer1WorkDuration`, `Employer2Name`, `Employer2Designation`, `Employer2CTC`, `Employer2WorkDuration`, `Employer3Name`, `Employer3Designation`, `Employer3CTC`, `Employer3WorkDuration`) VALUES
(2, '4', 'abc.pvt.td', 'software tester', '20,000 p/m', '4 yrs', 'tch.pvt.td', 'software tester', '32000 p/m', '4 yrs', 'dfg.pvt.td', 'SR.software tester', '45000 p/m', '7 yrs'),
(7, '2', 'SAR pvt.ltd', 'Software Developer', '25000 p/m', '3 yrs', 'abc enterprise', 'software developer', '30000 p/m', '3 yrs', 'dgfhgfg.pt.ltd', 'software developer', '35000 p/m', '2 yrs till '),
(8, '3', 'GHA pvt.ltd', 'accountant', '25000', '5 yrs', 'HRCH pvt.ltd', 'accountant', '75000', '5 yrs', 'TCGHB pvt ltd', 'Sr.Accountant', '95000 ', '8 yrs till'),
(9, '7', 'FAG pvt.ltd', 'HR Executive', '25000 p/m', '6 yrs', 'TYS', 'HR Executive', '35000 p/m', '7 yrs', 'hirp pvt.ltd', 'HR Executive', '45000 p/m', '4 yrs till'),
(10, '12', 'dfg.pvt.ltd', 'accountant', '25000 p/m', '1 yrs', 'fghpvt.ltd', 'accountant', '30000 p/m', '3 yrs', 'fghpvt.ltd', 'accountant', '45000 p/m', '5 yrs till'),
(11, '13', 'ABC', 'Developer', '12000 ', '2 years', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA'),
(12, '1', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, '14', 'ABC Tech', 'Jr Devloper', '1258000', '6 Month', 'XYZ Tech', 'Devloper', '2589300', '6 Month', 'It Tech', 'Sr Devloper', '853214447', '2 + Years');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminName` varchar(50) DEFAULT NULL,
  `AdminuserName` varchar(50) DEFAULT NULL,
  `Password` varchar(45) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  `Phone` varchar(15) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Birthday` date NOT NULL,
  `Address` text,
  `Gender` enum('Male', 'Female', 'Other') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci; 



--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`AdminName`, `AdminuserName`, `Password`, `role`, `AdminRegdate`) VALUES
('Admin', 'Admin', 'Test@123', 'Super Admin', '2023-02-25 16:52:45'),
('HR', 'HR', '1234', 'HR', '2023-02-25 16:52:45');



-- Table structure for table `employeedetail`
CREATE TABLE `employeedetail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EmpFname` varchar(50) DEFAULT NULL,
  `EmpLName` varchar(50) DEFAULT NULL,
  `EmpCode` varchar(50) DEFAULT NULL,
  `EmpDept` varchar(120) DEFAULT NULL,
  `EmpDesignation` varchar(120) DEFAULT NULL,
  `EmpContactNo` bigint(10) DEFAULT NULL,
  `EmpGender` enum('Male', 'Female') DEFAULT NULL,
  `EmpEmail` varchar(200) DEFAULT NULL,
  `EmpPassword` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `EmpJoingdate` date DEFAULT NULL,
  `EmpBirthday` date DEFAULT NULL, -- New column
  `EmpAddress` varchar(255) DEFAULT NULL, -- New column
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ManagerID` int(11) DEFAULT NULL, -- Added ManagerID column
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_manager` FOREIGN KEY (`ManagerID`) REFERENCES `tbladmin` (`ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



--
-- Dumping data for table `employeedetail`
--
INSERT INTO `employeedetail` (`EmpFname`, `EmpLName`, `EmpCode`, `EmpDept`, `EmpDesignation`, `EmpContactNo`, `EmpGender`, `EmpEmail`, `EmpPassword`, `role`, `EmpJoingdate`, `EmpBirthday`, `EmpAddress`)
VALUES
('Amit', 'Sharma', 'EMP00001', 'Sales', 'Agent', '9876543210', 'Male', 'amit.sharma@example.com', '1234', 'Agent', '2023-01-10', '1990-05-15', '1234, Street A, Mumbai, India'),
('Priya', 'Patel', 'EMP00002', 'Sales', 'Agent', '9876543211', 'Female', 'priya.patel@example.com', '1234', 'Agent', '2023-02-20', '1992-07-25', '5678, Street B, Delhi, India'),
('Rahul', 'Verma', 'EMP00003', 'Collection', 'Collection Executive', '9876543212', 'Male', 'rahul.verma@example.com', '1234', 'Collection Executive', '2023-03-15', '1991-03-30', '9101, Street C, Bangalore, India'),
('Neha', 'Singh', 'EMP00004', 'Collection', 'Collection Executive', '9876543213', 'Female', 'neha.singh@example.com', '1234', 'Collection Executive', '2023-04-10', '1993-12-05', '1122, Street D, Kolkata, India'),
('Karan', 'Jain', 'EMP00005', 'Sales', 'Agent', '9876543214', 'Male', 'karan.jain@example.com', '1234', 'Agent', '2023-05-25', '1988-09-10', '3344, Street E, Chennai, India'),
('Sonia', 'Reddy', 'EMP00006', 'Collection', 'Collection Executive', '9876543215', 'Female', 'sonia.reddy@example.com', '1234', 'Collection Executive', '2023-06-18', '1990-11-22', '5566, Street F, Hyderabad, India'),
('Vikram', 'Shukla', 'EMP00007', 'Sales', 'Agent', '9876543216', 'Male', 'vikram.shukla@example.com', '1234', 'Agent', '2023-07-02', '1994-01-12', '7788, Street G, Pune, India');

-- --------------------------------------------------------


--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique ID for each client
    FirstName VARCHAR(255) NOT NULL,         -- Client's first name
    LastName VARCHAR(255) NOT NULL,          -- Client's last name
    UserName VARCHAR(255),                   -- Client's username
    Email VARCHAR(255) NOT NULL UNIQUE,      -- Client's email (must be unique)
    Password VARCHAR(255),                   -- Client's password (could be hashed)
    Phone VARCHAR(15),                       -- Client's phone number
    Company VARCHAR(255),                    -- Client's company
    Address TEXT,                            -- Client's address
    Status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active', -- Client status (Active or Inactive)
    Picture VARCHAR(255),                    -- Optional picture file name or path
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of record creation
);

INSERT INTO clients (FirstName, LastName, UserName, Email, Password, Phone, Company, Address, Status)
VALUES 
    ('John', 'Doe', 'john_doe', 'john@example.com', 'password123', '555-1234', 'Example Corp', '123 Main St, City, Country', 'Active'),
    ('Jane', 'Smith', 'jane_smith', 'jane@example.com', 'password456', '555-5678', 'Tech Solutions', '456 Oak St, City, Country', 'Inactive');


--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `Department` varchar(200) NOT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `Department`, `Date`) VALUES
(1, 'IT', '2022-11-14');

-- --------------------------------------------------------
--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `Designation` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `Designation`, `Department`, `Date`) VALUES
(1, 'Web Designer', 'Web Development', '2020-09-27'),
(2, 'Web Developer', 'Web Development', '2020-09-27');

-- --------------------------------------------------------
--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `Holiday_Name` varchar(200) NOT NULL,
  `Holiday_Date` date NOT NULL,
  `DateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `Holiday_Name`, `Holiday_Date`, `DateTime`) VALUES
(1, 'Christmas', '2020-12-25', '2020-09-26 19:15:02');

-- --------------------------------------------------------

CREATE TABLE leaves (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Employee VARCHAR(255) NOT NULL,
    LeaveType VARCHAR(255) NOT NULL,  -- Leave type (e.g., Sick Leave, Casual Leave)
    Starting_At DATE NOT NULL,
    Ending_On DATE NOT NULL,
    Days INT NOT NULL,  -- Number of leave days
    Reason TEXT NOT NULL,
    Status VARCHAR(50) NOT NULL,  -- Status of the leave request (e.g., Pending, Approved, Rejected)
    Notified INT DEFAULT 0,  -- A flag indicating whether the employee has been notified
    EmpID INT NOT NULL,  -- Employee ID to link the leave to the employee
    FOREIGN KEY (EmpID) REFERENCES employeedetail(ID)  -- Foreign key reference to the employeedetail table
);


CREATE TABLE `overtime` (
  `id` int(11) NOT NULL,
  `Employee` varchar(200) NOT NULL,
  `OverTime_Date` date NOT NULL,
  `Hours` varchar(20) NOT NULL,
  `Type` varchar(200) NOT NULL,
  `Description` text NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `overtime`
INSERT INTO `overtime` (`id`, `Employee`, `OverTime_Date`, `Hours`, `Type`, `Description`, `dateTime`) VALUES
(1, 'Mushe Abdul-Hakim', '2020-09-29', '5', 'Normal ex.5', 'This extra minutes are spent on trying to improve my knowledge on programming everyday.', '2020-09-29 00:38:26');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `empeducation`
--
ALTER TABLE `empeducation`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `empexpireince`
--
ALTER TABLE `empexpireince`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `employeedetail`
--
ALTER TABLE `employeedetail`
  ADD UNIQUE KEY `EmpCode` (`EmpCode`);


--
-- AUTO_INCREMENT for table `empeducation`
--
ALTER TABLE `empeducation`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `empexpireince`
--
ALTER TABLE `empexpireince`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employeedetail`
--
ALTER TABLE `employeedetail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

-- Indexes for table `clients`
ALTER TABLE `clients`
  ADD UNIQUE KEY `UserName_UNIQUE` (`UserName`),
  ADD UNIQUE KEY `Email_UNIQUE` (`Email`);

-- Indexes for table `departments`
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Department_UNIQUE` (`Department`);

-- Indexes for table `designations`
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Department_INDEX` (`Department`);

-- Indexes for table `holidays`
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Holiday_Name_UNIQUE` (`Holiday_Name`);

-- Indexes for table `leaves`
ALTER TABLE `leaves`
  ADD KEY `Employee_INDEX` (`Employee`);

-- Indexes for table `overtime`
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Employee_INDEX` (`Employee`);

-- Altering all tables to add AUTO_INCREMENT to primary keys
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Foreign key constraints (if applicable, adjust based on relationships)
-- Example: Assuming `designations.Department` references `departments.Department`
ALTER TABLE `designations`
  ADD CONSTRAINT `FK_Department_Designations` FOREIGN KEY (`Department`) REFERENCES `departments` (`Department`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
