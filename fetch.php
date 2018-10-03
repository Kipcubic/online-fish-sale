<?php
   
 require_once 'db.php';
 
 if (isset($_POST['viewFish'])) {
   
 $id = $_POST['id'];
 $query = "SELECT * FROM products WHERE fish_id=:id";
 $stmt = $DBcon->prepare( $query );
 $stmt->execute(array(':id'=>$id));
 $row=$stmt->fetch(PDO::FETCH_ASSOC);
 extract($row);
 
 ?>
   
 <div class="table-responsive">
 <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="fish_images/<?php echo $fish_image; ?>" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="fish_images/<?php echo $fish_image; ?>" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    ...
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
  
 <table class="table table-striped table-bordered">
 <tr>
 <th>Fish Name</th>
 <td><?php echo $fish_title; ?></td>
 </tr>
 <tr>
 <th>PRICE</th>
 <td><?php echo $fish_price; ?></td>
 </tr>
 <tr>
 <th>Fish Description</th>
 <td><?php echo $fish_desc; ?></td>
 </tr>
 <tr>
 <th>Quantity</th>
 <td><?php echo $fish_quantity; ?></td>

 </tr>

 </table>
    <a class="btn btn-danger" href="order.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-buy"></span> Buy</a>
 </div>
   <?php } 


 if (isset($_POST['viewCat'])) {
   
 $id = $_POST['id'];
 $query = "SELECT * FROM Products where fish_cat='$id'";
        $stmt = $DBcon->prepare( $query );

        $stmt->execute();
        if($stmt->rowCount() > 0)
        {


        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        
        <div class="col-md-2" id="get_products">
          <div class="panel panel-info">
          <div class="panel-heading"><?php echo $row['fish_title'];?></div>
                <div class='panel-body'>
                  <img src="fish_images/<?php echo $row['fish_image']; ?>" class="img-rounded" width="150px" height="100px" />
                </div>


                <div class="panel-heading">@Kshs.<?php echo $row['fish_price'];?>
                <div>
                  <button data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['fish_id']; ?>" id="getfish" class="btn btn-sm btn-info">View </button>
                  <button data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['fish_id']; ?>" id="getfish" class="btn btn-sm btn-danger">Buy</button>
                </div>
                </div>
             </div>
            </div>
            
       
        
        <?php
      }
  }
  else
  {
    ?>
        <div class="col-xs-12">
          <div class="alert alert-warning">
              <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ...
            </div>
        </div>
        <?php 

  }
 }
 
if(isset($_POST['btnsave']))
  {
  $uid = $_SESSION["uid"];
  $fish_cat = $_POST['fish_cat'];// 
  $fish_title = $_POST['fish_title'];// 
  $fish_desc = $_POST['fish_desc'];// 
   $fish_price = $_POST['fish_price'];// 
   $fish_qty = $_POST['fish_qty'];//

    
  $imgFile = $_FILES['fish_img']['name'];
  $tmp_dir = $_FILES['fish_img']['tmp_name'];
  $imgSize = $_FILES['fish_img']['size'];
    
    
    if(empty($fish_cat)){
      $errMSG = "Please Enter Fish Category";
    }
    else if(empty($fish_qty)){
      $errMSG = "Please Enter Fish Quantity";
    }
    else if(empty($imgFile)){
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
        if($imgSize < 5000000)        {
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
      $stmt = $con->prepare('INSERT INTO products(userID,fish_cat,fish_title,fish_desc,fish_price,fish_quantity,fish_image) VALUES(:uid,:fish_cat, :fish_title, :fish_desc, :fish_price, :fish_qty, :fish_img)');
      $stmt->bindParam(':uid',$uid);
      $stmt->bindParam(':fish_cat',$fish_cat);
      $stmt->bindParam(':fish_title',$fish_title);
      $stmt->bindParam(':fish_desc',$fish_desc);
      $stmt->bindParam(':fish_price',$fish_price);
      $stmt->bindParam(':fish_img',$fish_img);
      $stmt->bindParam(':fish_qty',$fish_qty);
      
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