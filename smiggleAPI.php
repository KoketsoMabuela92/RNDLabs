<?php
session_start();
require_once 'smiggle.class.php';
$function = trim($_REQUEST["function"]);

if ($function != "") {
    
    $upperCaseFunction = strtoupper($function);
    if (strcmp($upperCaseFunction, 'REGISTER') === 0) {
        
        $username = trim($_REQUEST["username"]);
        $user_fullname = trim($_REQUEST["user_fullname"]);
        $user_email = trim($_REQUEST["user_email"]);
        $user_password = trim($_REQUEST["user_password"]);
        $smiggleEmailVerification = json_decode(smiggle::checkDuplicateEmail($user_email),TRUE);
        if (strcmp($smiggleEmailVerification["api_result"], 'FAILURE') === 0) {
            
            $responseToUser = $smiggleEmailVerification["api_result_description"]."\n<a href=\"index.php\">Click here to retry.</a>";
            
        } else {
        
            $smiggleAPIResponse = json_decode(smiggle::registerUser($username, $user_fullname, $user_email, $user_password),TRUE);
            if (strcmp($smiggleAPIResponse["api_result"], 'SUCCESS') === 0) {

                $_SESSION[$user_email] = 'VALID';
                $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"users.php?e=$user_email\">Click here to continue</a>";
                
            } else {

                $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"index.php\">Click here to try again</a>";
            }
        
        }
        
    } else if (strcmp($upperCaseFunction, 'LOGOUT') === 0) {
        
        $user_email = trim($_REQUEST["e"]);
        unset($_SESSION[$user_email]);
        header('Location: index.php');
        
    } else if (strcmp($upperCaseFunction, 'LOGIN') === 0) {
        
        $username = trim($_REQUEST["username"]);
        $user_password = trim($_REQUEST["user_password"]);
        $smiggleAPIResponse = json_decode(smiggle::validateLogIn($username, $user_password),TRUE);
        if (strcmp($smiggleAPIResponse["api_result"], 'SUCCESS') === 0) {
            
            $user_email = $smiggleAPIResponse["user_email"];
            $user_id = $smiggleAPIResponse["user_id"];
            $_SESSION[$user_email] = 'VALID';
            $_SESSION[$user_email]['user_id'] = $user_id;
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"users.php?e=$user_email\">Click here to continue</a>";
            
        } else {
            
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"index.php\">Click here to try again</a>";    
        }
        
    } else if (strcmp($upperCaseFunction, 'DEREGISTER') === 0) {
        
        $user_email = trim($_REQUEST["e"]);
        $smiggleAPIResponse = json_decode(smiggle::deRegisterUser($user_email),TRUE);
        if (strcmp($smiggleAPIResponse["api_result"], 'SUCCESS') === 0) {
            
            session_destroy();
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"index.php\">Click here to exit</a>";
            
        } else {
            
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"index.php\">Click here to try again</a>";    
        }
        
    } else if (strcmp($upperCaseFunction, 'NEWMESSAGE') === 0) {
        
        $source_user_id = trim($_REQUEST["source_user_id"]);
        $dest_user_id = trim($_REQUEST["dest_user_id"]);
        $message_content = trim($_REQUEST["message"]);
        $source_email = trim($_REQUEST["source_email"]);
        $smiggleAPIResponse = json_decode(smiggle::captureNewConversationMessage($source_user_id, $dest_user_id, $message_content),TRUE);
        if (strcmp($smiggleAPIResponse["api_result"], 'SUCCESS') === 0) {
        
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"users.php?e=".$source_email."\">Click here to continue</a>";
            
        } else {
            
            $responseToUser = $smiggleAPIResponse["api_result_description"]."\n<a href=\"users.php?e=".$source_email."\">Click here to try sending again</a>";
        }
    }

    echo $responseToUser;
    
} else {
    
    echo "Invalid request!";
}
die();
