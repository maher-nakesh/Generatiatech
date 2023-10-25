<?php
// username and password
$user_correct = '123';
$correct_password = '123';

// check if the page is accessed as a result of a POST request
$mesaj = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    // check if the user and password fields are set and not empty
    if (empty($_POST['user'])) {
        $mesaj = 'Username was not specified';
    } elseif (empty($_POST['pass'])) {
        $mesaj = 'The password was not specified';
    } else {
        // sanitize user input
        $input_user = htmlspecialchars($_POST['user']);
        $input_pass = htmlspecialchars($_POST['pass']);

        // perform secure validation and authentication
        if ($input_user === $user_correct && $input_pass === $correct_password) {
            // login successful, perform any necessary actions here
            echo 'You have been authenticated. You will be redirected...';
            exit; // prevent further execution of the script
        } else {
            // login failed
            $mesaj = 'Username or password are wrong';
        }
    }
}
?>

<html>
<head>
    <title>Form application: login page - Learn PHP</title>
</head>
<body style="font-family: verdana, sans-serif; font-size: small;">

<form action="" method="post" style="width: 30%">
    <fieldset>
        <legend>Authentication data</legend>
        <input type="text" name="user"/> User<br/>
        <input type="password" name="pass"/> Pass<br/>
    </fieldset>

    <fieldset>
        <legend>Actions</legend>
        <input type="submit" value="Login" name="send"/>
        <input type="reset" value="Clean form"/>
    </fieldset>

</form>
<?php
// display the error message securely
if (strlen($mesaj) > 0) {
    echo '<p style="color: red">', htmlspecialchars($mesaj), '</p>';
}
?>
</body>
</html>
