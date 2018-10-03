<div id="info_msgs">
</div>
      <div class="col-md-2 col-xz-12" >
                     <li><a href="index.php">All</a></li>
                          <?php 
                          require_once 'db.php';
                          $query = "SELECT * FROM categories";
                            $stmt = $DBcon->prepare( $query );
                            $stmt->execute();
                            if($stmt->rowCount()==0)
                            {
                              ?>
                    <div class="col-xs-12">
                      <div class="alert alert-warning">
                           <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry, No categories at the Moment
                       </div>
                    </div>
        </div>
        <?php 
                      }
                    
                      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                      ?>

                <div class="col-md-12">

                <li><a href="#" data-id="<?php echo $row['cat_id']; ?>" id="getcat" class="category"><?php echo $row['cat_title'];?></a></li>
              </div>
        <?php } ?>

<div class="col-md-8 col-xs-12" id="get_products">
    <?php     
        require_once 'db.php';   
        $query = "SELECT * FROM Products";
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
        
        <div class='col-md-4'>
          <div class="panel panel-info">
          <div class="panel-heading"><?php echo $row['fish_title'];?></div>
          <span class="label label-info">New</span>
                <div class='panel-body'>    
                <img src="fish_images/<?php echo $row['fish_image']; ?>" class="img-rounded" width="150px" height="100px" />
                </div>
                <div class="panel-heading">@Kshs.<?php echo $row['fish_price'];?>
                <div>
                  <button data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['fish_id']; ?>" id="getfish" class="btn btn-sm btn-info">View </button>
                  <a class="btn btn-danger" href="order.php?fish_id=<?php echo $row['fish_id']; ?>"  ><span class="glyphicon glyphicon-buy"></span> Buy</a>
                </div>
                </div>
             </div>
            </div>
            </div>

        <?php
      }

   ?>
