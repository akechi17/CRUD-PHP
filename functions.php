<?php

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}

// Ambil data dari tabel genshinchars
// $result = mysqli_query($conn, "SELECT * FROM genshinchars");
// var_dump($result);
// if ( !result ) {
//     echo mysqli_error($conn);
// }

// Ambil data (fetch) genshinchars dari objek result
// mysqli_fetch_row() //mengembalikan array numerik
// mysqli_fetch_assoc() //mengembalikan array associative
// mysqli_fetch_array() //mengembalikan keduanya
// mysqli_fetch_object()

// while ($chars = mysqli_fetch_assoc($result) ) {
// var_dump($chars["name"]);
// };

function tambah($data) {
    global $conn;
    // ambil data dari tiap elemen dalam form
    $name = htmlspecialchars($data["name"]);
    $birthday = htmlspecialchars($data["birthday"]);
    $region = htmlspecialchars($data["region"]);
    $vision = htmlspecialchars($data["vision"]);
    $weapon = htmlspecialchars($data["weapon"]);

    // Upload gambar
    $image = upload();
    if( !$image ) {
        return false;
    }

    // query insert data
    $query = "INSERT INTO genshinchars
                VALUES
                ('', '$name', '$birthday', '$region', '$vision', '$weapon', '$image')
                ";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function upload() {
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];
    $error = $_FILES['image']['error'];
    $tmpName = $_FILES['image']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if( $error === 4 ) {
        echo "<script>
                alert('please insert image');
              </script>";
        return false;
    }

    // Cek apakah yang diupload adalah gambar
    $validImageExtensions = ['jpg', 'jpeg', 'png']; //anggap aja banyak lah ya ekstensi lainnya, gweh males ngetik
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if( !in_array($imageExtension, $validImageExtensions)) {
        echo "<script>
                alert('yahaha ga bisa naro file jahad😜');
              </script>";
        return false;
    }

    // Cek jika ukurannya terlalu besar
    if( $fileSize > 1000000) {
        echo "<script>
                alert('file size is larger than 1MB');
              </script>";
        return false;
    }

    // generate nama gambar baru
    $newImageName = uniqid();
    $newImageName .= '.';
    $newImageName .= $imageExtension;

    // Lolos pengecekan, gambar siap diupload
    move_uploaded_file($tmpName, 'img/' . $newImageName );
    return $newImageName;
}

function delete($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM genshinchars WHERE id = $id");
    return mysqli_affected_rows($conn);
}

function edit($data) {
    global $conn;
    // ambil data dari tiap elemen dalam form
    $id = $data["id"];
    $name = htmlspecialchars($data["name"]);
    $birthday = htmlspecialchars($data["birthday"]);
    $region = htmlspecialchars($data["region"]);
    $vision = htmlspecialchars($data["vision"]);
    $weapon = htmlspecialchars($data["weapon"]);
    $oldImage = htmlspecialchars($data["oldImage"]);

    // Cek apakah user pilih gambar baru atau tidak
    if( $_FILES['image']['error'] === 4 ) {
        $image = $oldImage;
    } else {
        $image = upload();
    }

    // query insert data
    $query = "UPDATE genshinchars SET
                name = '$name',
                birthday = '$birthday',
                region = '$region',
                vision = '$vision',
                weapon = '$weapon',
                image = '$image'
              WHERE id = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function search($searchbar) {
    $query = "SELECT * FROM genshinchars
                WHERE
               name LIKE '%$searchbar%' OR
               birthday LIKE '%$searchbar%' OR
               region LIKE '%$searchbar%' OR
               vision LIKE '%$searchbar%' OR
               weapon LIKE '%$searchbar%'
               ";
    return query($query);
}

function register($data) {
    global $conn;
    $username = strtolower(stripslashes($data["username"]));
    $email = $data["email"];
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek username sudah ada atau belum
    $userAvailability = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if ( mysqli_fetch_assoc($userAvailability)) {
        echo "<script>
        alert('Username is not available');
      </script>";
      return false;
    }

    // Cek email sudah ada atau belum
    $emailAvailability = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if ( mysqli_fetch_assoc($emailAvailability)) {
        echo "<script>
        alert('Email is already registered');
      </script>";
      return false;
    }

    // Cek konfirmasi password
    if ( $password !== $password2 ) {
        echo "<script>
        alert('Confirm password does not match');
      </script>";
      return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);


    // Tambahkan user baru ke database
    mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$email', '$password')");
    return mysqli_affected_rows($conn);
}
?>