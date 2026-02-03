<?php

include 'database.php';

$result = $conn->query("SELECT * FROM data_siswa");

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Data Siswa</title>
  </head>
  <body>
    <div class="container border-4 border mt-4 p-4">
        <h1 class="fw-bold">Data Siswa</h1>
        <a href="create.php"><button type="button" class="btn btn-primary fw-bold">Tambah Siswa</button></a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <table class="table table-striped mt-4">
            <thead class="table-dark">
                <tr class="align-middle text-center">
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="align-middle text-center">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['telepon'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td><?= $row['tanggal_lahir'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?> " class="text-white"><button class="btn btn-warning fw-bold rounded">Edit</button></a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="text-white" onclick="return confirm('Yakin ingin menghapus?');"><button class="btn btn-danger fw-bold rounded">Hapus</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

  </body>
</html>