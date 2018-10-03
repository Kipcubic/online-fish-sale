$currIDArray = array($id);
$cat=$view_row['fish_cat'];

		// extract all fish under the same category
	$allFishIds = array("id","name","desc",);
	$relatedFish = $DBcon->prepare("SELECT * from products where fish_cat = '$cat' ");
	$relatedFish->execute();

	while ($relatedFishRow = $relatedFish->fetch()) {
			# code...
		array_push($allFishIds , $relatedFishRow['fish_id']);

	}

	$diffIDS = array_diff($allFishIds , $currIDArray);
	foreach ($diffIDS as $value) {
			# code...
		
		$relatedQuery = $DBcon->prepare("SELECT * from products where fish_id = '$value'");
		$relatedQuery->execute();
		
		while ($relatedQueryRow = $relatedQuery->fetch()) {
			# code...?
		?>
<?php echo $value;?>
<a class="btn btn-danger" href="order.php?fish_id=<?php echo $value; ?>"  ><span class="glyphicon glyphicon-buy"></span> Buy</a><br /><br />

		<?php
			
		}
	}