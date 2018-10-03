<html>
<head><meta charset="UTF-8">
    <title>Fish Store</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
   
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<title>
Fish Online
</title>
<body>
<nav class="navbar navbar-default navbar-xs" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#"><b><img src="img/1.jpg" style="max-width:100px; margin-top: -7px;"></b>Fish Auction</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

    <ul class="nav navbar-nav pull-right">
      <button type="button" class="btn btn-primary btn-md " data-toggle="modal" data-target="#myLoginModal">
      Login
      </button>
     <a href="signup.php">
              <button type="button" class="btn btn-success btn-md">
                <span class="glyphicon glyphicon-eye-user" aria-hidden="true"></span>Sign up
              </button></a>
   
      
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
<!-- Login Modal -->
<div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mylogin">Login</h4>
      </div>
      <div class="modal-body">
      <label>Mobile</label>
          <input type="text" name="phone" id="phone" class="form-control"/>
          <br />
          <label>Password</label>
          <input type="password" name="password" id="password" class="form-control" />
          <br />
        
      </div>
      <div class="modal-footer">     
        
         <button type="button" name="login_button" id="login_button" class="btn btn-success">Login</button>
       <div class="alert alert-info " role="alert">
          Click <a href="signup.php" class="alert-link ">If youre not registered</a>
          Forgot Password <a href="forgot.php" class="alert-link ">Forgot Password?</a>
      </div>
       <div class="panel-footer center">&copy; Online Fish Auction</div>

      </div>
    </div>
  </div>
</div>


	<div class="container-fluid">
		<div class="row">

			<div class="col-md-2"></div>
			
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
					<ol class="breadcrumb">
  <li><a href="index.php">Home</a></li>
  <li><a href="signup.php">Registration</a></li>
  
</ol>
				<div class="page-header"><h3>Registration </h3>
				</div>
				<div class="col-md-8" id="signup_msg">
				
			</div>
					
					<div class="panel-body">
					
					<form method="post">
						<div class="row">
							<div class="col-md-6">
								<label for="f_name">First Name</label>
								<input type="text" id="f_name" name="f_name" class="form-control">
							</div>
							<div class="col-md-6">
								<label for="f_name">Last Name</label>
								<input type="text" id="l_name" name="l_name" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="email">Email</label>
								<input type="text" id="email" name="email" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="password">password</label>
								<input type="password" id="password" name="password" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="repassword">Re-enter Password</label>
								<input type="password" id="repassword" name="repassword" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
							<label for="mobile">Mobile Number</label>
							<div class="input-group input-group-md">
								  <span class="input-group-addon" id="sizing-addon1">+254</span>
								  <input type="number" id="mobile" name="mobile" class="form-control" placeholder="Mobile Number" aria-describedby="sizing-addon1">
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="address1">County</label>
								<select class="form-control" type="text" name="county">
    <?php include 'db.php'; $counties=$DBcon->prepare("select * from counties ");
    $counties->execute();

    while ($countiesRow = $counties->fetch()) {
                                    ?>
  <option class="form-control" type="text" name="county" value="<?php  echo $countiesRow['county_code'] ?>"> 
<?php  echo $countiesRow['county_name']  ?></option>
                    
<?php                 
    }?>
    </select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="address2">Town</label>
								<input type="text" id="town" name="town" class="form-control">
							</div>
						</div>
						<p><br/></p>
						<div class="panel-footer">By signing up you're agreeing to the terms and conditions of this website, read it here <a href='terms.php'>Terms</a></div>
						<br />
						<div class="row">
							<div class="col-md-12">
								<input  value="Sign Up" type="button" id="signup_button" name="signup_button"class="btn btn-success btn-lg">
							</div>
						</div>
						
					</div>
					</form>
					
				
			
			<div class="col-md-2"></div>
		</div>
	</div>
</body>
</html>
<script>
$(document).ready(function(){
	$("#signup_button").click(function(event){
		event.preventDefault();
	
			$.ajax({
			url		:	"register.php",
			method	:	"POST",
			data	:	$("form").serialize(),
			success	:	function(data){ 
				$("#signup_msg").html(data);
				
			}
			
		});
		
	});
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
});
</script>




















