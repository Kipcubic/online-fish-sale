<?php  
include 'dbc.php';
 $sql = "SELECT * FROM products WHERE fish_title LIKE '%".$_POST["search"]."%'";  
 $result = mysqli_query($con, $sql);  
 if(mysqli_num_rows($result) > 0)  
 {  
     
      while($row = mysqli_fetch_array($result))  
      {  
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
                <a class="#" href="like.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">3</a>&nbsp;</span></a>&nbsp;</a>
                  <button data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['fish_id']; ?>" id="getfish" class="btn btn-sm btn-info">View </button>
                  <a class="btn btn-danger" href="order.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-buy"></span> Bid</a>
                </div>
                </div>
             </div>
            </div>
        <?php
                     
               
           
      }  
     
 }  
 else  
 {  
      echo 'No results matches your search'; 
      ?>
      <a href="index.php">Close</a>
      <?php 
 }  
 ?>  