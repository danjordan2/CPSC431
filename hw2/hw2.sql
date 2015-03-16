/**   
* CPSC 431 Homework #2   
* Name: Daniel Jordan   
* Email: daniel_jordan@csu.fullerton.edu   
* Titan Account: cs432s21   
* http://ecs.fullerton.edu/~cs431s21/  
*/ 
DROP TABLE IF EXISTS COURSE_SCORE, STUDENT, HOMEWORK_SCORE; 

CREATE TABLE STUDENT 
  ( 
     STUDENT_CCWID      INT(10) UNSIGNED NOT NULL, 
     STUDENT_FIRST_NAME VARCHAR(20) NOT NULL, 
     STUDENT_LAST_NAME  VARCHAR(20) NOT NULL, 
     STUDENT_DOB        DATE NULL, 
     PRIMARY KEY(STUDENT_CCWID) 
  ); 

CREATE TABLE COURSE_SCORE 
  ( 
     COURSE_ATTENDANCE_SCORE   FLOAT NOT NULL, 
     COURSE_TERM_PROJECT_SCORE FLOAT NOT NULL, 
     COURSE_MIDTERM_SCORE      FLOAT NOT NULL, 
     COURSE_FINAL_SCORE        FLOAT NOT NULL, 
     COURSE_STUDENT_CCWID      INT UNSIGNED NOT NULL, 
     PRIMARY KEY(COURSE_STUDENT_CCWID), 
     FOREIGN KEY(COURSE_STUDENT_CCWID) REFERENCES STUDENT(STUDENT_CCWID) 
  ); 

CREATE TABLE HOMEWORK_SCORE 
  ( 
     HOMEWORK_SCORE         FLOAT NOT NULL, 
     HOMEWORK_DATE          DATE NOT NULL, 
     HOMEWORK_STUDENT_CCWID INT UNSIGNED NOT NULL, 
     FOREIGN KEY(HOMEWORK_STUDENT_CCWID) REFERENCES COURSE_SCORE (COURSE_STUDENT_CCWID) 
  ); 
/** 
* Insert students
*/
INSERT INTO STUDENT (STUDENT_CCWID, STUDENT_FIRST_NAME, STUDENT_LAST_NAME, STUDENT_DOB) VALUES
(0123456789, "Bob", "Johnson", "1988-08-16"),
(9849849081, "Daniel", "Jordan", "1990-01-14"),
(3873298723, "Jack", "Smith", "1989-05-4");
/** 
* Insert course scores
*/
INSERT INTO COURSE_SCORE (COURSE_STUDENT_CCWID, COURSE_ATTENDANCE_SCORE, COURSE_TERM_PROJECT_SCORE, COURSE_MIDTERM_SCORE, COURSE_FINAL_SCORE) VALUES
(0123456789, 87.14, 90.12, 82.04, 90.29),
(9849849081, 81.31, 93.14, 88.24, 89.15),
(3873298723, 95.14, 92.14, 81.38, 90.14);
/** 
* Insert homework scores
*/
INSERT INTO HOMEWORK_SCORE (HOMEWORK_STUDENT_CCWID, HOMEWORK_DATE, HOMEWORK_SCORE) VALUES
(0123456789, "2015-02-15", 94.15),
(0123456789, "2015-01-14", 90.49),
(0123456789, "2015-02-05", 89.19),
(0123456789, "2015-01-29", 100.00),
(0123456789, "2015-02-25", 90.43),
(9849849081, "2015-02-15", 74.15),
(9849849081, "2015-01-14", 80.49),
(9849849081, "2015-02-05", 99.19),
(9849849081, "2015-01-29", 92.00),
(9849849081, "2015-02-25", 80.43),
(3873298723, "2015-02-15", 94.11),
(3873298723, "2015-01-14", 80.44),
(3873298723, "2015-02-05", 89.92),
(3873298723, "2015-01-29", 79.00),
(3873298723, "2015-02-25", 81.32);

/** 
* Look up all homework assignments for a student CCWID
+--------------------+-------------------+---------------+----------------+
| STUDENT_FIRST_NAME | STUDENT_LAST_NAME | HOMEWORK_DATE | HOMEWORK_SCORE |
+--------------------+-------------------+---------------+----------------+
| Bob                | Johnson           | 2015-02-15    |          94.15 | 
| Bob                | Johnson           | 2015-01-14    |          90.49 | 
| Bob                | Johnson           | 2015-02-05    |          89.19 | 
| Bob                | Johnson           | 2015-01-29    |            100 | 
| Bob                | Johnson           | 2015-02-25    |          90.43 | 
+--------------------+-------------------+---------------+----------------+
*/
SELECT STUDENT_FIRST_NAME, STUDENT_LAST_NAME, HOMEWORK_DATE, HOMEWORK_SCORE
FROM HOMEWORK_SCORE, STUDENT
WHERE HOMEWORK_STUDENT_CCWID = STUDENT_CCWID
AND   HOMEWORK_STUDENT_CCWID = 0123456789;

/** 
* Look up all homework assignments for a student name
+--------------------+-------------------+---------------+----------------+
| STUDENT_FIRST_NAME | STUDENT_LAST_NAME | HOMEWORK_DATE | HOMEWORK_SCORE |
+--------------------+-------------------+---------------+----------------+
| Bob                | Johnson           | 2015-02-15    |          94.15 | 
| Bob                | Johnson           | 2015-01-14    |          90.49 | 
| Bob                | Johnson           | 2015-02-05    |          89.19 | 
| Bob                | Johnson           | 2015-01-29    |            100 | 
| Bob                | Johnson           | 2015-02-25    |          90.43 | 
+--------------------+-------------------+---------------+----------------+
*/
SELECT STUDENT_FIRST_NAME, STUDENT_LAST_NAME, HOMEWORK_DATE, HOMEWORK_SCORE
FROM HOMEWORK_SCORE, STUDENT
WHERE HOMEWORK_STUDENT_CCWID = STUDENT_CCWID
AND   STUDENT_FIRST_NAME = "Bob" AND STUDENT_LAST_NAME = "Johnson";

/** 
* Look up course score by student name
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
| STUDENT_FIRST_NAME | STUDENT_LAST_NAME | COURSE_ATTENDANCE_SCORE | COURSE_TERM_PROJECT_SCORE | COURSE_MIDTERM_SCORE | COURSE_FINAL_SCORE |
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
| Bob                | Johnson           |                   87.14 |                     90.12 |                82.04 |              90.29 | 
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
*/
SELECT STUDENT_FIRST_NAME, STUDENT_LAST_NAME, COURSE_ATTENDANCE_SCORE, COURSE_TERM_PROJECT_SCORE, COURSE_MIDTERM_SCORE, COURSE_FINAL_SCORE 
FROM COURSE_SCORE, STUDENT
WHERE COURSE_STUDENT_CCWID = STUDENT_CCWID
AND   STUDENT_FIRST_NAME = "Bob" AND STUDENT_LAST_NAME = "Johnson";

/** 
* Look up course score by student CCWID
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
| STUDENT_FIRST_NAME | STUDENT_LAST_NAME | COURSE_ATTENDANCE_SCORE | COURSE_TERM_PROJECT_SCORE | COURSE_MIDTERM_SCORE | COURSE_FINAL_SCORE |
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
| Bob                | Johnson           |                   87.14 |                     90.12 |                82.04 |              90.29 | 
+--------------------+-------------------+-------------------------+---------------------------+----------------------+--------------------+
*/
SELECT STUDENT_FIRST_NAME, STUDENT_LAST_NAME, COURSE_ATTENDANCE_SCORE, COURSE_TERM_PROJECT_SCORE, COURSE_MIDTERM_SCORE, COURSE_FINAL_SCORE 
FROM  COURSE_SCORE, STUDENT
WHERE COURSE_STUDENT_CCWID = STUDENT_CCWID
AND   COURSE_STUDENT_CCWID = 0123456789;
