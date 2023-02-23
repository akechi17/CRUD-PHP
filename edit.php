<?php 
session_start();

if( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "functions.php";

// Ambil data di url
$id = $_GET["id"];
$chars = query("SELECT * FROM genshinchars WHERE id = $id")[0];
// Query data karakter berdasarkan id nya

// koneksi ke DBMS
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

// Cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"]) ) {
    // Cek apakah data berhasil diubah atau tidak
    if( edit($_POST) > 0) {
        echo "
            <script>
                alert('data edited sucessfully');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
        <script>
            alert('failed to edit data');
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
    <title>Edit Page</title>
    <link rel="stylesheet" href="styles/form.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="container">   
        <div class="title">Edit Data</div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="characterDetails">
            <input type="hidden" name="id" value="<?= $chars["id"]?>">
            <input type="hidden" name="oldImage" value="<?= $chars["image"]?>">
                <div class="inputBox">
                    <span class="details">Name</span>
                    <input class="inputText" type="text" name="name" id="name" required value="<?= $chars["name"]?>">
                </div>
                <div class="inputBox">
                    <span class="details">Birthday</span>
                    <input class="inputText" type="text" name="birthday" id="birthday" required value="<?= $chars["birthday"]?>">
                </div>
                <div class="inputBox">
                    <span class="details">Region</span>
                    <input class="inputText" type="text" name="region" id="region" required value="<?= $chars["region"]?>">
                </div>
                <div class="inputBox">
                    <span class="details">Vision</span>
                    <input class="inputText" type="text" name="vision" id="vision" required value="<?= $chars["vision"]?>">
                </div>
                <div class="inputBox">
                    <span class="details">Weapon</span>
                    <input class="inputText" type="text" name="weapon" id="weapon" required value="<?= $chars["weapon"]?>">
                </div>
                <div class="inputBox">
                    <span class="details">Image</span>
                    <img src="img/<?= $chars['image'];?>" alt="">
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