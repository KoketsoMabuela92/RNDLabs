<html>
    <head>
        <title>Smiggle - New Message</title>
    </head>
    <body>
        <h1>Smiggle Chat</h1>
        <form action="smiggleAPI.php" method="POST">
            <input type="hidden" value="NEWMESSAGE" id="function" name="function">
            <input type="hidden" value="<?php echo trim($_REQUEST["source_id"])?>" id="source_user_id" name="source_user_id">
            <input type="hidden" value="<?php echo trim($_REQUEST["dest_id"])?>" id="dest_user_id" name="dest_user_id">
            <input type="hidden" value="<?php echo trim($_REQUEST["source_email"])?>" id="source_email" name="source_email">
            <textarea required id="message" name="message" placeholder="Your message here"></textarea>
            <br>
            <br>
            <input type="submit" value="Send Message">
        </form>
    </body>
</html>
    