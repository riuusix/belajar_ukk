<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'laundry-sendiri');

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = md5($_POST['password']);
$query = "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);
$cek = mysqli_num_rows($result);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($result);

    if ($data['role'] == 'admin') {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['id_outlet'] = $data['id_outlet'];
        header('location: admin');
    } elseif ($data['role'] == 'kasir') {
        $_SESSION['role'] = 'kasir';
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['id_outlet'] = $data['id_outlet'];
        header('location: kasir');
    } elseif ($data['role'] == 'owner') {
        $_SESSION['role'] = 'owner';
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['id_outlet'] = $data['id_outlet'];
        header('location: owner');
    } else {
        $msg = 'Role Tidak Valid';
        header('location: index.php?msg=' . $msg);
    }
} else {
    $msg = 'Username atau Password Salah';
    header('location: index.php?msg=' . $msg);
}
