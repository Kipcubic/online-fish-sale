<?php
session_start();
if(!isset($_SESSION["uid"])){
  ?>
  <script>
  alert("Login first");
  </script>
  <?php
   header("location:index.php");
}
	require_once 'db.php';
	
	
	if(isset($_GET['delete_id']))
	{

		$delID=$_GET['delete_id'];
		$user=$_SESSION["uid"];
		
		
		$delete_product = $DBcon->prepare("UPDATE products 
									     SET status=1									     
								       WHERE userID=:user and fish_id=:delID");
			$delete_product->bindParam(':user',$user);
			$delete_product->bindParam(':delID',$delID);
			
				
			if($delete_product->execute()){
				?>
                <script>
				alert('Deleted');
				
				</script>
                <?php
			  }
		
		header("Location: products.php");
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<title>My products</title>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
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
  
  <li class="active">My Products</li>
</ol>
<br />


<?php
	$user=$_SESSION["uid"];
	$stmt = $DBcon->prepare("SELECT * FROM products  where userID='$user' and status=0 ORDER BY date_created DESC");
	$stmt->execute();
	
	if($stmt->rowCount() > 0)
	{
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			?>
			<div class="col-md-4">
				<p class="page-header"><?php echo $fish_title."&nbsp;/&nbsp;".$fish_desc; ?></p>
				<img src="fish_images/<?php echo $row['fish_image']; ?>" class="img-rounded" width="150px" height="100px" />
				<p class="page-header">
				<span>
				<a class="btn btn-info" href="editproduct.php?edit_id=<?php echo $row['fish_id']; ?>" title="click for edit" onclick="return confirm('sure to edit ?')"><span class="glyphicon glyphicon-edit"></span> Edit</a> 
				<a class="btn btn-danger" href="?delete_id=<?php echo $row['fish_id']; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-remove-circle"></span> Delete</a><br /><br />
				<a class="btn btn-success" href="bid.php?bid_id=<?php echo $row['fish_id']; ?>" title="click for edit" onclick="return confirm('sure to approve ?')"><span class="glyphicon glyphicon-edit"></span> Approve bids</a> 
				</span>
				</p>
			</div>       
			<?php
		}
	}
	else
	{
		?>
        <div class="col-xs-12">
        	<div class="alert alert-warning">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Active Products Online
            </div>
        </div>
        <?php
	}
	
?>
</div>	







<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>