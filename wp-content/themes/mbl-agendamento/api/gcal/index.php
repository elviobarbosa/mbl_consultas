<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/intialise.php';
if(empty($_SESSION['access_token']))
{
    header('Location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>It-InfoTech - Google Calnder Event Form Integration Using PHP</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="container">
        <form id="contact" action="google_calander_access.php" method="post">
            <h3>Google Calnder Event Form</h3>
            <h4>Fill The all Form</h4>
            <fieldset>
                <input placeholder="Name Of Event" name="event_name" type="text" tabindex="1" required autofocus>
            </fieldset>
            <fieldset>
                <input placeholder="Event Location" type="text" name="event_location" tabindex="2" required>
            </fieldset>
            <fieldset>
                <input type="text" tabindex="3" name="daterange" required>
            </fieldset>
            <fieldset>
                <textarea name="event_description" placeholder="Event Description...." tabindex="4" required></textarea>
            </fieldset>
            <fieldset>
                <button name="submit" type="submit" id="contact-submit" data-submit="...Sending">Submit</button>
            </fieldset>
            <fieldset>
                <a style="text-align:center;background:#d84343" href="login.php?logout=1"  id="contact-submit" >Logout(Remove your access)</a>
            </fieldset>

        </form>
    </div>
    <!-- partial -->

</body>
<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
</script>

</html>
</body>

</html>