<?php
session_start();
require_once 'smiggle.class.php';
$user_email = trim($_REQUEST["e"]);
if (isset($_SESSION[$user_email])) {
    
    echo "<a href=\"smiggleAPI.php?function=LOGOUT&e=$user_email\">Log out</a>"."<br/>"."<br/>";
    echo "<a href=\"smiggleAPI.php?function=DEREGISTER&e=$user_email\">De-register</a>"."<br/>"."<br/>";
    echo "<h1>"."Smiggle Users"."</h1>";
    $smiggleUsers = json_decode(smiggle::getSmiggleUsers($user_email),TRUE);
    $userDetails = $smiggleUsers["user_details"];
    $usersCount = count($userDetails);
    $source_user_id = $_SESSION[$user_email]["user_id"];
    
    for ($x = 0; $x < $usersCount; $x++) {
        
        $username = $userDetails[$x]["username"];
        $dest_user_id = $userDetails[$x]["user_id"];
        $smiggleAPIResponse = json_decode(smiggle::getMessageIDs($source_user_id,$dest_user_id),TRUE);
        if (strcmp($smiggleAPIResponse["api_result"], 'SUCCESS') === 0) {
            
            $chatLink = "<a href=\"messages.php?source=$source_user_id&dest=$dest_user_id&e=$user_email\">Continue chatting</a>";
            
        } else {
            
            $chatLink = "<a href=\"newmessage.php?source_email=".$user_email."&source_id=".$source_user_id."&dest_id=".$dest_user_id."\">Start chatting</a>";
        }     
                
        echo "USERNAME: ".$userDetails[$x]["username"]." | ".$chatLink."<br/>"."<br/>";
    }
    
} else {
    
    echo "You need to login fist! <a href=\"index.php\">Click here to log in</a>";
}