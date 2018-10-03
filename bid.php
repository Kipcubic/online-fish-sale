
<?php
session_start();
if(!isset($_SESSION["uid"])){
  
   header("location:index.php");
}
date_default_timezone_set("Africa/Nairobi");
require_once 'db.php';

if(isset($_GET['bid_id']) && !empty($_GET['bid_id']))
{
	$fish_id = $_GET['bid_id'];
	$uid=$_SESSION['uid'];
	$stmt_view = $DBcon->prepare("SELECT * FROM products WHERE fish_id =:fish_id and userID='$uid' ");
	$stmt_view->execute(array(':fish_id'=>$fish_id));
	$view_row = $stmt_view->fetch(PDO::FETCH_ASSOC);
	$cat=$view_row['fish_cat'];		
	extract($view_row);
}
else
{
	header("Location: index.php");
}

if(isset($_POST['approve']))
{
	

	
	$uid=$_SESSION['uid'];
	$checkbid = $DBcon->prepare("SELECT * FROM product_users WHERE fish_id=:fish_id and is_selected=1");
	
	$checkbid->bindParam(':fish_id',$fish_id);
	$checkbid->execute();	
	if($checkbid->rowCount()==0)
		{
			$approve=$DBcon->prepare('UPDATE product_users
			set is_selected=1
			where  fish_id=:fish_id');
			$productsUpdate=$DBcon->prepare('UPDATE products
			set status=1
			where  fish_id=:fish_id');
			$productsUpdate->bindParam(':fish_id',$fish_id);
			$productsUpdate->execute();
			// $approve->bindParam(':uid',$uid);
			$approve->bindParam(':fish_id',$fish_id);
						
			if($approve->execute()){

				?>
				<script>
					alert('You approved this bid');
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
	alert('Sorry, You have already approved someone for this bid ');

	// $yes=confirm('Are you sure you want to rewrite your previous bid?');
					
	</script>

	<?php

	}	

	}

	
?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $fish_title; ?></title>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="main.js"></script>
		
</head>
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
               
              BIDS PLACED <span class="badge text-info">
                 <?php
                $uid=$_SESSION['uid'];
                $noOfMessages=$DBcon->query("Select count(*) from product_users where userID='$uid'")->fetchColumn();
                echo $noOfMessages
                ?>
              </span>
                               
               
              </a>
      </li>
      <li ><a href="inbox.php">
              
                Approved Bids <span class="badge"  aria-hidden="true"> 
                <?php
                $uid=$_SESSION['uid'];
                $noOfMessages=$DBcon->query("Select count(*) from product_users where userID='$uid' and is_selected=1")->fetchColumn();
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
<div class="container">


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
				
				Fish Name: <label><?php echo $fish_title; ?></label>
				<br />
				Description: <label><?php echo $fish_desc; ?></label>
				<br />
				Price per Fish: <label><?php echo $fish_price; ?></label>
				<br />
				<label><?php echo $fish_quantity; ?></label>
				<br />



				<input type="hidden" value="<?php echo $fish_id;?>" name="fish_id">
				<input type="hidden" value="<?php echo $fish_price;?>" name="fish_price">
				<img src="fish_images/<?php echo $fish_image; ?>" height="100" width="150" /></p>
				</form>
				<div>
				<?php 
					$checkbid = $DBcon->prepare("SELECT user_info.user_id,user_info.first_name,user_info.last_name,product_users.comment,product_users.fish_id,product_users.time_bid,product_users.suggested_price from product_users inner join user_info on user_info.user_id=product_users.userID  WHERE fish_id=:fish_id and is_selected=1");	
	$checkbid->bindParam(':fish_id',$fish_id);

	$checkbid->execute();
	$row = $checkbid->fetch();
	?><h2>Bid Selected:</h2>
<?php
echo $row['first_name']." ".$row["last_name"];
echo $row['suggested_price'];



	 ?>
				</div>

<form method="post" >									
<div class="container">
    <div class="row">
        
  
                       <!--  Fetch Commments and time -->
                        <?php 
                      
        $postedcomments = $DBcon->prepare("SELECT user_info.user_id,user_info.first_name,user_info.last_name,product_users.comment,product_users.fish_id,product_users.time_bid,product_users.suggested_price from product_users inner join user_info on user_info.user_id=product_users.userID  where fish_id='$fish_id'order by time_bid desc");
		$postedcomments->execute();
		if($postedcomments->rowCount()==0)
		{
			?>
			<div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp;Sorry, No Bids Yet
            </div>
            <?php
		}


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
 <?php 
 $checkbid = $DBcon->prepare("SELECT * FROM product_users WHERE fish_id=:fish_id and is_selected=1");	
	$checkbid->bindParam(':fish_id',$fish_id);

	$checkbid->execute();

	if($checkbid->rowCount()==0)
{
 ?>
 <button id="approve" type="submit" name="approve" class="btn btn-success">
<span class="glyphicon glyphicon-ok" ></span> Aprrove
</button>
<?php
}
else
{

?>
<button id="approve" type="submit" name="approve" class="btn btn-danger">
<span class="glyphicon glyphicon-ok" disabled></span> Selection Done
</button>
<?php  }?>
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
</script>

