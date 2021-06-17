<?php
include("includes/header.php");
include("includes/form_handlers/settings_handler.php")
?>

<div class="main_column column">
  <h4> Account Setting</h4>
  <?php
    echo"<img src='".$user['profile_pic']."' class='small_profile_pic'>";
  ?>
  <br>

  <a href="upload.php">Upload new profile picture</a><br><br><br>

  Modify your information<br>
  <?php
    $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
    $row = mysqli_fetch_array($user_data_query);

    $firstname = $row['first_name'];
    $lastname =  $row['last_name'];
    $email = $row['email'];
   ?>

  <form action="settings.php" method="POST">
    First Name: <input type="text" name="first_name" value="<?= $firstname ?>" id="settings_input"><br>
    Last Name: <input type="text" name="last_name" value="<?= $lastname?>" id="settings_input"><br>
    Email: <input type="text" name="email" value="<?= $email ?>" id="settings_input"><br>
    <?= $message ?><br>
    <input type="submit" name="update_details" id="save_details" value="Update details" class="warning settings_submit"><br>

  </form>

  <h4>Change Password</h4>
  <form action="settings.php" method="POST">
    Old Password: <input type="password" name="old_password" id="settings_input"><br>
    New Password: <input type="password" name="new_password_1" id="settings_input"><br>
    Confirm New Password: <input type="password" name="new_password_2" id="settings_input"><br><br>
    <?= $password_message ?><br>
    <input type="submit" name="update_password" id="save_details" value="Update Password" class="warning settings_submit"><br>

  </form>

  <h4>Close Account</h4>
  <form action="settings.php" method="POST">
  <input type="submit" name="close_account" id="close_account" value="Close Account" class="danger settings_submit"><br>

  </form>
