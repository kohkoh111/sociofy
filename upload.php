<?php 
include("includes/header.php");

	if(isset($_FILES['image'])){
		$file_name = $_FILES['image']['name'];   
		$temp_file_location = $_FILES['image']['tmp_name']; 

		require 'vendor/autoload.php';

		$s3 = new Aws\S3\S3Client([
			'region'  => '-- your region --',
			'version' => 'latest',
			'credentials' => [
				'key'    => "-- access key id --",
				'secret' => "-- secret access key --",
			]
		]);		

		$result = $s3->putObject([
			'Bucket' => '-- bucket name --',
			'Key'    => $file_name,
			'SourceFile' => $temp_file_location			
		]);

		var_dump($result);
	}
?>

<form action="upload.php" method="post"  enctype="multipart/form-data">
	Upload something<br /><br />
	<input type="file" id="image" name="image" style="width:200px; height:30px; " /><br /><br />
	<input type="submit" value="Submit" style="width:85px; height:25px;" />
</form><br /><br />
<br /><br />