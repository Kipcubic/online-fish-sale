<?php

	error_reporting( ~E_NOTICE );
	session_start();

	require_once 'db.php';
	$user=$_SESSION['uid'];
	
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$edit_id = $_GET['edit_id'];
		$stmt_edit = $DBcon->prepare('SELECT * FROM products WHERE fish_id=:edit_id');
		$stmt_edit->execute(array(':edit_id'=>$edit_id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: products.php");
	}
	
	
	
	if(isset($_POST['save_changes']))
	{
		$fish_title = $_POST['fish_title'];
		$fish_desc = $_POST['fish_desc'];
		$fish_price = $_POST['fish_price'];
		$fish_location = $_POST['fish_location'];
		$fish_quantity = $_POST['fish_quantity'];
		$fish_location = $_POST['location'];


		$imgFile = $_FILES['fish_image']['name'];
		$tmp_dir = $_FILES['fish_image']['tmp_name'];
		$imgSize = $_FILES['fish_image']['size'];
					
		if($imgFile)
		{
			$upload_dir = 'fish_images/'; // upload directory	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
			$userpic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['userPic']);
					move_uploaded_file($tmp_dir,$upload_dir.$fish_image);
				}
				else
				{
					$errMSG = "Sorry, your file is too large it should be less then 5MB";
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}	
		}
		else
		{
			// if no image selected the old image remain as it is.
			$fish_image = $edit_row['fish_image']; // old image from database
		}	
						
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DBcon->prepare('UPDATE products
									     SET fish_title=:fish_title, 
										     fish_price=:fish_price, 
										     fish_image=:fish_image,
										     location=:fish_location,
										     fish_quantity=:fish_quantity,
										     fish_desc=:fish_desc
								       WHERE userID=:user and fish_id=:edit_id');
			$stmt->bindParam(':fish_title',$fish_title);
			$stmt->bindParam(':fish_price',$fish_price);
			$stmt->bindParam(':fish_image',$fish_image);
			$stmt->bindParam(':fish_desc',$fish_desc);
			$stmt->bindParam(':fish_location',$fish_location);
			$stmt->bindParam(':fish_quantity',$fish_quantity);		
			$stmt->bindParam(':edit_id',$edit_id);
			$stmt->bindParam(':user',$user);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry product Could Not Updated !";
			}
		
		}
		
						
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editing products</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<!-- custom stylesheet -->
<link rel="stylesheet" href="style.css">

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script src="jquery-1.11.3-jquery.min.js"></script>
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


	

<div class="clearfix"></div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>
   
    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">Fish Name</label></td>
        <td><input class="form-control" type="text" name="fish_title" value="<?php echo $fish_title; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Fish Description</label></td>
        <td><input class="form-control" type="text" name="fish_desc" value="<?php echo $fish_desc; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Image</label></td>
        <td>
        	<p><img src="fish_images/<?php echo $fish_image; ?>" height="150" width="150" /></p>
        	<input class="input-group" type="file" name="fish_image" accept="image/*" />
        </td>
    </tr>
     <tr>
    	<td><label class="control-label">Price</label></td>
        <td><input class="form-control" type="number" name="fish_price" value="<?php echo $fish_price; ?>" required /></td>
    </tr>
      <tr>
    	<td><label class="control-label">Quantity</label></td>
        <td><input class="form-control" type="number" name="fish_quantity" value="<?php echo $fish_quantity; ?>" required /></td>
    </tr>
      <tr>
    	<td><label class="control-label">Location</label></td>
        <td><input class="form-control" type="text" name="location" value="<?php echo $location; ?>" required /></td>
    </tr>

    
    <tr>
        <td colspan="2">
        <button type="submit" name="save_changes" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> Save Changes
        </button>
        
        <a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-backward"></span>Cancel</a>
        
        </td>
    </tr>
    
    </table>
    
</form>




</div>
</body>
</html>