<?php
require_once 'simple_html_dom.php';
require_once 'connect.php';

mysqli_query($conn, "DROP TABLE IF EXISTS `lich_thi_va_ket_qua`;");
$sql = "CREATE TABLE `lich_thi_va_ket_qua` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `bai_thi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ngay_thi` date DEFAULT NULL,
    `gio_thi_ca_sang` time DEFAULT NULL,
    `gio_thi_ca_chieu` time DEFAULT NULL,
    `gio_thi_ca_toi` time DEFAULT NULL,
    `dia_diem_thi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ngay_tra_ket_qua` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` int(1) DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key2` (`bai_thi`,`ngay_thi`,`gio_thi_ca_sang`,`gio_thi_ca_chieu`,`dia_diem_thi`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
if (mysqli_query($conn, $sql) === TRUE) {
    // echo "create table lich_thi_va_ket_qua";
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
    $gio_thi = explode(" ", trim($item->childNodes(3)->plaintext));
    $ca_sang = 0;
    $ca_chieu = 0;
    $ca_toi = 0;
    if (count($gio_thi) == 1) {
        // echo 'case=1';
        if (strpos($gio_thi[0], 'AM') !== false) {
            $ca_sang = "7:00";
        }
        if (strpos($gio_thi[0], 'PM') !== false) {
            $ca_chieu = "13:00";
        }
        if (strpos($gio_thi[0], 'EV') !== false) {
            $ca_toi = "18:00";
        }
        if (str_replace(':', '', $gio_thi[0]) > 1) {
            if (str_replace(':', '', $gio_thi[0]) < 1300) {
                $ca_sang = $gio_thi[0];
            } elseif (str_replace(':', '', $gio_thi[0]) < 1800) {
                $ca_chieu = $gio_thi[0];
            } else {
                $ca_toi = $gio_thi[0];
            }
        }
    } elseif (count($gio_thi) == 3) {
        // echo 'case=3';
        $ca_sang = $gio_thi[0];
        $ca_chieu = $gio_thi[2];
        $ca_toi = 0;
        // if ($ca_sang == "AM") {
        //     $ca_sang = "7:00";
        // }
        // if ($ca_chieu == "PM") {
        //     $ca_chieu = "13:00";
        // }
    } elseif (count($gio_thi) == 5) {
        // echo 'case=5';
        $ca_sang = $gio_thi[0];

        if (str_replace(':', '', $gio_thi[2]) < 1800 && str_replace(':', '', $gio_thi[2]) > 1299) {
            $ca_chieu =  $gio_thi[2];
            $ca_toi = $gio_thi[4];
        } elseif ($gio_thi[4] < 1800) {
            $ca_chieu =  $gio_thi[4];
            $ca_toi = 0;
        }




        if (strpos($gio_thi[0], 'AM') !== false) {
            $ca_sang = "7:00";
        }
        if (strpos($gio_thi[2], 'PM') !== false) {
            $ca_chieu = "13:00";
        }
        if (strpos($gio_thi[4], 'EV') !== false) {
            $ca_toi = "18:00";
        }
    }
    // echo "sang: " . $ca_sang . "   chieu: " . $ca_chieu . "   toi: " . $ca_toi;
    // echo "     count:" . count($gio_thi) . "    ";
    // print_r($gio_thi);
    // echo "<br>";
    $list_result[] = array(
        "bai_thi" => trim($item->childNodes(1)->plaintext),
        "ngay_thi" => date("Y-m-d", strtotime(str_replace("/", "-", trim($item->childNodes(2)->plaintext)))),
        "gio_thi_ca_sang" => $ca_sang,
        "gio_thi_ca_chieu" => $ca_chieu,
        "gio_thi_ca_toi" => $ca_toi,
        "dia_diem_thi" => trim($item->childNodes(4)->plaintext),
        "ngay_tra_ket_qua" => trim($item->childNodes(5)->plaintext)
    );
    $bai_thi = trim($item->childNodes(1)->plaintext);
    $ngay_thi = date("Y-m-d", strtotime(str_replace("/", "-", trim($item->childNodes(2)->plaintext))));
    $gio_thi_ca_sang = $ca_sang;
    $gio_thi_ca_chieu = $ca_chieu;
    $gio_thi_ca_toi = $ca_toi;
    $dia_diem_thi = trim($item->childNodes(4)->plaintext);
    $ngay_tra_ket_qua = trim($item->childNodes(5)->plaintext);
    $status = 0;
    if ($item->hasClass("active")) {
        $status = 1;
    }
    $updated_at = date("Y/m/d H:i");
    $sql = "INSERT INTO lich_thi_va_ket_qua (bai_thi, ngay_thi, gio_thi_ca_sang, gio_thi_ca_chieu, gio_thi_ca_toi, dia_diem_thi, ngay_tra_ket_qua, status, created_at, updated_at)
    VALUES ('$bai_thi', '$ngay_thi', '$gio_thi_ca_sang', '$gio_thi_ca_chieu', '$gio_thi_ca_toi', '$dia_diem_thi', '$ngay_tra_ket_qua', '$status', '$updated_at', '$updated_at')";
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