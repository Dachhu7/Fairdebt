CREATE TABLE leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Lead_Name VARCHAR(255) NOT NULL,
    Lead_Email VARCHAR(255) NOT NULL,
    Lead_Phone VARCHAR(15),
    Lead_Source VARCHAR(255),
    Lead_Status ENUM('New', 'Contacted', 'Converted', 'Not Interested') DEFAULT 'New',
    DateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example Data Insertion
INSERT INTO leads (Lead_Name, Lead_Email, Lead_Phone, Lead_Source, Lead_Status)
VALUES 
('John Doe', 'johndoe@example.com', '1234567890', 'Website', 'New'),
('Jane Smith', 'janesmith@example.com', '0987654321', 'Referral', 'Contacted'),
('Alice Johnson', 'alice.johnson@example.com', '1122334455', 'Advertisement', 'Converted'),
('Bob Brown', 'bob.brown@example.com', '2233445566', 'Cold Call', 'Not Interested');


CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Employee_Name VARCHAR(255),
    Month VARCHAR(50),
    Invoice_Date DATE,
    Vendor_Name VARCHAR(255),
    Invoice_Number VARCHAR(255),
    Taxable_Values DECIMAL(10, 2),
    CGST DECIMAL(10, 2),
    SGST DECIMAL(10, 2),
    Invoice_Amount DECIMAL(10, 2),
    TDS_Deduct DECIMAL(10, 2),
    Attachments VARCHAR(255),
    Account_Number VARCHAR(50),
    IFSC_Code VARCHAR(50),
    Payment_Status VARCHAR(50),
    Payment_Bank VARCHAR(50),
    Payment_Date DATE,
    Payment_Amount DECIMAL(10, 2),
    Payment_Method VARCHAR(50),
    Customer_Name VARCHAR(255),
    DateTime DATETIME
);

INSERT INTO payments (
    Employee_Name, Month, Invoice_Date, Vendor_Name, Invoice_Number, Taxable_Values,
    CGST, SGST, Invoice_Amount, TDS_Deduct, Attachments, Account_Number, IFSC_Code, 
    Payment_Status, Payment_Bank, Payment_Date, Payment_Amount, Payment_Method, Customer_Name, DateTime
) 
VALUES 
(
    'John Doe', 'January 2024', '2024-01-15', 'ABC Corp', 'INV12345', 10000.00, 
    500.00, 500.00, 11000.00, 1000.00, 'invoice123.pdf', '123456789012', 'IFSC12345', 
    'Pending', 'SBI', '2024-01-20', 11000.00, 'Bank Transfer', 'XYZ Pvt Ltd', '2024-01-20 10:30:00'
),
(
    'Jane Smith', 'February 2024', '2024-02-10', 'XYZ Ltd', 'INV12346', 15000.00, 
    750.00, 750.00, 16500.00, 1500.00, 'invoice124.pdf', '987654321098', 'IFSC98765', 
    'Approved', 'Fedrral', '2024-02-15', 16500.00, 'Cheque', 'ABC Inc', '2024-02-15 11:00:00'
),
(
    'Michael Johnson', 'March 2024', '2024-03-05', 'LMN Industries', 'INV12347', 20000.00, 
    1000.00, 1000.00, 22000.00, 2000.00, 'invoice125.pdf', '112233445566', 'IFSC11223', 
    'Pending', 'YES Bank', '2024-03-10', 22000.00, 'Bank Transfer', 'PQR Ltd', '2024-03-10 12:30:00'
),
(
    'Sarah Lee', 'April 2024', '2024-04-12', 'OPQ Enterprises', 'INV12348', 12000.00, 
    600.00, 600.00, 13200.00, 1200.00, 'invoice126.pdf', '556677889900', 'IFSC55667', 
    'Approved', 'HDFC Bank', '2024-04-18', 13200.00, 'Cash', 'LMN Co.', '2024-04-18 14:00:00'
);


CREATE TABLE `salary_payments` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `employee_name` VARCHAR(255) NOT NULL,
    `salary_amount` DECIMAL(10, 2) NOT NULL,
    `hra` DECIMAL(10, 2) NOT NULL,
    `conveyance` DECIMAL(10, 2) NOT NULL,
    `other_allowances` DECIMAL(10, 2) NOT NULL,
    `tds` DECIMAL(10, 2) NOT NULL,
    `provident_fund` DECIMAL(10, 2) NOT NULL,
    `esi` DECIMAL(10, 2) NOT NULL,
    `loan` DECIMAL(10, 2) NOT NULL,
    `payment_date` DATE NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `salary_status` ENUM('Pending', 'Approved') DEFAULT 'Pending',
    `notes` TEXT,
    `total_earnings` DECIMAL(10, 2) NOT NULL,
    `total_deductions` DECIMAL(10, 2) NOT NULL,
    `net_salary` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `salary_payments` (
    `employee_name`, `salary_amount`, `hra`, `conveyance`, `other_allowances`, `tds`, 
    `provident_fund`, `esi`, `loan`, `payment_date`, `payment_method`, `salary_status`, 
    `notes`, `total_earnings`, `total_deductions`, `net_salary`
) VALUES
    ('Ravi Kumar', 40000.00, 8000.00, 3000.00, 2000.00, 5000.00, 4800.00, 1000.00, 1500.00, '2024-11-30', 'UPI', 'Approved', 'Salary for November 2024', 53000.00, 11500.00, 41500.00),
    ('Anjali Sharma', 35000.00, 7000.00, 2500.00, 1800.00, 4500.00, 4200.00, 950.00, 1200.00, '2024-11-30', 'Credit Card', 'Pending', 'Salary for November 2024', 49500.00, 11550.00, 37950.00),
    ('Arun Singh', 50000.00, 10000.00, 3500.00, 2500.00, 7000.00, 6000.00, 1200.00, 1800.00, '2024-11-30', 'Net Banking', 'Approved', 'Salary for November 2024', 63500.00, 16000.00, 47500.00),
    ('Priya Patel', 45000.00, 9000.00, 4000.00, 2200.00, 6000.00, 5400.00, 1100.00, 1600.00, '2024-11-30', 'Cash', 'Approved', 'Salary for November 2024', 59500.00, 13800.00, 45700.00),
    ('Sandeep Yadav', 42000.00, 8500.00, 3200.00, 2100.00, 5500.00, 5100.00, 1050.00, 1400.00, '2024-11-30', 'Credit Card', 'Approved', 'Salary for November 2024', 57000.00, 14050.00, 42950.00),
    ('Neha Desai', 38000.00, 7600.00, 2800.00, 2100.00, 4600.00, 4800.00, 1000.00, 1300.00, '2024-11-30', 'UPI', 'Pending', 'Salary for November 2024', 50800.00, 11800.00, 39000.00),
    ('Manoj Verma', 47000.00, 9400.00, 3100.00, 2300.00, 5600.00, 4900.00, 1150.00, 1700.00, '2024-11-30', 'Net Banking', 'Approved', 'Salary for November 2024', 60200.00, 13850.00, 46350.00);



CREATE TABLE `empkyc` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EmpID` int(11) NOT NULL,
  `AadharCard` varchar(255) DEFAULT NULL,
  `PanCard` varchar(255) DEFAULT NULL,
  `ProfilePhoto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`EmpID`) REFERENCES `employeedetail`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE payroll_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('Addition', 'Deduction', 'Overtime') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Insert some sample payroll items
INSERT INTO payroll_items (name, type, amount, description) VALUES
('Basic Salary', 'Addition', 50000.00, 'Basic pay for employee'),
('HRA', 'Addition', 10000.00, 'House Rent Allowance'),
('Medical Allowance', 'Addition', 5000.00, 'Allowance for medical expenses'),
('Bonus', 'Addition', 7000.00, 'Annual bonus based on performance'),
('Tax Deduction', 'Deduction', 5000.00, 'Income tax deduction'),
('Provident Fund', 'Deduction', 3000.00, 'Contribution to Provident Fund'),
('Loan Deduction', 'Deduction', 2000.00, 'Salary loan repayment'),
('Overtime Pay', 'Overtime', 2500.00, 'Payment for overtime hours worked'),
('Night Shift Allowance', 'Overtime', 1500.00, 'Additional pay for night shifts'),
('Special Bonus', 'Addition', 3000.00, 'Special bonus for project completion');

-- Create the 'training' table
CREATE TABLE `training` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `training_type` VARCHAR(255) NOT NULL,
    `trainer` VARCHAR(255) NOT NULL,
    `time_duration` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data into the 'training' table
INSERT INTO `training` (`training_type`, `trainer`, `time_duration`, `description`, `status`, `created_at`)
VALUES 
    ('PayRupik', 'Buddhha', '2 weeks', 'Training on PayRupik system.', 'Active', NOW()),
    ('Hathway', 'Ranubala', '3 weeks', 'Comprehensive training on Hathway system.', 'Active', NOW()),
    ('Stashfin', 'Buddhha', '1 month', 'In-depth Stashfin operational training.', 'Active', NOW()),
    ('Navi', 'Ranubala', '1.5 weeks', 'Training to handle Navi platform operations.', 'Active', NOW()),
    ('Rupee Redee', 'Ranubala', '2 months', 'Complete Rupee Redee process training.', 'Active', NOW());

-- Show the inserted data
SELECT * FROM `training`;

CREATE TABLE `exit_list` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `employee` VARCHAR(255) NOT NULL,
    `exit_date` DATE NOT NULL,
    `reason` TEXT NOT NULL,
    `status` ENUM('Approved', 'Pending', 'Rejected') NOT NULL DEFAULT 'Pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting example exit records
INSERT INTO exit_list (employee, exit_date, reason, status)
VALUES 
('John Doe', '2024-12-01', 'Retirement', 'Approved'),
('Jane Smith', '2024-11-25', 'Health Issues', 'Pending'),
('Michael Johnson', '2024-11-20', 'Career Growth Opportunities', 'Approved'),
('Emily Davis', '2024-12-15', 'Personal Reasons', 'Rejected'),
('Robert Brown', '2024-12-10', 'Family Relocation', 'Pending'),
('Linda Clark', '2024-11-18', 'Job Change', 'Approved'),
('David Wilson', '2024-12-05', 'Workplace Conflict', 'Pending'),
('Sarah White', '2024-11-30', 'Retirement', 'Approved');


-- Create the assets table
CREATE TABLE assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_name VARCHAR(255) NOT NULL,
    employee VARCHAR(255) NOT NULL,
    purchase_date DATE NOT NULL,
    asset_condition ENUM('New', 'Used', 'Refurbished') NOT NULL,
    status ENUM('Active', 'Inactive', 'Damaged') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserting example data into the assets table
INSERT INTO assets (asset_name, employee, purchase_date, asset_condition, status)
VALUES
('Laptop', 'John Doe', '2023-05-20', 'New', 'Active'),
('Monitor', 'Jane Smith', '2022-08-15', 'Used', 'Active'),
('Keyboard', 'Michael Johnson', '2021-12-10', 'Refurbished', 'Inactive'),
('Mouse', 'Emily Davis', '2023-03-25', 'New', 'Damaged'),
('Projector', 'Robert Brown', '2024-06-30', 'New', 'Active');

-- -- Create the users table with appropriate fields
CREATE TABLE `tblusers` (
    `ID` INT AUTO_INCREMENT PRIMARY KEY,
    `FullName` VARCHAR(255) NOT NULL,
    `Username` VARCHAR(50) NOT NULL UNIQUE,
    `Email` VARCHAR(255) NOT NULL UNIQUE,
    `Phone` VARCHAR(15) NOT NULL,
    `Password` VARCHAR(255) NOT NULL,
    `Department` VARCHAR(100) DEFAULT NULL,
    `Status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `Birthday` DATE NOT NULL,
    `Address` TEXT,
    `Gender` ENUM('Male', 'Female', 'Other') NOT NULL
);


CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(255) NOT NULL,
    permissions TEXT NOT NULL
);


INSERT INTO `holidays` (`Holiday_Name`, `Holiday_Date`) VALUES
('New Year\s Day', '2025-01-01'),
('Republic Day', '2025-01-26'),
('Labour Day', '2025-05-01'),
('Independence Day', '2025-08-15'),
('Gandhi Jayanti', '2025-10-02'),
('Diwali', '2025-10-22'),
('Christmas Day', '2025-12-25'),
('Makar Sankranti', '2025-01-14'),
('Holi', '2025-03-06'),
('Eid al-Fitr', '2025-03-30'),
('Eid al-Adha', '2025-06-29'),
('Durga Puja', '2025-10-05'),
('Onam', '2025-08-15'),
('Good Friday', '2025-04-18'),
('Baisakhi', '2025-04-13');

CREATE TABLE asset_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

CREATE TABLE user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) NOT NULL,
    menu_item VARCHAR(100) NOT NULL,
    has_access BOOLEAN DEFAULT 0
);


CREATE TABLE `invoices` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `Invoice_Number` VARCHAR(255) NOT NULL,
    `Invoice_Date` DATE NOT NULL,
    `Customer_Name` VARCHAR(255) NOT NULL,
    `Vendor_Name` VARCHAR(255) NOT NULL,
    `GST_No` VARCHAR(20) NOT NULL,
    `HSN_Code` VARCHAR(20) NOT NULL,
    `Amount` DECIMAL(10, 2) NOT NULL,
    `DateTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`Invoice_Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `view_employee` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `training_id` INT NOT NULL,
    `employee_name` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`training_id`) REFERENCES `training`(`id`) ON DELETE CASCADE
);


CREATE TABLE announcements (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    AnnouncementDate DATE NOT NULL,
    CreatedBy INT NOT NULL,
    FOREIGN KEY (CreatedBy) REFERENCES tbladmin(ID) ON DELETE CASCADE
);

INSERT INTO announcements (Title, Description, AnnouncementDate, CreatedBy)
VALUES
('System Maintenance', 'The system will be down for maintenance on December 1, 2024, from 12:00 AM to 6:00 AM.', '2024-11-28', 1),
('Holiday Notice', 'The office will remain closed on December 25, 2024, for Christmas.', '2024-11-27', 2),
('Policy Update', 'A new leave policy has been implemented effective from January 1, 2025.', '2024-11-26', 1);

CREATE TABLE notifications (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Description TEXT,
    Type VARCHAR(50),
    Status INT DEFAULT 0,
    EmpID INT,  -- Add EmpID to associate notifications with the employee
    FOREIGN KEY (EmpID) REFERENCES employeedetail(ID)
);

CREATE TABLE employee_leave_balance (
    EmpID INT PRIMARY KEY,
    TotalLeaves INT NOT NULL DEFAULT 30,
    UsedLeaves INT NOT NULL DEFAULT 0,
    PendingLeaves INT NOT NULL DEFAULT 30,  -- This will be recalculated based on the used leaves
    FOREIGN KEY (EmpID) REFERENCES employeedetail(ID)
);


CREATE TABLE `docs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,           -- Unique identifier for each document
  `Title` varchar(255) NOT NULL,                  -- Title of the document
  `Description` text NOT NULL,                    -- Description of the document
  `DocDate` date NOT NULL,                        -- Date associated with the document
  `Type` varchar(50) NOT NULL,                    -- Type of document (e.g., Document, Spreadsheet, Slide, etc.)
  `Tags` varchar(255) DEFAULT NULL,               -- Tags for categorization
  `CreatedBy` int(11) NOT NULL,                   -- Reference to the user who created the document
  `FilePath` varchar(255) NOT NULL,               -- Path to the file in the server
  `GoogleFileID` varchar(255) DEFAULT NULL,       -- Google Drive File ID for integration with Google products
  `GoogleProduct` varchar(50) DEFAULT NULL,       -- New field to store Google product type (Docs, Sheets, Slides, etc.)
  PRIMARY KEY (`ID`),
  CONSTRAINT `fk_docs_tbladmin` FOREIGN KEY (`CreatedBy`) REFERENCES `tbladmin`(`ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_docs_employeedetail` FOREIGN KEY (`CreatedBy`) REFERENCES `employeedetail`(`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE tblmanager (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ManagerName VARCHAR(100) NOT NULL,
    ManagerUsername VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,  -- Store encrypted password (using hash in application)
    Phone VARCHAR(15),
    Email VARCHAR(100) NOT NULL UNIQUE,
    Birthday DATE,
    Address TEXT,
    Gender ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
    ManagerRegdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Department VARCHAR(100),  -- Optionally specify which department the manager oversees
    ReportingTo INT,  -- Manager reporting to another higher-level admin or supervisor (optional)
    IsActive BOOLEAN DEFAULT TRUE  -- Field to indicate if the manager is active
);

Updated SQL code for the `docs` table
Updated SQL code for the `docs` table with Google products


