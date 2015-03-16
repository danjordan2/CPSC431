<?php
/**
* NAME: Daniel Jordan
* EMAIL: daniel_jordan@csu.fullerton.edu
* ASSIGNMENT: CPSC 431 Homework #1
* 
* COMMAND LINE
* Data can be passed as single comma delimited string, or array of integers 
* php index.php 1,2,3,4,5
* php index.php 1 2 3 4 5
*
* BROWSER
* Input comma delimited string into input field. 
* 1, 2, 3, 4, 5
* 1,2,3,4,5
*
*/
//Data passed from command line
if(isset($argv)){
	//Data being passed as array of parameters
	if(sizeof($argv) > 2){
		//Strip out filename parameter
		array_shift($argv);
		//Convert string of numbers to integers
		$list = array_map('intval', $argv);
	}
	//Data passed as a single string of numbers
	else{
		//Split string into array of integers
		$list = array_map('intval', explode(',', $argv[1]));
	}
}
//Data posted from browser
else if(isset($_POST["list"])){
	//Strip whitespace and split string into array of integers
	$list = array_map('intval', explode(',', str_replace(' ', '', $_POST["list"])));
}
//UI for browser
else{
	echo "<p>CPSC 431 Homework #1<br/>Daniel Jordan<br/>daniel_jordan@csu.fullerton.edu</p>";
	echo "<form method='post' action='".$_SERVER['REQUEST_URI']."'>";
	echo "list of numbers (comma separated): ";
	echo "<input name='list' type='text'/>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
}
if(isset($list)){
	//Sort array
	sort($list);
	//Calculate mean
	function mean($list){
		return array_sum($list)/count($list); 
	}
	//Calculate median
	function median($list){
		//Even number of elements
		if(count($list) % 2 == 0){
			//Average middle two elements
			return ($list[floor(count($list)/2)-1] + $list[floor(count($list)/2)])/2;		
		}
		//Odd number of elements
		else{
			//Return middle element
			return $list[floor(count($list)/2)];
		}
	}
	//Calculate standard deviation
	function sd($list, $mean){
		//Return difference of mean squared
		function variance(&$num, $key, $mean){
			$num = pow(($num-$mean), 2);
		}
		//Apply variance function to each element
		array_walk($list, "variance", $mean);
		//Return square root of variance
		return sqrt(array_sum($list)/(count($list)-1));
	}
	$lineBreak = $_POST ? "<br/>" : "\n";
	echo "----------------------------------------".$lineBreak;
	echo "CPSC 431 Homework #1".$lineBreak;
	echo "Daniel Jordan".$lineBreak;
	echo "daniel_jordan@csu.fullerton.edu".$lineBreak;
	echo "----------------------------------------".$lineBreak;
	echo "Input: ".implode(',', $list).$lineBreak;
	echo "Mean: ".mean($list).$lineBreak;
	echo "Median: ".median($list).$lineBreak;
	echo "Standard Deviation: ".sd($list, mean($list)).$lineBreak;
}
?>

