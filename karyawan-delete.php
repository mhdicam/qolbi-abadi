<?php 
include 'aksi/functions.php';

$id = base64_decode($_GET["id"]);
if( hapusKaryawan($id) > 0) {
	echo "
		<script>
			document.location.href = 'karyawan';
		</script>
	";
} else {
	echo "
		<script>
			alert('Data gagal dihapus');
			document.location.href = 'karyawan';
		</script>
	";
}

?>