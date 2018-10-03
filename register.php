<?php

include "dbc.php";

$f_name = $_POST["f_name"];
$l_name = $_POST["l_name"];
$email = $_POST['email'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];
$mobile = $_POST['mobile'];
$county = $_POST['county'];
$town = $_POST['town'];
$name = "/^[A-Z][a-zA-Z ]+$/";
$emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
$number = "/^[0-9]+$/";

if(empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) ||
	empty($mobile) || empty($county) || empty($town)){
		
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Fill All Fields!</b>
			</div>
		";
		exit();
	} else {
		if(!preg_match($name,$f_name)){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>this $f_name is not Valid,Capitalize the Firstt Letter</b>
			</div>
		";
		exit();
	}
	if(!preg_match($name,$l_name)){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>this $l_name is not valid..!</b>
			</div>
		";
		exit();
	}
	if(!preg_match($emailValidation,$email)){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>this $email is not valid..!</b>
			</div>
		";
		exit();
	}
	if(strlen($password) < 9 ){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Password is weak, atleast 9 Characters</b>
			</div>
		";
		exit();
	}
	if(strlen($repassword) < 9 ){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Password is weak, at least 9 Character</b>
			</div>
		";
		exit();
	}
	if($password != $repassword){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>passwords do not Match</b>
			</div>
		";
	}
	if(!preg_match($number,$mobile)){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Mobile number $mobile is not valid</b>
			</div>
		";
		exit();
	}
	if(!(strlen($mobile) == 9)){
		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Mobile number must be 9 digits, i.e type 7 222 000 00</b>
			</div>
		";
		exit();
	}
	//cchecking duplicate emails
	$sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1" ;
	$sql_m = "SELECT user_id FROM user_info WHERE mobile = '$mobile' LIMIT 1" ;
	$check_query_m=mysqli_query($con,$sql_m);
	$check_query = mysqli_query($con,$sql);
	$count_mobile=mysqli_num_rows($check_query_m);
	$count_email = mysqli_num_rows($check_query);
	if($count_mobile>0)
	{
		echo "
			<div class='alert alert-danger'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Seems your Mobile Number is already registered with us</b>
			</div>
		";
	}
	elseif ($count_email > 0){
		echo "
			<div class='alert alert-danger'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Email Address is already taken Try Another email address</b>
			</div>
		";
		exit();
	} else {
		$password = md5($password);
		$sql = "INSERT INTO `user_info` 
		(`user_id`, `first_name`, `last_name`, `email`, 
		`password`, `mobile`, `county`, `town`) 
		VALUES (NULL, '$f_name', '$l_name', '$email', 
		'$password', '+254$mobile', '$county', '$town')";
		$run_query = mysqli_query($con,$sql);
		if($run_query){
			echo "
				<div class='alert alert-success'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Registration was Successful, Thank you!</b>
				</div>

			";

		}
	}
	}
	


?>
