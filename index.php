<?php
session_start();

//check if the user already logged in, redirect to dashboard

if(isset($_SESSION['username'])){
   header("Location:dashboard.php");
   exit;
}

//database configuration
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

//create database connection

$conn = mysqli_connect($servername, $username, $password, $dbname);

//check connection

if(!$conn){
    die("Connection failed:". $conn->connect_error);
}

//define function to sanitize user input

function sanitize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;

}

// process login form

if ($_SERVER["REQUEST METHOD"] == 'POST'){
    //   sanitize the user input
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);


    //prepare the SQL statement to prevent SQL injection

    $query = "SELECT * FROM db WHERE username = '$username' AND password = '$password'";

    $result = mysqli_query($conn,$query);

    $row = mysqli_num_rows($result);

    if($row = 1){
        // user found, set session variables 
        $_SESSION['username'] = $username;

        // redirect to dashboard 

        header("Location: dashboard.php");
    }
    else{
        // invalid login credinals
        $error = "Invalid usrname or password";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required> <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required> <br><br>

        <input type="submit" value="Login">
    </form>


    <?php
    // display error message , if any 

    if(isset($error)){
        echo '<p style="color: red;">' . $error . '</p>';
    }
    ?>
</body>
</html>
