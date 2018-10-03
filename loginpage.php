<?php
session_start();
if(isset($_SESSION["uid"])){
  header("location:nav-in.php");
}
?>
<html>
<head><meta charset="UTF-8">
    <title>Fish Store</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<title>
Fish Online
</title>
<body>

<!-- Login Modal -->
<div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mylogin">Login</h4>
      </div>
      <div class="modal-body">
      <label>Phone</label>
          <input type="text" name="phone" id="phone" class="form-control"/>
          <br />
          <label>Password</label>
          <input type="password" name="password" id="password" class="form-control" />
          <br />
        
      </div>
      <div class="modal-footer">
     
        
        
         <button type="button" name="login_button" id="login_button" class="btn btn-success">Login</button>
        <div class="alert alert-info " role="alert">
          <a href="signup.php" class="alert-link ">If youre not registered</a>
      </div>
      </div>
    </div>
  </div>
</div>



<div class="container">

<button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#myLoginModal">
  Login
</button>
<a type="button" href="signup.php"> Sign Up</a>




<script>

$(document).ready(function(){

   
$("#login_button").click(function(event){
    event.preventDefault();
    var phone = $("#phone").val();
    var password = $("#password").val();
    if(phone != '' && password !='')
    {
    $.ajax({
      url : "login.php",
      method: "POST",
      data  : {userLogin:1,phone:phone,password:password},
      success :function(data){
        if(data == "ndani"){
          window.location.href = "index.php";
        }
        else
        {
          alert ("Wrong Phone number and  Password");
        }
      }
    });
  }
  else
  {
    alert ("Empty Phone and Password");
  }
  });

  


</script>
