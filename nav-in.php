<?php
session_start();
include 'db.php';
if(!isset($_SESSION["uid"])){
	header("location:index.php");
}
?>
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

<div class="container-fluid">

    <div class="row">

      <div class="col-md-2 col-xs-12">

                <div class="form-group">  
                     <div class="input-group">  
                          <span class="input-group-addon">Search</span>  
                          <input type="text" name="search_text" id="search_text" placeholder="Searches ALL" class="form-control" />  
                     </div>  
                </div> 
        <div id="get_category">
        <h3><span class="label label-info ">CATEGORIES</span></h3>
                         <?php 
                          require_once 'db.php';
                          $query = "SELECT * FROM categories";
                            $stmt = $DBcon->prepare( $query );
                            $stmt->execute();
                            if($stmt->rowCount()==0)
                            {
                              ?>
                    <div >
                      <div class="alert alert-warning">
                           <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry, No categories at the Moment
                       </div>
                    </div>
        </div>
        <?php 
                      }
                    
                      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                      ?>

                <div  class="nav nav-pills nav-stacked">

                <li ><a href="#" data-id="<?php echo $row['cat_id']; ?>" id="getcat" class="category"><?php echo $row['cat_title'];?></a></li>
              </div>
        <?php } ?>
        </div>
        
        <div id="get_brand">
        </div>
        
      </div>
      <div class="col-md-8 col-xs-12">
        <div class="row">
          <div class="col-md-12 col-xs-12" id="product_msg">
          </div>
        </div>
        <div class="panel panel-info">
          <div class="panel-heading">Fish Products</div>
          <div class="panel-body">
            <div id="get_products">
               <?php     
        require_once 'db.php';   
        $query = "SELECT * FROM Products where status=0";
        $stmt = $DBcon->prepare( $query );
      $stmt->execute();
             if($stmt->rowCount()==0)
                      {
                        ?>
        <div class="col-xs-12">
          <div class="alert alert-warning">
              <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry, There No Fish products Uploaded yet
            </div>
        </div>
        <?php 
                      }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        
        <div class="col-md-4">
          <div class="panel panel-info">
          <div class="panel-heading"><strong><?php echo $row['fish_title'];?> </strong>
  <span class="glyphicon glyphicon-map-marker pull-right" aria-hidden="true"><?php echo $row['location'];?></span>
</div>
          <span class="label label-info">New</span>
                <div class='panel-body'>    
                <img src="fish_images/<?php echo $row['fish_image']; ?>" class="img-rounded" width="150px" height="100px" />
                </div>
                <div class="panel-heading">@Kshs.<?php echo $row['fish_price'];?>
                <div>
                <a class="#" href="like.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span><?php $likes = $DBcon->query("select count(*) from products_likes where fish_id='".$row['fish_id']."'")->fetchColumn(); 
echo $likes;  ?></a>&nbsp;</a>
                  <button data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['fish_id']; ?>" id="getfish" class="btn btn-sm btn-info">View </button>
                  <a class="btn btn-danger" href="order.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-buy"></span> Bid</a>
                </div>
                </div>
             </div>
            </div>
          

        <?php
      }

   ?>

            </div>
        
          </div>
          <div class="panel-footer">&copy; Project BSE</div>
        </div>
      </div>
      <div class="col-md-2">
      <div class="list-group">
 <h3><span class="label label-info">Fish POINTS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-map-marker"></span></span>
     </h3>
  <?php
  $query = "SELECT * FROM counties limit 10";
        $stmt = $DBcon->prepare( $query );
      $stmt->execute();
             if($stmt->rowCount()==0)
                      {
                        ?>
        <div class="col-xs-12">
          <div class="alert alert-warning">
              <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No counties Present
            </div>
        </div>
        <?php 
                      }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        

  <a href="fish_point.php?county_code=<?php echo $row['county_code']; ?>" class="list-group-item"><?php echo $row['county_name'];?></a>
<?php }?>
<p><a class="btn btn-primary btn-md" href="morepoints.php" role="button">More</a></p>
</div>
  <h3><span class="label"><a href="register_fishPoint.php">Add Fish Point &nbsp;</a><span class="glyphicon glyphicon-plus"></span></span>  
      </h3>
</div>
    </div>
  </div>
</body>
</html>
 
<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog"> 
     <div class="modal-content">  
   
        <div class="modal-header"> 
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
           <h4 class="modal-title">
           <i class="glyphicon glyphicon-user"></i>
           </h4> 
        </div> 
            
        <div class="modal-body">                     
           <div id="modal-loader" style="display: none; text-align: center;">
           <!-- ajax loader -->
           <img src="ajax-loader.gif">
           </div>
                            
                                    
           <div id="dynamic-content"></div>
        </div> 
                        
        <div class="modal-footer"> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        </div> 
                        
    </div> 
  </div>
</div>
<script>

$(document).ready(function(){

    $(document).on('click', '#getfish', function(e){
  
     e.preventDefault();
  
     var id = $(this).data('id'); // get id of clicked row
  
     $('#dynamic-content').html(''); 
     $('#modal-loader').show();      
 
     $.ajax({
          url: 'fetch.php',
          type: 'POST',
          data: {viewFish:1,id:id},
          dataType: 'html'
     })
     .done(function(data){
          console.log(data); 
          $('#dynamic-content').html(''); // blank before load.
          $('#dynamic-content').html(data); // load here
          $('#modal-loader').hide(); // hide loader  
     })
     .fail(function(){
          $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
          $('#modal-loader').hide();
     });

    });
    $(document).on('click', '#getcat', function(e){
  
     e.preventDefault();
  
     var id = $(this).data('id'); // get id of clicked row
  
    
 
     $.ajax({
          url: 'fetch.php',
          type: 'POST',
          data: {viewCat:1,id:id},
          dataType: 'html'
     })
     .done(function(data){
          console.log(data); 
          
          $('#get_products').html(data); // load here
          
     })
     .fail(function(){
          $('#get_products').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
          
     });

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
$('#search_text').keyup(function(){  
           var txt = $(this).val();  
           if(txt != '')  
           {  
                $.ajax({  
                     url:"search.php",  
                     method:"post",  
                     data:{search:txt},  
                     dataType:"text",  
                     success:function(data)  
                     {  
                          $('#get_products').html(data);  
                     }  
                });  
           }  
            
      }); 


</script>





















































