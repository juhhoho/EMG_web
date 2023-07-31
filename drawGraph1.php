<?php
session_start();
if (!isset($_SESSION["userInfo"])) {
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}

// 시간대 설정
date_default_timezone_set('Asia/Seoul'); // 서울 시간대로 설정 (원하는 시간대로 변경 가능)

$userInfo = $_SESSION["userInfo"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataFilePath = $_POST["dataToUpload"];
    $currentDateTime = date("Y-m-d_H-i-s");
    $outputFileName = $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".png";
    $outputFilePath = "act1_upload_data/" . $outputFileName;
    $characteristicsFilePath = "act1_upload_data/" . $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".txt";

    // Python 스크립트를 실행하여 그래프 그리기
    $command = 'python draw_graph1.py "' . $dataFilePath . '" "' . $outputFilePath . '"';
    exec($command, $output, $return_value);

    if ($return_value === 0) {
        // Display the original graph image directly on the page
        echo '<p>원본 그래프 이미지:</p>';
        echo '<img src="' . $outputFilePath . '" alt="Graph Image">';

        // Read the result file containing max, avg, and min values
        $resultFilePath = "act1_upload_data/" . $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".txt";
        if (file_exists($resultFilePath)) {
            $resultData = file_get_contents($resultFilePath);
            echo '<p>' . $resultData . '</p>';
        } else {
            echo "<p>최대값, 평균값, 최소값 정보를 불러오는데 실패했습니다.</p>";
        }

        // Add the "Save Graph" button to download the generated graph image
        echo '<form action="saveGraph1.php" method="post">
                <input type="hidden" name="graphFileName" value="' . $outputFileName . '">
                <input type="hidden" name="dataFilePath" value="' . $dataFilePath . '">
                <input type="hidden" name="characteristicsFilePath" value="' . $characteristicsFilePath . '">
                <input type="submit" value="Save Graph" class="button-link">
              </form>';
    } else {
        echo "<script type='text/javascript'>alert('Sorry, there was an error generating the graph.');</script>";
    }
}
?>
