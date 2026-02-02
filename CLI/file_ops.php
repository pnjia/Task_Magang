<?php

$content = file_get_contents('data.txt');
echo "File content:\n$content\n";

$data = "New log entry: " . date('Y-m-d H:i:s') . "\n";
file_put_contents('log.txt', $data, FILE_APPEND);
echo "Log entry added to log.txt\n";

echo "Data written to log.txt\n";

?>

<!-- Penjelasan -->

<!-- 
    file_get_contents() digunakan untuk membaca seluruh isi file 'data.txt' dan menyimpannya dalam variabel $content.
    file_put_contents() digunakan untuk menulis data ke file 'log.txt'. Opsi FILE_APPEND memastikan bahwa data baru ditambahkan di akhir file tanpa menghapus isi sebelumnya.
-->

<!-- Penggunaan -->
 
<!-- 
    Pastikan ada file bernama 'data.txt' di direktori yang sama dengan skrip ini.
    Jalankan skrip ini dari command line dengan perintah:
    php file_ops.php
-->