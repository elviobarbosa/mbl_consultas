<?php
require_once THEMEPATH .'/include/intialise.php';
if(!empty($_GET['logout']))
{
  unset($_SESSION['user_info']);
  unset($_SESSION['access_token']);
}
if(!empty($_SESSION['access_token']))
{
    header('Location:index.php');
}
else
{
print_r('<br>START<br>');
$calendar = new Calendar();
print_r($calendar);

}

?>
<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- partial:index.partial.html -->
<div class="form">
  <img src="http://www.androidpolice.com/wp-content/themes/ap2/ap_resize/ap_resize.php?src=http%3A%2F%2Fwww.androidpolice.com%2Fwp-content%2Fuploads%2F2015%2F10%2Fnexus2cee_Search-Thumb-150x150.png&w=150&h=150&zc=3"> 
  <h3>Please sign in trough google and provide access to create event in you account</h3>
  <a href="<?php //echo $calendar->GetCalendarAccessUrl() ?>">Sign using Google</a>
  <p>Note:We are not storing any kind of information that you provided.</p>
</div>
<!-- partial -->
  
</body>
</html>
