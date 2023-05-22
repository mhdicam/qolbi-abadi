<?php 
include 'aksi/koneksi.php';
$cabang = $_GET['cabang'];

// Database connection info 
$dbDetails = array( 
    'host' => $servername, 
    'user' => $username, 
    'pass' => $password, 
    'db'   => $db
); 
 
// DB table to use 
// $table = 'members'; 
$table = <<<EOT
 (
    SELECT 
      a.id, 
      a.nama_karyawan,
      a.jabatan,
      a.status, 
      a.tanggal_masuk, 
      a.tanggal_keluar,
      a.cabang
    FROM karyawan a
 ) temp
EOT;
 
// Table's primary key 
$primaryKey = 'id'; 
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'nama_karyawan', 'dt' => 1 ), 
    array( 'db' => 'jabatan', 'dt' => 2 ), 
    array( 'db' => 'status', 'dt' => 3 ), 
    array( 'db' => 'tanggal_masuk', 'dt' => 4 ),
    array( 
        'db'        => 'tanggal_keluar', 
        'dt'        => 5, 
        'formatter' => function( $d, $row ) { 
            return ($d == null) ? '-' : $d; 
        } 
    ) 
); 

// Include SQL query processing class 
require 'aksi/ssp.php'; 

// require('ssp.class.php');

// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, null, "cabang = $cabang ")
    // SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns)

);