<?php
include 'db.php';
if(isset($_GET['county_code']) && !empty($_GET['county_code']))
{
  $id = $_GET['county_code'];
  
}
else
{
  header("Location: index.php");
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

<div class="container">
<ol class="breadcrumb">
  <li><a href="index.php">Home</a></li>
  <li class="active"><a >Fish Points</a></li>
  
</ol>
<?php
$stmt_view = $DBcon->prepare("SELECT * FROM counties WHERE county_code ='$id'");
$stmt_view->execute();
while(($countyRow = $stmt_view->fetch()))
{?>
<div class="alert alert-success" role="alert"><?php echo $countyRow['county_name'];?></div> 
<?php
}


  ?>
   

      <div class="col-md-4">
         <div class="list-group">
 <h3><span class="label label-info">Fish POINTS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-map-marker"></span></span>
     </h3>
 
  
</div>

                <div class="form-group">  
                     <div class="input-group">  
                          <span class="input-group-addon">Search</span>  
                          <input type="text" name="search_text" id="search_text" placeholder="Searches ALL" class="form-control" />  
                     </div>  
                </div> 
        <div id="locations">
       
                  <?php     
        require_once 'db.php';   
        $query = "SELECT fish_points.ward_code,fish_points.location_desc,wards.ward_code,wards.ward_name from fish_points  inner join wards on wards.ward_code=fish_points.ward_code where fish_points.county_code='$id' ";
        $stmt = $DBcon->prepare( $query );
      $stmt->execute();
             if($stmt->rowCount()==0)
                      {
                        ?>
        <div class="col-xs-12">
          <div class="alert alert-warning">
              <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry, No Fish Points Available
            </div>
        </div>
        <?php 
                      }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
          echo $row['ward_name'];
          echo $row['location_desc'];
        ?>
        
       
          

        <?php
      }

   ?>
          <div class="panel-footer">&copy; Online Fish Store</div>
        </div>
      </div>
  
</body>
</html>
 


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
                     url:"search_fishpoints.php",  
                     method:"post",  
                     data:{search:txt},  
                     dataType:"text",  
                     success:function(data)  
                     {  
                          $('#locations').html(data);  
                     }  
                });  
           }  
            
      });  

</script>
