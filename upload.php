<?php 
include("includes/header.php");
// require_once "vendor/autoload.php";

// use Aws\S3\S3Client;

// $profile_id = $user['username'];
// $imgSrc = "";
// $result_path = "";
// $msg = "";

// //S3使用準備
// $bucket = '--- BUCKET_NAME ---';
// $key = '--- KEY_NAME ---';
// $secret = '--- SECRET_NAME ---';


// //S3インスタンス作成
// s3 = new S3Client(array(
//     'version' => 'latest',
//     'credentials' => array(
//         'key' => $key,
//         'secret' => $secret,
//     ),
//     'region'  => 'ap-northeast-3', // 大阪地域設定
// ));

// /***********************************************************
// 	0 - tmpの画像があれば削除
// ***********************************************************/
// 	if (!isset($_POST['x']) && !isset($_FILES['image']['name']) ){
// 		//Delete users temp image
// 			$temppath = 'assets/images/profile_pics/'.$profile_id.'_temp.jpeg';
// 			if (file_exists ($temppath)){ @unlink($temppath); }
// 	} 


// if(isset($_FILES['image']['name'])){	
// /***********************************************************
// 	1 - S3(AWS)へアップロード
// ***********************************************************/	
// // アップロードされた画像の処理
// $file = $_FILES['image']['tmp_name'];
// if (!is_uploaded_file($file)) {
//     return;
// }

// // S3バケットに画像をアップロード
// $result = $s3->putObject(array(
//     'Bucket' => $bucket,
//     'Key' => time() . '.jpg',
//     'Body' => fopen($file, 'rb'),
//     'ACL' => 'public-read', // 画像は一般公開されます
//     'ContentType' => mime_content_type($file),
// ));
// }
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
      
 	 
 
    <br /><br />
