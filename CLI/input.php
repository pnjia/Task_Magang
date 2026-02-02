<?php

echo "Enter your name: ";
$name = trim(fgets(STDIN));


echo "Enter your age: ";
$age = trim(fgets(STDIN));

echo "Hello, $name. You are $age years old.\n"

?>

<!-- Penjelasan -->

<!-- 
    fgets(STDIN) digunakan untuk membaca input dari pengguna di command line.
    trim() digunakan untuk menghapus spasi kosong atau karakter newline dari awal dan akhir string yang dimasukkan.
-->

<!-- Penggunaan -->

<!-- 
    Jalankan skrip ini dari command line dengan perintah:
    php input.php
    Kemudian masukkan nama dan usia Anda saat diminta.
-->