<?php
$conn = mysqli_connect("localhost", "root", "", "laundry-sendiri");


function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);

    // Cek apakah query berhasil
    if (!$result) {
        die("Query error: " . mysqli_error($conn)); // Menampilkan pesan error dari SQL
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
function tambah_outlet($data)
{
    global $conn;
    $nama_outlet = htmlspecialchars($data['nama_outlet']);
    $alamat_outlet = htmlspecialchars($data['alamat_outlet']);
    $telp_outlet = htmlspecialchars($data['telp_outlet']);

    $query = "INSERT INTO tb_outlet VALUES ('', '$nama_outlet', '$alamat_outlet', '$telp_outlet')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    return mysqli_affected_rows($conn);
}

function tambah_paket($data)
{
    global $conn;

    $nama_paket = htmlspecialchars($data['nama_paket']);
    $jenis_paket = htmlspecialchars($data['jenis_paket']);
    $harga = htmlspecialchars($data['harga']);
    $outlet = htmlspecialchars($data['outlet_id']);

    $query = "INSERT INTO tb_paket VALUES ('', '$jenis_paket', '$nama_paket', '$harga', '$outlet' )";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }
}

function tambah_user($data)
{
    global $conn;

    $nama_pengguna = htmlspecialchars($data['nama_user']);
    $username = mysqli_real_escape_string($conn, htmlspecialchars($data['username']));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($data['password']));
    $role = mysqli_real_escape_string($conn, htmlspecialchars($data['role']));
    $outlet = mysqli_real_escape_string($conn, htmlspecialchars($data['outlet_id']));
    $result = mysqli_query($conn, "SELECT username FROM tb_user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    if ($role == 'admin') {
        $query = "INSERT INTO tb_user VALUES ('', '$nama_pengguna', '$username', '$password', '$outlet', '$role')";
        mysqli_query($conn, $query);
        return mysqli_affected_rows($conn);
    } else {
        $query = "INSERT INTO tb_user VALUES ('', '$nama_pengguna', '$username', '$password', 'NULL', '$role')";
        mysqli_query($conn, $query);
        return mysqli_affected_rows($conn);
    }

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }
}

function tambah_member($data)
{
    global $conn;

    $no_ktp = htmlspecialchars($data['no_ktp']);
    $nama_member = htmlspecialchars($data['nama_member']);
    $alamat_member = htmlspecialchars(ucwords($data['alamat_member']));
    $telp_member = htmlspecialchars($data['telp_member']);
    $jenis_kelamin = htmlspecialchars($data['jenis_kelamin']);

    $result = mysqli_query($conn, "SELECT no_ktp FROM tb_member WHERE no_ktp = '$no_ktp'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('No KTP sudah digunakan!');</script>";
        return false;
    }
    $query = "INSERT INTO tb_member VALUES ('', '$nama_member', '$alamat_member', '$jenis_kelamin', '$telp_member', '$no_ktp')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);

    if (!$query) {
        die("Query gagal: " . mysqli_error($conn));
    }
}

function tambah_transaksi($data)
{
    global $conn;

    // Mulai session untuk ambil user_id
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Ambil user_id dari session
    $user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

    if (!$user_id) {
        echo "User tidak terdaftar di session!";
        return false;
    }

    // Ambil data dari $data dan sanitasi
    $id_member = mysqli_real_escape_string($conn, $data['id_member']);
    $id_outlet = mysqli_real_escape_string($conn, $data['id_outlet']);
    $kode_invoice = mysqli_real_escape_string($conn, $data['kode_invoice']);
    $biaya_tambahan = mysqli_real_escape_string($conn, $data['biaya_tambahan']);
    $diskon = mysqli_real_escape_string($conn, $data['diskon']);
    $pajak = mysqli_real_escape_string($conn, $data['pajak']);
    $tanggal = date('Y-m-d');
    $id_paket = mysqli_real_escape_string($conn, $data['paket_id']);
    $jumlah = mysqli_real_escape_string($conn, $data['qty']);

    // Buat query untuk INSERT ke tb_transaksi (dengan user_id)
    $query1 = "INSERT INTO tb_transaksi VALUES ('', '$id_outlet', '$kode_invoice', '$id_member', '$tanggal', NULL, NULL, '$biaya_tambahan', '$diskon', '$pajak', 'baru', 'belum', '$user_id')";
    // Coba eksekusi query 1
    if (mysqli_query($conn, $query1)) {
        // Dapatkan id_transaksi yang baru ditambahkan
        $id_transaksi = mysqli_insert_id($conn);

        // Ambil harga dari tb_paket berdasarkan id_paket
        $query_paket = "SELECT harga FROM tb_paket WHERE id_paket = '$id_paket'";
        $result_paket = mysqli_query($conn, $query_paket);

        // Cek apakah query paket berhasil
        if ($result_paket) {
            $data_paket = mysqli_fetch_assoc($result_paket);
            $harga = $data_paket['harga'];

            // Hitung total harga
            $total_harga = $harga * $jumlah;

            // Buat query untuk INSERT ke tb_detail_transaksi
            $query2 = "INSERT INTO tb_detail_transaksi 
                       (transaksi_id, paket_id, qty, total_harga, keterangan)
                       VALUES ('$id_transaksi', '$id_paket', '$jumlah', '$total_harga', NULL)";

            // Coba eksekusi query 2
            if (mysqli_query($conn, $query2)) {
                return $id_transaksi; // Berhasil, kembalikan ID transaksi
            } else {
                // Gagal insert ke tb_detail_transaksi
                echo "Gagal Insert ke tb_detail_transaksi: " . mysqli_error($conn) . "<br>";
                return false;
            }
        } else {
            // Gagal ambil harga dari tb_paket
            echo "Gagal Query Paket: " . mysqli_error($conn) . "<br>";
            return false;
        }
    } else {
        // Gagal insert ke tb_transaksi
        echo "Gagal Insert ke tb_transaksi: " . mysqli_error($conn) . "<br>";
        return false;
    }
}

function ubah_outlet($data)
{
    global $conn;

    $id_outlet = $data['id_outlet'];
    $nama_outlet = htmlspecialchars($data['nama_outlet']);
    $alamat_outlet = htmlspecialchars($data['alamat_outlet']);
    $telp_outlet = htmlspecialchars($data['telp_outlet']);

    $query = "UPDATE tb_outlet SET nama_outlet = '$nama_outlet', alamat_outlet = '$alamat_outlet', telp_outlet = '$telp_outlet' WHERE id_outlet = $id_outlet";

    // var_dump($query);
    // die();

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function ubah_paket($data)
{
    global $conn;
    $id_paket = $data['id_paket'];
    $nama_paket = htmlspecialchars($data['nama_paket']);
    $jenis_paket = htmlspecialchars($data['jenis_paket']);
    $harga = htmlspecialchars($data['harga']);
    $outlet = htmlspecialchars($data['outlet_id']);

    $query =  "UPDATE tb_paket SET jenis_paket = '$jenis_paket', nama_paket = '$nama_paket', harga = '$harga', outlet_id = '$outlet' WHERE id_paket = $id_paket";

    // var_dump($query);
    // die();

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function ubah_pengguna($data)
{
    global $conn;

    $id_user = $data['id_user'];
    $nama_user = htmlspecialchars($data['nama_user']);
    $username = mysqli_escape_string($conn, htmlspecialchars(strtolower(stripslashes(($data['username'])))));
    $password = mysqli_escape_string($conn, htmlspecialchars(hash('sha256', $data['password'])));
    $role = mysqli_escape_string($conn, htmlspecialchars($data['role']));

    $cekusername = mysqli_query($conn, "SELECT username FROM tb_user WHERE username = '$username' AND id_user != $id_user");

    if (mysqli_fetch_assoc($cekusername)) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        return false;
    }

    if (!empty($password)) {
        $password_baru = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE tb_user SET
            nama_user = '$nama_user',
            username = '$username',
            password = '$password_baru',
            role = '$role'
            WHERE id_user = $id_user
            ";
    } else {
        $query = "UPDATE tb_user SET
            nama_user = '$nama_user',
            username = '$username',
            role = '$role'
            WHERE id_user = $id_user";
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    return mysqli_affected_rows($conn);
}

function ubah_member($data)
{
    global $conn;

    $id_member = $data['id_member'];
    $no_ktp = htmlspecialchars($data['no_ktp']);
    $nama_member = htmlspecialchars($data['nama_member']);
    $alamat_member = htmlspecialchars(ucwords($data['alamat_member']));
    $telp_member = htmlspecialchars($data['telp_member']);
    $jenis_kelamin = htmlspecialchars($data['jenis_kelamin']);

    $query = "UPDATE tb_member SET no_ktp = '$no_ktp', nama_member = '$nama_member', alamat_member = '$alamat_member', '$jenis_kelamin', telp_member = '$telp_member' WHERE id_member = $id_member";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    return mysqli_affected_rows($conn);
}

function hapus_member($id_member)
{
    global $conn;
    $query = "DELETE FROM tb_member WHERE id_member = $id_member";

    return mysqli_query($conn, $query);
}

function hapus_outlet($id_outlet)
{
    global $conn;
    $query = "DELETE FROM tb_outlet WHERE id_outlet = $id_outlet";

    return mysqli_query($conn, $query);
}

function hapus_pengguna($id_user)
{
    global $conn;

    $query = "DELETE FROM tb_user WHERE id_user = $id_user";

    return mysqli_query($conn, $query);
}

function hapus_paket($id_paket)
{
    global $conn;
    $query = "DELETE FROM tb_paket WHERE id_paket = $id_paket";

    return mysqli_query($conn, $query);
}
