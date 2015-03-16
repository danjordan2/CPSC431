<?php
/**
* NAME: Daniel Jordan
* EMAIL: daniel_jordan@csu.fullerton.edu
* ASSIGNMENT: CPSC 431 Homework #3
*
* OUTPUT:
* -bash-3.2$ php hw3.php
* Student: Bob Johnson
* CCWID: 123456789
* Average course score: 84.45%
* Standard deviation: 5.83
*
*/

/**
* Class containing database logic
*/
include_once('../config.inc.php');
class Course{
	/** Variable to hold database connection */
    private $connection;
    /* Rules for weighted grades */
    private  $weights = array(
    		"homework_score" => 0.20,
            "attendance_score" => 0.05,
            "term_project_score" => 0.20,
            "midterm_score" => 0.20,
            "final_score" => 0.30
	);
    /**
    * @function __construct()
    * @param {string} database host
    * @param {string} database name
    * @param {string} database username
    * @param {string} database password
    * 
	* Initiates connection with database
    */
    function __construct($host, $db, $username, $password){
        $this->connection = new mysqli($host, $username, $password, $db, 3306);
        if ($this->connection->connect_errno) {
            echo $this->connection->connect_error;
            exit();
        }
    }
    /**
    * @function getStudent()
    * @param {string} CCWID for student
    * @returns {array} Array of student arrays
    * 
    * Queries the database for a students grades, based on their CCWID
    * Query performs a sub query to select all homework assignments and calculate their average score. 
    * That value is then dumped into a custom column and returned with the rest of the resultset.
    * Values are then used to create an associative array, which is pushed into a larger array, and returned
    */
    function getStudent($ccwid){
        $student = array();        
        $stmt = $this->connection->prepare("SELECT STUDENT_CCWID, STUDENT_FIRST_NAME, STUDENT_LAST_NAME, (SELECT AVG(HOMEWORK_SCORE) FROM HOMEWORK_SCORE WHERE HOMEWORK_STUDENT_CCWID = STUDENT_CCWID) as HOMEWORK_AVERAGE, COURSE_ATTENDANCE_SCORE, COURSE_TERM_PROJECT_SCORE, COURSE_MIDTERM_SCORE, COURSE_FINAL_SCORE FROM  COURSE_SCORE, STUDENT WHERE COURSE_STUDENT_CCWID = STUDENT_CCWID AND COURSE_STUDENT_CCWID = ?");
        $stmt->bind_param("s", $ccwid);
        $stmt->execute();
        $stmt->bind_result($ccwid, $first_name, $last_name, $homework_score, $attendance_score, $term_project_score, $midterm_score, $final_score);

        while($stmt->fetch()){
            $scores = array(
                "ccwid" => $ccwid,
                "first_name" => $first_name,
                "last_name" => $last_name, 
                "homework_score" => $homework_score,
                "attendance_score" => $attendance_score,
                "term_project_score" => $term_project_score,
                "midterm_score" => $midterm_score,
                "final_score" => $final_score
            );
            array_push($student, $scores);
        }
        $stmt->close();
        return $student;
    }
    /**
    * @function __destruct()
    * 
	* Closes connection with database
    */
    function __destruct(){
        $this->connection->close();
    }
    /**
    * @function mean()
    * @param {array} Array of student scores
    * @returns {float} Average of array values
    * 
	* Function sums all values in an array, and divides
	* the sum by the number of elements in array
    */
    function mean($scores){
        return array_sum($scores)/count($scores); 
    }
    /**
    * @function averageScore()
    * @param {array} Array of student scores
    * @returns {float} Average weighted grade
    * 
	* Function receives associative array of
	* student grades. It iterates through the
	* grade weights, and multiplies them with
	* the corresponding student category. A 2
	* digit average is then returned
    */
    function averageScore($student){
        $avg = 0;
        foreach ($this->weights as $key => $weight) {
            $avg += $weight * $student[$key];
        }
        return round($avg, 2);
    } 
    /**
    * @function sd()
    * @param {array} Array containing midterm and final scores
    * @param {float} Average of the midterm and final scores 
    * @returns {float} Standard deviation of midterm and final scores
    * 
    */   
    function sd($scores, $mean){
	    /**
	    * @function variance()
	    * @param {float} Reference to individual score value
	    * @param {float} Average of the midterm and final scores 
	    * 
		* Function is applied to each value in array. 
		* It calculates the difference from the mean squared
	    */   
        function variance(&$num, $key, $mean){
            $num = pow(($num-$mean), 2);
        }
        array_walk($scores, "variance", $mean);
        return round(sqrt(array_sum($scores)/(count($scores)-1)), 2);
    }
}
/** Open db connection */
$course = new Course($config["dbhost"], $config["dbname"], $config["dbuser"], $config["dbpass"]);

/** Retrieve data for student */
$scores = $course->getStudent('0123456789');

/** Grab midterm and final score and put them in their own array */
$midtermFinal = array($scores[0]["midterm_score"],$scores[0]["final_score"]);

/** Output the data */
echo "Student: ". $scores[0]["first_name"]. " ". $scores[0]["last_name"]."\n"; 
echo "CCWID: ". $scores[0]["ccwid"]."\n"; 
echo "Average course score: ". $course->averageScore($scores[0])."%\n";
echo "Standard deviation: ". $course->sd($midtermFinal, $course->mean($midtermFinal))."\n";
?>