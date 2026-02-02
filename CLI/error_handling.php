<?php

$file = 'data.txt';

// Cek apakah file ada
if (!file_exists($file)) {
    echo "Error file not found!\n";
    exit(1); // Keluar dengan kode kesalahan
}

echo "File processed successfully.\n";
exit(0); // Keluar dengan kode sukses

?>

<!-- Penjelasan -->

<!-- 
    file_exists() digunakan untuk memeriksa apakah file 'data.txt' ada di direktori saat ini.
    Jika file tidak ditemukan, pesan kesalahan ditampilkan dan skrip keluar dengan kode kesalahan 1.
    Jika file ditemukan, pesan sukses ditampilkan dan skrip keluar dengan kode sukses 0.
-->

<!-- Penggunaan -->

<!-- 
    Jalankan skrip ini dari command line dengan perintah:
    php error_handling.php
    Pastikan untuk menguji dengan dan tanpa file 'data.txt' di direktori yang sama.
-->