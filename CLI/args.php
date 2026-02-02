<?php

echo "Script name: " . $argv[0] . "\n";
echo "Argument count: " . $argc . "\n";

for ($i = 1; $i < $argc; $i++) {
    echo "Argument $i: " . $argv[$i] . "\n";
};

?>

<!-- Penjelasan -->

<!-- 
    $argv adalah array yang berisi argumen yang diteruskan ke skrip PHP melalui command line.
    $argc adalah jumlah total argumen yang diteruskan, termasuk nama skrip itu sendiri.
    Loop for digunakan untuk menampilkan setiap argumen yang diteruskan, dimulai dari indeks 1 karena indeks 0 adalah nama skrip.
-->

<!-- Penggunaan -->

<!-- 
    Jalankan skrip ini dari command line dengan perintah:
    php args.php hello world 123
-->