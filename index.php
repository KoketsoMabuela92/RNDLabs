<html>
    <head>
        <title>Smiggle Chat</title>
    </head>
    <body onload="login()">
        <h1>Welcome to Smiggle</h1>
        <h3>...Time to mingle!</h3>
        
        <div id="login">
            
            <form action="smiggleAPI.php" method="POST">
                <input type="hidden" id="function" name="function" value="LOGIN">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td><input required type="text" placeholder="username" id="username" name="username"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input required type="password" placeholder="**************" id="user_password" name="user_password"></td>
                    </tr>
                    <tr>
                        <td><input required type="submit" value="Log In..."></td>
                    </tr>
                </table>
            </form>
            
        </div>
       
        <br>
        
        <div id="forgotpassword">
            <form action="smiggleAPI.php" method="POST">
                <input type="hidden" id="function" name="function" value="forgotpassword">
                <table>
                    <tr>
                        <td>Enter your email address:</td>
                        <td><input required type="email" placeholder="koki@mabuela.co.za" id="user_email" name="user_email"></td>
                    </tr>
                    <tr>
                        <td><input required type="submit" value="Recover password..."></td>
                    </tr>
                </table>
            </form>
        </div>
        
        <div id="registrations">
            <form action="smiggleAPI.php" method="POST">
                <input type="hidden" id="function" name="function" value="REGISTER">
                <table>
                    <tr>
                        <td>Enter your full name:</td>
                        <td><input required type="text" placeholder="Koki Mabuela" id="user_fullname" name="user_fullname"></td>
                    </tr>
                    <tr>
                        <td>Enter your email address:</td>
                        <td><input required type="email" placeholder="koki@mabuela.co.za" id="user_email" name="user_email"></td>
                    </tr>
                    <tr>
                        <td>Enter your username:</td>
                        <td><input required type="text" placeholder="koki_mabuela" id="username" name="username"></td>
                    </tr>
                    <tr>
                        <td>Enter your password:</td>
                        <td><input required type="password" placeholder="**************" id="user_password" name="user_password"></td>
                    </tr>
                    <tr>
                        <td><input required type="submit" value="Register"></td>
                    </tr>
                </table>
            </form>
        </div>
        <footer>
            <button id="loginBtn" onclick="login()">Login</button> | <button id="forgotPasswordBtn" onclick="forgotpassword()">Forgot your password?</button> | <button id="registrationsBtn" onclick="registrations()">Don't have an account?</button>
        </footer>
        <script type="text/javascript">
        
            window.onload = function () {
                document.getElementById("login").style.display = "none";
                document.getElementById('registrations').style.display="none";
                document.getElementById('forgotpassword').style.display="none";
           };

            function login () {                
                document.getElementById('login').style.display="block";
                document.getElementById('registrations').style.display="none";
                document.getElementById('forgotpassword').style.display="none";
                
                document.getElementById('registrationsBtn').style.display="block";
                document.getElementById('forgotpasswordBtn').style.display="block";
                document.getElementById('loginBtn').style.display="none";
            }
            
            function forgotpassword () {                
                document.getElementById('login').style.display="none";
                document.getElementById('registrations').style.display="none";
                document.getElementById('forgotpassword').style.display="block";
                
                document.getElementById('registrationsBtn').style.display="block";
                document.getElementById('forgotpasswordBtn').style.display="none";
                document.getElementById('loginBtn').style.display="block";
            }
            
            function registrations () {                
                document.getElementById('login').style.display="none";
                document.getElementById('registrations').style.display="block";
                document.getElementById('forgotpassword').style.display="none";    
        
                document.getElementById('registrationsBtn').style.display="none";
                document.getElementById('forgotpasswordBtn').style.display="block";
                document.getElementById('loginBtn').style.display="block";
            }
        </script>
    </body>
</html>
