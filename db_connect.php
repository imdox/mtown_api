<?php
 
/**
 * A class file to connect to database
 */
class DbConnect {
	function connect(){
		$con = mysqli_connect("localhost","u217835407_mtown","jfnqCYR30PCo","u217835407_mtown");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
	}
}
 
?>