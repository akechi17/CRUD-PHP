<?php 
require "functions.php";
session_start();
if( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
// Pagination
// Konfigurasi
if (isset($_POST["search"])) {
    $jumlahDataPerHalaman = 4;
    $search = $_POST["search"];
    $jumlahData = count(query("SELECT * FROM genshinchars WHERE name LIKE '%$search%' OR birthday LIKE '%$search%' OR region LIKE '%$search%' OR vision LIKE '%$search%' OR weapon LIKE '%$search%'"));
    $totalPage = ceil($jumlahData / $jumlahDataPerHalaman);
    $activePage = ( isset($_GET["page"]) ) ? $_GET["page"] : 1;
    $firstData = ( $jumlahDataPerHalaman * $activePage ) - $jumlahDataPerHalaman;
    $genshinchars = query("SELECT * FROM genshinchars WHERE name LIKE '%$search%' OR birthday LIKE '%$search%' OR region LIKE '%$search%' OR vision LIKE '%$search%' OR weapon LIKE '%$search%' ORDER BY id ASC LIMIT $firstData, $jumlahDataPerHalaman");
}  
else {
     $jumlahDataPerHalaman = 4;
     $jumlahData = count(query("SELECT * FROM genshinchars"));
     $totalPage = ceil($jumlahData / $jumlahDataPerHalaman);
     $activePage = ( isset($_GET["page"]) ) ? $_GET["page"] : 1;
     $firstData = ( $jumlahDataPerHalaman * $activePage ) - $jumlahDataPerHalaman;
     $genshinchars = query("SELECT * FROM genshinchars ORDER BY id ASC LIMIT $firstData, $jumlahDataPerHalaman");
}

// Ketika tombol search ditekan
if(isset($_POST["search"])) {
    $genshinchars = search($_POST["searchbar"]);
    
}

// if(isset($_POST["search"])) {
//     $search = search($_POST["searchbar"]);
//     $jumlahDataPerHalaman = 4;
//     $jumlahData = count(query("SELECT * FROM genshinchars WHERE name LIKE '%$search%' OR birthday LIKE '%$search%' OR region LIKE '%$search%' OR vision LIKE '%$search%' OR weapon LIKE '%$search%'"));
//     $totalPage = ceil($jumlahData / $jumlahDataPerHalaman);
//     $activePage = ( isset($_GET["page"]) ) ? $_GET["page"] : 1;
//     $firstData = ( $jumlahDataPerHalaman * $activePage ) - $jumlahDataPerHalaman;
//     $genshinchars = query("SELECT * FROM genshinchars WHERE name LIKE '%$search%' OR birthday LIKE '%$search%' OR region LIKE '%$search%' OR vision LIKE '%$search%' OR weapon LIKE '%$search%' ORDER BY id ASC LIMIT $firstData, $jumlahDataPerHalaman");
// } else {
//     $jumlahDataPerHalaman = 4;
//     $jumlahData = count(query("SELECT * FROM genshinchars"));
//     $totalPage = ceil($jumlahData / $jumlahDataPerHalaman);
//     $activePage = ( isset($_GET["page"]) ) ? $_GET["page"] : 1;
//     $firstData = ( $jumlahDataPerHalaman * $activePage ) - $jumlahDataPerHalaman;
//     $genshinchars = query("SELECT * FROM genshinchars ORDER BY id ASC LIMIT $firstData, $jumlahDataPerHalaman");
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
    <div class="table">
        <div class="tableHeader">
            <p>Genshin Characters List</p>

            <form action="" method="post">
                <input type="text" placeholder="search" class="searchbar" name="searchbar" autofocus autocomplete="off">
                <button type="submit" name="search" class="searchBtn"><i class="fas fa-search"></i></button>
            </form>
            <div class="buttons">
                <a href="tambah.php"><button class="addNew">+Add New</button></a>
                <a href="logout.php"><button class="logout"><i class="fa-solid fa-right-from-bracket"></i></button></a>
            </div>
        </div>
        <div class="tableSection">
            <table>
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Birthday</th>
                        <th>Region</th>
                        <th>Vision</th>
                        <th>Weapon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                    <?php $i = $firstData + 1; ?>
                    <?php foreach( $genshinchars as $row ) :?>
                <tbody>
                    <tr>
                        <td><?= $i; ?></td>
                        <td>
                            <img src="img/<?= $row["image"];?>" alt="">
                        </td>
                        <td><?= $row["name"]?></td>
                        <td><?= $row["birthday"]?></td>
                        <td><?= $row["region"]?></td>
                        <td><?= $row["vision"]?></td>
                        <td><?= $row["weapon"]?></td>
                        <td>
                            <a href="edit.php?id=<?= $row["id"];?>"><button><i class="fa-solid fa-pen-to-square"></i></button></a>
                            <a href="delete.php?id=<?= $row["id"];?>" onclick="
                            return confirm('are you sure to delete this row?')"><button><i class="fa-solid fa-trash"></i></button></a>
                        </td>
                    </tr>
                </tbody>
                    <?php $i++; ?>
                    <?php endforeach; ?>
            </table>
        </div>
        <div class="pagination">
            <?php if( $activePage > 1 ) : ?>
                <a href="?page=1"><div><i class="fa-solid fa-angles-left"></i></div></a>
                <a href="?page=<?= $activePage - 1 ?>"><div><i class="fa-solid fa-chevron-left"></i></div></a>
            <?php endif; ?>
            <?php for($i=1; $i <= $totalPage; $i++) :?>
                <?php if( $i == $activePage) : ?>
                    <a href="?page=<?= $i; ?>"><div class="active"><?= $i; ?></div></a>
                <?php else : ?>
                    <a href="?page=<?= $i; ?>"><div><?= $i; ?></div></a>
                <?php endif; ?>
            <?php endfor;?>
            <?php if( $activePage < $totalPage ) : ?>
                <a href="?page=<?= $activePage + 1 ?>"><div><i class="fa-solid fa-chevron-right"></i></div></a>
                <a href="?page=<?= $totalPage?>"><div><i class="fa-solid fa-angles-right"></i></div></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>