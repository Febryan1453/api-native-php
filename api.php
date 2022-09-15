<?php

require_once "koneksi.php";

// Pemanggilan function tertentu
if(function_exists($_GET['function'])){
    $_GET['function']();
}

// Get Data Users
// URL DESIGN Get Data Users:
// localhost/api-native/api.php?function=getUsers
function getUsers(){

    // Permintaan ke server
    global $koneksi;
    $query = mysqli_query($koneksi, "SELECT * FROM users");
    while($data = mysqli_fetch_object($query)){
        $users[] = $data;
    }

    // Menghasilkan response server
    $respon = array(
        'status'    => 1,
        'message'   => 'Success get users',
        'users'     => $users
    );

    // Menampilkan data dalam bentuk JSON
    header('Content-Type: application/json');
    print json_encode($respon);

}

// Insert Data User
// URL DESIGN Insert Data User:
// localhost/api-native/api.php?function=addUser
function addUser(){
    
    global $koneksi;

    $parameter = array(
        'nama'   => '', 
        'alamat' => ''
    );

    $cekData = count(array_intersect_key($_POST, $parameter));

    if($cekData == count($parameter)){

        $nama   = $_POST['nama'];
        $alamat = $_POST['alamat'];
        
        $result = mysqli_query($koneksi, "INSERT INTO users VALUES('', '$nama', '$alamat')");

        if($result){
            return message(1, "Insert data $nama success");
        }else{
            return message(0, "Insert data failed");
        }

    }else{
        return message(0, "Parameter Salah");
    }

}

// Update Data User
// URL DESIGN Update Data User:
// localhost/api-native/api.php?function=updateUser&id={id}
function updateUser(){

    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $parameter = array(
        'nama'      => "",
        'alamat'    => ""
    );

    /* Fungsi array_intersect_key() berfungsi untuk membandingkan kunci dari dua 
    (atau lebih) array, dan mengembalikan kecocokan. */

    $cekData = count(array_intersect_key($_POST, $parameter));

    if($cekData == count($parameter)){

        $nama   = $_POST['nama'];
        $alamat = $_POST['alamat'];

        $result = mysqli_query($koneksi, "UPDATE users SET nama='$nama', alamat='$alamat' WHERE id='$id'");

        if($result){
            return message(1, "Update data $nama success");
        }else{
            return message(0, "Update data failed");
        }

    }else{
        return message(0, "Parameter Salah");
    }

}

// Delete Data User
// URL DESIGN Delete Data User:
// localhost/api-native/api.php?function=deleteUser&id={id}
function deleteUser(){

    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $result = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

    if($result){
        return message(1, "Delete data success");
    }else{
        return message(0, "Delete data failed");
    }

}

// Detail Data User per id
// URL DESIGN Detail Data User per id:
// localhost/api-native/api.php?function=detailUserId&id={id}
function detailUserId(){

    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $result = $koneksi->query("SELECT * FROM users WHERE id='$id'");

    while($data = mysqli_fetch_object($result)){
        $detailUser[] = $data;
        $nama = $data->nama;
    }

    if($detailUser){
        $respon = array(
            'status'    => 1,
            'message'   => "Berhasil mendapatkan data $nama",
            'user'      => $detailUser
        );
    }else{
        return message(0, "Data tidak ditemukan");
    }

    // Menampilkan data dalam bentuk JSON
    header('Content-Type: application/json');
    print json_encode($respon);

}

function message($status, $msg){

    $respon = array(
            'status'    => $status,
            'message'   => $msg
    );

    // Menampilkan data dalam bentuk JSON
    header('Content-Type: application/json');
    print json_encode($respon);
}

?>