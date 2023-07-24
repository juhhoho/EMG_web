<?php
session_start(); // Session should be started in every file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["new_name"];
    $email = $_POST["new_email"];
    $gender = $_POST["new_gender"];
    $birth_year = $_POST["new_birth_year"];
    $birth_month = $_POST["new_birth_month"];
    $birth_day = $_POST["new_birth_day"];
    $username = $_POST["new_username"];
    $password = $_POST["new_password"];

    // Check all fields are filled
    if(empty($name) || empty($email) || empty($gender) || empty($birth_year) || empty($birth_month) || empty($birth_day) || empty($username) || empty($password)){
        echo "<script>alert('All fields must be filled.');</script>";
    }
    else if (strlen($username) < 6 || strlen($username) > 10 || strlen($password) < 6 || strlen($password) > 10) {
        echo "<script>alert('ID and password must be at least 6 characters and no more than 10 characters.');</script>";
    } else {
        $file_id = fopen("data/id.txt", "r");
        $is_duplicate = false;
        while(!feof($file_id)) {
            $existing_id = trim(fgets($file_id));
            if($username === $existing_id) {
                $is_duplicate = true;
                break;
            }
        }
        fclose($file_id);

        if($is_duplicate) {
            echo "<script>alert('The username is already taken. Please choose a different one.');</script>";
        } else {
            $file_id = fopen("data/id.txt", "a");
            fwrite($file_id, $username . "\n");
            fclose($file_id);

            $file_pw = fopen("data/pw.txt", "a");
            fwrite($file_pw, $password . "\n");
            fclose($file_pw);

            // Information String
            $info_string = $name . "/" . $email . "/" . $gender . "/" . $birth_year . "/" . $birth_month . "/" . $birth_day . "/";
            $info_string .= count(file("data/id.txt")); // Adding the index of the newly registered user
            
            // Successful signup
            $file_info = fopen("data/info.txt", "a");
            fwrite($file_info, $info_string . "\n");
            fclose($file_info);
            
            // Log the user in
            $_SESSION["userInfo"] = array($name, $email, $gender, $birth_year, $birth_month, $birth_day);
            
            // Redirecting
            header("Location: http://localhost/myFistWeb/loggedIn.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>myWebsite</title>
        <link rel="stylesheet" type="text/css" href="http://localhost/myFistWeb/style.css">
    </head>
    <body>
        <header><h1><a href = "http://localhost/myFistWeb/main.php">MyFirstWeb</a></h1></header>
        
        <!-- 회원가입 -->
        <div id="signup">
            <form action="create.php" method="post">
                <!--이름-->
                <label for="new_name">Name:</label>
                <input type="text" id="new_name" name="new_name"><br>
                <!-- 이메일주소 -->
                <label for="new_email">Email:</label>
                <input type="email" id="new_email" name="new_email"><br>
                <!-- 성별 -->
                <label for="new_gender">Gender:</label>
                <select id="new_gender" name="new_gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select><br>
                <!--생년월일-->
                <label for="new_birth_year">Birth Year:</label>
                <select id="new_birth_year" name="new_birth_year">
                    <?php for($i = 1900; $i <= 2023; $i++) echo "<option value=$i>$i</option>"; ?>
                </select>
                <label for="new_birth_month">Birth Month:</label>
                <select id="new_birth_month" name="new_birth_month">
                    <?php for($i = 1; $i <= 12; $i++) echo "<option value=$i>$i</option>"; ?>
                </select>
                <label for="new_birth_day">Birth Day:</label>
                <select id="new_birth_day" name="new_birth_day">
                    <?php for($i = 1; $i <= 31; $i++) echo "<option value=$i>$i</option>"; ?>
                </select><br>
                <!-- id & pw -->
                <label for="new_username">Username:</label>
                <input type="text" id="new_username" name="new_username"><br>
                <label for="new_password">Password:</label>
                <input type="password" id="new_password" name="new_password"><br>
                <input type="submit" value="Sign Up">
            </form>
        </div>

        <img src="https://cdn.imweb.me/thumbnail/20210215/9c7ff5583fae2.jpg">
    </body>
</html>





