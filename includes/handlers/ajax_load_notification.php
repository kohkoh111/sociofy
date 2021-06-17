<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Notification.php");

$limit = 5;

$notification = new Notification($con, $_REQUEST['userLoggedIn']);

echo $notification->getNotification($_REQUEST, $limit);
 ?>
