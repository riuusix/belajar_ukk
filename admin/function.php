<?php

session_start();

if ($_SESSION) {
    if ($_SESSION['role'] == 'admin') {
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}

$conn = mysqli_connect("localhost", "root", "", "laundry-sendiri");

function ambildata($query, $conn)
{
    $data = mysqli_query($conn, $query);

    if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $hasil[] = $row;
        }
    }
}


function bisa($conn, $query)
{
    $db = mysqli_query($conn, $query);
    if ($db) {
        return 1;
    } else {
        return 0;
    }
}

function ambilsatubaris($conn, $query)
{
    $db = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($db);
}

function hapus($where, $table, $redirect)
{
    $query = "DELETE FROM $table WHERE $where";
    echo $query;
}
