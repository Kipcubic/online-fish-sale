
<?php
include 'dbc.php';
$query = "SELECT * FROM counties";
$result = $con->query($query);

while($row = $result->fetch_assoc()){
  $categories[] = array("county_code" => $row['county_code'], "val" => $row['county_name']);
}

$query = "SELECT * FROM wards";
$result = $con->query($query);

while($row = $result->fetch_assoc()){
  $subcats[$row['county_code']][] = array("ward_code" => $row['ward_code'], "val" => $row['ward_name']);
}

$jsonCats = json_encode($categories);
$jsonSubCats = json_encode($subcats);


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
    <body onload='loadCategories()'>

      <div class="container">
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>

          <li class="active">Registering fish point</li>
        </ol>
        <form method="post">
          <label class="label-default">Fish Point Registration</label><br />
          <label class="form-label">Select County: </label>
          <select name="county" class="form-control" id='categoriesSelect'>
          </select>
          <label class="form-label">Select Ward: </label>
          <select name="ward" class="form-control" class="" id='subcatsSelect'>
          </select>
          Location Descrtipion: <textarea class="form-control" placeholder="Use Words that are common to the place youre referring to" id="desc"></textarea>
          <button id="regPoint" type="submit" name="btn_bid" class="btn btn-default">
            <span class="glyphicon glyphicon-bid"></span> Register
          </form>
        </div> 
      </body>
      </html>
      <script type='text/javascript'>
        <?php
        echo "var categories = $jsonCats; \n";
        echo "var subcats = $jsonSubCats; \n";
        ?>
        function loadCategories(){
          var select = document.getElementById("categoriesSelect");
          select.onchange = updateSubCats;
          for(var i = 0; i < categories.length; i++){
            select.options[i] = new Option(categories[i].val,categories[i].county_code);          
          }
        }
        function updateSubCats(){
          var catSelect = this;
          var catid = this.value;
          var subcatSelect = document.getElementById("subcatsSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        for(var i = 0; i < subcats[catid].length; i++){
          subcatSelect.options[i] = new Option(subcats[catid][i].val,subcats[catid][i].ward_code);
        }
      }
      $("#regPoint").click(function(event){
        event.preventDefault();
        var county_code=$('#categoriesSelect').val();
        var ward_code=$('#subcatsSelect').val();
        var desc=$('#desc').val();

                $.ajax({
                    type: "POST",
                    url: "insert_points.php",
                    data: "county_code=" + county_code+ "&ward_code=" + ward_code+"&desc="+desc,
                    success: function(data) {
                      alert(data); 
                    }
                });
       
      });
    </script>