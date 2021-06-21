<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['profile_username'])){
  $username = $_GET['profile_username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
  $user_array = mysqli_fetch_array($user_details_query);

  $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

if(isset($_POST['remove_friend'])){
  $user = new User($con, $userLoggedIn);
  $user->removeFriend($username);
}

if(isset($_POST['add_friend'])){
  $user = new User($con, $userLoggedIn);
  $user->sendRequest($username);
}

if(isset($_POST['respond_request'])){
  header("Location:request.php");
}

if(isset($_POST['post_message'])){
  if(isset($_POST['message_body'])){
    $body = mysqli_real_escape_string($con,
    $_POST['message_body']);
    $date = date("Y-m-d H:i:s");

    $message_obj->sendMessage($username, $body, $date);

    //投稿の二重登録を禁止する
    // $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    // header("Location: $url");

  }
  //メッセージを送った後に、同じメッセージ画面を読み込むJS
  $link = '#profileTabs a[href="#messages_div"]';
  echo "<script>
          $(function(){
            $('".$link."').tab('show');
          });
        </script>";

}


 ?>

<style type="text/css">
.wrapper{
  margin-left: 0px;
  padding-left: 0px;
}
</style>

 <div class="profile_left">
   <img src="<?= $user_array['profile_pic']; ?>">

   <div class="profile_info">
   <p><?= "Posts:" .$user_array['num_posts']; ?></p>
   <p><?= "Likes:" .$user_array['num_likes']; ?></p>
   <p><?= "Friends:" .$num_friends; ?></p>
   </div>

   <form action="<?= $username; ?>" method="POST">
     <?php
     $profile_user_obj = new User($con, $username);
      if($profile_user_obj->isClosed()){
        header("Location: user_closed.php");
      }
      $logged_In_user_obj = new User($con, $userLoggedIn);
      if($userLoggedIn != $username){

        if($logged_In_user_obj->isFriend($username)){
          echo '<input type="submit" name="remove_friend" class="danger" value="Remove friend"><br>';

        } else if($logged_In_user_obj->didReceiveRequest($username)) {
          echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request "><br>';

        } else if($logged_In_user_obj->didSendRequest($username)) {
          echo '<input type="submit" name="" class="default" value="Request Sent"><br>';

        } else
          echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
      }
     ?>
   </form>

  <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post">

	<?php
	if($userLoggedIn != $username){
		echo '<div class="profile_info_bottom">';
		echo $logged_In_user_obj->getMutualFriends($username) ." Mutual Friends";
		echo '</div>';
	}
 	?>

 </div>

	<div class="main_column column">

    <!-- bootstrapから引用 -->
      <ul class="nav nav-tabs" role="tablist" id="profiletabs">
        <li role="presentation"  class="active">
            <a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Activity</a>

        <li role="presentation">
            <a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Message</a>
        </li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
          <div class="posts_area"></div>
            <img id="loading" src="assets/images/icons/loading.gif">
          </div>

          <div role="tabpanel" class="tab-pane fade" id="messages_div">
              <?php
              echo "<h4><a href='".$username."'>". $profile_user_obj->getFirstAndLastName(). "</a></h4><hr><br>";
              echo "<div class='loaded_messages' id='scroll_message'>";
              echo $message_obj->getMessages($username);
              echo "</div>";


              ?>

            <div class="message_post">
              <form action="" method="POST">
                <textarea name='message_body' id='message_textarea' placeholder='write messages'></textarea>
                <input type='submit' name='post_message' class='info' id='message_submit' value='send'>
              </form>


            <!-- div loaded_messagesクラスの終了タグ -->
            </div>

            <script>
            var div = document.getElementById("scroll_message");
            div.scrollTop = div.scrollHeight;
            </script>

          </div>
        </div>
      </div>


  <!-- Button trigger modal      bootstrapから引用-->
  <!-- Modal -->
	<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="postModalLabel">
						Post something!
					</h4>
	      </div>

	      <div class="modal-body">
	      	<p>
						Post your feelings!<br>
						If this profile page is not yours, this post is going to post to someone you open.
					</p>

	      	<form class="profile_post" action="" method="POST">
	      		<div class="form-group">
	      			<textarea class="form-control" name="post_body" placeholder="What's happening?"></textarea>
	      			<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
	      			<input type="hidden" name="user_to" value="<?php echo $username; ?>">
	      		</div>
	      	</form>
	      </div>


	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
	      </div>
	    </div>
	  </div>
	</div>


<script>
$(function(){

	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var inProgress = false;

	loadPosts(); //Load first posts

    $(window).scroll(function() {
    	var bottomElement = $(".status_post").last();
    	var noMorePosts = $('.posts_area').find('.noMorePosts').val();

        // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
        if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
            loadPosts();
        }
    });

    function loadPosts() {
        if(inProgress) { //If it is already in the process of loading some posts, just return
			return;
		}
		
		inProgress = true;
		$('#loading').show();

		var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

		$.ajax({
			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
			cache:false,

			success: function(response) {
				$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
				$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 
				$('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage 

				$('#loading').hide();
				$(".posts_area").append(response);

				inProgress = false;
			}
		});
    }

    //Check if the element is in view
    function isElementInView (el) {
        var rect = el.getBoundingClientRect();

        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
        );
    }
});

</script>



<!-- wrapperクラスの終了タグ -->
</div>

</body>
</html>
