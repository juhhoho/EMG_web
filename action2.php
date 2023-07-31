<?php
session_start();
if (!isset($_SESSION["userInfo"])) {
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}

// 함수: 지정한 경로에서 최근 10개의 파일 목록을 가져오는 함수
function getRecentFiles($directory, $username, $id) {
    $files = array();
    $count = 0;
    $iterator = new DirectoryIterator($directory);

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile() && !$fileInfo->isDot()) {
            $filename = $fileInfo->getFilename();
            $name_id = $username . "_" . $id;

            // 해당 이름과 아이디가 포함된 파일인지 확인
            if (strpos($filename, $name_id) !== false) {
                $files[] = $filename;
                $count++;

                if ($count >= 10) {
                    break; // 최근 10개 파일만 다룸
                }
            }
        }
    }

    return $files;
}

// act2_upload_data 폴더에서 최근 10개 파일 목록 가져오기
$dataDirectory = "C:/Bitnami/wampstack-8.0.3-2/apache2/htdocs/myFistWeb/act2_upload_data";
$recentFiles = getRecentFiles($dataDirectory, $_SESSION["userInfo"][0], $_SESSION["userInfo"][1]);

// 업로드된 파일의 개수에 따라 메시지 설정
$fileCount = count($recentFiles);
$fileMessage = "업로드된 파일이 존재하지 않습니다.";

// 업로드된 파일이 있는 경우 파일 개수와 정보를 표시하는 메시지 설정
if ($fileCount > 0) {
    $fileMessage = "업로드된 파일 개수: " . $fileCount;
}

// 파일에서 정보를 읽어 리스트에 추가
$list_date = array();
$list_aver = array();

foreach ($recentFiles as $filename) {
    $filePath = $dataDirectory . "/" . $filename;
    $fileContent = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // 파일의 3번째 줄부터 10번째 줄까지 값을 더한 후 8로 나누고, 소수점 둘째 자리에서 반올림
    $sum = 0;
    for ($i = 2; $i < min(11, count($fileContent)); $i++) {
        $sum += (float)$fileContent[$i];
    }
    $aver = round($sum / 8, 2);

    // 리스트에 파일명과 aver 값을 추가
    $datetime = explode("_", $filename)[2] . "_" . explode("_", $filename)[3];
    $list_date[] = $datetime;
    $list_aver[] = $aver;
}

// 요소 개수를 설정함
$dataDictCount = count($list_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="style.css">
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
                <p>성별: <?php echo $_SESSION["userInfo"][2]; ?></p>
                <p>이름: <?php echo $_SESSION["userInfo"][0]; ?></p>
                <p>생년월일: <?php echo $_SESSION["userInfo"][3]; ?>년 <?php echo $_SESSION["userInfo"][4]; ?>월 <?php echo $_SESSION["userInfo"][5]; ?>일</p>
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
            <h2>기술2</h2>
            <div id="right-top-section">
                <div id="upload-btn-container">
                    <form action="drawGraph2.php" method="post">
                        <!-- list_date와 list_aver를 POST 방식으로 전송 -->
                        <input type="hidden" name="list_date" value='<?php echo json_encode($list_date); ?>'>
                        <input type="hidden" name="list_aver" value='<?php echo json_encode($list_aver); ?>'>
                        <!-- 그래프 그리기 버튼을 input 태그로 변경 -->
                        <input type="submit" value="그래프 그리기" class="button-link">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

