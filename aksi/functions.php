<?php 

// koneksi ke database
include 'koneksi.php';


function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}
function tanggal_indo($tanggal){
    $bulan = array (1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

function singkat_angka($n, $presisi=1) {
	if ($n < 900) {
		$format_angka = number_format($n, $presisi);
		$simbol = '';
	} else if ($n < 900000) {
		$format_angka = number_format($n / 1000, $presisi);
		$simbol = ' rb';
	} else if ($n < 900000000) {
		$format_angka = number_format($n / 1000000, $presisi);
		$simbol = ' jt';
	} else if ($n < 900000000000) {
		$format_angka = number_format($n / 1000000000, $presisi);
		$simbol = ' M';
	} else {
		$format_angka = number_format($n / 1000000000000, $presisi);
		$simbol = ' T';
	}
 
	if ( $presisi > 0 ) {
		$pisah = '.' . str_repeat( '0', $presisi );
		$format_angka = str_replace( $pisah, '', $format_angka );
	}
	
	return $format_angka . $simbol;
}

// ================================================ USER ====================================== //
 
function tambahUser($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$user_nama = htmlspecialchars($data["user_nama"]);
	$user_no_hp = htmlspecialchars($data["user_no_hp"]);
	$user_alamat = htmlspecialchars($data["user_alamat"]);
	$user_email = htmlspecialchars($data["user_email"]);
	$user_password = md5(md5(htmlspecialchars($data["user_password"])));
	$user_create = date("d F Y g:i:s a");
	$user_level = htmlspecialchars($data["user_level"]);
	$user_status = htmlspecialchars($data["user_status"]);
	$user_cabang = htmlspecialchars($data["user_cabang"]);

	// Cek Email
	$email_user_cek = mysqli_num_rows(mysqli_query($conn, "select * from user where user_email = '$user_email' "));

	if ( $email_user_cek > 0 ) {
		echo "
			<script>
				alert('Email Sudah Terdaftar');
			</script>
		";
	} else {
		// query insert data
		$query = "INSERT INTO user VALUES ('', '$user_nama', '$user_no_hp', '$user_alamat', '$user_email', '$user_password', '$user_create', '$user_level' , '$user_status', '$user_cabang')";
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);
	}
}

function editUser($data){
	global $conn;
	$id = $data["user_id"];


	// ambil data dari tiap elemen dalam form
	$user_nama = htmlspecialchars($data["user_nama"]);
	$user_no_hp = htmlspecialchars($data["user_no_hp"]);
	$user_email = htmlspecialchars($data["user_email"]);
	$user_alamat = htmlspecialchars($data["user_alamat"]);
	$user_password = md5(md5(htmlspecialchars($data["user_password"])));
	$user_level = htmlspecialchars($data["user_level"]);
	$user_status = htmlspecialchars($data["user_status"]);

		// query update data
		$query = "UPDATE user SET 
						user_nama      = '$user_nama',
						user_no_hp     = '$user_no_hp',
						user_alamat    = '$user_alamat',
						user_email     = '$user_email',
						user_password  = '$user_password',
						user_level     = '$user_level',
						user_status    = '$user_status'
						WHERE user_id  = $id
				";
		// var_dump($query); die();
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);

}

function hapusUser($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM user WHERE user_id = $id");

	return mysqli_affected_rows($conn);
}
// ========================================= Toko ======================================== //
function tambahToko($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$toko_nama      = htmlspecialchars($data["toko_nama"]);
	$toko_kota      = htmlspecialchars($data["toko_kota"]);
	$toko_alamat    = htmlspecialchars($data["toko_alamat"]);
	$toko_tlpn      = htmlspecialchars($data["toko_tlpn"]);
	$toko_wa        = htmlspecialchars($data["toko_wa"]);
	$toko_email     = htmlspecialchars($data["toko_email"]);
	$toko_print     = htmlspecialchars($data["toko_print"]);
	$toko_status    = htmlspecialchars($data["toko_status"]);
	$toko_ongkir    = htmlspecialchars($data["toko_ongkir"]);
	$toko_cabang    = htmlspecialchars($data["toko_cabang"]);

	
	// query insert data toko
	$query = "INSERT INTO toko VALUES ('', '$toko_nama', '$toko_kota', '$toko_alamat', '$toko_tlpn', '$toko_wa', '$toko_email', '$toko_print' ,'$toko_status', '$toko_ongkir', '$toko_cabang')";
	mysqli_query($conn, $query);

	// query insert data laba bersih
	$query2 = "INSERT INTO laba_bersih VALUES ('', '', '', '', '', '', '', '' ,'', '', '$toko_cabang')";
	mysqli_query($conn, $query2);


	return mysqli_affected_rows($conn);
}

function editToko($data) {
	global $conn;
	$id = $data["toko_id"];

	// ambil data dari tiap elemen dalam form
	$toko_nama      = htmlspecialchars($data["toko_nama"]);
	$toko_kota      = htmlspecialchars($data["toko_kota"]);
	$toko_alamat    = htmlspecialchars($data["toko_alamat"]);
	$toko_tlpn      = htmlspecialchars($data["toko_tlpn"]);
	$toko_wa        = htmlspecialchars($data["toko_wa"]);
	$toko_email     = htmlspecialchars($data["toko_email"]);
	$toko_print     = htmlspecialchars($data["toko_print"]);
	$toko_status    = htmlspecialchars($data["toko_status"]);
	$toko_ongkir    = htmlspecialchars($data["toko_ongkir"]);

	// query update data
	$query = "UPDATE toko SET 
				toko_nama       = '$toko_nama',
				toko_kota       = '$toko_kota',
				toko_alamat     = '$toko_alamat',
				toko_tlpn       = '$toko_tlpn',
				toko_wa         = '$toko_wa',
				toko_email      = '$toko_email',
				toko_print      = '$toko_print',
				toko_status     = '$toko_status',
				toko_ongkir		= '$toko_ongkir'
				WHERE toko_id   = $id
				";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}
function hapusToko($id) {
	global $conn;

	$cabang = mysqli_query($conn, "select toko_cabang from toko where toko_id = ".$id." ");
	$cabang = mysqli_fetch_array($cabang);
	$toko_cabang = $cabang['toko_cabang'];

	mysqli_query( $conn, "DELETE FROM toko WHERE toko_id = $id");
	mysqli_query( $conn, "DELETE FROM laba_bersih WHERE lb_cabang = $toko_cabang");

	mysqli_query( $conn, "DELETE FROM supplier WHERE supplier_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM kategori WHERE kategori_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM satuan WHERE satuan_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM barang WHERE barang_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM barang_sn WHERE barang_sn_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM invoice_pembelian WHERE invoice_pembelian_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM pembelian WHERE pembelian_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM transfer WHERE transfer_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM transfer_produk_keluar WHERE tpk_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM transfer_produk_masuk WHERE tpm_cabang = $toko_cabang");
	mysqli_query( $conn, "DELETE FROM user WHERE user_cabang = $toko_cabang");

	return mysqli_affected_rows($conn);
}

// ========================================= Kategori ======================================= //
function tambahKategori($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$kategori_nama = htmlspecialchars($data['kategori_nama']);
	$kategori_status = $data['kategori_status'];
	$kategori_cabang = $data['kategori_cabang'];

	// query insert data
	$query = "INSERT INTO kategori VALUES ('', '$kategori_nama', '$kategori_status', '$kategori_cabang')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function editKategori($data) {
	global $conn;
	$id = $data["kategori_id"];

	// ambil data dari tiap elemen dalam form
	$kategori_nama = htmlspecialchars($data['kategori_nama']);
	$kategori_status = $data['kategori_status'];

	// query update data
	$query = "UPDATE kategori SET 
				kategori_nama   = '$kategori_nama',
				kategori_status = '$kategori_status'
				WHERE kategori_id = $id
				";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

function hapusKategori($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM kategori WHERE kategori_id = $id");

	return mysqli_affected_rows($conn);
}


// ======================================= Satuan ========================================= //
function tambahSatuan($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$satuan_nama = htmlspecialchars($data['satuan_nama']);
	$satuan_status = $data['satuan_status'];
	$satuan_cabang = $data['satuan_cabang'];

	// query insert data
	$query = "INSERT INTO satuan VALUES ('', '$satuan_nama', '$satuan_status', '$satuan_cabang')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function editSatuan($data) {
	global $conn;
	$id = $data["satuan_id"];

	// ambil data dari tiap elemen dalam form
	$satuan_nama = htmlspecialchars($data['satuan_nama']);
	$satuan_status = $data['satuan_status'];

	// query update data
	$query = "UPDATE satuan SET 
				satuan_nama   = '$satuan_nama',
				satuan_status = '$satuan_status'
				WHERE satuan_id = $id
				";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

function hapusSatuan($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM satuan WHERE satuan_id = $id");

	return mysqli_affected_rows($conn);
}


// ===================================== ekspedisi ========================================= //
function tambahEkspedisi($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$ekspedisi_nama = htmlspecialchars($data['ekspedisi_nama']);
	$ekspedisi_status = $data['ekspedisi_status'];
	$ekspedisi_cabang = $data['ekspedisi_cabang'];

	// query insert data
	$query = "INSERT INTO ekspedisi VALUES ('', '$ekspedisi_nama', '$ekspedisi_status', '$ekspedisi_cabang')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function editEkspedisi($data) {
	global $conn;
	$id = $data["ekspedisi_id"];

	// ambil data dari tiap elemen dalam form
	$ekspedisi_nama = htmlspecialchars($data['ekspedisi_nama']);
	$ekspedisi_status = $data['ekspedisi_status'];

	// query update data
	$query = "UPDATE ekspedisi SET 
				ekspedisi_nama   = '$ekspedisi_nama',
				ekspedisi_status = '$ekspedisi_status'
				WHERE ekspedisi_id = $id
				";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

function hapusEkspedisi($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM ekspedisi WHERE ekspedisi_id = $id");

	return mysqli_affected_rows($conn);
}


// ======================================== Barang =============================== //
function tambahBarang($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$barang_kode      			= htmlspecialchars($data["barang_kode"]);
	$barang_kode_slug			= str_replace(" ", "-", $barang_kode);
	$barang_kode_count  		= htmlspecialchars($data["barang_kode_count"]);
	$barang_nama      			= htmlspecialchars($data["barang_nama"]);
	$barang_deskripsi 			= htmlspecialchars($data["barang_deskripsi"]);

	$barang_harga     			= htmlspecialchars($data["barang_harga"]);
	$barang_harga_grosir_1     	= htmlspecialchars($data["barang_harga_grosir_1"]);
	$barang_harga_grosir_2     	= htmlspecialchars($data["barang_harga_grosir_2"]);

	$barang_harga_s2     		= htmlspecialchars($data["barang_harga_s2"]);
	$barang_harga_grosir_1_s2   = htmlspecialchars($data["barang_harga_grosir_1_s2"]);
	$barang_harga_grosir_2_s2   = htmlspecialchars($data["barang_harga_grosir_2_s2"]);

	$barang_harga_s3     		= htmlspecialchars($data["barang_harga_s3"]);
	$barang_harga_grosir_1_s3   = htmlspecialchars($data["barang_harga_grosir_1_s3"]);
	$barang_harga_grosir_2_s3   = htmlspecialchars($data["barang_harga_grosir_2_s3"]);

	$kategori_id      			= $data["kategori_id"];


	$satuan_id        			= $data["satuan_id"] != '' ? $data["satuan_id"] : 0;
	$satuan_id_2        		= $data["satuan_id_2"] != '' ? $data["satuan_id_2"] : 0;
	$satuan_id_3        		= $data["satuan_id_3"] != '' ? $data["satuan_id_3"] : 0;

	$satuan_isi_1 				= 1;
	$satuan_isi_2        		= $data["satuan_isi_2"] != '' ? $data["satuan_isi_2"] : 0;
	$satuan_isi_3        		= $data["satuan_isi_3"] != '' ? $data["satuan_isi_3"] : 0;


	$barang_tanggal   			= date("d F Y g:i:s a");
	$barang_stock     			= htmlspecialchars($data["barang_stock"]);
	$barang_harga_beli     		= htmlspecialchars($data["barang_harga_beli"]);
	$barang_option_sn 			= $data["barang_option_sn"];
	$barang_cabang				= $data["barang_cabang"];

	// Cek Email
	$barang_kode_cek = mysqli_num_rows(mysqli_query($conn, "select * from barang where barang_kode = '".$barang_kode."' && barang_cabang = ".$barang_cabang." "));

	if ( $barang_kode_cek > 0 ) {
		echo "
			<script>
				alert('Kode Barang Sudah Ada Coba Kode yang Lain !!!');
			</script>
		";
	} else {
		// query insert data
		// $query = "INSERT INTO barang VALUES ('', '$barang_kode', '$barang_kode_slug', '$barang_kode_count', '$barang_nama', '$barang_harga_beli', '$barang_harga', '$barang_harga_grosir_1', '$barang_harga_grosir_2', '$barang_harga_s2', '$barang_harga_grosir_1_s2', '$barang_harga_grosir_2_s2', '$barang_harga_s3', '$barang_harga_grosir_1_s3', '$barang_harga_grosir_2_s3', '$barang_stock', '$barang_tanggal', '$kategori_id', '$kategori_id', '$satuan_id', '$satuan_id', '$satuan_id_2', '$satuan_id_3', '$satuan_isi_1', '$satuan_isi_2', '$satuan_isi_3', '$barang_deskripsi', '$barang_option_sn', '', '$barang_cabang')";
		$query = "INSERT INTO barang (barang_kode, barang_kode_slug, barang_kode_count, barang_nama, barang_harga_beli, barang_harga, barang_harga_grosir_1, barang_harga_grosir_2, barang_harga_s2, barang_harga_grosir_1_s2, barang_harga_grosir_2_s2, barang_harga_s3, barang_harga_grosir_1_s3, barang_harga_grosir_2_s3, barang_stock, barang_tanggal, barang_kategori_id, kategori_id, barang_satuan_id, satuan_id, satuan_id_2, satuan_id_3, satuan_isi_1, satuan_isi_2, satuan_isi_3, barang_deskripsi, barang_option_sn, barang_terjual, barang_cabang)
				  VALUES ('$barang_kode', '$barang_kode_slug', '$barang_kode_count', '$barang_nama', '$barang_harga_beli', '$barang_harga', '$barang_harga_grosir_1', '$barang_harga_grosir_2', '$barang_harga_s2', '$barang_harga_grosir_1_s2', '$barang_harga_grosir_2_s2', '$barang_harga_s3', '$barang_harga_grosir_1_s3', '$barang_harga_grosir_2_s3', '$barang_stock', '$barang_tanggal', '$kategori_id', '$kategori_id', '$satuan_id', '$satuan_id', '$satuan_id_2', '$satuan_id_3', '$satuan_isi_1', '$satuan_isi_2', '$satuan_isi_3', '$barang_deskripsi', '$barang_option_sn', 0, '$barang_cabang')";
		mysqli_query($conn, $query);
		
		// echo $conn->error;
		// die;

		return mysqli_affected_rows($conn);
	}
}

function editBarang($data) {
	global $conn;
	$id = $data["barang_id"];

	// ambil data dari tiap elemen dalam form
	$barang_kode      			= htmlspecialchars($data["barang_kode"]);
	$barang_nama      			= htmlspecialchars($data["barang_nama"]);
	$barang_deskripsi 			= htmlspecialchars($data["barang_deskripsi"]);

	$barang_harga     			= htmlspecialchars($data["barang_harga"]);
	$barang_harga_grosir_1     	= htmlspecialchars($data["barang_harga_grosir_1"]);
	$barang_harga_grosir_2     	= htmlspecialchars($data["barang_harga_grosir_2"]);

	$barang_harga_s2     		= htmlspecialchars($data["barang_harga_s2"]);
	$barang_harga_grosir_1_s2   = htmlspecialchars($data["barang_harga_grosir_1_s2"]);
	$barang_harga_grosir_2_s2   = htmlspecialchars($data["barang_harga_grosir_2_s2"]);

	$barang_harga_s3     		= htmlspecialchars($data["barang_harga_s3"]);
	$barang_harga_grosir_1_s3   = htmlspecialchars($data["barang_harga_grosir_1_s3"]);
	$barang_harga_grosir_2_s3   = htmlspecialchars($data["barang_harga_grosir_2_s3"]);

	$kategori_id      			= $data["kategori_id"];

	$satuan_id        			= $data["satuan_id"] != '' ? $data["satuan_id"] : 0;
	$satuan_id_2        		= $data["satuan_id_2"] != '' ? $data["satuan_id_2"] : 0;
	$satuan_id_3        		= $data["satuan_id_3"] != '' ? $data["satuan_id_3"] : 0;

	$satuan_isi_2        		= $data["satuan_isi_2"] != '' ? $data["satuan_isi_2"] : 0;
	$satuan_isi_3        		= $data["satuan_isi_3"] != '' ? $data["satuan_isi_3"] : 0;

	$barang_stock     			= htmlspecialchars($data["barang_stock"]);
	$barang_harga_beli     		= htmlspecialchars($data["barang_harga_beli"]);
	$barang_option_sn 			= $data["barang_option_sn"];

	// query update data
	$query = "UPDATE barang SET 
				barang_kode       		= '$barang_kode',
				barang_nama       		= '$barang_nama',
				barang_harga      		= '$barang_harga',
				barang_harga_beli      	= '$barang_harga_beli',
				barang_harga_grosir_1   = '$barang_harga_grosir_1',
				barang_harga_grosir_2   = '$barang_harga_grosir_2',
				barang_harga_s2      	= '$barang_harga_s2',
				barang_harga_grosir_1_s2= '$barang_harga_grosir_1_s2',
				barang_harga_grosir_2_s2= '$barang_harga_grosir_2_s2',
				barang_harga_s3      	= '$barang_harga_s3',
				barang_harga_grosir_1_s3= '$barang_harga_grosir_1_s3',
				barang_harga_grosir_2_s3= '$barang_harga_grosir_2_s3',
				barang_stock      		= '$barang_stock',
				barang_kategori_id      = '$kategori_id',
				kategori_id       		= '$kategori_id',
				satuan_id         		= '$satuan_id',
				satuan_id_2         	= '$satuan_id_2',
				satuan_id_3         	= '$satuan_id_3',
				satuan_isi_2         	= '$satuan_isi_2',
				satuan_isi_3         	= '$satuan_isi_3',
				barang_deskripsi  		= '$barang_deskripsi',
				barang_option_sn  		= '$barang_option_sn'
				WHERE barang_id   		= $id
				";
	return mysqli_query($conn, $query);

	// echo $conn->err_no;die;
	// return mysqli_affected_rows($conn);
}

function hapusBarang($id) {
	global $conn;

	// Ambil ID produk
	$data_id = $id;

	// Mencari No. Invoice
	$sn = mysqli_query( $conn, "select barang_option_sn from barang where barang_id = '".$data_id."'");
    $sn = mysqli_fetch_array($sn); 
    $sn = $sn["barang_option_sn"];

    $barang = mysqli_query($conn, "select barang_kode_slug, barang_cabang from barang where barang_id = ".$data_id." ");
    $barang = mysqli_fetch_array($barang);
    $barang_kode_slug 	= $barang['barang_kode_slug'];
    $barang_cabang 		= $barang['barang_cabang'];

    $countBarangSn = mysqli_query($conn, "select * from barang_sn where barang_kode_slug = '".$barang_kode_slug."' && barang_sn_status > 0 && barang_sn_cabang = ".$barang_cabang." ");
    $countBarangSn = mysqli_num_rows($countBarangSn);

    if ( $sn < 1 ) {
    	mysqli_query( $conn, "DELETE FROM barang WHERE barang_id = $id");
    	return mysqli_affected_rows($conn);
    } else {
    	mysqli_query( $conn, "DELETE FROM barang WHERE barang_id = $id");
    	
    	if ( $countBarangSn > 0 ) {
    		mysqli_query( $conn, "DELETE FROM barang_sn WHERE barang_kode_slug = '".$barang_kode_slug."' && barang_sn_status > 0 && barang_sn_cabang = $barang_cabang ");
    	}
    	return mysqli_affected_rows($conn);
    }

	
}

// ===================================== Barang SN ========================================= //
function tambahBarangSn($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$barang_sn_desc 			= $data['barang_sn_desc'];
	$barang_kode_slug 			= $data['barang_kode_slug'];
	$barang_sn_status 			= $data['barang_sn_status'];
	$barang_sn_cabang 			= $data['barang_sn_cabang'];

	$jumlah = count($barang_kode_slug);

	// query insert data
	for( $x=0; $x<$jumlah; $x++ ){
		$query = "INSERT INTO barang_sn VALUES ('', '$barang_sn_desc[$x]', '$barang_kode_slug[$x]', '$barang_sn_status[$x]', '$barang_sn_cabang[$x]')";

		mysqli_query($conn, $query);
	}

	return mysqli_affected_rows($conn);
}

function editBarangSn($data) {
	global $conn;
	$id = $data["barang_sn_id"];

	// ambil data dari tiap elemen dalam form
	$barang_sn_desc 	= htmlspecialchars($data['barang_sn_desc']);
	$barang_sn_status 	= $data['barang_sn_status'];

	// query update data
	$query = "UPDATE barang_sn SET 
				barang_sn_desc    = '$barang_sn_desc',
				barang_sn_status  = '$barang_sn_status'
				WHERE barang_sn_id = $id
				";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

function hapusBarangSn($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM barang_sn WHERE barang_sn_id = $id");

	return mysqli_affected_rows($conn);
}

// ===================================== Keranjang ========================================= //
function tambahKeranjang($keranjang_cabang, 
	$barang_id, 
	$barang_kode_slug, 
	$keranjang_nama, 
	$keranjang_harga_beli, 
	$keranjang_harga, 
	$keranjang_satuan, 
	$keranjang_id_kasir, 
	$keranjang_qty, 
	$keranjang_konversi_isi, 
	$keranjang_barang_sn_id, 
	$keranjang_barang_option_sn, 
	$keranjang_sn, 
	$keranjang_id_cek, 
	$customer_category) {
	global $conn;

	// Cek STOCK
	$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang where keranjang_id_cek = '$keranjang_id_cek' "));
		
	if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
		$keranjangParent = mysqli_query( $conn, "select keranjang_qty, keranjang_qty_view, keranjang_konversi_isi from keranjang where keranjang_id_cek = '".$keranjang_id_cek."'");
        $kp = mysqli_fetch_array($keranjangParent); 
        // $kp += $keranjang_qty;
        $keranjang_qty_view_keranjang 		= $kp['keranjang_qty_view'];
        $keranjang_qty_keranjang 			= $kp['keranjang_qty'];
        $keranjang_konversi_isi_keranjang 	= $kp['keranjang_konversi_isi'];

        $kqvk = $keranjang_qty_view_keranjang + $keranjang_qty;
        $kqkk = $keranjang_qty_keranjang + $keranjang_konversi_isi_keranjang;

        $query = "UPDATE keranjang SET 
					keranjang_qty   	= '$kqkk',
					keranjang_qty_view  = '$kqvk'
					WHERE keranjang_id_cek = $keranjang_id_cek
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);

	} else {
		// query insert data
		// $query = "INSERT INTO keranjang VALUES ('', 
		// '$keranjang_nama', 
		// '$keranjang_harga_beli', 
		// '$keranjang_harga',
		// '$keranjang_harga', 
		// '0',
		// '$keranjang_satuan', 
		// '$barang_id', 
		// '$barang_kode_slug', 
		// '$keranjang_qty', 
		// '$keranjang_qty', 
		// '$keranjang_konversi_isi', 
		// '$keranjang_barang_sn_id', 
		// '$keranjang_barang_option_sn', 
		// '$keranjang_sn', 
		// '$keranjang_id_kasir', 
		// '$keranjang_id_cek', 
		// '$customer', 
		// '$keranjang_cabang')";

		$query = "INSERT INTO keranjang(
			keranjang_nama,
			keranjang_harga_beli,
			keranjang_harga,
			keranjang_harga_parent,
			keranjang_harga_edit,
			keranjang_satuan,
			barang_id,
			barang_kode_slug,
			keranjang_qty,
			keranjang_qty_view,
			keranjang_konversi_isi,
			keranjang_barang_sn_id,
			keranjang_barang_option_sn,
			keranjang_sn,
			keranjang_id_kasir,
			keranjang_id_cek,
			keranjang_tipe_customer,
			keranjang_cabang)
			VALUES
			(
			'$keranjang_nama', 
			'$keranjang_harga_beli', 
			'$keranjang_harga',
			'$keranjang_harga', 
			'0',
			'$keranjang_satuan', 
			'$barang_id', 
			'$barang_kode_slug', 
			'$keranjang_qty', 
			'$keranjang_qty', 
			'$keranjang_konversi_isi', 
			'$keranjang_barang_sn_id', 
			'$keranjang_barang_option_sn', 
			'$keranjang_sn', 
			'$keranjang_id_kasir', 
			'$keranjang_id_cek', 
			'$customer_category', 
			'$keranjang_cabang'
			)";
		
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);
	}
}

function tambahKeranjangDraft($keranjang_cabang, 
	$barang_id, 
	$barang_kode_slug, 
	$keranjang_nama, 
	$keranjang_harga_beli, 
	$keranjang_harga, 
	$keranjang_satuan, 
	$keranjang_id_kasir, 
	$keranjang_qty, 
	$keranjang_konversi_isi, 
	$keranjang_barang_sn_id, 
	$keranjang_barang_option_sn, 
	$keranjang_sn, 
	$keranjang_id_cek, 
	$invoice,
	$customer) {
	global $conn;


	// Cek STOCK
	$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_draft where barang_id = ".$barang_id." && keranjang_invoice = ".$invoice." && keranjang_cabang = ".$keranjang_cabang." "));

	if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
		$keranjangParent = mysqli_query( $conn, "select keranjang_qty, keranjang_qty_view, keranjang_konversi_isi from keranjang_draft where keranjang_id_cek = '".$keranjang_id_cek."'");
        $kp = mysqli_fetch_array($keranjangParent); 
        // $kp += $keranjang_qty;
        $keranjang_qty_view_keranjang 		= $kp['keranjang_qty_view'];
        $keranjang_qty_keranjang 			= $kp['keranjang_qty'];
        $keranjang_konversi_isi_keranjang 	= $kp['keranjang_konversi_isi'];

        $kqvk = $keranjang_qty_view_keranjang + $keranjang_qty;
        $kqkk = $keranjang_qty_keranjang + $keranjang_konversi_isi_keranjang;

        $query = "UPDATE keranjang_draft SET 
					keranjang_qty   	= '$kqkk',
					keranjang_qty_view  = '$kqvk'
					WHERE keranjang_id_cek = $keranjang_id_cek
					";

		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);

	} else {
		// query insert data
		$query = "INSERT INTO keranjang_draft VALUES ('', 
		'$keranjang_nama', 
		'$keranjang_harga_beli', 
		'$keranjang_harga',
		'$keranjang_harga', 
		'0', 
		'$keranjang_satuan', 
		'$barang_id', 
		'$barang_kode_slug', 
		'$keranjang_qty', 
		'$keranjang_qty', 
		'$keranjang_konversi_isi', 
		'$keranjang_barang_sn_id', 
		'$keranjang_barang_option_sn', 
		'$keranjang_sn', 
		'$keranjang_id_kasir', 
		'$keranjang_id_cek', 
		'$customer', 
		'1',
		'$invoice',
		'$keranjang_cabang')";
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);
	}
}

function tambahKeranjangBarcode($data) {
	global $conn;

	$barang_kode 		= htmlspecialchars($data['inputbarcode']);
	$keranjang_id_kasir = $data['keranjang_id_kasir'];
	$tipe_harga 		= $data['tipe_harga'];
	$keranjang_cabang   = $data['keranjang_cabang'];

	// Ambil Data Barang berdasarkan Kode Barang 
	$barang 	= mysqli_query( $conn, "select barang_id, 
		barang_nama, 
		barang_harga_beli, 
		barang_harga, 
		barang_harga_grosir_1, 
		barang_harga_grosir_2, 
		barang_stock, 
		barang_kode_slug, 
		satuan_id,
		satuan_isi_1,
		barang_option_sn from barang where barang_kode = '".$barang_kode."' && barang_cabang = ".$keranjang_cabang." ");
    $br 		= mysqli_fetch_array($barang);

    $barang_id  				= $br["barang_id"];
    $keranjang_nama  			= $br["barang_nama"];
    $keranjang_harga_beli  		= $br["barang_harga_beli"];
    $keranjang_satuan           = $br["satuan_id"];
    $keranjang_konversi_isi     = $br["satuan_isi_1"];

    if ( $tipe_harga == 1 ) {
      	$keranjang_harga  = $br["barang_harga_grosir_1"];
  	} elseif ( $tipe_harga == 2 ) {
      	$keranjang_harga  = $br["barang_harga_grosir_2"];
  	} else {
      	$keranjang_harga  = $br["barang_harga"];
  	}
    
    $barang_stock 				= $br["barang_stock"];
    $barang_kode_slug 			= $br["barang_kode_slug"];
    $keranjang_barang_option_sn = $br["barang_option_sn"];
    $keranjang_qty      		= 1;
    $keranjang_konversi_isi     = $br['satuan_isi_1'];
	$keranjang_barang_sn_id     = 0;
	$keranjang_sn       		= 0;
	$keranjang_tipe_customer    = $tipe_harga;
	$keranjang_id_cek   		= $barang_id.$keranjang_id_kasir.$keranjang_cabang;


	// Kondisi jika scan Barcode Tidak sesuai
	if ( $barang_id != null ) {

		// Cek apakah data barang sudah sesuai dengan jumlah stok saat Insert Ke Keranjang dan jika melebihi stok maka akan dikembalikan
		$idBarang = mysqli_query($conn, "select keranjang_qty, keranjang_konversi_isi, keranjang_tipe_customer from keranjang where barang_id = ".$barang_id." ");
    	$idBarang = mysqli_fetch_array($idBarang);
   		$keranjang_qty_stock = $idBarang['keranjang_qty'] * $idBarang['keranjang_konversi_isi'];

   		if ( $keranjang_qty_stock >= $barang_stock ) {
	   		echo '
				<script>
					alert("Produk TIDAK BISA DITAMBAHKAN Karena Jumlah QTY Melebihi Stock yang Ada di Semua Transaksi Kasir & Mohon di Cek Kembali !!!");
					document.location.href = "";
				</script>
			';
	   	} else {
	   		// Cek STOCK
			$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang where keranjang_id_cek = ".$keranjang_id_cek." "));
				
			if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
				$keranjangParent = mysqli_query( $conn, "select keranjang_qty, keranjang_qty_view, keranjang_konversi_isi from keranjang where keranjang_id_cek = '".$keranjang_id_cek."'");
		        $kp = mysqli_fetch_array($keranjangParent); 
		        // $kp += $keranjang_qty;
		        $keranjang_qty_view_keranjang 		= $kp['keranjang_qty_view'];
		        $keranjang_qty_keranjang 			= $kp['keranjang_qty'];
		        $keranjang_konversi_isi_keranjang 	= $kp['keranjang_konversi_isi'];

		        $kqvk = $keranjang_qty_view_keranjang + $keranjang_qty;
		        $kqkk = $keranjang_qty_keranjang + $keranjang_konversi_isi_keranjang;

		        $query = "UPDATE keranjang SET 
							keranjang_qty   	= '$kqkk',
							keranjang_qty_view  = '$kqvk'
							WHERE keranjang_id_cek = $keranjang_id_cek
							";
				mysqli_query($conn, $query);
				return mysqli_affected_rows($conn);

			} else {
				// query insert data
				$query = "INSERT INTO keranjang VALUES ('', 
				'$keranjang_nama', 
				'$keranjang_harga_beli', 
				'$keranjang_harga',
				'$keranjang_harga', 
				'0',
				'$keranjang_satuan',
				'$barang_id', 
				'$barang_kode_slug', 
				'$keranjang_qty', 
				'$keranjang_qty',
				'$keranjang_konversi_isi',
				'$keranjang_barang_sn_id', 
				'$keranjang_barang_option_sn', 
				'$keranjang_sn', 
				'$keranjang_id_kasir', 
				'$keranjang_id_cek', 
				'$keranjang_tipe_customer',
				'$keranjang_cabang')";
				mysqli_query($conn, $query);

				return mysqli_affected_rows($conn);
			}
	   	}
	} else {
		echo '
			<script>
				alert("Kode Produk Tidak ada di Data Master Barang dan Coba Cek Kembali !! ");
				document.location.href = "";
			</script>
		';
	}
}

function tambahKeranjangBarcodeDraft($data) {
	global $conn;

	$barang_kode 		= htmlspecialchars($data['inputbarcodeDraft']);
	$keranjang_id_kasir = $data['keranjang_id_kasir'];
	$tipe_harga 		= $data['tipe_harga'];
	$keranjang_invoice  = $data['keranjang_invoice'];
	$keranjang_cabang   = $data['keranjang_cabang'];

	// Ambil Data Barang berdasarkan Kode Barang 
	$barang 	= mysqli_query( $conn, "select barang_id, 
		barang_nama, 
		barang_harga_beli, 
		barang_harga, 
		barang_harga_grosir_1, 
		barang_harga_grosir_2, 
		barang_stock, 
		barang_kode_slug, 
		satuan_id,
		satuan_isi_1,
		barang_option_sn from barang where barang_kode = '".$barang_kode."' && barang_cabang = ".$keranjang_cabang." ");
    $br 		= mysqli_fetch_array($barang);

    $barang_id  				= $br["barang_id"];
    $keranjang_nama  			= $br["barang_nama"];
    $keranjang_harga_beli  		= $br["barang_harga_beli"];
    $keranjang_satuan           = $br["satuan_id"];
    $keranjang_konversi_isi     = $br["satuan_isi_1"];

    if ( $tipe_harga == 1 ) {
      	$keranjang_harga  = $br["barang_harga_grosir_1"];
  	} elseif ( $tipe_harga == 2 ) {
      	$keranjang_harga  = $br["barang_harga_grosir_2"];
  	} else {
      	$keranjang_harga  = $br["barang_harga"];
  	}
    
    $barang_stock 				= $br["barang_stock"];
    $barang_kode_slug 			= $br["barang_kode_slug"];
    $keranjang_barang_option_sn = $br["barang_option_sn"];
    $keranjang_qty      		= 1;
    $keranjang_konversi_isi     = $br['satuan_isi_1'];
	$keranjang_barang_sn_id     = 0;
	$keranjang_sn       		= 0;
	$keranjang_tipe_customer    = $tipe_harga;
	$keranjang_id_cek   		= $barang_id.$keranjang_id_kasir.$keranjang_cabang;


	// Kondisi jika scan Barcode Tidak sesuai
	if ( $barang_id != null ) {

		// Cek apakah data barang sudah sesuai dengan jumlah stok saat Insert Ke Keranjang dan jika melebihi stok maka akan dikembalikan
		$idBarang = mysqli_query($conn, "select keranjang_qty, keranjang_konversi_isi, keranjang_tipe_customer from keranjang_draft where barang_id = ".$barang_id." ");
    	$idBarang = mysqli_fetch_array($idBarang);
   		$keranjang_qty_stock = $idBarang['keranjang_qty'] + $idBarang['keranjang_konversi_isi'];

   		if ( $keranjang_qty_stock >= $barang_stock ) {
	   		echo '
				<script>
					alert("Produk TIDAK BISA DITAMBAHKAN Karena Jumlah QTY Melebihi Stock yang Ada di Semua Transaksi Kasir & Mohon di Cek Kembali !!!");
					document.location.href = "";
				</script>
			';
	   	} else {
	   		// Cek STOCK
			$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_draft where barang_id = ".$barang_id." && keranjang_invoice = ".$keranjang_invoice." && keranjang_cabang = ".$keranjang_cabang." "));
				
			if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
				$keranjangParent = mysqli_query( $conn, "select keranjang_qty, keranjang_qty_view, keranjang_konversi_isi from keranjang_draft where keranjang_id_cek = '".$keranjang_id_cek."'");
		        $kp = mysqli_fetch_array($keranjangParent); 
		        // $kp += $keranjang_qty;
		        $keranjang_qty_view_keranjang 		= $kp['keranjang_qty_view'];
		        $keranjang_qty_keranjang 			= $kp['keranjang_qty'];
		        $keranjang_konversi_isi_keranjang 	= $kp['keranjang_konversi_isi'];

		        $kqvk = $keranjang_qty_view_keranjang + $keranjang_qty;
		        $kqkk = $keranjang_qty_keranjang + $keranjang_konversi_isi_keranjang;

		        $query = "UPDATE keranjang_draft SET 
							keranjang_qty   	= '$kqkk',
							keranjang_qty_view  = '$kqvk'
							WHERE keranjang_id_cek = $keranjang_id_cek
							";
				mysqli_query($conn, $query);
				return mysqli_affected_rows($conn);

			} else {
				// query insert data
				$query = "INSERT INTO keranjang_draft VALUES ('', 
				'$keranjang_nama', 
				'$keranjang_harga_beli', 
				'$keranjang_harga', 
				'$keranjang_harga', 
				'0',
				'$keranjang_satuan',
				'$barang_id', 
				'$barang_kode_slug', 
				'$keranjang_qty', 
				'$keranjang_qty',
				'$keranjang_konversi_isi',
				'$keranjang_barang_sn_id', 
				'$keranjang_barang_option_sn', 
				'$keranjang_sn', 
				'$keranjang_id_kasir', 
				'$keranjang_id_cek', 
				'$keranjang_tipe_customer',
				'1',
				'$keranjang_invoice',
				'$keranjang_cabang')";
				mysqli_query($conn, $query);

				return mysqli_affected_rows($conn);
			}
	   	}
	} else {
		echo '
			<script>
				alert("Kode Produk Tidak ada di Data Master Barang dan Coba Cek Kembali !! ");
				document.location.href = "";
			</script>
		';
	}
}

function updateSn($data){
	global $conn;
	$id = $data["keranjang_id"];


	// ambil data dari tiap elemen dalam form
	$barang_sn_id  = $data["barang_sn_id"];


	$barang_sn_desc = mysqli_query( $conn, "select barang_sn_desc from barang_sn where barang_sn_id = '".$barang_sn_id."'");
    $barang_sn_desc = mysqli_fetch_array($barang_sn_desc); 
    $barang_sn_desc = $barang_sn_desc['barang_sn_desc'];

	// query update data
	$query = "UPDATE keranjang SET 
						keranjang_barang_sn_id  = '$barang_sn_id',
						keranjang_sn            = '$barang_sn_desc'
						WHERE keranjang_id      = $id
				";

	$query2 = "UPDATE barang_sn SET 
						barang_sn_status     = 0
						WHERE barang_sn_id = $barang_sn_id
				";

	mysqli_query($conn, $query);
	mysqli_query($conn, $query2);

	return mysqli_affected_rows($conn);

}

function updateSnDrfat($data){
	global $conn;
	$id = $data["keranjang_draf_id"];


	// ambil data dari tiap elemen dalam form
	$barang_sn_id  = $data["barang_sn_id"];


	$barang_sn_desc = mysqli_query( $conn, "select barang_sn_desc from barang_sn where barang_sn_id = '".$barang_sn_id."'");
    $barang_sn_desc = mysqli_fetch_array($barang_sn_desc); 
    $barang_sn_desc = $barang_sn_desc['barang_sn_desc'];

	// query update data
	$query = "UPDATE keranjang_draft SET 
						keranjang_barang_sn_id  = '$barang_sn_id',
						keranjang_sn            = '$barang_sn_desc'
						WHERE keranjang_draf_id      = $id
				";

	$query2 = "UPDATE barang_sn SET 
						barang_sn_status     = 0
						WHERE barang_sn_id = $barang_sn_id
				";

	mysqli_query($conn, $query);
	mysqli_query($conn, $query2);

	return mysqli_affected_rows($conn);

}

// function updateHarga($data){
// 	global $conn;
// 	$id 				= $data["keranjang_id"];
// 	$keranjang_harga 	= htmlspecialchars($data["keranjang_harga"]);

// 	$query = "UPDATE keranjang SET 
// 						keranjang_harga  		= '$keranjang_harga'
// 						WHERE keranjang_id      = $id
// 				";

// 	mysqli_query($conn, $query);
// 	return mysqli_affected_rows($conn);
// }

// function updateQTY($data) {
// 	global $conn;
// 	$id = $data["keranjang_id"];

// 	// ambil data dari tiap elemen dalam form
// 	$keranjang_qty = htmlspecialchars($data['keranjang_qty']);
// 	$stock_brg = $data['stock_brg'];

// 	if ( $keranjang_qty > $stock_brg ) {
// 		echo"
// 			<script>
// 				alert('QTY Melebihi Stock Barang.. Coba Cek Lagi !!!');
// 				document.location.href = 'beli-langsung.php';
// 			</script>
// 		";
// 	} else {
// 		// query update data
// 		$query = "UPDATE keranjang SET 
// 					keranjang_qty   = '$keranjang_qty'
// 					WHERE keranjang_id = $id
// 					";
// 		mysqli_query($conn, $query);
// 		return mysqli_affected_rows($conn);
// 	}
// }

function updateQTYHarga($data) {
	global $conn;
	$id = $data["keranjang_id"];

	// ambil data dari tiap elemen dalam form
	$keranjang_qty_view 		= htmlspecialchars($data['keranjang_qty_view']);
	$keranjang_barang_option_sn = $data['keranjang_barang_option_sn'];

	$keranjang_satuan_end_isi   = $data['keranjang_satuan_end_isi'];
	$pecah_data 				= explode("-",$keranjang_satuan_end_isi);

	if ( $keranjang_barang_option_sn < 1 ) {
		$keranjang_satuan   		= $pecah_data[0];
		$keranjang_konversi_isi 	= $pecah_data[1];
		$checkboxHarga              = $data['checkbox-harga'];
		if ( $checkboxHarga > 0 ) {
			$keranjang_harga 		= htmlspecialchars($data["keranjang_harga"]);
		} else {
			$keranjang_harga 	    = $pecah_data[2];
		}

	} else {
		$keranjang_satuan   		= $data['keranjang_satuan'];
		$keranjang_konversi_isi 	= $data['keranjang_konversi_isi'];
		$checkboxHarga              = $data['checkbox-harga'];
		$keranjang_harga 			= htmlspecialchars($data["keranjang_harga"]);
	}

	$stock_brg 			        = $data['stock_brg'];
	$keranjang_qty              = $keranjang_qty_view * $keranjang_konversi_isi;
	$checkboxHarga 				= $checkboxHarga != '' ? $checkboxHarga : 0;

	if ( $keranjang_qty > $stock_brg ) {
		echo"
			<script>
				alert('QTY Melebihi Stock Barang.. Coba Cek Lagi !!!');
				document.location.href = '';
			</script>
		";
	} else {
		// query update data
		$query = "UPDATE keranjang SET 
					keranjang_harga  		= '$keranjang_harga',
					keranjang_harga_edit  	= '$checkboxHarga',
					keranjang_satuan        = '$keranjang_satuan',
					keranjang_qty   		= '$keranjang_qty',
					keranjang_qty_view   	= '$keranjang_qty_view',
					keranjang_konversi_isi  = '$keranjang_konversi_isi'
					WHERE keranjang_id 		= $id
					";

		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
	}
}

function updateQTYHargaDraft($data) {
	global $conn;
	$id = $data["keranjang_draf_id"];


	// ambil data dari tiap elemen dalam form
	$keranjang_qty_view 		= htmlspecialchars($data['keranjang_qty_view']);
	$keranjang_barang_option_sn = $data['keranjang_barang_option_sn'];

	$keranjang_satuan_end_isi   = $data['keranjang_satuan_end_isi'];
	$pecah_data 				= explode("-",$keranjang_satuan_end_isi);
	$keranjang_satuan   		= $pecah_data[0];
	$keranjang_konversi_isi 	= $pecah_data[1];

	if ( $keranjang_barang_option_sn < 1 ) {
		$keranjang_harga 	        = $pecah_data[2];
	} else {
		$keranjang_harga 			= htmlspecialchars($data["keranjang_harga"]);
	}

	$stock_brg 			        = $data['stock_brg'];
	$keranjang_qty              = $keranjang_qty_view * $keranjang_konversi_isi;

	if ( $keranjang_qty > $stock_brg ) {
		echo"
			<script>
				alert('QTY Melebihi Stock Barang.. Coba Cek Lagi !!!');
				document.location.href = '';
			</script>
		";
	} else {
		// query update data
		$query = "UPDATE keranjang_draft SET 
					keranjang_harga  		= '$keranjang_harga',
					keranjang_satuan        = '$keranjang_satuan',
					keranjang_qty   		= '$keranjang_qty',
					keranjang_qty_view   	= '$keranjang_qty_view',
					keranjang_konversi_isi  = '$keranjang_konversi_isi'
					WHERE keranjang_draf_id 		= $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
	}
}

function hapusKeranjang($id) {
	global $conn;


	// Ambil ID produk
	$data_id = $id;

	// Mencari keranjang_barang_sn_id
	$keranjang_barang_sn_id = mysqli_query( $conn, "select keranjang_barang_sn_id from keranjang where keranjang_id = '".$data_id."'");
    $keranjang_barang_sn_id = mysqli_fetch_array($keranjang_barang_sn_id); 
    $keranjang_barang_sn_id = $keranjang_barang_sn_id["keranjang_barang_sn_id"];


    
    if ( $keranjang_barang_sn_id > 0 ) {
    	$query2 = "UPDATE barang_sn SET 
					barang_sn_status    = 1
					WHERE barang_sn_id  = $keranjang_barang_sn_id
					";
		mysqli_query($conn, $query2);
    }
    
	mysqli_query( $conn, "DELETE FROM keranjang WHERE keranjang_id = $id");

	return mysqli_affected_rows($conn);
}

function hapusKeranjangDraft($id) {
	global $conn;
	// Ambil ID produk
	$data_id = $id;

	// Mencari keranjang_barang_sn_id
	$keranjang_barang_sn_id = mysqli_query( $conn, "select keranjang_barang_sn_id from keranjang_draft where keranjang_draf_id = '".$data_id."'");
    $keranjang_barang_sn_id = mysqli_fetch_array($keranjang_barang_sn_id); 
    $keranjang_barang_sn_id = $keranjang_barang_sn_id["keranjang_barang_sn_id"];

    
    if ( $keranjang_barang_sn_id > 0 ) {
    	$query2 = "UPDATE barang_sn SET 
					barang_sn_status    = 1
					WHERE barang_sn_id  = $keranjang_barang_sn_id
					";
		mysqli_query($conn, $query2);
    }
    
	mysqli_query( $conn, "DELETE FROM keranjang_draft WHERE keranjang_draf_id = $id");

	return mysqli_affected_rows($conn);
}

function updateStock($data) {
	global $conn;
	global $sessionCabang;
	$id                  		= $data['barang_ids'];
	$keranjang_qty       		= $data['keranjang_qty'];
	$keranjang_qty_view       	= $data['keranjang_qty_view'];
	$keranjang_konversi_isi     = $data['keranjang_konversi_isi'];
	$keranjang_satuan           = $data['keranjang_satuan'];
	$keranjang_harga_beli       = $data['keranjang_harga_beli'];
	$keranjang_harga			= $data['keranjang_harga'];
	$keranjang_harga_parent		= $data['keranjang_harga_parent'];
	$keranjang_harga_edit		= $data['keranjang_harga_edit'];
	$keranjang_id_kasir  		= $data['keranjang_id_kasir'];
	$penjualan_invoice   		= $data['penjualan_invoice'];
	$keranjang_barang_option_sn = $data['keranjang_barang_option_sn'];
	$keranjang_barang_sn_id     = $data['keranjang_barang_sn_id'];
	$keranjang_sn               = $data['keranjang_sn'];
	$invoice_customer_category2 = $data['invoice_customer_category2'];
	// $penjualan_cabang        	= $data['penjualan_cabang'];

	$kik                 		= $_SESSION['user_id'];
	$penjualan_invoice2  		= $data['penjualan_invoice2'];
	$invoice_tgl         		= date("d F Y g:i:s a");
	$invoice_total_beli       	= $data['invoice_total_beli'];
	$invoice_total       		= $data['invoice_total'];
	$invoice_ongkir      		= htmlspecialchars($data['invoice_ongkir']);
	$invoice_diskon      		= htmlspecialchars($data['invoice_diskon']);
	
	$invoice_sub_total   		= $invoice_total + $invoice_ongkir;
	$invoice_sub_total   		= $invoice_sub_total - $invoice_diskon;
	$invoice_bayar       		= htmlspecialchars($data['angka1']);
	if ( $invoice_bayar == null ) {
		echo"
			<script>
				alert('Anda Belum Input Nominal BAYAR !!!');
				document.location.href = '';
			</script>
		";
	} 

	$invoice_kembali     		= $invoice_bayar - $invoice_sub_total;
	$invoice_date        		= $data['tanggal'];
	$invoice_date_year_month    = date("Y-m", strtotime($invoice_date ));
	// $penjualan_date      		= $data['tanggal'];
	$invoice_customer    		= $data['invoice_customer'];
	$invoice_customer_category  = $data['invoice_customer_category'];
	$invoice_kurir    	 		= $data['invoice_kurir'];
	$invoice_tipe_transaksi  	= $data['invoice_tipe_transaksi'];
	$penjualan_invoice_count 	= $data['penjualan_invoice_count'];
	$invoice_piutang			= $data['invoice_piutang'];
	if ( $invoice_piutang == 1 ) {
		$invoice_piutang_dp = $invoice_bayar;
	} else {
		$invoice_piutang_dp = 0;
	}
	$invoice_piutang_jatuh_tempo= $data['invoice_piutang_jatuh_tempo'];
	$invoice_piutang_lunas		= $data['invoice_piutang_lunas'];
	$invoice_cabang             = $sessionCabang;
	// $invoice_cabang             = $data['invoice_cabang'];
	

	if ( $invoice_customer == 1 ) {
		$invoice_marketplace = htmlspecialchars($data['invoice_marketplace']);
		$invoice_ekspedisi   = htmlspecialchars($data['invoice_ekspedisi']);
		$invoice_no_resi     = htmlspecialchars($data['invoice_no_resi']);
	} else {
		$invoice_marketplace = "";
		$invoice_ekspedisi   = 0;
		$invoice_no_resi     = "-";
	}
	$jumlah = count($keranjang_id_kasir);

	if ( $invoice_piutang == 0 && $invoice_bayar < $invoice_sub_total ) {
		echo"
			<script>
				alert('Transaksi TIDAK BISA Dilanjutakn !!! Nominal Pembayaran LEBIH KECIL dari Total Pembayaran.. Silahkan Melakukan Transaksi PIUTANG jika Nominal Kurang Dari Total Pembayaran');
				document.location.href = '';
			</script>
		";
	} elseif ( $invoice_piutang == 1 && $invoice_bayar >= $invoice_sub_total ) {
		echo"
			<script>
				alert('Transaksi TIDAK BISA Dilanjutakn !!! Nominal DP LEBIH BESAR / SAMA dari Total Piutang.. Silahkan Melakukan Transaksi CASH jika Nominal Lebih Besar / Sama Dari Total Pembayaran');
				document.location.href = '';
			</script>
		";
	} else {
		// query insert invoice
		$query1 = "INSERT INTO invoice
					(penjualan_invoice, penjualan_invoice_count, invoice_tgl, invoice_customer, invoice_customer_category, invoice_kurir, invoice_status_kurir, invoice_tipe_transaksi, invoice_total_beli, invoice_total, invoice_ongkir, invoice_diskon, invoice_sub_total, invoice_bayar, invoice_kembali, invoice_kasir, invoice_date, invoice_date_year_month, invoice_date_edit, invoice_kasir_edit, invoice_total_beli_lama, invoice_total_lama, invoice_ongkir_lama, invoice_sub_total_lama, invoice_bayar_lama, invoice_kembali_lama, invoice_marketplace, invoice_ekspedisi, invoice_no_resi, invoice_date_selesai_kurir, invoice_piutang, invoice_piutang_dp, invoice_piutang_jatuh_tempo, invoice_piutang_lunas, invoice_draft, invoice_cabang)
					VALUES
					('$penjualan_invoice2', '$penjualan_invoice_count', '$invoice_tgl', '$invoice_customer', '$invoice_customer_category', '$invoice_kurir', '1', '$invoice_tipe_transaksi', '$invoice_total_beli', '$invoice_total', '$invoice_ongkir', '$invoice_diskon', '$invoice_sub_total', '$invoice_bayar', '$invoice_kembali', '$kik', '$invoice_date', '$invoice_date_year_month', ' ', ' ', '$invoice_total_beli', '$invoice_total', '$invoice_ongkir', '$invoice_sub_total', '$invoice_bayar', '$invoice_kembali', '$invoice_marketplace', '$invoice_ekspedisi', '$invoice_no_resi', '-', '$invoice_piutang', '$invoice_piutang_dp', '$invoice_piutang_jatuh_tempo', '$invoice_piutang_lunas', 0, '$invoice_cabang')";
		// var_dump($query1); die();
		mysqli_query($conn, $query1);

		for( $x=0; $x<$jumlah; $x++ ){
			$query = "INSERT INTO penjualan
						(penjualan_barang_id, barang_id, barang_qty, barang_qty_keranjang, barang_qty_konversi_isi, keranjang_satuan, keranjang_harga_beli, keranjang_harga, keranjang_harga_parent, keranjang_harga_edit, keranjang_id_kasir, penjualan_invoice, penjualan_date, penjualan_date_year_month, barang_qty_lama, barang_qty_lama_parent, barang_option_sn, barang_sn_id, barang_sn_desc, invoice_customer_category, penjualan_cabang)
						VALUES
						('$id[$x]', '$id[$x]', '$keranjang_qty_view[$x]', '$keranjang_qty[$x]', '$keranjang_konversi_isi[$x]', '$keranjang_satuan[$x]','$keranjang_harga_beli[$x]', '$keranjang_harga[$x]', '$keranjang_harga_parent[$x]', '$keranjang_harga_edit[$x]', '$keranjang_id_kasir[$x]', '$penjualan_invoice[$x]' , '$invoice_date', '$invoice_date_year_month', '$keranjang_qty_view[$x]', '$keranjang_qty_view[$x]', '$keranjang_barang_option_sn[$x]', '$keranjang_barang_sn_id[$x]', '$keranjang_sn[$x]', '$invoice_customer_category2[$x]', '$sessionCabang')";
			$query2 = "INSERT INTO terlaris (barang_id, barang_terjual) VALUES ('$id[$x]', '$keranjang_qty[$x]')";

			mysqli_query($conn, $query);
			mysqli_query($conn, $query2);
		}
		

		mysqli_query( $conn, "DELETE FROM keranjang WHERE keranjang_id_kasir = $kik");
		return mysqli_affected_rows($conn);
	}
}

function updateStockDraft($data) {
	global $conn;
	global $sessionCabang;
	$id                  		= $data['barang_ids'];
	$keranjang_qty       		= $data['keranjang_qty'];
	$keranjang_qty_view       	= $data['keranjang_qty_view'];
	$keranjang_konversi_isi     = $data['keranjang_konversi_isi'];
	$keranjang_satuan           = $data['keranjang_satuan'];
	$keranjang_harga_beli       = $data['keranjang_harga_beli'];
	$keranjang_harga			= $data['keranjang_harga'];
	$keranjang_harga_parent		= $data['keranjang_harga_parent'];
	$keranjang_harga_edit		= $data['keranjang_harga_edit'];
	$keranjang_id_kasir  		= $data['keranjang_id_kasir'];
	$penjualan_invoice   		= $data['penjualan_invoice'];
	$keranjang_barang_option_sn = $data['keranjang_barang_option_sn'];
	$keranjang_barang_sn_id     = $data['keranjang_barang_sn_id'];
	$keranjang_sn               = $data['keranjang_sn'];
	$invoice_customer_category2 = $data['invoice_customer_category2'];
	$keranjang_nama 			= $data['keranjang_nama'];
	$barang_kode_slug 			= $data['barang_kode_slug'];
	$keranjang_id_cek 			= $data['keranjang_id_cek'];

	$kik                 		= $_SESSION['user_id'];
	$penjualan_invoice2  		= $data['penjualan_invoice2'];
	$invoice_tgl         		= date("d F Y g:i:s a");
	$invoice_total_beli       	= $data['invoice_total_beli'];
	$invoice_total       		= $data['invoice_total'];
	$invoice_ongkir      		= htmlspecialchars($data['invoice_ongkir']);
	$invoice_diskon      		= htmlspecialchars($data['invoice_diskon']);
	
	$invoice_sub_total   		= $invoice_total + $invoice_ongkir;
	$invoice_sub_total   		= $invoice_sub_total - $invoice_diskon;
	$invoice_bayar       		= $invoice_bayar == '' ? 0 : htmlspecialchars($data['angka1']);

	$invoice_kembali     		= $invoice_bayar - $invoice_sub_total;
	// $invoice_date        		= date("Y-m-d");
	// $invoice_date_year_month    = date("Y-m");
	$invoice_date        		= $data['tanggal'];
	$invoice_date_year_month    = date("Y-m", strtotime($invoice_date ));
	// $penjualan_date      		= $data['date'];
	$invoice_customer    		= $data['invoice_customer'];
	$invoice_customer_category  = $data['invoice_customer_category'];
	$invoice_kurir    	 		= $data['invoice_kurir'];
	$invoice_tipe_transaksi  	= $data['invoice_tipe_transaksi'];
	$penjualan_invoice_count 	= $data['penjualan_invoice_count'];
	$invoice_piutang			= $data['invoice_piutang'];
	if ( $invoice_piutang == 1 ) {
		$invoice_piutang_dp = $invoice_bayar;
	} else {
		$invoice_piutang_dp = 0;
	}
	$invoice_piutang_jatuh_tempo= $data['invoice_piutang_jatuh_tempo'];
	$invoice_piutang_lunas		= $data['invoice_piutang_lunas'];
	// $invoice_cabang             = $data['invoice_cabang'];
	

	if ( $invoice_customer == 1 ) {
		$invoice_marketplace = htmlspecialchars($data['invoice_marketplace']);
		$invoice_ekspedisi   = htmlspecialchars($data['invoice_ekspedisi']);
		$invoice_no_resi     = htmlspecialchars($data['invoice_no_resi']);
	} else {
		$invoice_marketplace = "";
		$invoice_ekspedisi   = 0;
		$invoice_no_resi     = "-";
	}
	$jumlah = count($keranjang_id_kasir);


	// query insert invoice
	$query1 = "INSERT INTO invoice 
				(penjualan_invoice, penjualan_invoice_count, invoice_tgl, invoice_customer, invoice_customer_category, invoice_kurir, invoice_status_kurir, invoice_tipe_transaksi, invoice_total_beli, invoice_total, invoice_ongkir, invoice_diskon, invoice_sub_total, invoice_bayar, invoice_kembali, invoice_kasir, invoice_date, invoice_date_year_month, invoice_date_edit, invoice_kasir_edit, invoice_total_beli_lama, invoice_total_lama, invoice_ongkir_lama, invoice_sub_total_lama, invoice_bayar_lama, invoice_kembali_lama, invoice_marketplace, invoice_ekspedisi, invoice_no_resi, invoice_date_selesai_kurir, invoice_piutang, invoice_piutang_dp, invoice_piutang_jatuh_tempo, invoice_piutang_lunas, invoice_draft, invoice_cabang)
				VALUES
				('$penjualan_invoice2', '$penjualan_invoice_count', '$invoice_tgl', '$invoice_customer', '$invoice_customer_category', '$invoice_kurir', '1', '$invoice_tipe_transaksi', '$invoice_total_beli', '$invoice_total', '$invoice_ongkir', '$invoice_diskon', '$invoice_sub_total', '$invoice_bayar', '$invoice_kembali', '$kik', '$invoice_date', '$invoice_date_year_month', ' ', ' ', '$invoice_total_beli', '$invoice_total', '$invoice_ongkir', '$invoice_sub_total', '$invoice_bayar', '$invoice_kembali', '$invoice_marketplace', '$invoice_ekspedisi', '$invoice_no_resi', '-', '$invoice_piutang', '$invoice_piutang_dp', '$invoice_piutang_jatuh_tempo', '$invoice_piutang_lunas', 1, '$sessionCabang')";
		// var_dump($query1); die();
		mysqli_query($conn, $query1);

	for( $x=0; $x<$jumlah; $x++ ){

		$query = "INSERT INTO keranjang_draft
					(keranjang_nama,keranjang_harga_beli,keranjang_harga,keranjang_harga_parent,keranjang_harga_edit,keranjang_satuan,barang_id,barang_kode_slug,keranjang_qty,keranjang_qty_view,keranjang_konversi_isi,keranjang_barang_sn_id,keranjang_barang_option_sn,keranjang_sn,keranjang_id_kasir,keranjang_id_cek,keranjang_tipe_customer,keranjang_draft_status,keranjang_invoice, keranjang_cabang)
					VALUES ('$keranjang_nama[$x]', '$keranjang_harga_beli[$x]', '$keranjang_harga[$x]', '$keranjang_harga_parent[$x]', '$keranjang_harga_edit[$x]', '$keranjang_satuan[$x]', '$id[$x]', '$barang_kode_slug[$x]', '$keranjang_qty[$x]', '$keranjang_qty_view[$x]', '$keranjang_konversi_isi[$x]', '$keranjang_barang_sn_id[$x]', '$keranjang_barang_option_sn[$x]', '$keranjang_sn[$x]', '$keranjang_id_kasir[$x]', '$keranjang_id_cek[$x]', '$invoice_customer_category2[$x]', 1, '$penjualan_invoice2', '$sessionCabang')";
		mysqli_query($conn, $query);
	}
		

	mysqli_query( $conn, "DELETE FROM keranjang WHERE keranjang_id_kasir = $kik");
	return mysqli_affected_rows($conn);
}


function updateStockSaveDraft($data) {
	global $conn;
	global $sessionCabang;
	$id                  		= $data['barang_ids'];
	$keranjang_qty       		= $data['keranjang_qty'];
	$keranjang_qty_view       	= $data['keranjang_qty_view'];
	$keranjang_konversi_isi     = $data['keranjang_konversi_isi'];
	$keranjang_satuan           = $data['keranjang_satuan'];
	$keranjang_harga_beli       = $data['keranjang_harga_beli'];
	$keranjang_harga			= $data['keranjang_harga'];
	$keranjang_harga_parent		= $data['keranjang_harga_parent'];
	$keranjang_harga_edit		= $data['keranjang_harga_edit'];
	$keranjang_id_kasir  		= $data['keranjang_id_kasir'];
	// $penjualan_invoice   		= $data['penjualan_invoice'];
	$keranjang_barang_option_sn = $data['keranjang_barang_option_sn'];
	$keranjang_barang_sn_id     = $data['keranjang_barang_sn_id'];
	$keranjang_sn               = $data['keranjang_sn'];
	$invoice_customer_category2 = $data['invoice_customer_category2'];
	// $penjualan_cabang        	= $data['penjualan_cabang'];

	$invoice_id 				= $data['invoice_id'];
	$kik                 		= $_SESSION['user_id'];
	$penjualan_invoice2  		= $data['penjualan_invoice2'];
	$invoice_tgl         		= date("d F Y g:i:s a");
	$invoice_total_beli       	= $data['invoice_total_beli'];
	$invoice_total       		= $data['invoice_total'];
	$invoice_ongkir      		= htmlspecialchars($data['invoice_ongkir']);
	$invoice_diskon      		= htmlspecialchars($data['invoice_diskon']);
	
	$invoice_sub_total   		= $invoice_total + $invoice_ongkir;
	$invoice_sub_total   		= $invoice_sub_total - $invoice_diskon;
	$invoice_bayar       		= htmlspecialchars($data['angka1']);

	if ( $invoice_bayar == null ) {
		echo"
			<script>
				alert('Anda Belum Input Nominal BAYAR !!!');
				document.location.href = '';
			</script>
		";
	} 

	$invoice_kembali     		= $invoice_bayar - $invoice_sub_total;
	$invoice_date        		= $data['tanggal'];
	$invoice_date_year_month    = date("Y-m", strtotime($invoice_date ));
	// $penjualan_date      		= $data['penjualan_date'];
	$invoice_customer    		= $data['invoice_customer'];
	$invoice_customer_category  = $data['invoice_customer_category'];
	$invoice_kurir    	 		= $data['invoice_kurir'];
	$invoice_tipe_transaksi  	= $data['invoice_tipe_transaksi'];
	$penjualan_invoice_count 	= $data['penjualan_invoice_count'];
	$invoice_piutang			= $data['invoice_piutang'];
	if ( $invoice_piutang == 1 ) {
		$invoice_piutang_dp = $invoice_bayar;
	} else {
		$invoice_piutang_dp = 0;
	}
	$invoice_piutang_jatuh_tempo= $data['invoice_piutang_jatuh_tempo'];
	$invoice_piutang_lunas		= $data['invoice_piutang_lunas'];
	$invoice_cabang             = $data['invoice_cabang'];
	

	if ( $invoice_customer == 1 ) {
		$invoice_marketplace = htmlspecialchars($data['invoice_marketplace']);
		$invoice_ekspedisi   = htmlspecialchars($data['invoice_ekspedisi']);
		$invoice_no_resi     = htmlspecialchars($data['invoice_no_resi']);
	} else {
		$invoice_marketplace = "";
		$invoice_ekspedisi   = 0;
		$invoice_no_resi     = "-";
	}
	$jumlah = count($keranjang_id_kasir);


	if ( $invoice_bayar == null ) {
		echo"
			<script>
				alert('Anda Belum Input Nominal BAYAR !!!');
				document.location.href = '';
			</script>
		";
	} else {
		// query Update invoice
		$query1 = "UPDATE invoice SET  
				invoice_tgl 				= '$invoice_tgl', 
				invoice_customer 			= '$invoice_customer', 
				invoice_customer_category 	= '$invoice_customer_category', 
				invoice_tipe_transaksi 		= '$invoice_tipe_transaksi', 
				invoice_total_beli 			= '$invoice_total_beli', 
				invoice_total 				= '$invoice_total', 
				invoice_ongkir 				= '$invoice_ongkir', 
				invoice_diskon 				= '$invoice_diskon', 
				invoice_sub_total 			= '$invoice_sub_total', 
				invoice_bayar 				= '$invoice_bayar', 
				invoice_kembali 			= '$invoice_kembali', 
				invoice_kasir 				= '$kik', 
				invoice_date 				= '$invoice_date', 
				invoice_date_year_month 	= '$invoice_date_year_month', 
				invoice_total_beli_lama 	= '$invoice_total_beli', 
				invoice_total_lama 			= '$invoice_total', 
				invoice_ongkir_lama 		= '$invoice_ongkir', 
				invoice_sub_total_lama 		= '$invoice_sub_total', 
				invoice_bayar_lama 			= '$invoice_bayar', 
				invoice_kembali_lama 		= '$invoice_kembali',  
				invoice_piutang 			= '$invoice_piutang', 
				invoice_piutang_dp 			= '$invoice_piutang_dp', 
				invoice_piutang_jatuh_tempo = '$invoice_piutang_jatuh_tempo', 
				invoice_piutang_lunas 		= '$invoice_piutang_lunas', 
				invoice_draft 				= 0, 
				invoice_cabang 				= '$sessionCabang'
				WHERE invoice_id 			= $invoice_id
		";
		// var_dump($query1); die();
		mysqli_query($conn, $query1);

		for( $x=0; $x<$jumlah; $x++ ){
			$query = "INSERT INTO penjualan
						(penjualan_barang_id, barang_id, barang_qty, barang_qty_keranjang, barang_qty_konversi_isi, keranjang_satuan, keranjang_harga_beli, keranjang_harga, keranjang_harga_parent, keranjang_harga_edit, keranjang_id_kasir, penjualan_invoice, penjualan_date, penjualan_date_year_month, barang_qty_lama, barang_qty_lama_parent, barang_option_sn, barang_sn_id, barang_sn_desc, invoice_customer_category, penjualan_cabang)
						VALUES
						('$id[$x]', '$id[$x]', '$keranjang_qty_view[$x]', '$keranjang_qty[$x]', '$keranjang_konversi_isi[$x]', '$keranjang_satuan[$x]','$keranjang_harga_beli[$x]', '$keranjang_harga[$x]', '$keranjang_harga_parent[$x]', '$keranjang_harga_edit[$x]', '$keranjang_id_kasir[$x]', '$penjualan_invoice2' , '$invoice_date', '$invoice_date_year_month', '$keranjang_qty_view[$x]', '$keranjang_qty_view[$x]', '$keranjang_barang_option_sn[$x]', '$keranjang_barang_sn_id[$x]', '$keranjang_sn[$x]', '$invoice_customer_category2[$x]', '$sessionCabang')";
			$query2 = "INSERT INTO terlaris (barang_id, barang_terjual) VALUES ('$id[$x]', '$keranjang_qty[$x]')";
			// var_dump($query); die();
			mysqli_query($conn, $query);
			mysqli_query($conn, $query2);
		}
		

		mysqli_query( $conn, "DELETE FROM keranjang_draft WHERE keranjang_invoice = $penjualan_invoice2 && keranjang_cabang = $invoice_cabang ");
		return mysqli_affected_rows($conn);
	}
}

function hapusDraft($invoice, $cabang) {
	global $conn;

	$countDraft = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM keranjang_draft WHERE keranjang_invoice = $invoice && keranjang_cabang = $cabang"));
	// var_dump($countDraft); die();
	if ( $countDraft > 0 ) {
		mysqli_query( $conn, "DELETE FROM invoice WHERE penjualan_invoice = $invoice && invoice_cabang = $cabang");

		mysqli_query( $conn, "DELETE FROM keranjang_draft WHERE keranjang_invoice = $invoice && keranjang_cabang = $cabang");
		return mysqli_affected_rows($conn);
	} else {
		mysqli_query( $conn, "DELETE FROM invoice WHERE penjualan_invoice = $invoice && invoice_cabang = $cabang");
		return mysqli_affected_rows($conn);
	}	
}

// =========================================== CUSTOMER ====================================== //
 
function tambahCustomer($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$customer_nama     = htmlspecialchars($data["customer_nama"]);
	$customer_tlpn     = htmlspecialchars($data["customer_tlpn"]);
	$customer_email    = htmlspecialchars($data["customer_email"]);
	$customer_alamat   = htmlspecialchars($data["customer_alamat"]);
	$customer_create   = date("d F Y g:i:s a");
	$customer_status   = htmlspecialchars($data["customer_status"]);
	$customer_category = $data["customer_category"];
	$customer_cabang   = htmlspecialchars($data["customer_cabang"]);

	// Cek Email
	$customer_tlpn_cek = mysqli_num_rows(mysqli_query($conn, "select * from customer where customer_tlpn = '$customer_tlpn' "));

	if ( $customer_tlpn_cek > 0 ) {
		echo "
			<script>
				alert('Customer Sudah Terdaftar');
			</script>
		";
	} else {
		// query insert data
		$query = "INSERT INTO customer VALUES ('', '$customer_nama', '$customer_tlpn', '$customer_email', '$customer_alamat', '$customer_create', '$customer_status', '$customer_category', '$customer_cabang')";
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);
	}
}

function editCustomer($data){
	global $conn;
	$id = $data["customer_id"];


	// ambil data dari tiap elemen dalam form
	$customer_nama     = htmlspecialchars($data["customer_nama"]);
	$customer_tlpn     = htmlspecialchars($data["customer_tlpn"]);
	$customer_email    = htmlspecialchars($data["customer_email"]);
	$customer_alamat   = htmlspecialchars($data["customer_alamat"]);
	$customer_status   = htmlspecialchars($data["customer_status"]);
	$customer_category = $data["customer_category"];

		// query update data
		$query = "UPDATE customer SET 
						customer_nama     = '$customer_nama',
						customer_tlpn     = '$customer_tlpn',
						customer_email    = '$customer_email',
						customer_alamat   = '$customer_alamat',
						customer_status   = '$customer_status',
						customer_category = '$customer_category'
						WHERE customer_id = $id
				";
		// var_dump($query); die();
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);

}


function hapusCustomer($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM customer WHERE customer_id = $id");

	return mysqli_affected_rows($conn);
}


// =========================================== Panjualan ===================================== //
function hapusPenjualan($id) {
	global $conn;
    
	mysqli_query( $conn, "DELETE FROM penjualan WHERE penjualan_id = $id");

	return mysqli_affected_rows($conn);
}

function hapusPenjualanInvoice($id) {
	global $conn;

	// Mencari Invoive Penjualan dan cabang
	$invoiceTbl = mysqli_query( $conn, "select penjualan_invoice, invoice_cabang from invoice where invoice_id = '".$id."'");

    $ivc = mysqli_fetch_array($invoiceTbl); 
    $penjualan_invoice  = $ivc["penjualan_invoice"];
    $invoice_cabang  	= $ivc["invoice_cabang"];


	// Mencari banyak barang SN
	$barang_option_sn = mysqli_query( $conn, "select barang_option_sn from penjualan where penjualan_invoice = '".$penjualan_invoice."' && barang_option_sn > 0 && penjualan_cabang = '".$invoice_cabang."' ");
	$barang_option_sn = mysqli_num_rows($barang_option_sn);

	// Menghitung data di tabel piutang sesuai No. Invoice
	$piutang = mysqli_query($conn,"select * from piutang where piutang_invoice = '".$penjualan_invoice."' && piutang_cabang = '".$invoice_cabang."' ");
    $jmlPiutang = mysqli_num_rows($piutang);

    
	// Mencari ID SN
	if ( $barang_option_sn > 0 ) {
		$barang_sn_id = query("SELECT * FROM penjualan WHERE penjualan_invoice = $penjualan_invoice && barang_option_sn > 0 && penjualan_cabang = $invoice_cabang ");

		foreach ( $barang_sn_id as $row ) :
		 	$barang_sn_id = $row['barang_sn_id'];

		 	$barang = count($barang_sn_id);
		 	for ( $i = 0; $i < $barang; $i++ ) {
		 		$query = "UPDATE barang_sn SET 
						barang_sn_status     = 3
						WHERE barang_sn_id = $barang_sn_id
				";
		 	}
		 	mysqli_query($conn, $query);
		endforeach;
	}

	// Kondisi Hapus jika terdapat cicilan di tabel Piutang
	if ( $jmlPiutang > 0 ) {
		mysqli_query( $conn, "DELETE FROM piutang WHERE piutang_invoice = $penjualan_invoice && piutang_cabang = $invoice_cabang ");

		mysqli_query( $conn, "DELETE FROM penjualan WHERE penjualan_invoice = $penjualan_invoice && penjualan_cabang = $invoice_cabang ");

		mysqli_query( $conn, "DELETE FROM invoice WHERE invoice_id = $id");
	} else {
	// Kondisi Hapus jika Tanpa cicilan di tabel Piutang
		mysqli_query( $conn, "DELETE FROM penjualan WHERE penjualan_invoice = $penjualan_invoice && penjualan_cabang = $invoice_cabang ");

		mysqli_query( $conn, "DELETE FROM invoice WHERE invoice_id = $id");
	}



	return mysqli_affected_rows($conn);
}

function updateQTY2($data) {
	global $conn;
	$id = $data["penjualan_id"];
	$bid = $data["barang_id"];

	// ambil data dari tiap elemen dalam form
	$barang_qty      			= htmlspecialchars($data['barang_qty']);
	$barang_qty_lama 			= $data['barang_qty_lama'];
	$barang_terjual  			= $data['barang_terjual'];
	$barang_qty_konversi_isi 	= $data['barang_qty_konversi_isi'];

	// Edit No SN Jika Produk Menggunakan SN
	$barang_option_sn 			= $data['barang_option_sn'];
	$barang_sn_id     			= $data['barang_sn_id'];

	// retur
	$barang_stock           	= $data['barang_stock'];
	$barang_stock_kurang    	= $barang_qty_lama - $barang_qty;
	$barang_stock_kurang       *= $barang_qty_konversi_isi;

	$barang_stock_hasil     	= $barang_stock + $barang_stock_kurang;
	$barang_terjual         	= $barang_terjual - $barang_stock_kurang;
	// var_dump($barang_stock_hasil); die();

	if ( $barang_qty > $barang_qty_lama ) {
		echo"
			<script>
				alert('Jika Anda Ingin Menambahkan QTY Barang.. Lakukan Transaksi Invoice Baru !!!');
			</script>
		";
	} else {
		// query update data

		$query = "UPDATE penjualan SET 
					barang_qty       = '$barang_qty'
					WHERE penjualan_id = $id
					";
		$query1 = "UPDATE barang SET 
					barang_stock   = '$barang_stock_hasil',
					barang_terjual = '$barang_terjual'
					WHERE barang_id = $bid
					";
		if ( $barang_option_sn > 0 ) {
			$query2 = "UPDATE barang_sn SET 
					barang_sn_status = 2
					WHERE barang_sn_id = $barang_sn_id
				";
			mysqli_query($conn, $query2);
		}

		mysqli_query($conn, $query);
		mysqli_query($conn, $query1);
		
		return mysqli_affected_rows($conn);
		// $query1 = "INSERT INTO retur VALUES ('', '$retur_barang_id', '$retur_invoice', '$retur_admin_id', '$retur_date', ' ', '$barang_stock')";
		// mysqli_query($conn, $query1);
		
	} 
}

function updateInvoice($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_total_beli   = htmlspecialchars($data['invoice_total_beli']);
	$invoice_total        = htmlspecialchars($data['invoice_total']);
	$invoice_ongkir       = $data['invoice_ongkir'];
	$invoice_sub_total    = $data['invoice_sub_total'];
	$invoice_bayar        = htmlspecialchars($data['angka1']);
	$invoice_kembali      = $invoice_bayar - $invoice_sub_total;
	$invoice_kasir_edit   = $data['invoice_kasir_edit'];
	$invoice_date_edit    = date('Y-m-d');

		// query update data
		$query = "UPDATE invoice SET 
					invoice_total_beli = '$invoice_total_beli',
					invoice_total      = '$invoice_total',
					invoice_ongkir     = '$invoice_ongkir',
					invoice_sub_total  = '$invoice_sub_total',
					invoice_bayar      = '$invoice_bayar',
					invoice_kembali    = '$invoice_kembali',
					invoice_date_edit  = '$invoice_date_edit',
					invoice_kasir_edit = '$invoice_kasir_edit'
					WHERE invoice_id = $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
}

function editInvoiceEkspedisi($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_marketplace        = htmlspecialchars($data['invoice_marketplace']);
	$invoice_ekspedisi          = htmlspecialchars($data['invoice_ekspedisi']);
	$invoice_no_resi            = htmlspecialchars($data['invoice_no_resi']);
	$invoice_total              = $data['invoice_total'];
	$invoice_ongkir             = htmlspecialchars($data['invoice_ongkir']);
	$invoice_sub_total          = $invoice_total + $invoice_ongkir;
	$invoice_bayar              = $data['invoice_bayar'];
	$invoice_kembali            = $invoice_bayar - $invoice_sub_total;

		// query update data
		$query = "UPDATE invoice SET 
					invoice_total          = '$invoice_total',
					invoice_ongkir         = '$invoice_ongkir',
					invoice_sub_total      = '$invoice_sub_total',
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_marketplace    = '$invoice_marketplace',
					invoice_ekspedisi      = '$invoice_ekspedisi',
					invoice_no_resi        = '$invoice_no_resi'
					WHERE invoice_id = $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
}

function editInvoiceKurir($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_total              = $data['invoice_total'];
	$invoice_ongkir             = htmlspecialchars($data['invoice_ongkir']);
	$invoice_sub_total          = $invoice_total + $invoice_ongkir;
	$invoice_bayar              = $data['invoice_bayar'];
	$invoice_kembali            = $invoice_bayar - $invoice_sub_total;
	$invoice_kurir              = htmlspecialchars($data['invoice_kurir']);
	$invoice_status_kurir       = htmlspecialchars($data['invoice_status_kurir']);

		// query update data
		$query = "UPDATE invoice SET 
					invoice_kurir 		   = '$invoice_kurir',
					invoice_status_kurir   = '$invoice_status_kurir',
					invoice_total          = '$invoice_total',
					invoice_ongkir         = '$invoice_ongkir',
					invoice_sub_total      = '$invoice_sub_total',
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali'
					WHERE invoice_id = $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
}

// ============================================ Supplier ====================================== // 
function tambahSupplier($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$supplier_nama      = htmlspecialchars($data["supplier_nama"]);
	$supplier_wa 		= htmlspecialchars($data["supplier_wa"]);
	$supplier_alamat    = htmlspecialchars($data["supplier_alamat"]);
	$supplier_company   = htmlspecialchars($data["supplier_company"]);
	$supplier_status    = htmlspecialchars($data["supplier_status"]);
	$supplier_create    = date("d F Y g:i:s a");
	$supplier_cabang    = htmlspecialchars($data["supplier_cabang"]);

	// Cek Email
	$supplier_wa_cek = mysqli_num_rows(mysqli_query($conn, "select * from supplier where supplier_wa = '$supplier_wa' "));

	if ( $supplier_wa_cek > 0 ) {
		echo "
			<script>
				alert('No. WhatsApp Sudah Terdaftar');
			</script>
		";
	} else {
		// query insert data
		$query = "INSERT INTO supplier VALUES ('', '$supplier_nama', '$supplier_wa', '$supplier_alamat', '$supplier_company', '$supplier_status', '$supplier_create', '$supplier_cabang')";
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);
	}
}

function editSupplier($data){
	global $conn;
	$id = $data["supplier_id"];


	// ambil data dari tiap elemen dalam form
	$supplier_nama      = htmlspecialchars($data["supplier_nama"]);
	$supplier_wa 		= htmlspecialchars($data["supplier_wa"]);
	$supplier_alamat    = htmlspecialchars($data["supplier_alamat"]);
	$supplier_company   = htmlspecialchars($data["supplier_company"]);
	$supplier_status    = htmlspecialchars($data["supplier_status"]);

		// query update data
		$query = "UPDATE supplier SET 
						supplier_nama      = '$supplier_nama',
						supplier_wa        = '$supplier_wa',
						supplier_alamat    = '$supplier_alamat',
						supplier_company   = '$supplier_company',
						supplier_status    = '$supplier_status'
						WHERE supplier_id  = $id
				";
		// var_dump($query); die();
		mysqli_query($conn, $query);

		return mysqli_affected_rows($conn);

}

function hapusSupplier($id) {
	global $conn;
	mysqli_query( $conn, "DELETE FROM supplier WHERE supplier_id = $id");

	return mysqli_affected_rows($conn);
}

// ===================================== Keranjang Pembelian =============================== //
function tambahKeranjangPembelian($barang_id, $keranjang_nama, $keranjang_harga, $keranjang_id_kasir, $keranjang_qty, $keranjang_cabang, $keranjang_id_cek) {
	global $conn;
	
	// Cek STOCK
	$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_pembelian where keranjang_id_cek = '$keranjang_id_cek' "));
	
	// Kondisi jika scan Barcode Tidak sesuai
	if ( $barang_id != null ) {
		$q_barang = mysqli_query($conn, "SELECT barang_harga_beli FROM barang WHERE barang_id = '" . $barang_id . "'");
		$barang = mysqli_fetch_assoc($q_barang);
		$keranjang_harga = $barang ? $barang['barang_harga_beli'] : 0;
		if ( $barang_id_cek > 0 ) {
			$keranjangParent = mysqli_query( $conn, "select keranjang_qty from keranjang_pembelian where keranjang_id_cek = '".$keranjang_id_cek."'");
		    $kp = mysqli_fetch_array($keranjangParent); 
		    $kp = $kp['keranjang_qty'];
		    $kp += $keranjang_qty;

		    $query = "UPDATE keranjang_pembelian SET 
							keranjang_qty   = '$kp'
							WHERE keranjang_id_cek = $keranjang_id_cek
							";
			mysqli_query($conn, $query);
			return mysqli_affected_rows($conn);

		} else {
			// query insert data
			$query = "INSERT INTO keranjang_pembelian(keranjang_nama, keranjang_harga, barang_id, keranjang_qty, keranjang_id_kasir, keranjang_id_cek, keranjang_cabang) VALUES ('$keranjang_nama', '$keranjang_harga', '$barang_id', '$keranjang_qty', '$keranjang_id_kasir', '$keranjang_id_cek', '$keranjang_cabang')";
			
			mysqli_query($conn, $query);

			return mysqli_affected_rows($conn);
		}
	} else {
		echo '
			<script>
				alert("Kode Produk Tidak ada di Data Master Barang dan Coba Cek Kembali !! ");
				document.location.href = "transaksi-pembelian";
			</script>
		';
	}
}

function tambahKeranjangPembelianBarcode($data) {
	global $conn;
	$barang_kode 		= htmlspecialchars($data['inputbarcode']);
	$keranjang_id_kasir = $data['keranjang_id_kasir'];
	$keranjang_cabang   = $data['keranjang_cabang'];

	// Ambil Data Barang berdasarkan Kode Barang 
	$barang 	= mysqli_query( $conn, "select barang_id, barang_nama from barang where barang_kode = '".$barang_kode."' && barang_cabang = '".$keranjang_cabang."' ");
    $br 		= mysqli_fetch_array($barang);

    $barang_id          = $br['barang_id'];
	$keranjang_nama     = $br['barang_nama'];
	$keranjang_harga    = $br['barang_harga_beli'];
	$keranjang_qty      = 1;
	$keranjang_id_cek   = $barang_id.$keranjang_id_kasir.$keranjang_cabang;

	// Cek STOCK
	$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_pembelian where keranjang_id_cek = '$keranjang_id_cek' "));
	
	// Kondisi jika scan Barcode Tidak sesuai
	if ( $barang_id != null ) {
		if ( $barang_id_cek > 0 ) {
			$keranjangParent = mysqli_query( $conn, "select keranjang_qty from keranjang_pembelian where keranjang_id_cek = '".$keranjang_id_cek."'");
		    $kp = mysqli_fetch_array($keranjangParent); 
		    $kp = $kp['keranjang_qty'];
		    $kp += $keranjang_qty;

		    $query = "UPDATE keranjang_pembelian SET 
							keranjang_qty   = '$kp'
							WHERE keranjang_id_cek = $keranjang_id_cek
							";
			mysqli_query($conn, $query);
			return mysqli_affected_rows($conn);

		} else {
			// query insert data
			$query = "INSERT INTO keranjang_pembelian VALUES ('', '$keranjang_nama', '$keranjang_harga', '$barang_id', '$keranjang_qty', '$keranjang_id_kasir', '$keranjang_id_cek', '$keranjang_cabang')";
			
			mysqli_query($conn, $query);

			return mysqli_affected_rows($conn);
		}
	} else {
		echo '
			<script>
				alert("Kode Produk Tidak ada di Data Master Barang dan Coba Cek Kembali !! ");
				document.location.href = "transaksi-pembelian";
			</script>
		';
	}

}

function hapusKeranjangPembelian($id) {
	global $conn;

	mysqli_query( $conn, "DELETE FROM keranjang_pembelian WHERE keranjang_id = $id");

	return mysqli_affected_rows($conn);
}

function updateQTYpembelian($data) {
	global $conn;
	$id = $data["keranjang_id"];

	// ambil data dari tiap elemen dalam form
	$keranjang_qty = htmlspecialchars($data['keranjang_qty']);
	$stock_brg = $data['stock_brg'];


	// query update data
	$query = "UPDATE keranjang_pembelian SET 
				keranjang_qty   = '$keranjang_qty'
				WHERE keranjang_id = $id
			";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
	
}

// ============================================== Transaksi Pembelian ======================== //
function updateStockPembelian($data) {
	global $conn;
	global $sessionCabang;

	$id                  = $data["barang_ids"];
	$keranjang_qty       = $data["keranjang_qty"];
	$keranjang_id_kasir  = $data['keranjang_id_kasir'];
	// $pembelian_invoice   = $data['pembelian_invoice'];
	$kik                 = $_SESSION['user_id'];
	$barang_harga_beli   = $data['barang_harga_beli'];
	// $pembelian_invoice_parent = $data['pembelian_invoice_parent'];
	$invoice_pembelian_cabang = $sessionCabang;

	// $pembelian_invoice2  = $data['pembelian_invoice2'];
	$invoice_tgl         = date("d F Y g:i:s a");
	$invoice_supplier    = $data['invoice_supplier'];
	$invoice_total       = $data['invoice_total'];
	$invoice_bayar       = $data['angka1'];
	$invoice_date        = $data['tanggal'];
	$invoice_kembali     = $invoice_bayar - $invoice_total;
	// $pembelian_date      = $data['pembelian_date'];
	// $invoice_pembelian_number_delete = $data['invoice_pembelian_number_delete'];
	// $pembelian_invoice_parent2       = $data['pembelian_invoice_parent2'];
	$invoice_hutang				 	 = $data['invoice_hutang'];

	$pembelian = mysqli_query($conn,"select invoice_pembelian_id, pembelian_invoice_parent from invoice_pembelian order by invoice_pembelian_id desc");
	$jmlPembelian = mysqli_num_rows($pembelian);
	$invoice = date('Ymd') . ($jmlPembelian + 1);
	
	if($jmlPembelian > 0){
		$invoice = mysqli_fetch_assoc($pembelian);
		$invoice = $invoice['pembelian_invoice_parent'] + 1;
	}

	if ( $invoice_hutang == 1 ) {
		$invoice_hutang_dp = $invoice_bayar;
	} else {
		$invoice_hutang_dp = 0;
	}
	$invoice_hutang_jatuh_tempo	    = $data['invoice_hutang_jatuh_tempo'];
	$invoice_hutang_lunas			= $data['invoice_hutang_lunas'];
	$pembelian_cabang				= $data['pembelian_cabang'];

	$jumlah = count($keranjang_id_kasir);

	// Cek No. Invoice
	$invoice_cek = mysqli_num_rows(mysqli_query($conn, "select * from invoice_pembelian where pembelian_invoice = '$invoice' && invoice_pembelian_cabang = '$invoice_pembelian_cabang' "));

	if ( $invoice_cek > 0 ) {
		echo "
			<script>
				alert('No. Invoice Pembelian Sudah Digunakan Sebelumnya !!');
			</script>
		";
	} else {
		// query insert invoice
		$query1 = "INSERT INTO invoice_pembelian
					(pembelian_invoice, pembelian_invoice_parent, invoice_tgl, invoice_supplier, invoice_total, invoice_bayar, invoice_kembali, invoice_kasir, invoice_date, invoice_date_edit, invoice_kasir_edit, invoice_total_lama, invoice_bayar_lama, invoice_kembali_lama, invoice_hutang, invoice_hutang_dp, invoice_hutang_jatuh_tempo, invoice_hutang_lunas, invoice_pembelian_cabang) 
					VALUES
					('$invoice', '$invoice', '$invoice_tgl', '$invoice_supplier', '$invoice_total', '$invoice_bayar', '$invoice_kembali', '$kik', '$invoice_date', ' ', ' ', '$invoice_total', '$invoice_bayar', '$invoice_kembali', '$invoice_hutang', '$invoice_hutang_dp', '$invoice_hutang_jatuh_tempo', '$invoice_hutang_lunas', '$invoice_pembelian_cabang')";
		// var_dump($query1); die();
		mysqli_query($conn, $query1);
		

		for( $x=0; $x<$jumlah; $x++ ){
			$query = "INSERT INTO pembelian
						(pembelian_barang_id, barang_id, barang_qty, keranjang_id_kasir, pembelian_invoice, pembelian_invoice_parent, pembelian_date, barang_qty_lama, barang_qty_lama_parent, barang_harga_beli, pembelian_cabang)
						VALUES
						('$id[$x]', '$id[$x]', '$keranjang_qty[$x]', '$keranjang_id_kasir[$x]', '$invoice', '$invoice', '$invoice_date', '$keranjang_qty[$x]', '$keranjang_qty[$x]', '$barang_harga_beli[$x]', '$pembelian_cabang[$x]')";
			mysqli_query($conn, $query);

			// Mencari Rata-rata Pembelian
			// $hargaBeli= mysqli_query($conn, "SELECT AVG(barang_harga_beli) AS average FROM pembelian WHERE barang_id = $id[$x]");
            // $hargaBeli = mysqli_fetch_assoc($hargaBeli);
            // $hargaBeli = $hargaBeli['barang_harga_beli'];

            // Edit Data
			$query2 = "UPDATE barang SET 
						barang_harga_beli     = '$barang_harga_beli[$x]'
						WHERE barang_id       = $id[$x]
				";

			mysqli_query($conn, $query2);
			if($conn->error) {echo($conn->error);die;}
		}

		mysqli_query( $conn, "DELETE FROM keranjang_pembelian WHERE keranjang_id_kasir = $kik");
		// mysqli_query( $conn, "DELETE FROM invoice_pembelian_number WHERE invoice_pembelian_number_delete = $invoice_pembelian_number_delete");
		return ['success' => mysqli_affected_rows($conn), 'data' => ['invoice' => $invoice]];
	}
}

// ======================================== Pembelian Edit ================================ //
function updateQTY2pembelian($data) {
	global $conn;
	$id = $data["pembelian_id"];
	$bid = $data["barang_id"];

	// ambil data dari tiap elemen dalam form
	$barang_qty      = htmlspecialchars($data['barang_qty']);
	$barang_qty_lama = $data['barang_qty_lama'];

	// retur
	$barang_stock           = $data['barang_stock'];
	$barang_stock_kurang    = $barang_qty_lama - $barang_qty;
	$barang_stock_hasil     = $barang_stock - $barang_stock_kurang;
	// var_dump($barang_stock_hasil); die();

	if ( $barang_qty > $barang_qty_lama ) {
		echo"
			<script>
				alert('Jika Anda Ingin Menambahkan QTY Barang.. Lakukan Transaksi Invoice Baru !!!');
			</script>
		";
	} else {
		// query update data
		$query = "UPDATE pembelian SET 
					barang_qty       = '$barang_qty'
					WHERE pembelian_id = $id
					";
		$query1 = "UPDATE barang SET 
					barang_stock   = '$barang_stock_hasil'
					WHERE barang_id = $bid
					";
		mysqli_query($conn, $query1);
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
		// $query1 = "INSERT INTO retur VALUES ('', '$retur_barang_id', '$retur_invoice', '$retur_admin_id', '$retur_date', ' ', '$barang_stock')";
		// mysqli_query($conn, $query1);
		
	} 
}

function updateInvoicePembelian($data) {
	global $conn;
	$id = $data["invoice_pembelian_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_total        = htmlspecialchars($data['invoice_total']);
	$invoice_bayar        = htmlspecialchars($data['angka1']);
	$invoice_kembali      = $invoice_bayar - $invoice_total;
	$invoice_kasir_edit   = $data['invoice_kasir_edit'];
	$invoice_date_edit    = date('Y-m-d');

		// query update data
		$query = "UPDATE invoice_pembelian SET 
					invoice_total      = '$invoice_total',
					invoice_bayar      = '$invoice_bayar',
					invoice_kembali    = '$invoice_kembali',
					invoice_date_edit  = '$invoice_date_edit',
					invoice_kasir_edit = '$invoice_kasir_edit'
					WHERE invoice_pembelian_id = $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
}

function hapusPembelianInvoice($id) {
	global $conn;

	$id = $id;

	$pembelian_invoice_parent = mysqli_query( $conn, "select pembelian_invoice_parent, invoice_pembelian_cabang from invoice_pembelian where invoice_pembelian_id = '".$id."'");
    $pip = mysqli_fetch_array($pembelian_invoice_parent); 
    $pembelian_invoice_parent  = $pip["pembelian_invoice_parent"];
    $invoice_pembelian_cabang  = $pip["invoice_pembelian_cabang"];

    // Menghitung data di tabel HUtang sesuai No. Invoice Parent
	$hutang = mysqli_query($conn,"select * from hutang where hutang_invoice_parent = '".$pembelian_invoice_parent."' && hutang_cabang = '".$invoice_pembelian_cabang."' ");
    $jmlHutang = mysqli_num_rows($hutang);

    if ( $jmlHutang > 0 ) {
    	mysqli_query( $conn, "DELETE FROM hutang WHERE hutang_invoice_parent = $pembelian_invoice_parent && hutang_cabang = $invoice_pembelian_cabang");

    	mysqli_query( $conn, "DELETE FROM pembelian WHERE pembelian_invoice_parent = $pembelian_invoice_parent && pembelian_cabang = $invoice_pembelian_cabang")
    	;

		mysqli_query( $conn, "DELETE FROM invoice_pembelian WHERE pembelian_invoice_parent = $pembelian_invoice_parent && invoice_pembelian_cabang = $invoice_pembelian_cabang");
    } else {
    	mysqli_query( $conn, "DELETE FROM pembelian WHERE pembelian_invoice_parent = $pembelian_invoice_parent && pembelian_cabang = $invoice_pembelian_cabang")
    	;

		mysqli_query( $conn, "DELETE FROM invoice_pembelian WHERE pembelian_invoice_parent = $pembelian_invoice_parent && invoice_pembelian_cabang = $invoice_pembelian_cabang");
    }

	return mysqli_affected_rows($conn);
}

// ===================================== Pindah Cabang ===================================== //
function editLokasiCabang($data) {
	global $conn;
	$id = $data["user_id"];

	// ambil data dari tiap elemen dalam form
	$user_cabang = htmlspecialchars($data['user_cabang']);

	// query update data
	$query = "UPDATE user SET 
				user_cabang       = '$user_cabang'
				WHERE user_id     = $id
				";
	// var_dump($query); die();
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

// ======================================== Kurir ========================================== //
function editStatusKurir($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_status_kurir       = $data['invoice_status_kurir'];
	$invoice_date_selesai_kurir = date("d F Y g:i:s a");

	if ( $invoice_status_kurir == 3 ) {
		// query update data
		$query = "UPDATE invoice SET 
				invoice_status_kurir 		= '$invoice_status_kurir',
				invoice_date_selesai_kurir	= '$invoice_date_selesai_kurir'
				WHERE invoice_id     = $id
		";
	} else {
		// query update data
		$query = "UPDATE invoice SET 
				invoice_status_kurir 		= '$invoice_status_kurir',
				invoice_date_selesai_kurir	= '-'
				WHERE invoice_id     = $id
		";
	}
	
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

// ======================================= Piutang ======================================= //
function tambahCicilanPiutang($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_bayar_lama			= $data['invoice_bayar'];
	$piutang_nominal			= $data['piutang_nominal'];
	$invoice_bayar         		= $invoice_bayar_lama + $piutang_nominal;
	$invoice_sub_total			= $data['invoice_sub_total'];
	$invoice_kembali            = $invoice_bayar - $invoice_sub_total;

	$piutang_invoice			= $data['piutang_invoice'];
	$piutang_date				= date("Y-m-d");
	$piutang_date_time			= date("d F Y g:i:s a");
	$piutang_kasir				= $data['piutang_kasir'];
	$piutang_tipe_pembayaran	= $data['piutang_tipe_pembayaran'];
	$piutang_cabang				= $data['piutang_cabang'];

	if ( $invoice_bayar >= $invoice_sub_total ) {
		// query update data
		$query = "UPDATE invoice SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_piutang        = 0,
					invoice_piutang_lunas  = 1
					WHERE invoice_id = $id
				";
		mysqli_query($conn, $query);

		// Insert Tabel kembalian Piutang Cicilan
		$kembalian_piutang = $invoice_bayar - $invoice_sub_total;
		$query3 = "INSERT INTO piutang_kembalian VALUES ('', '$piutang_invoice', '$piutang_date', '$piutang_date_time', '$kembalian_piutang', '$piutang_cabang')";
		mysqli_query($conn, $query3);

	} else {
		// query update data
		$query = "UPDATE invoice SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali'
					WHERE invoice_id = $id
				";
		mysqli_query($conn, $query);
	} 
	
	

	// query insert data
	$query2 = "INSERT INTO piutang VALUES ('', '$piutang_invoice', '$piutang_date', '$piutang_date_time', '$piutang_kasir', '$piutang_nominal', '$piutang_tipe_pembayaran', '$piutang_cabang')";
	mysqli_query($conn, $query2);

	return mysqli_affected_rows($conn);
}

function hapusCicilanPiutang($id) {
	global $conn;


	// Ambil ID produk
	$data_id = $id;

	// Mencari No. Invoice
	$noInvoice = mysqli_query( $conn, "select piutang_invoice, piutang_nominal, piutang_cabang from piutang where piutang_id = '".$data_id."'");
    $noInvoice = mysqli_fetch_array($noInvoice); 
    $piutangInvoice = $noInvoice["piutang_invoice"];
    $nominal 		= $noInvoice["piutang_nominal"];
    $cabangInvoice 	= $noInvoice["piutang_cabang"];

    // Mencari Nilai Bayar di Tabel Invoive
    $bayarInvoice = mysqli_query ( $conn, "select invoice_id, invoice_bayar, invoice_sub_total from invoice where penjualan_invoice = '".$piutangInvoice."' && invoice_cabang = '".$cabangInvoice."' ");
    $bayarInvoice = mysqli_fetch_array($bayarInvoice);
    $invoice_id         = $bayarInvoice['invoice_id'];
    $bayar       		= $bayarInvoice['invoice_bayar'];
    $subTotalInvoice 	= $bayarInvoice['invoice_sub_total'];

    // Proses
    $invoice_bayar         		= $bayar - $nominal;
	$invoice_kembali            = $invoice_bayar - $subTotalInvoice;

	if ( $invoice_bayar >= $subTotalInvoice ) {
		// query update data
		$query2 = "UPDATE invoice SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_piutang        = 0,
					invoice_piutang_lunas  = 1
					WHERE invoice_id = $invoice_id
				";
	} else {
		// query update data
		$query2 = "UPDATE invoice SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_piutang        = 1,
					invoice_piutang_lunas  = 0
					WHERE invoice_id = $invoice_id
				";
	} 
	mysqli_query($conn, $query2);
   
    
	mysqli_query( $conn, "DELETE FROM piutang WHERE piutang_id = $id");

	return mysqli_affected_rows($conn);
}

function updateInvoicePiutang($data) {
	global $conn;
	$id = $data["invoice_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_total        = htmlspecialchars($data['invoice_total']);
	$invoice_ongkir       = $data['invoice_ongkir'];
	$invoice_sub_total    = $data['invoice_sub_total'];
	$invoice_bayar        = htmlspecialchars($data['angka1']);
	$invoice_kembali      = $invoice_bayar - $invoice_sub_total;
	$invoice_kasir_edit   = $data['invoice_kasir_edit'];
	$invoice_date_edit    = date('Y-m-d');



	if ( $invoice_bayar >= $invoice_sub_total ) {
		// query update data
		$query = "UPDATE invoice SET 
					invoice_total      		= '$invoice_total',
					invoice_ongkir     		= '$invoice_ongkir',
					invoice_sub_total  		= '$invoice_sub_total',
					invoice_bayar      		= '$invoice_bayar',
					invoice_kembali    		= '$invoice_kembali',
					invoice_date_edit  		= '$invoice_date_edit',
					invoice_kasir_edit 		= '$invoice_kasir_edit',
					invoice_piutang        	= 0,
					invoice_piutang_lunas 	= 1
					WHERE invoice_id = $id
				";
	} else {
		// query update data
		$query = "UPDATE invoice SET 
					invoice_total      		= '$invoice_total',
					invoice_ongkir     		= '$invoice_ongkir',
					invoice_sub_total  		= '$invoice_sub_total',
					invoice_bayar      		= '$invoice_bayar',
					invoice_kembali    		= '$invoice_kembali',
					invoice_date_edit  		= '$invoice_date_edit',
					invoice_kasir_edit 		= '$invoice_kasir_edit',
					invoice_piutang        	= 1,
					invoice_piutang_lunas 	= 0
					WHERE invoice_id = $id
				";
	} 
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

// ======================================= Hutang ======================================= //
function tambahCicilanhutang($data) {
	global $conn;
	$id = $data["invoice_pembelian_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_bayar_lama			= $data['invoice_bayar'];
	$hutang_nominal				= $data['hutang_nominal'];
	$invoice_bayar         		= $invoice_bayar_lama + $hutang_nominal;
	$invoice_total				= $data['invoice_total'];
	$invoice_kembali            = $invoice_bayar - $invoice_total;

	$hutang_invoice				= $data['hutang_invoice'];
	$hutang_invoice_parent		= $data['hutang_invoice_parent'];
	$hutang_date				= date("Y-m-d");
	$hutang_date_time			= date("d F Y g:i:s a");
	$hutang_kasir				= $data['hutang_kasir'];
	$hutang_tipe_pembayaran		= $data['hutang_tipe_pembayaran'];
	$hutang_cabang				= $data['hutang_cabang'];

	if ( $invoice_bayar >= $invoice_total ) {
		// query update data
		$query = "UPDATE invoice_pembelian SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_hutang         = 0,
					invoice_hutang_lunas   = 1
					WHERE invoice_pembelian_id = $id
				";
		mysqli_query($conn, $query);

		// Insert Tabel kembalian Piutang Cicilan
		$kembalian_hutang = $invoice_bayar - $invoice_total;
		$query3 = "INSERT INTO hutang_kembalian VALUES ('', '$hutang_invoice', '$hutang_invoice_parent', '$hutang_date', '$hutang_date_time', '$kembalian_hutang', '$hutang_cabang')";
		mysqli_query($conn, $query3);
	} else {
		// query update data
		$query = "UPDATE invoice_pembelian SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali'
					WHERE invoice_pembelian_id = $id
				";
		mysqli_query($conn, $query);
	} 
	
	

	// query insert data
	$query2 = "INSERT INTO hutang VALUES ('', '$hutang_invoice', '$hutang_invoice_parent', '$hutang_date', '$hutang_date_time', '$hutang_kasir', '$hutang_nominal', '$hutang_tipe_pembayaran', '$hutang_cabang')";
	mysqli_query($conn, $query2);

	return mysqli_affected_rows($conn);
}

function hapusCicilanHutang($id) {
	global $conn;


	// Ambil ID produk
	$data_id = $id;

	// Mencari No. Invoice
	$noInvoice = mysqli_query( $conn, "select hutang_invoice_parent, hutang_nominal, hutang_cabang from hutang where hutang_id = '".$data_id."'");
    $noInvoice = mysqli_fetch_array($noInvoice); 
    $invoiceParent 		 = $noInvoice["hutang_invoice_parent"];
    $nominal 			 = $noInvoice["hutang_nominal"];
    $cabangInvoice 	 	 = $noInvoice["hutang_cabang"];

    // Mencari Nilai Bayar di Tabel Invoive
    $bayarInvoicePembelian = mysqli_query ( $conn, "select invoice_pembelian_id, invoice_bayar, invoice_total from invoice_pembelian where pembelian_invoice_parent = '".$invoiceParent."' && invoice_pembelian_cabang = '".$cabangInvoice."' ");
    $bip 				  		  = mysqli_fetch_array($bayarInvoicePembelian);
    $invoice_pembelian_id         = $bip['invoice_pembelian_id'];
    $bayar       				  = $bip['invoice_bayar'];
    $totalInvoice 	              = $bip['invoice_total'];

    // Proses
    $invoice_bayar         		= $bayar - $nominal;
	$invoice_kembali            = $invoice_bayar - $totalInvoice;

	if ( $invoice_bayar >= $totalInvoice ) {
		// query update data
		$query2 = "UPDATE invoice_pembelian SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_hutang         = 0,
					invoice_hutang_lunas   = 1
					WHERE invoice_pembelian_id = $invoice_pembelian_id
				";
	} else {
		// query update data
		$query2 = "UPDATE invoice_pembelian SET 
					invoice_bayar          = '$invoice_bayar',
					invoice_kembali        = '$invoice_kembali',
					invoice_hutang         = 1,
					invoice_hutang_lunas   = 0
					WHERE invoice_pembelian_id = $invoice_pembelian_id
				";
	} 
	mysqli_query($conn, $query2);
   
    
	mysqli_query( $conn, "DELETE FROM hutang WHERE hutang_id = $id");

	return mysqli_affected_rows($conn);
}

function updateInvoicePembelianHutang($data) {
	global $conn;
	$id = $data["invoice_pembelian_id"];

	// ambil data dari tiap elemen dalam form
	$invoice_total        = htmlspecialchars($data['invoice_total']);
	$invoice_bayar        = htmlspecialchars($data['angka1']);
	$invoice_kembali      = $invoice_bayar - $invoice_total;
	$invoice_kasir_edit   = $data['invoice_kasir_edit'];
	$invoice_date_edit    = date('Y-m-d');

	if ( $invoice_bayar >= $invoice_total ) {
		// query update data
		$query = "UPDATE invoice_pembelian SET 
					invoice_total      = '$invoice_total',
					invoice_bayar      = '$invoice_bayar',
					invoice_kembali    = '$invoice_kembali',
					invoice_date_edit  = '$invoice_date_edit',
					invoice_kasir_edit = '$invoice_kasir_edit',
					invoice_hutang        	= 0,
					invoice_hutang_lunas 	= 1
					WHERE invoice_pembelian_id = $id
				";
	} else {
		// query update data
		$query = "UPDATE invoice_pembelian SET 
					invoice_total      = '$invoice_total',
					invoice_bayar      = '$invoice_bayar',
					invoice_kembali    = '$invoice_kembali',
					invoice_date_edit  = '$invoice_date_edit',
					invoice_kasir_edit = '$invoice_kasir_edit',
					invoice_hutang        	= 1,
					invoice_hutang_lunas 	= 0
					WHERE invoice_pembelian_id = $id
				";
	}
		
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

// ================================ Tranfer Stock Cabang =================================== //
function tambahTransferSelectCabang($data) {
	global $conn;

	// ambil data dari tiap elemen dalam form
	$tsc_cabang_pusat 		= htmlspecialchars($data['tsc_cabang_pusat']);
	$tsc_cabang_penerima 	= htmlspecialchars($data['tsc_cabang_penerima']);
	$tsc_user_id 			= htmlspecialchars($data['tsc_user_id']);
	$tsc_cabang 			= htmlspecialchars($data['tsc_cabang']);


	$count = mysqli_query($conn, "select * from transfer_select_cabang where tsc_user_id = ".$tsc_user_id." && tsc_cabang = ".$tsc_cabang." ");
	$count = mysqli_num_rows($count);

	if ( $count < 1 ) {
		// query insert data
		$query = "INSERT INTO transfer_select_cabang VALUES ('', '$tsc_cabang_pusat', '$tsc_cabang_penerima', '$tsc_user_id', '$tsc_cabang')";
		mysqli_query($conn, $query);
	} else {
		mysqli_query( $conn, "DELETE FROM transfer_select_cabang WHERE tsc_user_id = $tsc_user_id && tsc_cabang = $tsc_cabang");
	}

	return mysqli_affected_rows($conn);
}

function resetTransferSelectCabang($data) {
	global $conn;

	// ambil data dari tiap elemen dalam form
	$tsc_user_id 			= htmlspecialchars($data['tsc_user_id']);
	$tsc_cabang 			= htmlspecialchars($data['tsc_cabang']);
	$tsc_cabang_pusat		= htmlspecialchars($data['tsc_cabang_pusat']);

	$keranjang = mysqli_query($conn,"select * from keranjang_transfer where keranjang_transfer_id_kasir = ".$tsc_user_id." && keranjang_transfer_cabang = ".$tsc_cabang_pusat." ");
    $jmlkeranjang = mysqli_num_rows($keranjang);


    if ( $jmlkeranjang > 0 ) {
    	mysqli_query( $conn, "DELETE FROM keranjang_transfer WHERE keranjang_transfer_id_kasir = $tsc_user_id && keranjang_transfer_cabang = $tsc_cabang_pusat");
    } 

	mysqli_query( $conn, "DELETE FROM transfer_select_cabang WHERE tsc_user_id = $tsc_user_id && tsc_cabang = $tsc_cabang");

	return mysqli_affected_rows($conn);
}

function tambahkeranjangtransfer($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$keranjang_nama     			= $data['keranjang_nama'];
	$barang_id          			= $data['barang_id'];
	$keranjang_qty      			= 1;
	$keranjang_barang_sn_id     	= 0;
	$keranjang_barang_option_sn 	= $data['keranjang_barang_option_sn'];
	$keranjang_sn       			= 0;
	$keranjang_id_kasir 			= $data['keranjang_id_kasir'];
	$keranjang_cabang   			= $data['keranjang_cabang'];
	
	$keranjang_id_cek   			= $barang_id.$keranjang_id_kasir.$keranjang_cabang;
	
	$keranjang_cabang_pengirim 		= $data['keranjang_cabang_pengirim'];
	$keranjang_cabang_tujuan		= $data['keranjang_cabang_tujuan'];
	$barang_kode_slug				= $data['barang_kode_slug'];
	$barang_kode 					= $data['barang_kode'];
	$cabang_penerima_stock			= $data['cabang_penerima_stock'];

	// Mencari Data Barang berdasarkan Kode Slug dan cabang
	$barangTujuan 		= mysqli_query($conn,"select * from barang where barang_kode_slug = '".$barang_kode_slug."' && barang_cabang = ".$keranjang_cabang_tujuan." ");
    $jmlBarangTujuan 	= mysqli_num_rows($barangTujuan);

  	// Kondisi Jika Cabang Penerima tidak memiliki Produk terkait
  	if ( $jmlBarangTujuan < 1 ) {
  		echo "
  			<script>
  				alert('Maaf Kode Produk ".$barang_kode." Tidak Ada di Toko ".$cabang_penerima_stock." dan Coba Cek Kembali !!');
  			</script>
  		";
  	} else {
  		// Cek STOCK
		$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_transfer where keranjang_id_cek = '$keranjang_id_cek' "));
		
		if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
			$keranjangParent = mysqli_query( $conn, "select keranjang_transfer_qty from keranjang_transfer where keranjang_id_cek = '".$keranjang_id_cek."'");
	        $kp = mysqli_fetch_array($keranjangParent); 
	        $kp = $kp['keranjang_transfer_qty'];
	        $kp += $keranjang_qty;

	        $query = "UPDATE keranjang_transfer SET 
						keranjang_transfer_qty   = '$kp'
						WHERE keranjang_id_cek = $keranjang_id_cek
						";
			mysqli_query($conn, $query);
			return mysqli_affected_rows($conn);

		} else {
			// query insert data
			$query = "INSERT INTO keranjang_transfer VALUES ('', '$keranjang_nama', '$barang_id', '$barang_kode_slug', '$keranjang_qty', '$keranjang_barang_sn_id', '$keranjang_barang_option_sn', '$keranjang_sn', '$keranjang_id_kasir', '$keranjang_id_cek', '$keranjang_cabang_pengirim', '$keranjang_cabang_tujuan', '$keranjang_cabang')";
			
			mysqli_query($conn, $query);

			return mysqli_affected_rows($conn);
		}
  	}
}

function tambahKeranjangBarcodeTransfer($data) {
	global $conn;

	$barang_kode 					= htmlspecialchars($data['inputbarcode']);
	$barang_kode_slug   			= str_replace(" ", "-", $barang_kode);
	$keranjang_cabang_pengirim 		= $data['keranjang_cabang_pengirim'];
	$keranjang_cabang_tujuan		= $data['keranjang_cabang_tujuan'];
	$keranjang_id_kasir 			= $data['keranjang_id_kasir'];
	$keranjang_cabang   			= $data['keranjang_cabang'];

	// Ambil Data Barang berdasarkan Kode Barang 
	$barang 	= mysqli_query( $conn, "select barang_id, barang_nama, barang_harga, barang_option_sn from barang where barang_kode = '".$barang_kode."' && barang_cabang = '".$keranjang_cabang."' ");
    $br 		= mysqli_fetch_array($barang);

    $barang_id  				= $br["barang_id"];
    $keranjang_nama  			= $br["barang_nama"];
    $keranjang_barang_option_sn = $br["barang_option_sn"];
    $keranjang_qty      		= 1;
	$keranjang_barang_sn_id     = 0;
	$keranjang_sn       		= 0;
	$keranjang_id_cek   		= $barang_id.$keranjang_id_kasir.$keranjang_cabang;

	// Kondisi jika scan Barcode Tidak sesuai
	if ( $barang_id != null ) {

		// Cek STOCK
		$barang_id_cek = mysqli_num_rows(mysqli_query($conn, "select * from keranjang_transfer where keranjang_id_cek = '$keranjang_id_cek' "));
			
		if ( $barang_id_cek > 0 && $keranjang_barang_option_sn < 1 ) {
			$keranjangParent = mysqli_query( $conn, "select keranjang_transfer_qty from keranjang_transfer where keranjang_id_cek = '".$keranjang_id_cek."'");
	        $kp = mysqli_fetch_array($keranjangParent); 
	        $kp = $kp['keranjang_transfer_qty'];
	        $kp += $keranjang_qty;

	        $query = "UPDATE keranjang_transfer SET 
						keranjang_transfer_qty   = '$kp'
						WHERE keranjang_id_cek = $keranjang_id_cek
						";
			mysqli_query($conn, $query);
			return mysqli_affected_rows($conn);

		} else {
			// query insert data
			$query = "INSERT INTO keranjang_transfer VALUES ('', '$keranjang_nama', '$barang_id', '$barang_kode_slug', '$keranjang_qty', '$keranjang_barang_sn_id', '$keranjang_barang_option_sn', '$keranjang_sn', '$keranjang_id_kasir', '$keranjang_id_cek', '$keranjang_cabang_pengirim', '$keranjang_cabang_tujuan', '$keranjang_cabang')";
			
			mysqli_query($conn, $query);

			return mysqli_affected_rows($conn);
		}
	} else {
		echo '
			<script>
				alert("Kode Produk Tidak ada di Data Master Barang dan Coba Cek Kembali !! ");
				document.location.href = "";
			</script>
		';
	}
}

function updateSnTransfer($data){
	global $conn;
	$id = $data["keranjang_id"];


	// ambil data dari tiap elemen dalam form
	$barang_sn_id  				= $data["barang_sn_id"];
	$keranjang_transfer_cabang 	= $data['keranjang_transfer_cabang'];


	$barang_sn_desc = mysqli_query( $conn, "select barang_sn_desc from barang_sn where barang_sn_id = '".$barang_sn_id."'");
    $barang_sn_desc = mysqli_fetch_array($barang_sn_desc); 
    $barang_sn_desc = $barang_sn_desc['barang_sn_desc'];

    // Menghitung jumlah No SN berdasarkan cabang jika ada maka di tolak
    $barang_sn_count = mysqli_query($conn, "select * from keranjang_transfer where keranjang_sn = '".$barang_sn_desc."' && keranjang_transfer_cabang = '".$keranjang_transfer_cabang."' ");
    $barang_sn_count = mysqli_num_rows($barang_sn_count);

    if ( $barang_sn_count > 0 ) {
    	echo "
    		<script>
    			alert('Data No.SN ".$barang_sn_desc." Sudah ada di daftar transfer coba pilih yang lain !!');
    			document.location.href = 'transfer-stock-cabang';
    		</script>
    	";
    } else {
    	// query update data
		$query = "UPDATE keranjang_transfer SET 
							keranjang_barang_sn_id  			= '$barang_sn_id',
							keranjang_sn            			= '$barang_sn_desc'
							WHERE keranjang_transfer_id      	= $id
					";

		mysqli_query($conn, $query);
    }

	return mysqli_affected_rows($conn);

}


function updateQtyTransfer($data) {
	global $conn;
	$id = $data["keranjang_id"];

	// ambil data dari tiap elemen dalam form
	$keranjang_qty 		= htmlspecialchars($data['keranjang_qty']);
	$stock_brg 			= $data['stock_brg'];

	if ( $keranjang_qty > $stock_brg ) {
		echo"
			<script>
				alert('QTY Melebihi Stock Barang.. Coba Cek Lagi !!!');
				document.location.href = '';
			</script>
		";
	} else {
		// query update data
		$query = "UPDATE keranjang_transfer SET 
					keranjang_transfer_qty   		= '$keranjang_qty'
					WHERE keranjang_transfer_id 	= $id
					";
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
	}
}

function hapusKeranjangTransfer($id) {
	global $conn;

	mysqli_query( $conn, "DELETE FROM keranjang_transfer WHERE keranjang_transfer_id = $id");

	return mysqli_affected_rows($conn);
}

function prosesTransfer($data) {
	global $conn;
	
	// Data Input Tabel Transfer
	$transfer_ref 				= htmlspecialchars($data['transfer_ref']);
	$transfer_count				= htmlspecialchars($data['transfer_count']); 
	$transfer_date				= date("Y-m-d");
	$transfer_date_time			= date("d F Y g:i:s a");
	$transfer_note				= htmlspecialchars($data['transfer_note']);
	$transfer_pengirim_cabang   = $data['transfer_pengirim_cabang'];
	$transfer_penerima_cabang   = $data['transfer_penerima_cabang'];
	$transfer_id_tipe_keluar    = $data['transfer_id_tipe_keluar'];
	$transfer_id_tipe_masuk		= $data['transfer_id_tipe_masuk'];
		// Status Trnsfer Stock Antar Cabang
		// 1. Proses Kirim
		// 2. Selesai
		// 3. Dibatalkan 
	$transfer_status			= 1;
	$transfer_user				= htmlspecialchars($data['transfer_user']);
	$transfer_cabang 			= $data['transfer_cabang'];

	// ============================================================================= //
	// Data Input Tabel transfer_produk_keluar
	$tpk_transfer_barang_id		= $data['barang_id'];
	$tpk_barang_id				= $data['barang_id'];
	$tpk_kode_slug				= $data['tpk_kode_slug'];
	$tpk_qty					= $data['keranjang_transfer_qty'];
	$tpk_ref 					= $data['tpk_ref'];
	$tpk_date                   = $data['tpk_date'];
	$tpk_date_time              = $data['tpk_date_time'];
	$tpk_barang_option_sn       = $data['tpk_barang_option_sn'];
	$tpk_barang_sn_id           = $data['tpk_barang_sn_id'];
	$tpk_barang_sn_desc         = $data['tpk_barang_sn_desc'];
	$tpk_user                   = $data['keranjang_transfer_id_kasir'];
	$tpk_pengirim_cabang        = $data['tpk_pengirim_cabang'];
	$tpk_penerima_cabang        = $data['tpk_penerima_cabang'];
	$tpk_cabang                 = $data['tpk_cabang'];


	$jumlah = count($tpk_user);

	// query insert invoice
	$query1 = "INSERT INTO transfer VALUES ('', 
							'$transfer_ref', 
							'$transfer_count', 
							'$transfer_date', 
							'$transfer_date_time',
							'',
							'', 
							'$transfer_note', 
							'$transfer_pengirim_cabang', 
							'$transfer_penerima_cabang',
							'$transfer_id_tipe_keluar', 
							'$transfer_id_tipe_masuk', 
							'$transfer_status', 
							'$transfer_user', 
							'',
							'$transfer_cabang')";
	// var_dump($query1); die();
	mysqli_query($conn, $query1);

	for( $x=0; $x<$jumlah; $x++ ){
		$query = "INSERT INTO transfer_produk_keluar VALUES ('', 
										'$tpk_transfer_barang_id[$x]', 
										'$tpk_barang_id[$x]', 
										'$tpk_kode_slug[$x]', 
										'$tpk_qty[$x]', 
										'$tpk_ref[$x]', 
										'$tpk_date[$x]', 
										'$tpk_date_time[$x]', 
										'$tpk_barang_option_sn[$x]', 
										'$tpk_barang_sn_id[$x]', 
										'$tpk_barang_sn_desc[$x]', 
										'$tpk_user[$x]', 
										'$tpk_pengirim_cabang[$x]', 
										'$tpk_penerima_cabang[$x]',
										'$tpk_cabang[$x]')";

		mysqli_query($conn, $query);
	}
	
	// Mencari banyak barang SN
	$barang_option_sn = mysqli_query( $conn, "select tpk_barang_option_sn from transfer_produk_keluar where tpk_ref = '".$transfer_ref."' && tpk_barang_option_sn > 0 && tpk_cabang = '".$transfer_cabang."' ");
	$barang_option_sn = mysqli_num_rows($barang_option_sn);

	
    
	// Mencari ID SN
	if ( $barang_option_sn > 0 ) {
		$barang_sn_id = query("SELECT * FROM transfer_produk_keluar WHERE tpk_ref = $transfer_ref && tpk_barang_option_sn > 0 && tpk_cabang = $transfer_cabang ");

		// var_dump($barang_sn_id); die();
		foreach ( $barang_sn_id as $row ) :
		 	$barang_sn_id = $row['tpk_barang_sn_id'];

		 	$barang = count($barang_sn_id);
		 	for ( $i = 0; $i < $barang; $i++ ) {
		 		$query5 = "UPDATE barang_sn SET 
						barang_sn_status     = 5
						WHERE barang_sn_id = $barang_sn_id
				";
		 	}
		 	mysqli_query($conn, $query5);
		endforeach;
	}

	mysqli_query( $conn, "DELETE FROM keranjang_transfer WHERE keranjang_transfer_id_kasir = $transfer_user");
	mysqli_query( $conn, "DELETE FROM transfer_select_cabang WHERE tsc_user_id = $transfer_user && tsc_cabang = $transfer_id_tipe_keluar");

	return mysqli_affected_rows($conn);
}

function hapusTransferStockCabang($id) {
	global $conn;
    
	mysqli_query( $conn, "DELETE FROM transfer WHERE transfer_ref = $id");
	mysqli_query( $conn, "DELETE FROM transfer_produk_keluar WHERE tpk_ref = $id");

	return mysqli_affected_rows($conn);
}

function prosesKonfirmasiTransfer($data) {
	global $conn;
	
	// Data Input Tabel Transfer
	$transfer_status 					= htmlspecialchars($data['transfer_status']); 
	$transfer_terima_date				= date("Y-m-d");
	$transfer_terima_date_time			= date("d F Y g:i:s a");
	$transfer_ref 						= $data['transfer_ref'];
	$transfer_user_penerima 			= $data['transfer_user_penerima'];
	$transfer_penerima_cabang			= $data['transfer_penerima_cabang'];
		// Status Trnsfer Stock Antar Cabang
		// 1. Proses Kirim
		// 2. Selesai
		// 3. Dibatalkan 

	// ============================================================================= //
	// Data Input Tabel transfer_produk_masuk
	$tpm_kode_slug			= $data['tpm_kode_slug'];
	$tpm_qty				= $data['tpm_qty'];
	$tpm_ref				= $data['tpm_ref'];
	$tpm_date				= $data['tpm_date'];
	$tpm_date_time 			= $data['tpm_date_time'];
	$tpm_barang_option_sn   = $data['tpm_barang_option_sn'];
	$tpm_barang_sn_id       = $data['tpm_barang_sn_id'];
	$tpm_barang_sn_desc     = $data['tpm_barang_sn_desc'];
	$tpm_user           	= $data['tpm_user'];
	$tpm_pengirim_cabang    = $data['tpm_pengirim_cabang'];
	$tpm_penerima_cabang    = $data['tpm_penerima_cabang'];
	$tpm_cabang        		= $data['tpm_cabang'];


	$jumlah = count($tpm_user);

	// Mencari banyak barang SN di tabel transfer_produk_keluar
	$barang_option_sn_produk_keluar = mysqli_query( $conn, "select tpk_barang_option_sn from transfer_produk_keluar where tpk_ref = '".$transfer_ref."' && tpk_barang_option_sn > 0 && tpk_penerima_cabang = '".$transfer_penerima_cabang."' ");
	$barang_option_sn_produk_keluar = mysqli_num_rows($barang_option_sn_produk_keluar);


	if ( $barang_option_sn_produk_keluar > 0 ) {
		if ( $transfer_status > 0 ) {
			// query update data
			$query = "UPDATE transfer SET 
						transfer_terima_date   		= '$transfer_terima_date',
						transfer_terima_date_time   = '$transfer_terima_date_time',
						transfer_status 			= 2,
						transfer_user_penerima      = '$transfer_user_penerima'
						WHERE transfer_ref 			= $transfer_ref
						";
			mysqli_query($conn, $query);

			for( $x=0; $x<$jumlah; $x++ ){
				$query1 = "INSERT INTO transfer_produk_masuk VALUES ('', 
											'$tpm_kode_slug[$x]', 
											'$tpm_qty[$x]', 
											'$tpm_ref[$x]', 
											'$tpm_date[$x]', 
											'$tpm_date_time[$x]', 
											'$tpm_barang_option_sn[$x]', 
											'$tpm_barang_sn_id[$x]', 
											'$tpm_barang_sn_desc[$x]', 
											'$tpm_user[$x]', 
											'$tpm_pengirim_cabang[$x]', 
											'$tpm_penerima_cabang[$x]', 
											'$tpm_cabang[$x]')";
				mysqli_query($conn, $query1);
			}

			// Mencari banyak barang SN
			$barang_option_sn = mysqli_query( $conn, "select tpm_barang_option_sn from transfer_produk_masuk where tpm_ref = '".$transfer_ref."' && tpm_barang_option_sn > 0 && tpm_penerima_cabang = '".$transfer_penerima_cabang."' ");
			$barang_option_sn = mysqli_num_rows($barang_option_sn);


			// Mencari ID SN
			if ( $barang_option_sn > 0 ) {
				$barang_sn_id = query("SELECT * FROM transfer_produk_masuk WHERE tpm_ref = $transfer_ref && tpm_barang_option_sn > 0 && tpm_penerima_cabang = $transfer_penerima_cabang ");
				
				// var_dump($barang_sn_id); die();
				foreach ( $barang_sn_id as $row ) :
				 	$barang_sn_id = $row['tpm_barang_sn_id'];

				 	$barang = count($barang_sn_id);
				 	for ( $i = 0; $i < $barang; $i++ ) {
				 		$query5 = "UPDATE barang_sn SET 
								barang_sn_status     = 1,
								barang_sn_cabang     = '$transfer_penerima_cabang'
								WHERE barang_sn_id = $barang_sn_id
						";
				 	}
				 	mysqli_query($conn, $query5);
				endforeach;
			}
		} else {
			// query update data
			$query = "UPDATE transfer SET 
							transfer_terima_date   		= '$transfer_terima_date',
							transfer_terima_date_time   = '$transfer_terima_date_time',
							transfer_status 			= 0,
							transfer_user_penerima      = '$transfer_user_penerima'
							WHERE transfer_ref 			= $transfer_ref
							";
			mysqli_query($conn, $query);
		}
	} else {
		if ( $transfer_status > 0 ) {
			// query update data
			$query = "UPDATE transfer SET 
						transfer_terima_date   		= '$transfer_terima_date',
						transfer_terima_date_time   = '$transfer_terima_date_time',
						transfer_status 			= 2,
						transfer_user_penerima      = '$transfer_user_penerima'
						WHERE transfer_ref 			= $transfer_ref
						";
			mysqli_query($conn, $query);

			for( $x=0; $x<$jumlah; $x++ ){
				$query1 = "INSERT INTO transfer_produk_masuk VALUES ('', 
											'$tpm_kode_slug[$x]', 
											'$tpm_qty[$x]', 
											'$tpm_ref[$x]', 
											'$tpm_date[$x]', 
											'$tpm_date_time[$x]', 
											'$tpm_barang_option_sn[$x]', 
											'$tpm_barang_sn_id[$x]', 
											'$tpm_barang_sn_desc[$x]', 
											'$tpm_user[$x]', 
											'$tpm_pengirim_cabang[$x]', 
											'$tpm_penerima_cabang[$x]', 
											'$tpm_cabang[$x]')";
				mysqli_query($conn, $query1);
			}
		} else {
			// query update data
			$query = "UPDATE transfer SET 
							transfer_terima_date   		= '$transfer_terima_date',
							transfer_terima_date_time   = '$transfer_terima_date_time',
							transfer_status 			= 0,
							transfer_user_penerima      = '$transfer_user_penerima'
							WHERE transfer_ref 			= $transfer_ref
							";
			mysqli_query($conn, $query);
		}
	}

	return mysqli_affected_rows($conn);
}


// ====================================== Laba Bersih ===================================== //
function editLabaBersih($data) {
	global $conn;
	$id = $data["lb_id"];

	// ambil data dari tiap elemen dalam form
	$lb_pendapatan_lain      			= $data["lb_pendapatan_lain"];
	$lb_pengeluaran_gaji      			= $data["lb_pengeluaran_gaji"];
	$lb_pengeluaran_listrik 			= $data["lb_pengeluaran_listrik"];
	$lb_pengeluaran_tlpn_internet     	= $data["lb_pengeluaran_tlpn_internet"];
	$lb_pengeluaran_perlengkapan_toko   = $data["lb_pengeluaran_perlengkapan_toko"];
	$lb_pengeluaran_biaya_penyusutan    = $data["lb_pengeluaran_biaya_penyusutan"];
	$lb_pengeluaran_bensin     			= $data["lb_pengeluaran_bensin"];
	$lb_pengeluaran_tak_terduga 		= $data["lb_pengeluaran_tak_terduga"];
	$lb_pengeluaran_lain        		= $data["lb_pengeluaran_lain"];
	$lb_cabang 							= $data["lb_cabang"];

	// query update data
	$query = "UPDATE laba_bersih SET 
				lb_pendapatan_lain       			= '$lb_pendapatan_lain',
				lb_pengeluaran_gaji       			= '$lb_pengeluaran_gaji',
				lb_pengeluaran_listrik      		= '$lb_pengeluaran_listrik',
				lb_pengeluaran_tlpn_internet      	= '$lb_pengeluaran_tlpn_internet',
				lb_pengeluaran_perlengkapan_toko    = '$lb_pengeluaran_perlengkapan_toko',
				lb_pengeluaran_biaya_penyusutan     = '$lb_pengeluaran_biaya_penyusutan',
				lb_pengeluaran_bensin  				= '$lb_pengeluaran_bensin',
				lb_pengeluaran_tak_terduga  		= '$lb_pengeluaran_tak_terduga',
				lb_pengeluaran_lain 				= '$lb_pengeluaran_lain'
				WHERE lb_id = $id && lb_cabang = $lb_cabang
				";

	mysqli_query($conn, $query);
	echo $conn->error;die;
	return mysqli_affected_rows($conn);
}

// ============================= Stock Opname Keseluruhan ================================= //
function tambahStockOpname($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form
	$stock_opname_date_create 		= date("Y-m-d");
	$stock_opname_datetime_create 	= date("d F Y g:i:s a");
	$stock_opname_date_proses 		= htmlspecialchars($data['stock_opname_date_proses']);
	$stock_opname_user_create 		= htmlspecialchars($data['stock_opname_user_create']);
	$stock_opname_user_eksekusi 	= htmlspecialchars($data['stock_opname_user_eksekusi']);
	// Status 0 = Proses || 1 = selesai
	$stock_opname_status 			= 0;
	$stock_opname_tipe 				= htmlspecialchars($data['stock_opname_tipe']);
	$stock_opname_cabang 			= htmlspecialchars($data['stock_opname_cabang']);

	// query insert data
	$query = "INSERT INTO stock_opname (stock_opname_date_create, stock_opname_datetime_create, stock_opname_date_proses, stock_opname_user_create, stock_opname_user_eksekusi, stock_opname_status, stock_opname_user_upload, stock_opname_date_upload, stock_opname_datetime_upload, stock_opname_tipe, stock_opname_cabang)
				VALUES
			  ('$stock_opname_date_create', '$stock_opname_datetime_create', '$stock_opname_date_proses', '$stock_opname_user_create', '$stock_opname_user_eksekusi', '$stock_opname_status', 0, '', '', '$stock_opname_tipe', '$stock_opname_cabang')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function hapusStockOpname($id, $sessionCabang) {
	global $conn;

	$stock_opname_hasil_count = mysqli_query($conn, "SELECT * FROM stock_opname_hasil WHERE soh_stock_opname_id = $id && soh_barang_cabang = $sessionCabang");
	$stock_opname_hasil_count = mysqli_num_rows($stock_opname_hasil_count);


	if ( $stock_opname_hasil_count > 0 ) {
		mysqli_query( $conn, "DELETE FROM stock_opname_hasil WHERE soh_stock_opname_id = $id && soh_barang_cabang = $sessionCabang");
	}

	mysqli_query( $conn, "DELETE FROM stock_opname WHERE stock_opname_id = $id");

	return mysqli_affected_rows($conn);
}

function tambahStockOpnamePerProduk($data) {
	global $conn;
	// ambil data dari tiap elemen dalam form

	$soh_stock_opname_id 		= htmlspecialchars($data['soh_stock_opname_id']);
	$soh_barang_kode 			= htmlspecialchars($data['soh_barang_kode']);
	$soh_stock_fisik 			= htmlspecialchars($data['soh_stock_fisik']);
	$soh_note 					= htmlspecialchars($data['soh_note']);
	$soh_date 					= date("Y-m-d");
	$soh_datetime 				= date("d F Y g:i:s a");
	$soh_tipe 					= htmlspecialchars($data['soh_tipe']);
	$soh_user 					= htmlspecialchars($data['soh_user']);
	$soh_barang_cabang 			= htmlspecialchars($data['soh_barang_cabang']);

	$soh_barang_kode_slug       = str_replace(" ", "-", $soh_barang_kode);

    $barang         = mysqli_query($conn, "SELECT barang_id, barang_stock FROM barang WHERE barang_cabang = $soh_barang_cabang && barang_kode_slug = '".$soh_barang_kode_slug."' ");
    $barang         = mysqli_fetch_array($barang);
    $barang_id      = $barang['barang_id'];
    $barang_stock   = $barang['barang_stock'];
    $soh_selisih            	= $soh_stock_fisik - $barang_stock;

    if ( $barang_id == null ) {
        echo '
            <script>
                alert("Kode Barang/Barcode '.$soh_barang_kode.' TIDAK ADA di DATA Barang !! Silahkan Sesuaikan & Cek Kembali dari penulisan Huruf Besar, Kecil, Spasi beserta KODE HARUS SESUAI !!");
                  document.location.href = "";
            </script>
        '; exit();
    } 
	
	// query insert data
	$query = "INSERT INTO stock_opname_hasil VALUES ('', 
            '$soh_stock_opname_id',
            '$barang_id', 
            '$soh_barang_kode', 
            '$barang_stock', 
            '$soh_stock_fisik',
            '$soh_selisih', 
            '$soh_note',
            '$soh_date',
            '$soh_datetime',
            '$soh_tipe',
            '$soh_user',
            '$soh_barang_cabang')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function editStockOpname($data) {
	global $conn;
	$id = $data["stock_opname_id"];

	// ambil data dari tiap elemen dalam form
	$stock_opname_user_upload 		= htmlspecialchars($data['stock_opname_user_upload']);
	$stock_opname_status 			= htmlspecialchars($data['stock_opname_status']);
	$stock_opname_date_upload 		= date("Y-m-d");
	$stock_opname_datetime_upload 	= date("d F Y g:i:s a");
	$stock_opname_cabang			= htmlspecialchars($data['stock_opname_cabang']);

	$query = "UPDATE stock_opname SET 
            stock_opname_status           = '$stock_opname_status',
            stock_opname_user_upload      = '$stock_opname_user_upload',
            stock_opname_date_upload      = '$stock_opname_date_upload',
            stock_opname_datetime_upload  = '$stock_opname_datetime_upload'
            WHERE stock_opname_id         = $id && stock_opname_cabang = $stock_opname_cabang;
            ";
    mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

// ======================================== KARYAWAN =============================== //
function tambahKaryawan($data) {
	global $conn;
	global $sessionCabang;
	$nama_karyawan  = htmlspecialchars($data["nama_karyawan"]);
	$jabatan        = base64_decode($data["jabatan"]);
	$status         = base64_decode($data["status"]);
	$tanggal_masuk  = $data["tanggal_masuk"];
	$tanggal_keluar = $data["tanggal_keluar"] == '' ? NULL : $data["tanggal_keluar"];
	$created_at     = date('Y-m-d H:i:s');
	$updated_at     = date('Y-m-d H:i:s');

	$query = "INSERT INTO karyawan(nama_karyawan, jabatan, status, tanggal_masuk, tanggal_keluar, cabang, created_at, updated_at)
				VALUES
			  (?, ?, ?, ?, ?, ?, ?, ?)";
	if ($stmt = $conn->prepare($query)) {
		$stmt->bind_param('ssssssss', $nama_karyawan, $jabatan, $status, $tanggal_masuk, $tanggal_keluar, $sessionCabang, $created_at, $updated_at);
		if ($stmt->execute()) {
			$stmt->close();
			return true;
		}
	}

	return false;
}

function editKaryawan($data) {
	global $conn;
	$id 			= $data["id"];
	$nama_karyawan  = htmlspecialchars($data["nama_karyawan"]);
	$jabatan        = base64_decode($data["jabatan"]);
	$status         = base64_decode($data["status"]);
	$tanggal_masuk  = $data["tanggal_masuk"];
	$tanggal_keluar = $data["tanggal_keluar"] == '' ? NULL : $data["tanggal_keluar"];
	$updated_at     = date('Y-m-d H:i:s');

	$query = "UPDATE karyawan SET nama_karyawan = ?, jabatan = ?, status = ?, tanggal_masuk = ?, tanggal_keluar = ?, updated_at = ? WHERE id = ?";

	mysqli_query($conn, $query);
	if ($stmt = $conn->prepare($query)) {
		$stmt->bind_param('ssssssi', $nama_karyawan, $jabatan, $status, $tanggal_masuk, $tanggal_keluar, $updated_at, $id);
		if ($stmt->execute()) {
			$stmt->close();
			return true;
		}
	}

	return false;
}

function hapusKaryawan($id) {
	global $conn;
	$id = $id;
	mysqli_query($conn, "DELETE FROM karyawan WHERE id = $id");
	return mysqli_affected_rows($conn);
}

// ====================================== INCOMES & EXPENSES =============================== //

function tambahPendapatan($data){
	global $conn;
	global $sessionCabang;
	$incomes_expenses = base64_decode($data['incomes_expenses']);
	$jenis = base64_decode($data['type']);
	$nama = htmlspecialchars($data['nama']);
	$total = str_replace(',', '', $data['total']);
	$real_income = str_replace(',', '', $data['real_income']);
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$tanggal = $data['tanggal'];
	$created_at = date('Y-m-d H:i:s');
	$updated_at = date('Y-m-d H:i:s');

	$conn->begin_transaction();
	try {
		$query = "INSERT INTO laba_bersih_detail(incomes_expenses, jenis, nama, total, real_income, jenis_pembayaran, tanggal, cabang, created_at, updated_at)
					VALUES
				(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		if ($stmt = $conn->prepare($query)) {
			$stmt->bind_param('ssssssssss', $incomes_expenses, $jenis, $nama, $total, $real_income, $jenis_pembayaran, $tanggal, $sessionCabang, $created_at, $updated_at);
			if ($stmt->execute()) {
				$stmt->close();
				
				/** INSERT OR UPDATE LABA BERSIH */
				$laba_bersih_query = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

				$sum_incomes_expenses = sumIncomesExpenses(['pendapatan_lain', 'revenue'], $tanggal, $tanggal, $sessionCabang);
				$lb_pendapatan_lain = $sum_incomes_expenses['sum_real_income'] != null ? $sum_incomes_expenses['sum_real_income'] : 0;

				if(!$laba_bersih){
					mysqli_query($conn, "INSERT INTO laba_bersih(lb_pendapatan_lain, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
						VALUES
						('$lb_pendapatan_lain', 0, 0, 0, 0, 0, 0, 0, 0, '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
				} else {
					mysqli_query($conn, "UPDATE laba_bersih SET lb_pendapatan_lain='$lb_pendapatan_lain', updated_at='$updated_at' WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				}
			}
		}
		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function updatePendapatan($data){
	global $conn;
	global $sessionCabang;
	$nama = htmlspecialchars($data['nama']);
	$total = str_replace(',', '', $data['total']);
	$real_income = str_replace(',', '', $data['real_income']);
	$id = base64_decode($data['id']);
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$tanggal = $data['tanggal'];
	$updated_at = date('Y-m-d H:i:s');
	$created_at = date('Y-m-d H:i:s');

	$revenue_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE id='$id' AND cabang='$sessionCabang'");

	if(mysqli_num_rows($revenue_query) == 0){
		return false;
	}

	$revenue = mysqli_fetch_assoc($revenue_query);
	
	$conn->begin_transaction();
	try {
		$query = "UPDATE laba_bersih_detail SET nama = ?, total = ?, real_income = ?, jenis_pembayaran = ?, tanggal = ?, cabang = ?, updated_at = ? WHERE id = ?";
		if ($stmt = $conn->prepare($query)) {
			$stmt->bind_param('sssssssi', $nama, $total, $real_income, $jenis_pembayaran, $tanggal, $sessionCabang, $updated_at, $id);
			if ($stmt->execute()) {
				$stmt->close();

				/** ROLLBACK LABA BERSIH */
				$tanggal_pendapatan = $revenue['tanggal'];
				$total_rollback = $revenue['real_income'];
				mysqli_query($conn, "UPDATE laba_bersih SET lb_pendapatan_lain=lb_pendapatan_lain - $total_rollback, updated_at='$updated_at' WHERE tanggal='$tanggal_pendapatan' AND lb_cabang='$sessionCabang'");

				/** INSERT OR UPDATE LABA BERSIH */
				$laba_bersih_query = mysqli_query($conn, "SELECT lb_id FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

				$sum_incomes_expenses = sumIncomesExpenses(['pendapatan_lain', 'revenue'], $tanggal, $tanggal, $sessionCabang);
				$lb_pendapatan_lain = $sum_incomes_expenses['sum_real_income'] != null ? $sum_incomes_expenses['sum_real_income'] : 0;

				if(!$laba_bersih){
					mysqli_query($conn, "INSERT INTO laba_bersih(lb_pendapatan_lain, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
						VALUES
						('$lb_pendapatan_lain', 0, 0, 0, 0, 0, 0, 0, 0, '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
				} else {
					mysqli_query($conn, "UPDATE laba_bersih SET lb_pendapatan_lain='$lb_pendapatan_lain', updated_at='$updated_at' WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				}
			}
		}

		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function tambahIncomesExpenses($data){
	global $conn;
	global $sessionCabang;
	$incomes_expenses = base64_decode($data['incomes_expenses']);
	$jenis = base64_decode($data['type']);
	$nama = htmlspecialchars($data['nama']);
	$harga = isset($data['harga']) ? str_replace(',', '', $data['harga']) : 0;
	$qty = isset($data['qty']) ? $data['qty'] : 0;
	// $total = isset($data['total']) ? str_replace(',', '', $data['total']) : 0;
	$total = $harga * $qty;
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$tanggal = $data['tanggal'];
	$created_at = date('Y-m-d H:i:s');
	$updated_at = date('Y-m-d H:i:s');

	$conn->begin_transaction();
	try {
		$laba_bersih_detail_id = 0;

		$query = "INSERT INTO laba_bersih_detail(incomes_expenses, jenis, nama, harga, qty, total, jenis_pembayaran, tanggal, cabang, created_at, updated_at)
					VALUES
				(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		if ($stmt = $conn->prepare($query)) {
			$stmt->bind_param('sssssssssss', $incomes_expenses, $jenis, $nama, $harga, $qty, $total, $jenis_pembayaran, $tanggal, $sessionCabang, $created_at, $updated_at);
			if ($stmt->execute()) {
				$laba_bersih_detail_id = $conn->insert_id;
				$stmt->close();

				/** PENAMBAHAN ASET PENGELUARAN PERLENGKAPAN TOKO */
				if($jenis == 'perlengkapan_toko'){
					if(isset($_POST['nama_barang'])){
						$query_penambahan_aset = "INSERT INTO penambahan_aset(id_laba_bersih_detail, nama_barang, qty, harga, total, cabang, created_at, updated_at)
													VALUES
												(?, ?, ?, ?, ?, ?, ?, ?)";
						if ($stmt = $conn->prepare($query_penambahan_aset)) {
							$total = 0;
							for($i = 0; $i < count($_POST['nama_barang']); $i++){
								$nama_barang = htmlspecialchars($_POST['nama_barang'][$i]);
								$qty_barang = isset($_POST['qty_barang'][$i]) ? $_POST['qty_barang'][$i] : 0;
								$harga_barang = isset($_POST['harga_barang'][$i]) ? str_replace(',', '', $_POST['harga_barang'][$i]) : 0;
								$total_harga = $qty_barang * $harga_barang;
								$stmt->bind_param("ssssssss", $laba_bersih_detail_id, $nama_barang, $qty_barang, $harga_barang, $total_harga, $sessionCabang, $created_at, $updated_at);
								$stmt->execute();
								$total += $total_harga;
							}

							mysqli_query($conn, "UPDATE laba_bersih_detail SET total='$total' WHERE id='$laba_bersih_detail_id' AND cabang='$sessionCabang'");
						}
					}
				}

				/** INSERT OR UPDATE LABA BERSIH */
				$sum_listrik = sumIncomesExpenses('listrik', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_listrik = $sum_listrik['sum_total'] != null ? $sum_listrik['sum_total'] : 0;

				$sum_internet = sumIncomesExpenses('telepon_internet', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_tlpn_internet = $sum_internet['sum_total'] != null ? $sum_internet['sum_total'] : 0;

				$sum_ptoko = sumIncomesExpenses('perlengkapan_toko', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_perlengkapan_toko = $sum_ptoko['sum_total'] != null ? $sum_ptoko['sum_total'] : 0;

				$sum_penyusutan = sumIncomesExpenses('biaya_penyusutan', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_biaya_penyusutan = $sum_penyusutan['sum_total'] != null ? $sum_penyusutan['sum_total'] : 0;

				$sum_bensin = sumIncomesExpenses('bensin', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_bensin = $sum_bensin['sum_total'] != null ? $sum_bensin['sum_total'] : 0;
				
				$sum_tak_terduga = sumIncomesExpenses('tak_terduga', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_tak_terduga = $sum_tak_terduga['sum_total'] != null ? $sum_tak_terduga['sum_total'] : 0;

				$sum_lain = sumIncomesExpenses('lain_lain', $tanggal, $tanggal, $sessionCabang);
				$lb_pengeluaran_lain = $sum_lain['sum_total'] != null ? $sum_lain['sum_total'] : 0;

				$laba_bersih_query = mysqli_query($conn, "SELECT lb_id FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

				if(!$laba_bersih){
					mysqli_query($conn, "INSERT INTO laba_bersih(lb_pendapatan_lain, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
						VALUES
						('0', '0', '$lb_pengeluaran_listrik', '$lb_pengeluaran_tlpn_internet', '$lb_pengeluaran_perlengkapan_toko', '$lb_pengeluaran_biaya_penyusutan', '$lb_pengeluaran_bensin', '$lb_pengeluaran_tak_terduga', '$lb_pengeluaran_lain', '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
				} else {
					mysqli_query($conn, "UPDATE laba_bersih
											SET lb_pengeluaran_listrik='$lb_pengeluaran_listrik',
											lb_pengeluaran_tlpn_internet='$lb_pengeluaran_tlpn_internet',
											lb_pengeluaran_perlengkapan_toko='$lb_pengeluaran_perlengkapan_toko',
											lb_pengeluaran_biaya_penyusutan='$lb_pengeluaran_biaya_penyusutan',
											lb_pengeluaran_bensin='$lb_pengeluaran_bensin',
											lb_pengeluaran_tak_terduga='$lb_pengeluaran_tak_terduga',
											lb_pengeluaran_lain='$lb_pengeluaran_lain',
											updated_at='$updated_at'
											WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				}

			}
		}

		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function updateIncomesExpenses($data){
	global $conn;
	global $sessionCabang;
	$jenis = base64_decode($data['type']);
	$nama = htmlspecialchars($data['nama']);
	$harga = isset($data['harga']) ? str_replace(',', '', $data['harga']) : 0;
	$qty = isset($data['qty']) ? $data['qty'] : 0;
	// $total = isset($data['total']) ? str_replace(',', '', $data['total']) : 0;
	$total = $harga * $qty;
	$id = base64_decode($data['id']);
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$tanggal = $data['tanggal'];
	$created_at = date('Y-m-d H:i:s');
	$updated_at = date('Y-m-d H:i:s');

	$incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE id='$id' AND cabang='$sessionCabang'");

	if(mysqli_num_rows($incomes_expenses_query) == 0){
		return false;
	}

	$incomes_expenses = mysqli_fetch_assoc($incomes_expenses_query);
	$laba_bersih_query = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
	$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);
	
	$conn->begin_transaction();
	try {
		$query = "UPDATE laba_bersih_detail SET nama = ?, harga = ?, qty = ?, total = ?, jenis_pembayaran = ?, tanggal = ?, cabang = ?, updated_at = ? WHERE id = ?";
		

		/** PENAMBAHAN ASET PENGELUARAN PERLENGKAPAN TOKO */
		if($jenis == 'perlengkapan_toko'){
			if(isset($_POST['nama_barang'])){
				mysqli_query($conn, "DELETE FROM penambahan_aset WHERE id_laba_bersih_detail = '$id' AND cabang='$sessionCabang'");
				$query_penambahan_aset = "INSERT INTO penambahan_aset(id_laba_bersih_detail, nama_barang, qty, harga, total, cabang, created_at, updated_at)
											VALUES
										 (?, ?, ?, ?, ?, ?, ?, ?)";
				if ($stmt = $conn->prepare($query_penambahan_aset)) {
					$total = 0;
					for($i = 0; $i < count($_POST['nama_barang']); $i++){
						$nama_barang = htmlspecialchars($_POST['nama_barang'][$i]);
						$qty_barang = isset($_POST['qty_barang'][$i]) ? $_POST['qty_barang'][$i] : 0;
						$harga_barang = isset($_POST['harga_barang'][$i]) ? str_replace(',', '', $_POST['harga_barang'][$i]) : 0;
						$total_harga = $qty_barang * $harga_barang;
						$stmt->bind_param("ssssssss", $id, $nama_barang, $qty_barang, $harga_barang, $total_harga, $sessionCabang, $created_at, $updated_at);
						$stmt->execute();
						$total += $total_harga;
					}
				}
			}
		}

		if ($stmt = $conn->prepare($query)) {
			$stmt->bind_param('ssssssssi', $nama, $harga, $qty, $total, $jenis_pembayaran, $tanggal, $sessionCabang, $updated_at, $id);
			if ($stmt->execute()) {
				$stmt->close();
				
				/** ROLLBACK LABA BERSIH */
				$tanggal_incomes_expenses = $incomes_expenses['tanggal'];
				$total_rollback = $incomes_expenses['total'];
				$set_rollback_query = '';
				
				switch($jenis){
					case 'listrik':
						$set_rollback_query = "lb_pengeluaran_listrik=lb_pengeluaran_listrik - $total_rollback";
						break;

					case 'telepon_internet':
						$set_rollback_query = "lb_pengeluaran_tlpn_internet=lb_pengeluaran_tlpn_internet - $total_rollback";
						break;

					case 'perlengkapan_toko':
						$set_rollback_query = "lb_pengeluaran_perlengkapan_toko=lb_pengeluaran_perlengkapan_toko - $total_rollback";
						break;

					case 'biaya_penyusutan':
						$set_rollback_query = "lb_pengeluaran_biaya_penyusutan=lb_pengeluaran_biaya_penyusutan - $total_rollback";
						break;

					case 'bensin':
						$set_rollback_query = "lb_pengeluaran_bensin=lb_pengeluaran_bensin - $total_rollback";
						break;

					case 'tak_terduga':
						$set_rollback_query = "lb_pengeluaran_tak_terduga=lb_pengeluaran_tak_terduga - $total_rollback";
						break;

					case 'lain_lain':
						$set_rollback_query = "lb_pengeluaran_lain=lb_pengeluaran_lain - $total_rollback";
						break;
				}

				mysqli_query($conn, "UPDATE laba_bersih SET $set_rollback_query, updated_at='$updated_at' WHERE tanggal='$tanggal_incomes_expenses' AND lb_cabang='$sessionCabang'");
				
				/** INSERT OR UPDATE LABA BERSIH */
				$sum_listrik = sumIncomesExpenses('listrik', $tanggal, $tanggal, $sessionCabang);
				$sum_listrik = $sum_listrik['sum_total'] != null ? $sum_listrik['sum_total'] : 0;
				$lb_pengeluaran_listrik = $sum_listrik;

				$sum_internet = sumIncomesExpenses('telepon_internet', $tanggal, $tanggal, $sessionCabang);
				$sum_internet = $sum_internet['sum_total'] != null ? $sum_internet['sum_total'] : 0;
				$lb_pengeluaran_tlpn_internet = $sum_internet;

				$sum_ptoko = sumIncomesExpenses('perlengkapan_toko', $tanggal, $tanggal, $sessionCabang);
				$sum_ptoko = $sum_ptoko['sum_total'] != null ? $sum_ptoko['sum_total'] : 0;
				$lb_pengeluaran_perlengkapan_toko = $sum_ptoko;

				$sum_penyusutan = sumIncomesExpenses('biaya_penyusutan', $tanggal, $tanggal, $sessionCabang);
				$sum_penyusutan = $sum_penyusutan['sum_total'] != null ? $sum_penyusutan['sum_total'] : 0;
				$lb_pengeluaran_biaya_penyusutan = $sum_penyusutan;

				$sum_bensin = sumIncomesExpenses('bensin', $tanggal, $tanggal, $sessionCabang);
				$sum_bensin = $sum_bensin['sum_total'] != null ? $sum_bensin['sum_total'] : 0;
				$lb_pengeluaran_bensin = $sum_bensin;
				
				$sum_tak_terduga = sumIncomesExpenses('tak_terduga', $tanggal, $tanggal, $sessionCabang);
				$sum_tak_terduga = $sum_tak_terduga['sum_total'] != null ? $sum_tak_terduga['sum_total'] : 0;
				$lb_pengeluaran_tak_terduga = $sum_tak_terduga;

				$sum_lain = sumIncomesExpenses('lain_lain', $tanggal, $tanggal, $sessionCabang);
				$sum_lain = $sum_lain['sum_total'] != null ? $sum_lain['sum_total'] : 0;
				$lb_pengeluaran_lain = $sum_lain;

				$laba_bersih_query = mysqli_query($conn, "SELECT lb_id FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
				$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

				if(!$laba_bersih){
					mysqli_query($conn, "INSERT INTO laba_bersih(lb_pendapatan_lain, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
						VALUES
						('0', '0', '$lb_pengeluaran_listrik', '$lb_pengeluaran_tlpn_internet', '$lb_pengeluaran_perlengkapan_toko', '$lb_pengeluaran_biaya_penyusutan', '$lb_pengeluaran_bensin', '$lb_pengeluaran_tak_terduga', '$lb_pengeluaran_lain', '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
						
				} else {
					mysqli_query($conn, "UPDATE laba_bersih
											SET lb_pengeluaran_listrik='$lb_pengeluaran_listrik',
											lb_pengeluaran_tlpn_internet='$lb_pengeluaran_tlpn_internet',
											lb_pengeluaran_perlengkapan_toko='$lb_pengeluaran_perlengkapan_toko',
											lb_pengeluaran_biaya_penyusutan='$lb_pengeluaran_biaya_penyusutan',
											lb_pengeluaran_bensin='$lb_pengeluaran_bensin',
											lb_pengeluaran_tak_terduga='$lb_pengeluaran_tak_terduga',
											lb_pengeluaran_lain='$lb_pengeluaran_lain',
											updated_at='$updated_at'
											WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
					
				}

			}
		}

		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function tambahGajiKaryawan($data){
	global $conn;
	global $sessionCabang;
	$incomes_expenses = base64_decode($data['incomes_expenses']);
	$jenis = 'gaji_karyawan';
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$id_karyawan = base64_decode($data['karyawan']);
	$periode = $data['periode'];
	// $tanggal = $data['periode'] . '-' . date('d');
	// $tanggal = date('Y-m-d');
	$tanggal = $data['tanggal'];
	$kddh = $data['kddh'] != '' ? str_replace(',', '', $data['kddh']) : 0;
	$bonus_omset = $data['bonus_omset'] != '' ? str_replace(',', '', $data['bonus_omset']) : 0;
	$salary = $data['salary'] != '' ? str_replace(',', '', $data['salary']) : 0;
	$overtime = $data['overtime'] != '' ? str_replace(',', '', $data['overtime']) : 0;
	$day = $data['day'];
	$total = $kddh + $bonus_omset + $salary + $overtime;
	$created_at = date('Y-m-d H:i:s');
	$updated_at = date('Y-m-d H:i:s');

	$conn->begin_transaction();
	try {

		$karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id='$id_karyawan'");
		$karyawan = mysqli_fetch_assoc($karyawan_query);

		if(!$karyawan){
			return false;
		}

		$nama = $karyawan['nama_karyawan'];
		$query = mysqli_query($conn, "INSERT INTO laba_bersih_detail(incomes_expenses, jenis, nama, total, real_income, jenis_pembayaran, tanggal, cabang, created_at, updated_at)
					VALUES
				('$incomes_expenses', '$jenis', '$nama', '$total', 0, '$jenis_pembayaran', '$tanggal', '$sessionCabang', '$created_at', '$updated_at')");

		$laba_bersih_detail_id = $conn->insert_id;

		/** INSERT OR UPDATE LABA BERSIH */
		$laba_bersih_query = mysqli_query($conn, "SELECT lb_id FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
		$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

		$sum_incomes_expenses = sumIncomesExpenses('gaji_karyawan', $tanggal, $tanggal, $sessionCabang);
		$lb_pengeluaran_gaji = $sum_incomes_expenses['sum_total'] != null ? $sum_incomes_expenses['sum_total'] : 0;

		if(!$laba_bersih){
			mysqli_query($conn, "INSERT INTO laba_bersih(lb_pengeluaran_gaji, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
				VALUES
				('$lb_pengeluaran_gaji', 0, 0, 0, 0, 0, 0, 0, 0, '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
		} else {
			mysqli_query($conn, "UPDATE laba_bersih SET lb_pengeluaran_gaji='$lb_pengeluaran_gaji', updated_at='$updated_at' WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
		}

		mysqli_query($conn, "INSERT INTO gaji_karyawan(id_karyawan, periode, day, kddh, bonus_omset, salary, overtime, thp, laba_bersih_detail_id, cabang, created_at, updated_at)
					VALUES
					('$id_karyawan', '$periode', '$day', '$kddh', '$bonus_omset', '$salary', '$overtime', '$total', '$laba_bersih_detail_id', '$sessionCabang', '$created_at', '$updated_at')");
					
		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function updateGajiKaryawan($data){
	global $conn;
	global $sessionCabang;
	$id = base64_decode($data['id']);
	$jenis_pembayaran = base64_decode($data['jenis_pembayaran']);
	$id_karyawan = base64_decode($data['karyawan']);
	$periode = $data['periode'];
	// $tanggal = $data['periode'] . '-' . date('d');
	$tanggal = $data['tanggal'];
	$kddh = $data['kddh'] != '' ? str_replace(',', '', $data['kddh']) : 0;
	$bonus_omset = $data['bonus_omset'] != '' ? str_replace(',', '', $data['bonus_omset']) : 0;
	$salary = $data['salary'] != '' ? str_replace(',', '', $data['salary']) : 0;
	$overtime = $data['overtime'] != '' ? str_replace(',', '', $data['overtime']) : 0;
	$day = $data['day'];
	$total = $kddh + $bonus_omset + $salary + $overtime;
	$created_at = date('Y-m-d H:i:s');
	$updated_at = date('Y-m-d H:i:s');

	$conn->begin_transaction();
	try {

		$karyawan_query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id='$id_karyawan'");
		$karyawan = mysqli_fetch_assoc($karyawan_query);

		if(!$karyawan){
			return false;
		}

		$lb_dt_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE id='$id' AND cabang='$sessionCabang'");
		$lb_dt = mysqli_fetch_assoc($lb_dt_query);

		// $tanggal = $lb_dt['tanggal'];
		$nama = $karyawan['nama_karyawan'];
		mysqli_query($conn, "UPDATE laba_bersih_detail SET nama='$nama', total='$total', jenis_pembayaran='$jenis_pembayaran', tanggal='$tanggal', updated_at='$updated_at' WHERE id='$id' AND cabang='$sessionCabang'");

		$laba_bersih_detail_id = $lb_dt['id'];
		
		/** ROLLBACK LABA BERSIH */
		$tanggal_pengeluaran = $lb_dt['tanggal'];
		$total_rollback = $lb_dt['total'];
		mysqli_query($conn, "UPDATE laba_bersih SET lb_pengeluaran_gaji=lb_pengeluaran_gaji - $total_rollback, updated_at='$updated_at' WHERE tanggal='$tanggal_pengeluaran' AND lb_cabang='$sessionCabang'");

		/** INSERT OR UPDATE LABA BERSIH */
		$laba_bersih_query = mysqli_query($conn, "SELECT lb_id FROM laba_bersih WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
		$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

		$sum_incomes_expenses = sumIncomesExpenses('gaji_karyawan', $tanggal, $tanggal, $sessionCabang);
		$lb_pengeluaran_gaji = $sum_incomes_expenses['sum_total'] != null ? $sum_incomes_expenses['sum_total'] : 0;

		if(!$laba_bersih){
			mysqli_query($conn, "INSERT INTO laba_bersih(lb_pengeluaran_gaji, lb_pengeluaran_gaji, lb_pengeluaran_listrik, lb_pengeluaran_tlpn_internet, lb_pengeluaran_perlengkapan_toko, lb_pengeluaran_biaya_penyusutan, lb_pengeluaran_bensin, lb_pengeluaran_tak_terduga, lb_pengeluaran_lain, lb_cabang, tanggal, created_at, updated_at)
				VALUES
				('$lb_pengeluaran_gaji', 0, 0, 0, 0, 0, 0, 0, 0, '$sessionCabang', '$tanggal', '$created_at', '$updated_at')");
		} else {
			mysqli_query($conn, "UPDATE laba_bersih SET lb_pengeluaran_gaji='$lb_pengeluaran_gaji', updated_at='$updated_at' WHERE tanggal='$tanggal' AND lb_cabang='$sessionCabang'");
		}

		mysqli_query($conn, "UPDATE gaji_karyawan SET id_karyawan='$id_karyawan', periode='$periode', day='$day', kddh='$kddh', bonus_omset='$bonus_omset', salary='$salary', overtime='$overtime', thp='$total', updated_at='$updated_at' WHERE laba_bersih_detail_id='$laba_bersih_detail_id' AND cabang='$sessionCabang'");
					
		$conn->commit();
		return true;
	} catch (mysqli_sql_exception $exception) {
		$conn->rollback();
		throw $exception;
	}

	return false;
}

function deleteIncomesExpenses($data){
	global $conn;
	global $sessionCabang;
	$jenis = base64_decode($data['type']);
	$id = base64_decode($data['delete']);
	$incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE id='$id' AND cabang='$sessionCabang'");
	$updated_at = date('Y-m-d H:i:s');

	if(mysqli_num_rows($incomes_expenses_query) == 0){
		return false;
	}

	$incomes_expenses = mysqli_fetch_assoc($incomes_expenses_query);
	$ie_tanggal = $incomes_expenses['tanggal'];
	$laba_bersih_query = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE tanggal='$ie_tanggal' AND lb_cabang='$sessionCabang'");
	$laba_bersih = mysqli_fetch_assoc($laba_bersih_query);

	if($incomes_expenses['incomes_expenses'] == 'pendapatan' && $incomes_expenses['jenis'] == 'revenue'){
		$conn->begin_transaction();
		try {
			$total_incomes_expenses = $incomes_expenses['real_income'];
			mysqli_query($conn, "UPDATE laba_bersih SET lb_pendapatan_lain=lb_pendapatan_lain - $total_incomes_expenses WHERE tanggal='$ie_tanggal' AND lb_cabang='$sessionCabang'");
			mysqli_query($conn, "DELETE FROM laba_bersih_detail WHERE id = '$id' AND cabang='$sessionCabang'");
			$conn->commit();
			return true;
		} catch (mysqli_sql_exception $exception) {
			$conn->rollback();
			throw $exception;
		}
	} else if($incomes_expenses['incomes_expenses'] == 'pengeluaran' && $incomes_expenses['jenis'] == 'gaji_karyawan'){
		$conn->begin_transaction();
		try {
			$total_incomes_expenses = $incomes_expenses['total'];
			mysqli_query($conn, "UPDATE laba_bersih SET lb_pengeluaran_gaji=lb_pengeluaran_gaji - $total_incomes_expenses, updated_at='$updated_at' WHERE tanggal='$ie_tanggal' AND lb_cabang='$sessionCabang'");
			mysqli_query($conn, "DELETE FROM laba_bersih_detail WHERE id = '$id' AND cabang='$sessionCabang'");
			mysqli_query($conn, "DELETE FROM gaji_karyawan WHERE laba_bersih_detail_id = '$id' AND cabang='$sessionCabang'");
			$conn->commit();
			return true;
		} catch (mysqli_sql_exception $exception) {
			$conn->rollback();
			throw $exception;
		}
	} else {
		$conn->begin_transaction();
		try {

			$tanggal_incomes_expenses = $incomes_expenses['tanggal'];
			$total_rollback = $incomes_expenses['total'];
			$set_rollback_query = '';
			switch($jenis){
				case 'listrik':
					$set_rollback_query = "lb_pengeluaran_listrik=lb_pengeluaran_listrik - $total_rollback";
					break;

				case 'telepon_internet':
					$set_rollback_query = "lb_pengeluaran_tlpn_internet=lb_pengeluaran_tlpn_internet - $total_rollback";
					break;

				case 'perlengkapan_toko':
					$set_rollback_query = "lb_pengeluaran_perlengkapan_toko=lb_pengeluaran_perlengkapan_toko - $total_rollback";
					break;

				case 'biaya_penyusutan':
					$set_rollback_query = "lb_pengeluaran_biaya_penyusutan=lb_pengeluaran_biaya_penyusutan - $total_rollback";
					break;

				case 'bensin':
					$set_rollback_query = "lb_pengeluaran_bensin=lb_pengeluaran_bensin - $total_rollback";
					break;

				case 'tak_terduga':
					$set_rollback_query = "lb_pengeluaran_tak_terduga=lb_pengeluaran_tak_terduga - $total_rollback";
					break;

				case 'lain_lain':
					$set_rollback_query = "lb_pengeluaran_lain=lb_pengeluaran_lain - $total_rollback";
					break;
			}

			mysqli_query($conn, "UPDATE laba_bersih SET $set_rollback_query, updated_at='$updated_at' WHERE tanggal='$tanggal_incomes_expenses' AND lb_cabang='$sessionCabang'");
			mysqli_query($conn, "DELETE FROM laba_bersih_detail WHERE id = '$id' AND cabang='$sessionCabang'");
			mysqli_query($conn, "DELETE FROM penambahan_aset WHERE id_laba_bersih_detail = '$id' AND cabang='$sessionCabang'");
			$conn->commit();
			return true;
		} catch (mysqli_sql_exception $exception) {
			$conn->rollback();
			throw $exception;
		}
	}

	return false;
}

function sumIncomesExpenses($jenis, $start_date, $end_date, $cabang){
	global $conn;
	if(!is_array($jenis)){
		$jenis = array($jenis);
	}

	$jenis = "'" . implode ( "', '", $jenis ) . "'";
	$incomes_expenses = mysqli_query($conn, "SELECT SUM(total) as sum_total, SUM(real_income) as sum_real_income
												FROM laba_bersih_detail
												WHERE jenis IN ($jenis)
												AND cabang='$cabang' AND tanggal>='$start_date' AND tanggal<='$end_date'");
									
	return mysqli_fetch_assoc($incomes_expenses);
}

?>