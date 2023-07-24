<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $file_id = fopen("data/id.txt", "r");
        $file_pw = fopen("data/pw.txt", "r");

        $id_lines = array_map('trim', file('data/id.txt'));
        $pw_lines = array_map('trim', file('data/pw.txt'));
        $info_lines = file('data/info.txt');

        $key_id = array_search($username, $id_lines);
        $key_pw = array_search($password, $pw_lines);

        fclose($file_id);
        fclose($file_pw);

        if($key_id === false) {
            echo "<script>alert('The entered username does not exist.');</script>";
        }
        else if($key_pw === false) {
            echo "<script>alert('The entered password does not exist.');</script>";
        }
        else if($key_id !== $key_pw) {
            echo "<script>alert('Username and password do not match.');</script>";
        }
        else {
            // Successful login, redirecting
            $userInfo = explode("/", trim($info_lines[$key_id]));
            $_SESSION["userInfo"] = $userInfo;
            header("Location: http://localhost/myFistWeb/loggedIn.php"); // redirect to loggedIn.php
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
        <header><h1>MyFirstWeb</h1></header>
        <div id="main-container">
            <div id="login-container">
                <div id="login">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username"><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password"><br>
                        <div class="buttons-container">
                            <input type="submit" value="Login">
                            <a href="http://localhost/myFistWeb/create.php">
                                <button type="button" name="create">Create</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <article>
            <h2>우리 웹사이트는,,</h2>
                <p>
                ⅰ. 제작 동기
 대부분의 재활치료는 병원과 같은 의료기관에서 제공된다. 수술 후 환자의 상태가 안정될 때까지 재활치료가 이루어지고 나서, 퇴원이 이루어진다. 그러나 일부 환자들은 지속적인 입원 재활치료가 필요한 경우가 많아, 다른 병원으로 이송되어 치료를 계속 받게 된다. 특히 십자인대의 경우, 수술 후 재활치료는 일반적으로 3~6개월의 지속적인 재활운동이 필요하다. 이 기간 동안의 집중 재활치료를 제때 시행한다면, 가벼운 스포츠나 레져활동이 가능한 수준까지 회복할 수 있다. 그러나 이러한 회복 기간 동안 집중 재활치료를 제공할 수 있는 환경이 현재의 병원 시스템에서는 제한적이라는 것이 문제이다. 대부분의 병원은 주로 급성기 치료에 중점을 둔 병원 체계로 구성되어 있기 때문에 상태가 안정된 재활환자의 장기 입원치료가 어려울 수 있다. 이에 따라 일부 환자들은 정기적으로 병원을 이동하거나 장기 입원이 가능한 요양병원으로 이송되어 치료를 받게 된다. 일부 요양병원은 고품질의 재활서비스를 제공하기는 하지만, 대다수의 요양병원은 장기 입원 시 전문 재활 서비스에 별도의 비용을 청구하여 이익을 얻는 구조로 되어 있다. 이로 인해 조기 사회복귀를 위한 집중적인 재활치료를 실현하기는 어렵다. 이에 따라 간단히 환자의 상태를 측정하고 결과를 볼 수 있는 기기와 의사와 환자를 이어줄 수 있는 플랫폼의 필요성이 대두 되었고, 'EMG 신호 웹사이트'는 이러한 불편함을 해소할 하나의 해결책이 될 것이다.
                </p>
            <article>
        </div>
        <img src="https://cdn.imweb.me/thumbnail/20210215/9c7ff5583fae2.jpg">
    </body>
</html>


