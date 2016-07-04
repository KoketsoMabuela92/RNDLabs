<?php
class smiggle {

    const DB_HOST = 'localhost';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = 'weakpassword';
    const DB_NAME = 'smiggle_chat';
    
    //---------------
    //  MySQL
    //---------------
    public static function openDB () {
        global $loggerObj;
        try {
           $conObj = mysqli_connect(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
        } catch (Exception $exc) {
           $loggerObj->LogInfo($exc->getTraceAsString());
        }

        return $conObj;
    }

    public static function disconnectToDB ($conObj) {
     $conObj->close();
    }
    
    public static function registerUser ($userName,$userFullName,$userEmail,$userPassword) {
        
        $con = self::openDB();
        $username = mysqli_real_escape_string($con,$userName);
        $user_fullname = mysqli_real_escape_string($con,$userFullName);
        $user_email = mysqli_real_escape_string($con,$userEmail);
        $user_password = mysqli_real_escape_string($con,$userPassword);
        $query = sprintf("INSERT INTO smiggle_users(username,user_fullname,user_email,user_password) VALUES('%s','%s','%s','%s')",$username,$user_fullname,$user_email,$user_password);
        $con->query($query);
        
        if ($con->affected_rows > 0) {
            
            $responseData = array(
                'api_result'=>'SUCCESS',
                'api_result_description'=>'User successfully registered.',
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'Fialed to register user successfully.',
            );
        }
     
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function checkDuplicateEmail ($userEmail) {
        
        $con = self::openDB();
        $user_email = mysqli_real_escape_string($con,$userEmail);
        $query = sprintf("SELECT COUNT(*) AS email_appearance_counter FROM smiggle_users WHERE user_email = '%s'",$user_email);
        $res = $con->query($query);
        if ($con->affected_rows > 0) {
            
            $dbReturnedRow = $res->fetch_assoc();
            $email_appearance_counter = stripslashes($dbReturnedRow["email_appearance_counter"]);
            
            if ($email_appearance_counter > 0) {
                
                $responseData = array(
                    'api_result'=>'FAILURE',
                    'api_result_description'=>'Email verification failed: This email address has already been used by someone on this platform.',
                );
                
            } else {
              
                $responseData = array(
                    'api_result'=>'SUCCESS',
                    'api_result_description'=>'Email address verified successfully.',
                );
            }
            
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'Fialed to verify the email address.',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function validateLogIn ($userName,$userPass) {
        
        $con = self::openDB();
        $username = mysqli_real_escape_string($con,$userName);
        $query = sprintf("SELECT user_password,user_email,user_id FROM smiggle_users WHERE username = '%s'",$username);
        $res = $con->query($query);
        if ($con->affected_rows > 0) {
            
            $dbReturnedRow = $res->fetch_assoc();
            $db_user_password = stripslashes($dbReturnedRow["user_password"]);
            $user_email = stripslashes($dbReturnedRow["user_email"]);
            $user_id = stripslashes($dbReturnedRow["user_id"]);
            if (strcmp($db_user_password, $userPass) === 0) {
                
                $responseData = array(
                    'api_result'=>'SUCCESS',
                    'api_result_description'=>'Log in successful.',
                    'user_email'=>$user_email,
                    'user_id'=>$user_id
                );
                
            } else {
              
                $responseData = array(
                    'api_result'=>'FAILURE',
                    'api_result_description'=>'Invalid username/password combination.',
                );
            }
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'Unrecognised account.',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function getSmiggleUsers($userEmail) {
        
        $con = self::openDB();
        $user_email = mysqli_real_escape_string($con,$userEmail);
        $query = sprintf("SELECT user_id,username FROM smiggle_users WHERE user_email NOT IN('%s')",$user_email);
        $res = $con->query($query);
        $userDetails = array();
        if ($con->affected_rows > 0) {
                     
            for ($x = 0; $x < $con->affected_rows; $x++) {
                
                $dbReturnedRow = $res->fetch_assoc();
                $user_id = stripslashes($dbReturnedRow["user_id"]);
                $username = stripslashes($dbReturnedRow["username"]);
                $userDetails[$x] = array(
                    'user_id'=>$user_id,
                    'username'=>$username
                );
            }
            
            if ($con->affected_rows > 1) {
                
                $smiggleMsg = $con->affected_rows." Smiggle users found.";
                        
            } else {
                
                $smiggleMsg = $con->affected_rows." Smiggle user found.";
            }
            
            $responseData = array(
                'api_result'=>'SUCCESS',
                'api_result_description'=>$smiggleMsg,
                'user_details'=>$userDetails
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'Unrecognised account.',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function deRegisterUser ($userEmail) {
        
        $con = self::openDB();
        $user_email = mysqli_real_escape_string($con,$userEmail);
        $query = sprintf("DELETE FROM smiggle_users WHERE user_email = '%s'",$user_email);
        $con->query($query);
        if ($con->affected_rows > 0) {
            
            $responseData = array(
                'api_result'=>'SUCCESS',
                'api_result_description'=>'You have been successfully de-registered from Smiggle.',
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'User de-registration unsuccessful. Please contact support at; glenton92@gmail.com',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function getMessageIDs ($sourceUserID,$destUserID) {
        
        $con = self::openDB();
        $source_id = mysqli_real_escape_string($con,$sourceUserID);
        $dest_id = mysqli_real_escape_string($con,$destUserID);
        $query = sprintf("SELECT message_id FROM smiggle_messages WHERE dest_id = '%s' AND source_id = '%s' OR dest_id = '%s' AND source_id = '%s'",$source_id,$dest_id,$dest_id,$source_id);
        $con->query($query);
        if ($con->affected_rows > 0) {
            
            $responseData = array(
                'api_result'=>'SUCCESS',
                'api_result_description'=>'Converstaions IDs retrieved successfully.'
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'NONE',
                'api_result_description'=>'No conversation IDs available',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function captureNewConversationMessage ($sourceUserID,$destUserID,$messageContent) {
        
        $con = self::openDB();
        $source_id = mysqli_real_escape_string($con,$sourceUserID);
        $dest_id = mysqli_real_escape_string($con,$destUserID);
        $message_content = mysqli_real_escape_string($con,$messageContent);
        $query = sprintf("INSERT INTO smiggle_messages(source_id,dest_id,message_content) VALUES('%s','%s','%s')",$source_id,$dest_id,$message_content);
        $con->query($query);
        if ($con->affected_rows > 0) {
            
            $responseData = array(
                'api_result'=>'SUCCESS',
                'api_result_description'=>'Message has been successfully sent.'
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'FAILURE',
                'api_result_description'=>'Failed to send message.'
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function getAllMessages ($sourceUserID,$destUserID) {
   
        $con = self::openDB();
        $source_id = mysqli_real_escape_string($con,$sourceUserID);
        $dest_id = mysqli_real_escape_string($con,$destUserID);
        $query = sprintf("SELECT * FROM smiggle_messages WHERE dest_id = '%s' AND source_id = '%s' OR dest_id = '%s' AND source_id = '%s'",$source_id,$dest_id,$dest_id,$source_id);
        $res = $con->query($query);
        if ($con->affected_rows > 0) {
            $messages = array();
            for ($x = 0; $x < $con->affected_rows; $x++) {
              
                $dbReturnedRow = $res->fetch_assoc();
                $source_id = stripslashes($dbReturnedRow["source_id"]);
                $dest_id = stripslashes($dbReturnedRow["dest_id"]);
                $message_content = stripslashes($dbReturnedRow["message_content"]);
                $message_sent_timestamp = stripslashes($dbReturnedRow["message_sent_timestamp"]);
                
                $source_username = $apiResponse["username"] = self::getUserName($source_id);
                $dest_username = $apiResponse["username"] = self::getUserName($dest_id);
                
                $messages[$x] = array(
                    'source_username'=>$source_username,
                    'dest_username'=>$dest_username,
                    'message_content'=>$message_content,
                    'message_sent_timestamp'=>$message_sent_timestamp
                );
            }
            
            $responseData = array(
                'api_result'=>'SUCCESSFUL',
                'api_result_description'=>'Messages retrieved successfully',
                'messages'=>$messages
            );
            
        } else {
            
            $responseData = array(
                'api_result'=>'NONE',
                'api_result_description'=>'No messages exists between you and the other user.',
            );
        }
        
        self::disconnectToDB($con);
        return json_encode($responseData);
    }
    
    public static function getUserName ($userID) {
        
        $con = self::openDB();
        $user_id = mysqli_real_escape_string($con,$userID);
        $query = sprintf("SELECT username FROM smiggle_users WHERE user_id = '%s'",$user_id);
        $res = $con->query($query);
        if ($con->affected_rows > 0) {
        
            $dbReturnedRow = $res->fetch_assoc();
            $username = stripslashes($dbReturnedRow["username"]);
            
            $responseData = $username;
            
        } else {
            
            $responseData = "No user detected with that user ID.";
        }
        
        self::disconnectToDB($con);
        return $responseData;
    }
}
