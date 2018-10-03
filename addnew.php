<?php
session_start();
if(!isset($_SESSION["uid"])){
	header("location:index.php");
}

	error_reporting( ~E_NOTICE ); // avoid notice
	
	include 'db.php';
	
	if(isset($_POST['btnsave']))
	{
  $uid = $_SESSION["uid"];
  $fish_cat = $_POST['fish_cat'];// 
  $fish_title = $_POST['fish_title'];// 
  $fish_desc = $_POST['fish_desc'];// 
   $fish_price = $_POST['fish_price'];// 
   $fish_qty = $_POST['fish_qty'];//
   
   $location = $_POST['location'];//


		
  $imgFile = $_FILES['fish_img']['name'];
  $tmp_dir = $_FILES['fish_img']['tmp_name'];
  $imgSize = $_FILES['fish_img']['size'];
		
		if($fish_qty<=0)
    {
      $errMSG="Quantity cant be less than 0";
    }

		if(empty($fish_qty)){
			$errMSG = "Please Enter Fish Quantity";
		}
		else if (empty($imgFile)){
			$errMSG = "Please Select Image File.";
		}
		else
		{
			$upload_dir = 'fish_images/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$fish_img = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$fish_img);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DBcon->prepare('INSERT INTO products(userID,fish_cat,fish_title,fish_price,fish_desc,fish_image,fish_quantity,location) VALUES(:uid,:fish_cat,:fish_title,:fish_price,:fish_desc,:fish_img,:fish_qty,:location)');
			$stmt->bindParam(':uid',$uid);
			$stmt->bindParam(':fish_cat',$fish_cat);
			$stmt->bindParam(':fish_title',$fish_title);
			$stmt->bindParam(':fish_price',$fish_price);
			$stmt->bindParam(':fish_desc',$fish_desc);
			$stmt->bindParam(':fish_img',$fish_img);
			$stmt->bindParam(':fish_qty',$fish_qty);
			
			$stmt->bindParam(':location',$location);

			
			if($stmt->execute())
			{
				$successMSG = "Fished Added for Sale";
				header("refresh:5;index.php"); // redirects image view page after 5 seconds.
			}
			else
			{
				$errMSG = "Error while Uploading your Fish Product";
			}
		}
	}
?>
<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fish</title>
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
  
  <li class="active">Sell Fish</li>
</ol>
	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
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

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
	
    <label class="control-label">Fish category</label> 
    <select class="form-control" type="text" name="fish_cat">
    <?php $Category=$DBcon->prepare("select * from categories ");
    $Category->execute();

    while ($CategoryRow = $Category->fetch()) {
                                    ?>
  <option class="form-control" type="text" name="fish_cat" value="<?php  echo $CategoryRow['cat_id'] ?>"> 
<?php  echo $CategoryRow['cat_title']  ?></option>
                    
<?php                 
    }?>
    </select>
    <label class="control-label">Fish Name</label><input class="form-control" type="text" name="fish_title" placeholder="Like Mbuta" value="<?php echo $fish_title; ?>" />
    <label class="control-label">Fish Description</label><input class="form-control" type="text" name="fish_desc" placeholder="Description" value="<?php echo $fish_desc; ?>" />
    <label class="control-label">Price</label><input class="form-control" type="number" name="fish_price" placeholder="Kshs. 100" value="<?php echo $fish_price; ?>" />
    <label class="control-label">Quantity</label><input class="form-control" type="number" name="fish_qty" placeholder="Enter Username" value="<?php echo $fish_qty; ?>" />
     
      <label class="control-label">Location</label><input class="form-control" type="text" name="location" placeholder="Describe fish Location" value="<?php echo $location; ?>" />
    <label class="control-label">Fish  Image</label><input class="input-group" type="file" name="fish_img" accept="image/*" />
    <br />
    <button type="submit" name="btnsave" class="btn btn-success">
        <span class="glyphicon glyphicon-upload"></span> &nbsp; Sell
     </button>
     
    
</form>



<div class="alert alert-info">
    <strong>Online Fish Auction</strong></a>
</div>

</div>



	


<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="validate.js"></script>

</body>
</html>