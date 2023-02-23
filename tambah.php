<?php 
session_start();

if( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "functions.php";
// koneksi ke DBMS
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

// Cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"]) ) {
    // Cek apakah data berhasil ditambahkan atau tidak
    if( tambah($_POST) > 0) {
        echo "
            <script>
                alert('data submitted sucessfully');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
        <script>
            alert('failed to submit data');
            document.location.href = 'index.php';
        </script>
    ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Karakter</title>
    <link rel="stylesheet" href="styles/form.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="title">Add New Data</div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="characterDetails">
                <div class="inputBox">
                    <span class="details">Name</span>
                    <input class="inputText" type="text" name="name" id="name" required autocomplete="off">
                </div>
                <div class="inputBox">
                    <span class="details">Birthday</span>
                    <input class="inputText" type="text" name="birthday" id="birthday" required autocomplete="off">
                </div>
                <div class="inputBox">
                    <span class="details">Region</span>
                    <input class="inputText" type="text" name="region" id="region" required autocomplete="off">
                </div>
                <div class="inputBox">
                    <span class="details">Vision</span>
                    <input class="inputText" type="text" name="vision" id="vision" required autocomplete="off">
                </div>
                <div class="inputBox">
                    <span class="details">Weapon</span>
                    <input class="inputText" type="text" name="weapon" id="weapon" required autocomplete="off">
                </div>
                <div class="inputBox">
                    <span class="details">Image</span>
                    <input type="file" name="image" id="image">
                    <label for="image">
                        <i class="material-icons">add_photo_alternate</i> &nbsp;
                        Choose a Photo
                    </label>
                </div>
            </div>
            <div class="button">
                <input type="submit" name="submit" value="Confirm">
            </div>
        </form>
    </div>
</body>
</html>