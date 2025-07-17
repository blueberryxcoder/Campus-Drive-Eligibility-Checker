CAMPUS DRIVE ELIGIBILITY CHECKER
================================

Project Description:
--------------------
The Campus Drive Eligibility Checker is a web-based application developed using PHP, JavaScript, HTML, and CSS. 
It helps college students determine their eligibility for campus recruitment drives based on their resume data 
and job profile requirements.

Key Features:
-------------
- Students upload their resumes (skills, qualifications, etc.)
- Admin uploads job profiles and drive details
- System analyzes student resumes and compares with job criteria
- Displays eligibility status: Eligible / Not Eligible
- Dynamic drive listing with real-time checking

Technologies Used:
------------------
- PHP (Server-side logic)
- JavaScript (Client-side interaction)
- HTML5 (Markup language)
- CSS3 (Styling and layout)

Project Modules:
----------------
1. Student Module
   - Resume upload (PDF or text extraction)
   - Skill and qualification parser
   - Eligibility result display

2. Admin Module
   - Admin login panel
   - Add, update, delete ongoing drive/job postings
   - Set eligibility criteria (skills, CGPA, degree, etc.)

3. Eligibility Engine
   - Parses student resume to extract relevant data
   - Matches data with job profile requirements
   - Displays result with appropriate message

Folder Structure:
-----------------
- /admin – Admin panel pages
- /students – Student upload and results pages
- /uploads – Stores uploaded resumes
- /css – Custom stylesheets
- /js – JavaScript logic for UI and validation
- /includes – PHP logic, DB config, utilities

How to Run:
-----------
1. Make sure you have a local server setup (XAMPP, WAMP, or LAMP).
2. Place the project folder in the htdocs directory.
3. Create a MySQL database and import the provided SQL file (if available).
4. Configure DB credentials in /includes/config.php.
5. Start Apache and MySQL servers.
6. Access the project via http://localhost/Campus_Drive_Eligibility_Checker

Default Admin Login (for testing):
----------------------------------
- Username: admin
- Password: admin123
(Note: Change credentials from the DB or admin panel after first login)

Future Improvements:
--------------------
- Integration with OCR for image-based resume parsing
- Resume score calculation based on industry match
- Email notifications for selected students
- Enhanced UI with responsive design

Contact:
--------
For queries or contributions, please contact:  
Sneha,
snehaannam69@gmail.com
