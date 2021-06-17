<?php
include("includes/header.php");

if(isset($_GET['q'])){
  $query = $_GET['q'];
}else{
  $query = "";
}

if(isset($_GET['type'])){
  $type = $_GET['tyoe'];
}else{
  $type = "name";
}
?>

<div class="main_column column" id="main_column">
  <?php
    if($query == "")
      echo "enter something in search box";
      else{
        if($type == "username")
          $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 5");
        else{
          $name = explode(" ", $query);

          if(count($name) == 3)
          $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$name[0]%' AND last_name LIKE '$name[2]%') AND user_closed='no'");

          else if(count($name) ==2)
          $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$name[0]%' AND last_name LIKE '$name[1]%') AND user_closed='no'");
          $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$name[0]%' OR last_name LIKE '$name[0]%') AND user_closed='no'");
        }

      //入力された名前が存在しているかチェックする
      if(mysqli_num_rows($user_returned_query) == 0)
        echo "Not found the".$type. " like: ".$query;
        else
          echo mysqli_num_rows($user_returned_query). " results found <br><br>";

          echo "<p id='grey'>Try searching for</p>";
          echo "<a href='search.php?q=".$query."&type=name'>Names</a>,<a href='search.php?q=".$query."&type=username'>Username</a><br><hr id='search_hr'>";

          while($row =mysqli_fetch_array($user_returned_query)){
            $user_obj = new User($con, $user['username']);

            $button = "";
            $mutual_friends ="";

            if($user['username'] != $row['username']){
              if($user_obj->isFriend($row['username']))
                $button = "<input type='submit' name='".$row['username']."' class='danger' value='Remove Friend'>";

              else if($user_obj->didReceiveRequest($row['username']))
                $button = "<input type='submit' name='".$row['username']."' class='warning' value='Respond to Request'>";

              else if($user_obj->didSendRequest($row['username']))
                $button = "<input class='default' value='Request Sent'>";

              else
                $button = "<input type='submit' name='".$row['username']."' class='success' value='Add Friend'>";

              $mutual_friends = $user_obj->getMutualFriends($row['username'])." Friends in common";

            //ボタンを押した時の処理
            if(isset($_POST[$row['username']])){
              if($user_obj->isFriend($row['username'])){
                $user_obj->removeFriend($row['username']);
                header("Location:http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
              }
              else if($user_obj->didReceiveRequest($row['username'])){
                header("Location:requests.php");
              }
              else if($user_obj->didSendRequest($row['username'])){

              }
              else{
                $user_obj->sendRequest($row['username']);
                header("Location:http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
              }
            }

            }

            echo "<div class='search_result'>
                    <div class='searchPageFriendButtons'>
                      <form action='' method='POST'>
                      ".$button."<br>
                      </form>
                    </div>

                    <div class='result_profile_pic'>
                      <a href='".$row['username']."'>
                      <img src='".$row['profile_pic']."' style='height:100px;'></a>
                    </div>

                      <a href='".$row['username']."'>".$row['first_name']." ".$row['last_name']."
                      <p id='grey'> ".$row['username']."</p>
                      </a><br>
                      ".$mutual_friends."<br>
                  </div>
                  <hr id='search_hr'>";
            } //whileループの終了タグ
      }
  ?>

</div>
