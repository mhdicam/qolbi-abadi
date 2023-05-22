<?php 
    include '_header.php';
    include '_nav.php';
    include '_sidebar.php'; 
    error_reporting(0);

    if ( $levelLogin === "kasir" && $levelLogin === "kurir" ) {
        echo "
          <script>
              document.location.href = 'bo';
          </script>
        ";
    }

    $id = abs((int)base64_decode($_GET['id']));
    $karyawan = query("SELECT * FROM karyawan WHERE id = $id ")[0];

    if( isset($_POST["submit"]) ){
        $_POST['id'] = $id;
        if( editKaryawan($_POST) > 0 ) {
            echo "
                <script>
                    document.location.href = 'karyawan';
                </script>
            ";
        } else {
          echo "
              <script>
                  alert('Data gagal diedit');
              </script>
          ";
        }
    }
?>

  <div class="content-wrapper">
      <section class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1>Edit Data Karyawan</h1>
                  </div>
                  <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="bo">Home</a></li>
                          <li class="breadcrumb-item active">Edit Karyawan</li>
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
                                            <input type="text" name="nama_karyawan" class="form-control" id="nama_karyawan" placeholder="Input Nama Karyawan" value="<?= $karyawan['nama_karyawan'] ?>" required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="jabatan">Jabatan</label>
                                            <select name="jabatan" id="jabatan" class="form-control" required>
                                            <option value="">-Pilih Jabatan-</option>
                                            <option value="<?= base64_encode('Supervisor') ?>" <?= $karyawan['jabatan'] == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                            <option value="<?= base64_encode('Cook') ?>" <?= $karyawan['jabatan'] == 'Cook' ? 'selected' : '' ?>>Cook</option>
                                            <option value="<?= base64_encode('Cashier') ?>" <?= $karyawan['jabatan'] == 'Cashier' ? 'selected' : '' ?>>Cashier</option>
                                            <option value="<?= base64_encode('Server') ?>" <?= $karyawan['jabatan'] == 'Server' ? 'selected' : '' ?>>Server</option>
                                            <option value="<?= base64_encode('Kitchen') ?>" <?= $karyawan['jabatan'] == 'Kitchen' ? 'selected' : '' ?>>Kitchen</option>
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control" required>
                                            <option value="">-Pilih Status-</option>
                                            <option value="<?= base64_encode('Reguler') ?>" <?= $karyawan['status'] == 'Reguler' ? 'selected' : '' ?>>Reguler</option>
                                            <option value="<?= base64_encode('Part Time') ?>" <?= $karyawan['status'] == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                                            <option value="<?= base64_encode('Non Active') ?>" <?= $karyawan['status'] == 'Non Active' ? 'selected' : '' ?>>Non Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="tanggal-masuk">Tanggal Masuk</label>
                                            <input type="date" class="form-control" id="tanggal-masuk" name="tanggal_masuk" value="<?= $karyawan['tanggal_masuk'] ?>" required>
                                        </div>
                                        <div class="form-group ">
                                            <label for="tanggal-keluar">Tanggal Keluar</label>
                                            <input type="date" class="form-control" id="tanggal-keluar" name="tanggal_keluar" value="<?= $karyawan['tanggal_keluar'] ?>">
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