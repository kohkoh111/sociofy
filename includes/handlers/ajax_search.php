<?php
ini_set('display_errors',1);

include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$name = explode(" ", $query);

if(strpos($query, '_') !== false)
  $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 5");

else if(count($name) == 2)
  $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$name[0]%' AND last_name LIKE '$name[1]%') AND user_closed='no' LIMIT 5");

else
  $user_returned_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$name[0]%' OR last_name LIKE '$name[0]%') AND user_closed='no' LIMIT 5");


if($query != ""){
  while($row = mysqli_fetch_array($user_returned_query)){
    $user = new User($con, $userLoggedIn);

    if($row['username'] != $userLoggedIn)
      $mutual_friends = $user->getMutualFriends($row['username']). "friends in common";
      else
        $mutual_friends = "";

    echo "<div class='resultDisplay'>
          <a href='".$row['username']."' style='color:#1485BD;'>
            <div class='liveSearchProfilePic'>
              <img src='".$row['profile_pic']."'>
            </div>
            <div class='liveSearchText'>
              ".$row['first_name']. " ".$row['last_name']."
              <p>".$row['username']."</p>
              <p id='grey'>".$mutual_friends."</p>
            </div>
          </a>
          </div>";

  }
}
 ?>
