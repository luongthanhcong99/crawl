<form action="crawl.php" method="get">
    <button type="submit">Crawl</button>
</form>
<?php require_once 'connect.php'; ?>
<style>
    td {
        border: 1px solid #000;
    }
</style>
<table>
    <?php


    ?>
    <?php
    $sql = "SELECT * FROM lich_thi_va_ket_qua";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output
        while ($row = $result->fetch_assoc()) {
            if ($row["status"] == 1) {
                $style = 'style="color: red;"';
            } else {
                $style = '';
            }
            echo '<tr ' . $style . '>';
            echo '<td>' . $row["id"] . "</td>";
            echo '<td>' . $row["bai_thi"] . "</td>";
            echo '<td>' . date("d-m-Y", strtotime($row["ngay_thi"])) . "</td>";
            echo '<td>' . $row["gio_thi_ca_sang"] . "</td>";
            echo '<td>' . $row["gio_thi_ca_chieu"] . "</td>";
            echo '<td>' . $row["gio_thi_ca_toi"] . "</td>";
            echo '<td>' . $row["dia_diem_thi"] . "</td>";
            echo '<td>' . $row["ngay_tra_ket_qua"] . "</td>";
            echo '</tr>';
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</table>