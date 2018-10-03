
<?php
session_start();
if(!isset($_SESSION["uid"])){
  
   header("location:index.php");
}
date_default_timezone_set("Africa/Nairobi");
require_once 'db.php';



?>
<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>my bids</title>
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
               
              INBOX <span class="badge text-info">
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

<div class="container">
<ol class="breadcrumb">
  <li><a href="index.php">Home</a></li>
  
  <li class="active">My bids</li>
</ol>
<?php
$uid=$_SESSION['uid'];
$selectmybids=$DBcon->prepare("SELECT products.fish_id,products.userID,products.fish_title,products.fish_desc,products.location,products.fish_price,product_users.userID, product_users.fish_id, product_users.suggested_price,product_users.comment,user_info.mobile  FROM product_users inner join products on products.fish_id=product_users.fish_id 
inner join user_info on user_info.user_id=products.userID
 WHERE product_users.userID=:uid");

$selectmybids->bindParam(':uid',$uid);
$selectmybids->execute();

while ($selectmybidsrow = $selectmybids->fetch()) {
	?>


<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $selectmybidsrow['fish_title'];?></h3>/Khs.<?php echo $selectmybidsrow['fish_price'];?>

  </div>
  <div class="panel-body">
    <?php echo $selectmybidsrow['comment'];?><br />

     Your Price:<?php echo $selectmybidsrow['suggested_price'];?>
     BID WON BY:<?php 
     $fid=$selectmybidsrow['fish_id'];

     $me=$DBcon->prepare("select * from product_users where fish_id='$fid' and is_selected=1 and userID='$uid'");
     $me->execute();

     $won=$DBcon->prepare("select * from product_users where fish_id='$fid' and is_selected=1");
     $won->execute();
     if ($won->rowCount()==0)
     {
     	echo "Pending";
     }
     else
     {
     	if($won->rowCount()>0 && $me->rowCount()==1)
     	{
     		?>

     		<button type="button" class="btn btn-success btn-md">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 
</button>
<div class="alert alert-success" role="alert">Congratulations!, you won the bid</div>
Call me on:<span class="glyphicon glyphicon-phone" aria-hidden="true"></span><h5><?php echo $selectmybidsrow['mobile']; ?></h5>
for business, remember, if you do business well. You will be rated High and get more advantage on your bids

     		<?php

     	}
     	else
     	{
     			?>

     		<button type="button" class="btn btn-danger btn-md">
  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> 
</button>
<div class="alert alert-danger" role="alert">Sorry, someone else has been chosen for the deal, try next time</div>

     		<?php
     	}
     	
     }

     ?>


  </div>
</div>
	<?php
	}


?>



<script src="js/jquery.min.js"></script>
</body>

</html>
<script type="text/javascript">
	
</script>

