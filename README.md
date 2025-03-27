# Library Management System

A web-based system for Australian University at Gelos Enterprises to manage book borrowing and returns efficiently.

## Features

- User authentication (signup/login)
- Browse, borrow, return, delete, and edit books
- Responsive and professional UI
- Data stored in a relational database (3rd Normal Form compliance)

## Technologies

- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend:** PHP, MySQL

## Setup

### 1. Copy Project to `htdocs`

```sh
cp -r code_lms_sawinee /path/to/htdocs
```

### 2. Start XAMPP and Services

- Launch XAMPP

- Start Apache and MySQL

### 3. Create Database and User

- Open phpMyAdmin

- Run the following SQL:

```sh
CREATE DATABASE lms_sawinee_srisakul;

CREATE USER 'admin_lms_sawinee_srisakul'@'localhost' IDENTIFIED BY 'BDTE2r3nZ4Bd7ENk';

GRANT ALL PRIVILEGES ON lms_sawinee_srisakul.* TO 'admin_lms_sawinee_srisakul'@'localhost';

FLUSH PRIVILEGES;
```

### 4. Test Database Connection

- Open: Database Test Script
  http://localhost/code_lms_sawinee/database_template_sawinee_test.php

- Expected output: "Connected successfully"

### 5. Import Database

- Import db.sql file from the code_lms_sawinee folder:

```sh
mysql -u admin_lms_sawinee_srisakul -p lms_sawinee_srisakul < /path/to/code_lms_sawinee/db.sql
```

- Expected result: "3 tables created, with test data"

### 6. Set Folder Permissions

- Linux/macOS

```sh
cd code_lms_sawinee
chmod 777 images-cover
```

- Windows
  -- 1.Right-click the images-cover folder and select Properties.

  -- 2.Go to the Security tab and click Edit.

  -- 3.Select the user/group and check Allow for Write (or Modify).

  -- 4.Click OK to apply changes.

### 7. Run the Application

- Open: http://localhost/code_lms_sawinee

## Test Users

Use the following credentials to log in:

### Member Account

- **Email:** sawinee.ss4@gmail.com
- **Password:** 12345678arM#

### Admin Accounts

- **Email:** sawinee.ss1@gmail.com
- **Password:** 12345678arM#
- **Email:** sawinee.ss2@gmail.com
- **Password:** 12345678arM#

### License

- MIT License
