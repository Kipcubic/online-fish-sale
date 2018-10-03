
<?php
session_start();
date_default_timezone_set("Africa/Nairobi");
require_once 'db.php';



if(isset($_GET['fish_id']) && !empty($_GET['fish_id']))
{
	$id = $_GET['fish_id'];
	$stmt_view = $DBcon->prepare('SELECT * FROM products WHERE fish_id =:id');
	$stmt_view->execute(array(':id'=>$id));
	$view_row = $stmt_view->fetch(PDO::FETCH_ASSOC);
	$cat=$view_row['fish_cat'];		
	extract($view_row);
}
else
{
	header("Location: index.php");
}

if(isset($_POST['place_bid']))
{
	
	
	$fish_price = $_POST['fish_price'];
	
	if(!isset($_SESSION["uid"]))
	{
	$errMSG = "Sign In First! ";
	}
	else
	{
		$uid=$_SESSION["uid"];
		     // check for user ownership of product
			$checkownership=$DBcon->prepare("SELECT * FROM products WHERE fish_id=:id and userID=:uid");
			$checkownership->bindParam(':uid',$uid);
			$checkownership->bindParam(':id',$id);
			$checkownership->execute();
	if($checkownership->rowCount()>0)
	{
		$errMSG="Cant bid on your products";
	}

	if(!isset($errMSG))
	{	
	$checkbid = $DBcon->prepare("SELECT * FROM product_users WHERE fish_id='$id' and userID='$uid'");
	$checkbid->bindParam(':uid',$uid);
	$checkbid->bindParam(':fish_id',$fish_id);
	$checkbid->execute();	
	if($checkbid->rowCount()==0)
		{
		$stmt=$DBcon->prepare('INSERT INTO product_users(userID,fish_id,suggested_price,comment) VALUES(:uid,:fish_id,:fish_price,"Bid as it is")');
			$stmt->bindParam(':uid',$uid);
			$stmt->bindParam(':fish_id',$fish_id);
			$stmt->bindParam(':fish_price',$fish_price);
			
			if($stmt->execute()){
				?>
				<script>
					alert('Your Bid was Successful,thank you');
				</script>

				<?php
				}
				else{
				$errMSG = "There was a problem placing your bid try again !";
					}
    
		}
	else
	{
	?>
	<script>
	alert('Your previous bid will be overwritten');

	// $yes=confirm('Are you sure you want to rewrite your previous bid?');
					
	</script>

	<?php
	$updateBid = $DBcon->prepare("UPDATE product_users 
									     SET suggested_price=:fish_price, 
										     comment=:comment									     
								       WHERE userID=:uid and fish_id=:id");
			$updateBid->bindParam(':uid',$uid);
			$updateBid->bindParam(':fish_price',$fish_price);
			$updateBid->bindParam(':comment',$comment);
			$updateBid->bindParam(':id',$id);
			
				
			if($updateBid->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry Data Could Not Updated !";
			}

	}	

	}
	


	}






}


if(isset($_POST['placebid']))
{

	if(!isset($_SESSION["uid"]))
	{

	$errMSG = "Sign In First  to Proceed Bidding!";
	}	
	else
	{	$suggested_price=$_POST['bidprice'];
		$fish_id=$_POST['fish_id'];
		$bidprice = $_POST['bidprice'];
		$uid=$_SESSION['uid'];
		$comment=$_POST['comment'];
		     // check for user ownership of product
			$checkownership=$DBcon->prepare("SELECT * FROM products WHERE fish_id=:id and userID=:uid");
			$checkownership->bindParam(':uid',$uid);
			$checkownership->bindParam(':id',$id);
			$checkownership->execute();
	if($checkownership->rowCount()>0)
	{
		$errMSG="Cant bid on your products";
	}

		if(!isset($errMSG))
		{
			$checkbid = $DBcon->prepare("SELECT * FROM product_users WHERE fish_id='$id' and userID='$uid'");
			$checkbid->bindParam(':uid',$fish_id);
			$checkbid->bindParam(':id',$id);
			$checkbid->execute();	
		if($checkbid->rowCount()==0)
			{
			$stmt=$DBcon->prepare('INSERT INTO product_users(userID,fish_id,suggested_price) VALUES(:uid,:fish_id,:suggested_price)');
			$stmt->bindParam(':uid',$uid);
			$stmt->bindParam(':fish_id',$fish_id);
			$stmt->bindParam(':suggested_price',$suggested_price);
			
			if($stmt->execute()){
				?>
				<script>
					alert('Your Bid was Successful,thank you');
				</script>

				<?php
				}
				else{
				$errMSG = "There was a problem placing your bid try again !";
					}
    
		}
	else
	{
	?>
	<script>
	alert('Your previous bid will be overwritten');

	// $yes=confirm('Are you sure you want to rewrite your previous bid?');
					
	</script>

	<?php
	$updateBid = $DBcon->prepare("UPDATE product_users 
									     SET suggested_price=:suggested_price, 
										     comment=:comment									     
								       WHERE userID=:uid and fish_id=:id");
			$updateBid->bindParam(':uid',$uid);
			$updateBid->bindParam(':suggested_price',$suggested_price);
			$updateBid->bindParam(':comment',$comment);
			$updateBid->bindParam(':id',$id);
			
				
			if($updateBid->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry Data Could Not Updated !";
			}

	}

}
}
}

?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $fish_title; ?></title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">

		<link rel="stylesheet" href="style.css">
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.min.js"></script>
</head>
<body>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Online Fish Auction</title>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="main.js"></script>
		
	</head>
<body>
<?php
if(!isset($_SESSION["uid"]))
{
?>
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
<?php 
}
else
{?>
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
  <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li><a href="addnew.php">
             
                <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Sell Fish
             </a>
      </li>
      <li><a href="products.php">
            
                <span class="glyphicon glyphicon-eye-open text-success" aria-hidden="true"></span> Manage My Products
            </a>
      </li>
       <li><a href="recycle.php">
            
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Recycle BIN
            </a>
      </li>

     
        <li><a href="inbox.php">
               
              INBOX<span class="badge text-info">
                 <?php
                $uid=$_SESSION['uid'];
                $noOfMessages=$DBcon->query("Select count(*) from product_users where userID='$uid'")->fetchColumn();
                echo $noOfMessages
                ?>
              </span>
                               
               
              </a>
      </li>
      
       <li><a href="">
              
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $_SESSION['name']; ?>
              </a>
      </li>
      <li><a href="logout.php">
             
                <span class="glyphicon glyphicon-off red" aria-hidden="true"></span> |Log Out
             </a>
      </li>     
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
<?php
}
?>

<div class="container">
<ol class="breadcrumb">
  <li><a href="index.php">Home</a></li>
  
  <li class="active">Bidding</li>
</ol>


<form method="post" enctype="multipart/form-data" class="form-horizontal">
			<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            

            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?>&nbsp;</strong> <a href="loginpage.php">Login/Sign Up</a>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
			}
			?> 

				<div class="panel panel-success">
					<div class="panel-heading"><?php echo $fish_title; ?></div>
					

					
					<div class="panel-body">
					<img class="center-block" src="fish_images/<?php echo $fish_image; ?>" height="100" width="100" />
					<div class="well well-sm">Description:<?php echo $fish_desc; ?><br />Fish Price:<?php echo $fish_price; ?><br />Quantity:<?php echo $fish_quantity; ?></div>
					
					</div>
					<div class="panel-footer">
					<button type="submit" name="place_bid" class="btn btn-default" id="un">
				<span class="glyphicon glyphicon-save"></span> Bid As It IS
				</button>
				<button id="bidbutton" type="submit" name="btn_bid" class="btn btn-default">
				<span class="glyphicon glyphicon-bid"></span> BID
				</button>
				<a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-backward"></span> cancel </a>
				</div>
				</div>
				<input type="hidden" value="<?php echo $fish_id;?>" name="fish_id">
				<input type="hidden" value="<?php echo $fish_price;?>" name="fish_price">
				
				
</form>
<form method="post" style="display:none;" id="bid">
			
	<div class="input-group">
  <span class="input-group-addon" >Kshs.</span>

  <input type="number" name="bidprice" id="bidprice" class="form-control" placeholder="like 20" required>
</div>

										<textarea name="comment" id="comment" placeholder="Say something about the price" class="form-control" rows="5" required></textarea> 
										<input type="hidden" value="<?php echo $fish_id;?>" name="fish_id">
										<button name="placebid" id="placebid" class="btn btn-default">Bid</button>
										<input type="hidden" value="<?php echo $time_bid;?>" name="time_bid">

									

<br />
<div class="container">
    <div class="row">
        
                       
                        <h6 class="text-muted time">Time</h6>
                       <!--  Fetch Commments and time -->
                        <?php 
                      
        $postedcomments = $DBcon->prepare("SELECT user_info.user_id,user_info.first_name,user_info.last_name,product_users.comment,product_users.fish_id,product_users.time_bid,product_users.suggested_price from product_users inner join user_info on user_info.user_id=product_users.userID  where fish_id='$id'order by time_bid desc");
		$postedcomments->execute();


		while ($postedcommentsRow = $postedcomments->fetch()) {

                        $date_a = new DateTime('now');
				$date_b = new DateTime($postedcommentsRow['time_bid']);

				$interval = date_diff($date_a,$date_b);

				$correctTime = '';

				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1 && $interval->format('%i') < 1) {
					$correctTime = $interval->format('%s secs ago');
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1) {
					if ($interval->format('%i') < 1 && $interval->format('%s') >= 0) {
						$correctTime = $interval->format('%s secs ago');
					}
					elseif ($interval->format('%i') == 1) {
						$correctTime = $interval->format('%i min ago');
					}else{
						$correctTime = $interval->format('%i mins ago');
					}
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') > 0) {
					$correctTime = $interval->format('%h hours %i mins ago');
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') >= 1) {
					if ($interval->format('%d') == 1) {
						$correctTime = $interval->format('Yesterday at %h:%i hrs');
					}else{
						$correctTime = $interval->format('%d days ago');
					}
				}
				if ($interval->format('%m') >= 1) {
					$correctTime = $interval->format('%m months ago');
				}?>
<div class="panel panel-default">
<div class="panel-heading"><?php echo  $postedcommentsRow['first_name']." ".$postedcommentsRow['last_name'];
 ?>
	<span class="label label-info">Kshs.<?php echo  $postedcommentsRow['suggested_price'];
 ?></span>
</div>
<div class="panel-body">
<?php echo  $postedcommentsRow['comment'];
 ?>
<a href="#"><span class="badge pull-right"><?php echo $correctTime;?></span></a><br />
</div>
</div>
	<?php			
  
            }
                        ?>
</div>
</div>

</form>

<div class="col-md-2" id="relateditems">
<div class="col-md-4">
<h3><span class="label label-info">You may also like this</span></h3>
<?php

$currIDfish= array($id);
$cat=$view_row['fish_cat'];

		// extract all fish under the same category
	$allFishIds = array();
	$relatedFish = $DBcon->prepare("SELECT * from products where fish_cat = '$cat' ");
	$relatedFish->execute();

	while ($relatedFishRow = $relatedFish->fetch()) {
			# code...
		array_push($allFishIds , $relatedFishRow['fish_id']);

	}

	$diffIDS = array_diff($allFishIds , $currIDfish);
	foreach ($diffIDS as $value) {
			# code...
		
		$relatedQuery = $DBcon->prepare("SELECT * from products where fish_id = '$value'");
		$relatedQuery->execute();
		
		while ($relatedQueryRow = $relatedQuery->fetch()) 
			# code...?
			echo $relatedQueryRow['fish_title'];
			?>
			<img src="fish_images/<?php echo $relatedQueryRow['fish_image']; ?>" height="100" width="100" />

<a class="btn btn-danger" href="order.php?fish_id=<?php echo $value; ?>"  ><span class="glyphicon glyphicon-buy"></span> Bid</a><br /><br />
		<?php
			
		
	}
?>

</div>
</div>
</div>
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
          Click <a href="signup.php" class="alert-link ">If youre not registered</a>
          Forgot Password <a href="forgot.php" class="alert-link ">Forgot Password?</a>
      </div>
      </div>
    </div>
  </div>
</div>
<script src="js/jquery.min.js"></script>
</body>

</html>
<script type="text/javascript">
	$(function(){
		$("#bidbutton").click(function(event) {
			/* Act on the event */
			event.preventDefault();
			$("#bid").show(2000);
			$("#bidbutton").hide('slow/400/fast', function() {
				
			});
			$("#un").hide('fast', function() {
				
			});
		});
	})
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

