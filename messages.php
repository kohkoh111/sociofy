<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u']))
  $user_to = $_GET['u'];
else{
  $user_to = $message_obj->getMostRecentUser();
    if($user_to == false)
      $user_to = 'new';
}
if($user_to != "new")
  $user_to_obj = new User($con, $user_to);

if(isset($_POST['post_message'])){
  if(isset($_POST['message_body'])){
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($user_to, $body, $date);
  }
}

?>

<div class="user_details column">
  <a href="<?= $userLoggedIn; ?>">  <img src="<?= $user['profile_pic']; ?>"> </a>

  <div class="user_details_left_right">
    <a href="<?php echo $userLoggedIn; ?>">
    <?= $user['first_name'] . " " . $user['last_name'];?>
    </a>
    <br>
    <?php echo "Posts: " . $user['num_posts']. "<br>";
    echo "Likes: " . $user['num_likes'];
    ?>
  </div>

<!-- div user_details columnクラスの終了タグ -->
</div>

<div class="main_column column">
  <?php
  if($user_to != 'new'){
    echo "<h4><a href='$user_to'>". $user_to_obj->getFirstAndLastName(). "</a></h4><hr><br>";
    echo "<div class='loaded_messages' id='scroll_message'>";
    echo $message_obj->getMessages($user_to);
    echo "</div>";
  } else{
    echo "<h4>New Messages</h4>";
  }
  ?>

<div class="message_post">
  <form action="" method="POST">
    <?php
      if($user_to == "new"){
        echo "Select friends you would like to send messages<br>";
        ?>
        To:<input type='text' onkeyup='getUser(this.value, "<?= $userLoggedIn ?>")' name='q' id='search_text_input' placeholder='select name' autocomplete='off'>
        <?php
        echo "<div class='results'></div>";
      }else{
        echo "<textarea name='message_body' id='message_textarea' placeholder='write messages'></textarea>";
        echo "<input type='submit' name='post_message' class='info' id='message_submit' value='send'>";
      }

     ?>
  </form>

<!-- div loaded_messagesクラスの終了タグ -->
</div>

<script>
var div = document.getElementById("scroll_message");
div.scrollTop = div.scrollHeight;
</script>

<!-- div main_column columnクラスの終了タグ -->
</div>

<div class="user_details column" id="conversations">
  <h4>Conversations</h4>
  <div class="loaded_conversations">
    <?= $message_obj->getConversations(); ?>
  </div>
  <br>
  <a href="messages.php?u=new">New messages</a>

<!-- div=user_details column id=conversationクラスの終了タグ -->
</div>
