<?php
	/*PURPOSE:
		This file retrieves all user information once its proven they 
		have successfully logged in.
	*/

	error_reporting(-1);
	include('connection.php');
	@session_start(); // Starting Session 
	// Storing Session
	$user_check=$_SESSION['login_user'];  
    //$user_id = $_SESSION['user_id'];

	$result = $dbc->query("SELECT users.id, users.first_name, users.last_name, users.usernames, users.email, users.course, users.section,users.time, users.score, login_history.busy FROM users INNER JOIN login_history ON users.id = login_history.id WHERE usernames='$user_check'");
	$row = $result->fetch_assoc();
	$login_id=$row['id'];
	//$login_status = $row['online_status'];
	$login_session =$row['first_name'];
	$login_lname=$row['last_name'];
	$login_uname=$row['usernames'];
	$login_session_email=$row['email'];
	$course=$row['course'];
	$section=$row['section'];

    //IterativeORrandomCheck
    if (true)
    {
        //added for Play Iterative Mode
	   $_SESSION['course'] = $course;
	   $_SESSION['section'] = $section;
		$score = $row['score'];
		$busy = $row['busy'];
		$time= $row['time'];
		$sql = "SELECT id FROM games_iterative WHERE (player1='$login_id' OR player2='$login_id') AND (status=6)";
		$query = $dbc->query($sql);
		$gplayed = $query->num_rows;
	   //end additions
    }
    else
    {
	   //added for Play Random Mode
	   $_SESSION['course'] = $course;
	   $_SESSION['section'] = $section;
		$score = $row['score'];
		$busy = $row['busy'];
		$time= $row['time'];
		$sql = "SELECT id FROM games WHERE (player1='$login_id' OR player2='$login_id') AND (status=6)";
		$query = $dbc->query($sql);
		$gplayed = $query->num_rows;
	//end additions
    }    

	$_SESSION['login_id'] = $login_id;

	$result2 = $dbc->query("SELECT * from teamcode where users_id='$login_id'");
	$row2 = $result2->fetch_assoc();
	
    $tag = strtolower($row2["tag"]);
    $exp = explode("-",$tag);

    $login_teamcolor = $row2['user_group'];
	$login_teamcolor_num = $exp[1];  //substr($row2['tag'],-1);
	if(!isset($login_session))
	{
		mysqli_close($dbc);
		header('Location: index.php'); // Redirecting To Home Page
	}
?>