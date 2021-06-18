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
				'key'    => "getenv('AWS_ACCESS_KEY_ID')",
				'secret' => "getenv('AWS_SECRET_ACCESS_KEY')",
			]
		]);		

		$result = $s3->putObject([
			'Bucket' => 'getenv("S3_BUCKET")',
			'Key'    => $file_name,
			'SourceFile' => $temp_file_location			
		]);

		var_dump($result);
	}
?>

<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;"></div>
<div class="main_column column">


	<div id="formExample">
		
	    <p><b> <?=$msg?> </b></p>
	    
	    <form action="upload.php" method="post"  enctype="multipart/form-data">
	        Upload something<br /><br />
	        <input type="file" id="image" name="image" style="width:200px; height:30px; " /><br /><br />
	        <input type="submit" value="Submit" style="width:85px; height:25px;" />
	    </form><br /><br />
	    
	</div> <!-- Form-->  
</div>