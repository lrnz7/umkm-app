<?php
require 'pasien/config/koneksi.php';

$sql = "SELECT product_name FROM products";
$result = mysqli_query($koneksi, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['product_name'] . "\n";
    }
} else {
    echo "Error fetching products: " . mysqli_error($koneksi);
}
?>
