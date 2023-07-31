<?php
session_start();
if (!isset($_SESSION["userInfo"])) {
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}
date_default_timezone_set('Asia/Seoul'); // 서울 시간대로 설정 (원하는 시간대로 변경 가능)

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




// 이미지 크기 설정
$image_width = 800;
$image_height = 600;

// 박스 크기 및 여백 설정
$box_margin = 100;
$box_width = $image_width - 2 * $box_margin;
$box_height = $image_height - 2 * $box_margin;

// 그래프 영역 설정
$graph_margin = 50;
$graph_width = $box_width - 2 * $graph_margin;
$graph_height = $box_height - 2 * $graph_margin;

// 이미지 생성
$image = imagecreatetruecolor($image_width, $image_height);

// 색상 설정
$color_white = imagecolorallocate($image, 255, 255, 255);
$color_black = imagecolorallocate($image, 0, 0, 0);
$color_gray = imagecolorallocate($image, 200, 200, 200);
$color_blue = imagecolorallocate($image, 0, 0, 255);

// 배경 및 박스 색상 설정
imagefill($image, 0, 0, $color_white);
imagerectangle($image, $box_margin, $box_margin, $box_width + $box_margin, $box_height + $box_margin, $color_black);

// 격자 그리기
$grid_lines = 10; // x, y축 각각에 그릴 격자의 개수
$x_unit = $graph_width / ($grid_lines - 1); // x축 단위 간격
$y_unit = $graph_height / $grid_lines; // y축 단위 간격

for ($i = 1; $i < $grid_lines; $i++) {
    $x = $graph_margin + $x_unit * $i;
    $y = $graph_margin + $y_unit * $i;

    // 세로 격자 그리기
    imageline($image, $x, $graph_margin, $x, $graph_margin + $graph_height, $color_gray);

    // 가로 격자 그리기
    imageline($image, $graph_margin, $y, $graph_margin + $graph_width, $y, $color_gray);
}

// x축 값 표시
$x_text_color = $color_black;
$x_text_size = 12;
$x_interval = ceil(count($list_date) / ($grid_lines - 1));

for ($i = 0; $i < count($list_date); $i += $x_interval) {
    $x = $graph_margin + $x_unit * $i;
    $y = $graph_margin + $graph_height + 20;
    imagestring($image, $x_text_size, $x, $y, $list_date[$i], $x_text_color);
}

// y축 값 표시
$max_value = max($list_aver) * 1.5; // y축 최대값 설정 (최대값의 1.5배)
$y_text_color = $color_black;
$y_text_size = 12;

for ($i = 0; $i <= $grid_lines; $i++) {
    $x = $graph_margin - 30;
    $y = $graph_margin + $graph_height - $y_unit * $i;
    imagestring($image, $y_text_size, $x, $y, round($max_value * $i / $grid_lines, 1), $y_text_color);
}

// 그래프 그리기
$point_color = $color_blue;
$point_radius = 4;
$font_size = 10;
$data_count = count($list_aver);
$x_interval = $graph_width / ($data_count - 1);

for ($i = 0; $i < $data_count; $i++) {
    $x = $graph_margin + $x_interval * $i;
    $y = $graph_margin + $graph_height - $graph_height * $list_aver[$i] / $max_value;

    // 그래프 점 그리기
    imagefilledellipse($image, $x, $y, $point_radius, $point_radius, $point_color);

    // x축 값 표시
    $x_text_color = $color_black;
    $x_text_size = 10;
    $x_text = $list_date[$i];
    $x_text_width = imagefontwidth($x_text_size) * strlen($x_text);
    $x_text_x = $x - $x_text_width / 2;
    $x_text_y = $graph_margin + $graph_height + 10;
    imagestring($image, $x_text_size, $x_text_x, $x_text_y, $x_text, $x_text_color);

    // y축 값 표시
    $y_text_color = $color_black;
    $y_text_size = 10;
    $y_text = $list_aver[$i];
    $y_text_width = imagefontwidth($y_text_size) * strlen($y_text);
    $y_text_x = $graph_margin - $y_text_width - 10;
    $y_text_y = $y - 5;
    imagestring($image, $y_text_size, $y_text_x, $y_text_y, $y_text, $y_text_color);
}

// 이미지로 저장
$image_filename = 'C:\Bitnami\wampstack-8.0.3-2\apache2\htdocs\myFistWeb\act2_result\graph.png';
imagepng($image, $image_filename);
imagedestroy($image);

echo '그래프가 성공적으로 그려졌고, 이미지로 저장되었습니다.';
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
        <h1>Action2 result</h1>
    </header>
    <div class="column-container">
        <div class="left-column">
            <img src="act2_result/graph.png" alt="Graph">

        </div>
        <div class="right-column">
            <!-- 업로드된 파일 목록 표시 -->
            <?php echo $fileMessage; ?>
            <ul>
                <?php
                foreach ($recentFiles as $filename) {
                    echo "<li>$filename</li>";
                }
                ?>
            </ul>
            <!-- 리스트 표시 -->
            <?php
            if ($dataDictCount > 0) {
                echo "<h3>리스트 정보</h3>";
                echo "<ul>";
                for ($i = 0; $i < $dataDictCount; $i++) {
                    echo "<li>" . $list_date[$i] . ": " . $list_aver[$i] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>리스트 정보가 없습니다.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
