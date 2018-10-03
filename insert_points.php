<?php  
 include 'db.php'; 
 $county_code=$_POST['county_code'];
            $ward_code=$_POST['ward_code']; 
            $location_desc=$_POST['desc'];
 
 $stmt=$DBcon->prepare('INSERT INTO fish_points (county_code,ward_code,location_desc) VALUES(:county_code,:ward_code,:location_desc)');
			$stmt->bindParam(':county_code',$county_code);
			$stmt->bindParam(':ward_code',$ward_code);
			$stmt->bindParam(':location_desc',$location_desc);
			$stmt->execute();
			if($stmt) 
			{
				echo "success";
			}
			else
			{
				echo "Something went wroong";
			}