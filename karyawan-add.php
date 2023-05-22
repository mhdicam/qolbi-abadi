<?php 
    include '_header.php';
    include '_nav.php';
    include '_sidebar.php';

    if ( $levelLogin === "kasir" && $levelLogin === "kurir" ) {
        echo "
          <script>
            document.location.href = 'bo';
          </script>
        ";
    }

    if( isset($_POST["submit"]) ){
        if( tambahKaryawan($_POST) > 0 ) {
            echo "
                <script>
                    document.location.href = 'karyawan';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Data gagal ditambahkan');
                </script>
            ";
        }
    }

    $barang = mysqli_query($conn,"select * from barang where barang_cabang = ".$sessionCabang." ");
    $jmlBarang = mysqli_num_rows($barang); 

    if ( $jmlBarang < 1 ) {
        $barangCount = 1;
    } else {
        $barangCount = query("SELECT * FROM barang ORDER BY barang_id DESC LIMIT 1")[0];
        $barangCount = $barangCount['barang_kode_count'];
        $barangCount += 1;
        
    }
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data Karyawan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="bo">Home</a></li>
                        <li class="breadcrumb-item active">Data Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form role="form" action="" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Data Karyawan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="nama_karyawan">Nama Karyawan</label>
                                            <input type="text" name="nama_karyawan" class="form-control" id="nama_karyawan" placeholder="Input Nama Karyawan" required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="jabatan">Jabatan</label>
                                            <select name="jabatan" id="jabatan" class="form-control" required>
                                            <option value="">-Pilih Jabatan-</option>
                                            <option value="<?= base64_encode('Supervisor') ?>">Supervisor</option>
                                            <option value="<?= base64_encode('Cook') ?>">Cook</option>
                                            <option value="<?= base64_encode('Cashier') ?>">Cashier</option>
                                            <option value="<?= base64_encode('Server') ?>">Server</option>
                                            <option value="<?= base64_encode('Kitchen') ?>">Kitchen</option>
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control" required>
                                            <option value="">-Pilih Status-</option>
                                            <option value="<?= base64_encode('Reguler') ?>">Reguler</option>
                                            <option value="<?= base64_encode('Part Time') ?>">Part Time</option>
                                            <option value="<?= base64_encode('Non Active') ?>">Non Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="tanggal-masuk">Tanggal Masuk</label>
                                            <input type="date" class="form-control" id="tanggal-masuk" name="tanggal_masuk" required>
                                        </div>
                                        <div class="form-group ">
                                            <label for="tanggal-keluar">Tanggal Keluar</label>
                                            <input type="date" class="form-control" id="tanggal-keluar" name="tanggal_keluar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer text-right">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php include '_footer.php'; ?>

