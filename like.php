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

if(isset($_GET['fish_id']) && !empty($_GET['fish_id']))
{

	
	$user=$_SESSION['uid'];
	$fish_id=$_GET['fish_id'];
	$check = "SELECT * FROM products_likes where fish_id='$fish_id' and user_id='$user'";
        $stmtCheck = $DBcon->prepare( $check);
        $stmtCheck->execute();
        if($stmtCheck->rowCount() == 0)
        {
			$insert = $DBcon->prepare('INSERT INTO products_likes(fish_id,user_id) VALUES(:fish_id, :user_id)');
			$insert->bindParam(':fish_id',$fish_id);
			$insert->bindParam(':user_id',$user);
			$insert->execute();
      	}
      	else
      	{
      		$stmt_delete_like = $DBcon->prepare("DELETE FROM products_likes WHERE fish_id='$fish_id' and user_id='$user' ");
		$stmt_delete_like->bindParam(':fish_id',$fish_id);
		$stmt_delete_like->bindParam(':user_id',$user_id);
		$stmt_delete_like->execute();
			

      	}


	// switch ($type) {
	// 	case 'product' :
	// 		$DBcon->query("
	// 				INSERT into products_likes(fish_id,user_id)
	// 				SELECT {$user},{$fish_id}
	// 				from products 
	// 				where EXISTS
	// 				(
	// 				SELECT fish_id from products where fish_id={$fish_id}

	// 				)
	// 				AND NOT EXISTS (
	// 				SELECT like_id from products_likes where user_id={$user} and fish_id={$fish_id}
	// 				)
	// 				limit 1
	// 				");
				
			
	// 		break;
	

	// }
		
}
header('Location:index.php');
