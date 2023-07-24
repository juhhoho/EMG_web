<?php
session_start();
if (!isset($_SESSION["userInfo"])) {
    header("Location: http://localhost/myFistWeb/main.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $graphFileName = $_POST["graphFileName"];
    $graphFilePath = "act1_upload_data/" . $graphFileName;

    // 현재 날짜와 시간을 가져옵니다.
    $currentDateTime = date("Y-m-d_H-i");

    // 그래프 특징 파일의 경로를 가져옵니다.
    $characteristicsFilePath = $_POST["characteristicsFilePath"];

    // 새 파일 이름을 다음 형식으로 생성합니다: "이름_년월일_시간_분.png"
    $newFileName = $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".png";

    // 그래프 이미지를 새 파일명과 함께 지정된 디렉토리로 이동합니다.
    $saveDir = "C:/Bitnami/wampstack-8.0.3-2/apache2/htdocs/myFistWeb/act1_result/";
    $savePath = $saveDir . $newFileName;

    // 그래프 이미지를 새 위치로 이동합니다.
    if (copy($graphFilePath, $savePath)) {
        // 성공 메시지를 보여줍니다.
        echo "<script type='text/javascript'>alert('파일이 성공적으로 저장되었습니다.');</script>";

        // 업로드 디렉토리에서 그래프 이미지 파일을 삭제합니다.
        if (unlink($graphFilePath)) {
            echo "<script type='text/javascript'>alert('그래프 이미지 파일이 삭제되었습니다.');</script>";
        } else {
            echo "<script type='text/javascript'>alert('그래프 이미지 파일을 삭제하는데 실패했습니다.');</script>";
        }

        // 그래프 특징을 담은 텍스트 파일을 새 위치로 이동합니다.
        $newCharacteristicsFilePath = "C:/Bitnami/wampstack-8.0.3-2/apache2/htdocs/myFistWeb/act2_upload_data/" . $_SESSION["userInfo"][0] . "_" . $currentDateTime . ".txt";
        if (file_exists($characteristicsFilePath)) {
            if (rename($characteristicsFilePath, $newCharacteristicsFilePath)) {
                echo "<script type='text/javascript'>alert('특징 파일이 성공적으로 이동되었습니다.');</script>";
            } else {
                echo "<script type='text/javascript'>alert('특징 파일 이동에 실패했습니다.');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('특징 파일을 찾을 수 없습니다.');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('파일 저장에 실패했습니다.');</script>";
    }

    // 저장 후 action1.php로 리디렉션합니다.
    header("Location: http://localhost/myFistWeb/action1.php");
    exit();
}
?>
