<?php  
include 'dbc.php';
 $sql = "SELECT * FROM fish_points WHERE location_desc LIKE '%".$_POST["search"]."%'";  
 $result = mysqli_query($con, $sql);  
 if(mysqli_num_rows($result) > 0)  
 {  
     
      while($row = mysqli_fetch_array($result))  
      {  
        ?>
        
          <?php echo $row['location_desc'];?>
               
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