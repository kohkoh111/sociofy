<?php
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");


if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>

<html lang="en">
<head>
	<title>Welcome to Sociofy</title>

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!-- <script src="assets/js/bootstrap.js"></script> -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="assets/js/sociofy.js"></script>
	<script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
	<script src="assets/js/bootbox.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
	
	
</head>
<body class="light-theme">

	<div class="top_bar">

		<div class="logo">
			<a href="index.php">Sociofy!</a>
		</div>

		<!-- dark mode -->
		<div class="themeSwitchToggle">
			<div class="DispMessage">
			<p id="DispMessage">Light Dark Mode</p>
			</div>
			<label class="switch">
	  			<div class="checkbox">
	  				<input type="checkbox" />
	    				<span class="slider round"></span>
				</div>
			</label>
		</div>


		<script>
			//dark mode light mode 切り替え
			const themeSwitch = document.querySelector('input');
			themeSwitch.addEventListener('change', (event) => {
		  document.body.classList.toggle('dark-theme');
			});
		</script>


		<div class="Search">
			<form action="search.php" method="GET" name="search_form">
				<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?= $userLoggedIn ?>')"
				name="q" placeholder="Search Users" autocomplete="off" id="search_text_input">
				<div class="button_holder">
					<img src="assets/images/icons/search.png">
				</div>
			</form>

			<div class="search_results">
			</div>

			<div class="search_results_footer_empty">
			</div>

		<!-- div searchクラスの終了タグ	 -->
		</div>

		<nav>
			<?php
				//未読メッセージ
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();

				//未読の通知
				$notification = new Notification($con, $userLoggedIn);
				$num_notifications = $notification->getUnreadNumber();

				//友達申請の通知
				$user_obj = new User($con, $userLoggedIn);
				$num_requests = $user_obj->getNumbertOfFriendsReq();

			 ?>
			<a href="<?php echo $userLoggedIn; ?>">
				<?php echo $user['first_name']; ?>
			</a>
			<a href="index.php">
				<i class="fa fa-home fa-lg"></i>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn; ?>', 'message')">
				<i class="fa fa-envelope fa-lg"></i>
				<?php
				if($num_messages > 0)
				echo '<span class="notification_badge" id="unread_message">'.$num_messages.'</span>';
				?>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn; ?>', 'notification')">
				<i class="fa fa-bell fa-lg"></i>
				<?php
				if($num_notifications > 0)
				echo '<span class="notification_badge" id="unread_notification">'.$num_notifications.'</span>';
				?>
			</a>
			<a href="requests.php">
				<i class="fa fa-users fa-lg"></i>
				<?php
				if($num_requests > 0)
				echo '<span class="notification_badge" id="unread_requests">'.$num_requests.'</span>';
				?>
			</a>
			<a href="settings.php">
				<i class="fa fa-cog fa-lg"></i>
			</a>
			<a href="includes/handlers/logout.php">
				<i class="fa fa-sign-out fa-lg"></i>
			</a>
		</nav>

		<div class="dropdown_data_window" style="height:0px; border:none;"></div>
		<input type="hidden" id="dropdown_data_type" value="">
	</div>

	<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	$(document).ready(function() {

		$('.dropdown_data_window').scroll(function() {
			var inner_height = $('.dropdown_data_window').inner_height(); //Div containing posts
			var scroll_top = $('.dropdown_data_window').scrollTop();
			var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
			var noMoreData = $('.dropdown_data_window').find('.noMoreDropDownData').val();

			if (scroll_top + innerHeight >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

				var pageName;
				var type = $('#dropdown_data_type').val();

				if(type='notification')
					pageName = "ajax_load_notifications.php";
				else if(type='message')
					pageName = "ajax_load_messages.php";
				}

				var ajaxReq = $.ajax({
					url: "includes/handlers/"+ pageName +,
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache:false,

					success: function(response) {
						$('.dropdown_data_window').find('.nextPageDropDownData').remove(); //Removes current .nextpage
						$('.dropdown_data_window').find('.noMoreDropDownData').remove(); //Removes current .nextpage

						$('.dropdown_data_window').append(response);
					}
				});

			} //End if

			return false;

		}); //End (window).scroll(function())


	});

	</script>



	<div class="wrapper">
