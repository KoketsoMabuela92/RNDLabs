<?php
require_once 'smiggle.class.php';
$source_id = trim($_REQUEST["source"]);
$dest_id = trim($_REQUEST["dest"]);
$user_email = trim($_REQUEST["e"]);

if ($source_id != "" && $dest_id != "" && $user_email != "") {
    
    $smiggleMessages = json_decode(smiggle::getAllMessages($source_id, $dest_id),TRUE);
     
    if (strcmp($smiggleMessages["api_result"], "NONE") === 0) {
        
        echo $smiggleMessages["api_result_description"]." <a href=\"users.php?e=$user_email\">Click here to continue</a>";
        
    } else if (strcmp($smiggleMessages["api_result"], "SUCCESSFUL") === 0) {
        
        $messagesCounter = count($smiggleMessages["messages"]);
        $messageData = $smiggleMessages["messages"];
        //print_r($messageData);
        for ($x = 0; $x < $messagesCounter; $x++) {
            
            echo "From: ".$messageData[$x]["source_username"]."<br>";
            echo "To: ".$messageData[$x]["dest_username"]."<br>";
            echo "At: ".$messageData[$x]["message_sent_timestamp"]."<br>";
            echo "Message: ".$messageData[$x]["message_content"]."<br>"."<br>";
        }
        echo "<br>"."<br>";
        echo "<form action=\"smiggleAPI.php\" method=\"POST\">"
        . "<input type=\"hidden\" id=\"source_email\" name=\"source_email\" value=\"$user_email\">"
        . "<input type=\"hidden\" id=\"source_user_id\" name=\"source_user_id\" value=\"$source_id\">"
        . "<input type=\"hidden\" id=\"dest_user_id\" name=\"dest_user_id\" value=\"$dest_id\">"
        . "<input type=\"hidden\" id=\"function\" name=\"function\" value=\"NEWMESSAGE\">"
        . "<textarea required id=\"message\" name=\"message\" placeholder=\"Your message here\"></textarea>"
        . "<br><br><input type=\"submit\" value=\"Send Message\"></fom>"."<br>";
    } 
    
} else {
    
    die("Invalid request!");
}
