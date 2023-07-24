<?php
session_start();
if (!isset($_SESSION["userInfo"])) {
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}

// 시간대 설정
date_default_timezone_set('Asia/Seoul'); // 서울 시간대로 설정 (원하는 시간대로 변경 가능)

$userInfo = $_SESSION["userInfo"];
$uploadedFileName = "";
$uploadedFileContent = "";
$target_file = ""; // $target_file 변수 초기화

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        // 데이터 파일 업로드 처리
        $target_file = "act1_upload_data/" . $_SESSION["userInfo"][0] . "_" . date("Y-m-d_H-i") . "_" . basename($_FILES["dataToUpload"]["name"]);

        if (move_uploaded_file($_FILES["dataToUpload"]["tmp_name"], $target_file)) {
            $uploadedFileName = htmlspecialchars(basename($_FILES["dataToUpload"]["name"]));
            $uploadedFileContent = file_get_contents($target_file);
        } else {
            echo "<script type='text/javascript'>alert('죄송합니다, 파일을 업로드하는 동안 오류가 발생했습니다.');</script>";
        }
    } else if (isset($_POST["drawGraph"])) {
        // 그래프 그리기 버튼을 클릭한 경우
        $dataFilePath = $target_file; // 업로드된 데이터 파일의 경로
        $currentDateTime = date("Y-m-d_H-i-s");
        $outputFileName = $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".png";
        $outputFilePath = "act1_upload_data/" . $outputFileName;

        $command = 'python draw_graph.py "' . $dataFilePath . '" "' . $outputFilePath . '"';
        exec($command, $output, $return_value);

        if ($return_value === 0) {
            // Display the graph image
            echo "<img src='" . $outputFilePath . "' />";
            // Add the "Save Graph" button to download the generated graph image
            echo '<form action="saveGraph.php" method="post">
                    <input type="hidden" name="graphFileName" value="' . $outputFileName . '">
                    <input type="hidden" name="dataFilePath" value="' . $dataFilePath . '">
                    <input type="submit" value="Save Graph" class="button-link">
                  </form>';
        } else {
            echo "<script type='text/javascript'>alert('Sorry, there was an error generating the graph.');</script>";
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
        <header>
            <h1><a href="http://localhost/myFistWeb/loggedIn.php">MyFirstWeb</a></h1>
        </header>
        <div class="column-container">
            <div class="left-column">
                <!-- Left column content goes here -->
                <div id="user-info">
                    <h2>User Information</h2>
                    <p>성별: <?php echo $userInfo[2]; ?></p>
                    <p>이름: <?php echo $userInfo[0]; ?></p>
                    <p>생년월일: <?php echo $userInfo[3]; ?>년 <?php echo $userInfo[4]; ?>월 <?php echo $userInfo[5]; ?>일</p>
                </div>
                <a href="http://localhost/myFistWeb/logout.php" class="button-link">Logout</a>
                <nav>
                    <ul>
                        <li><a href="http://localhost/myFistWeb/action1.php" class="button-link">기술1</a></li>
                        <li><a href="http://localhost/myFistWeb/action2.php" class="button-link">기술2</a></li>
                    </ul>
                </nav>
                <img src="https://cdn.imweb.me/thumbnail/20210215/9c7ff5583fae2.jpg">
            </div>
            <!-- Right column content goes here -->
            <div class="right-column">
                <h2>기술1</h2>
                <div id="right-top-section">
                    <div>
                        <form action="action1.php" method="post" enctype="multipart/form-data">
                            업로드할 데이터 선택:
                            <input type="file" name="dataToUpload" id="dataToUpload">
                            <input type="submit" value="데이터 업로드" name="submit">
                        </form>
                    </div>
                    <div id="upload-btn-container">
                        <ul>
                            <li>
                                <form action="drawGraph.php" method="post">
                                    <input type="hidden" name="dataToUpload" value="<?php echo $target_file; ?>">
                                    <input type="submit" value="그래프 그리기" class="button-link">
                                </form>
                            </li>
                        </ul>
                        <?php if (!empty($uploadedFileName)): ?>
                            <p>업로드된 파일: <?php echo $uploadedFileName; ?></p>
                            <p>파일 내용:</p>
                            <pre><?php echo $uploadedFileContent; ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
