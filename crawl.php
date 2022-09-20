<?php
require_once 'simple_html_dom.php';
require_once 'connect.php';

mysqli_query($conn, "DROP TABLE IF EXISTS `lich_thi_va_ket_qua`;");
$sql = "CREATE TABLE `lich_thi_va_ket_qua` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `bai_thi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ngay_thi` date DEFAULT NULL,
    `gio_thi` varchar(200),
    `dia_diem_thi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ngay_tra_ket_qua` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key2` (`bai_thi`,`ngay_thi`,`gio_thi`,`dia_diem_thi`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
if (mysqli_query($conn, $sql) === TRUE) {
    echo "create table lich_thi_va_ket_qua";
} else {
    echo "can't create lich_thi_va_ket_qua <br>" . mysqli_error($conn);
}

$html = file_get_html('https://iigvietnam.com/lich-thi/?end_date&location&test_type=92&start_date');
$default = $html->find('._list_location', 0);
$content = $html->find('._list_location', 0)->find('tr');
$list_result = array();
foreach ($content as $key => $item) {
    if ($key == 0) {
        continue;
    }

    $list_result[] = array(
        "bai_thi" => trim($item->childNodes(1)->plaintext),
        "ngay_thi" => date("Y-m-d", strtotime(str_replace("/", "-", trim($item->childNodes(2)->plaintext)))),
        // "ngay_thi" => trim($item->childNodes(2)->plaintext),
        "gio_thi" => trim($item->childNodes(3)->plaintext),
        "dia_diem_thi" => trim($item->childNodes(4)->plaintext),
        "ngay_tra_ket_qua" => trim($item->childNodes(5)->plaintext)
    );
    $bai_thi = trim($item->childNodes(1)->plaintext);
    $ngay_thi = date("Y-m-d", strtotime(str_replace("/", "-", trim($item->childNodes(2)->plaintext))));
    $gio_thi = trim($item->childNodes(3)->plaintext);
    $dia_diem_thi = trim($item->childNodes(4)->plaintext);
    $ngay_tra_ket_qua = trim($item->childNodes(5)->plaintext);
    $ngay_tra_ket_qua = trim($item->childNodes(5)->plaintext);
    $update_at = date("Y/m/d H:i");
    $sql = "INSERT INTO lich_thi_va_ket_qua (bai_thi, ngay_thi, gio_thi, dia_diem_thi, ngay_tra_ket_qua, created_at, updated_at)
    VALUES ('$bai_thi', '$ngay_thi', '$gio_thi', '$dia_diem_thi', '$ngay_tra_ket_qua', '$update_at', '$update_at')";
    if (mysqli_query($conn, $sql) === TRUE) {
        // echo "insert successfull";
    } else {
        echo "fail insert " . mysqli_error($conn) . "<br>";
    }
}


mysqli_close($conn);
// array_shift($list_result);
// echo (json_encode($list_result));
?>
<pre> <?php print_r($list_result); ?></pre>