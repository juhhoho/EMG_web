<?php
session_start(); // Start the session
if (!isset($_SESSION["userInfo"])) {
    // Not logged in, redirecting to login page
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}

$userInfo = $_SESSION["userInfo"];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>myWebsite</title>
        <link rel="stylesheet" type="text/css" href="http://localhost/myFistWeb/style.css">
    </head>
    <body>
        <header><h1><a href = "http://localhost/myFistWeb/loggedIn.php">MyFirstWeb</a></h1></header>
        <div id="user-info">
            <h2>User Information</h2>
            <p>성별: <?php echo $userInfo[2]; ?></p>
            <p>이름: <?php echo $userInfo[0]; ?></p>
            <p>생년월일: <?php echo $userInfo[3]; ?>년 <?php echo $userInfo[4]; ?>월 <?php echo $userInfo[5]; ?>일</p>
        </div>
        <a href="http://localhost/myFistWeb/logout.php" class="button-link">Logout</a>
        <nav>
            <ul>
                <li><a href = "http://localhost/myFistWeb/action1.php" class="button-link">기술1</a></li>
                <li><a href = "http://localhost/myFistWeb/action2.php" class="button-link">기술2</a></li>
            </ul>
        </nav>
        <img src="https://cdn.imweb.me/thumbnail/20210215/9c7ff5583fae2.jpg">
    </body>
</html>

