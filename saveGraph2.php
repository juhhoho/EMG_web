<?php
// saveGraph2.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // POST 요청에서 그래프 이미지 데이터를 받습니다.
    $graphData = $_POST["graphData"];

    // 그래프 데이터가 제대로 전달되었는지 확인합니다.
    if (!empty($graphData)) {
        // "act2_result" 디렉토리 경로를 설정합니다.
        $resultDirectory = "C:/Bitnami/wampstack-8.0.3-2/apache2/htdocs/myFistWeb/act2_result";

        // 파일명을 "이름_id_날짜_시간.png" 형식으로 생성합니다.
        $userInfo = $_SESSION["userInfo"];
        $username = $userInfo[0];
        $id = $userInfo[1];
        $date = date("Y-m-d");
        $time = date("H-i-s");
        $filename = "{$username}_{$id}_{$date}_{$time}.png";

        // 이미지 데이터를 디코딩하여 파일로 저장합니다.
        $decodedImage = base64_decode(str_replace('data:image/png;base64,', '', $graphData));
        file_put_contents("$resultDirectory/$filename", $decodedImage);

        // 저장된 파일명을 출력합니다. 이후에 필요하다면 사용할 수 있습니다.
        echo $filename;

        // 저장된 파일명을 출력합니다. 이후에 필요하다면 사용할 수 있습니다.
        echo $filename;

        // 그래프 저장 후, action2.php로 리디렉션합니다.
        header("Location: http://localhost/myFistWeb/action2.php");
        exit();
    } else {
        // 그래프 데이터가 없는 경우 에러 메시지를 출력합니다.
        echo "Error: Graph data is missing.";
    }
} else {
    // POST 요청이 아닌 경우 에러 메시지를 출력합니다.
    echo "Error: Invalid request method.";
}
?>
