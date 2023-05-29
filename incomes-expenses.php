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
    
    if($_POST){
        $incomes_expenses = $_POST['incomes_expenses'];
        $type = $_POST['type'];

        if($_POST['delete']){
            if(deleteIncomesExpenses($_POST)){
                echo "
                    <script>
                        document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Data gagal dihapus');
                    </script>
                ";
            }
        }

        if($_POST['incomes_expenses'] == 'cGVuZGFwYXRhbg=='){
            if(!$_POST['id']){
                if(tambahPendapatan($_POST)){
                    echo "
                        <script>
                            document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal ditambahkan');
                        </script>
                    ";
                }
            } else {
                if(updatePendapatan($_POST)){
                    echo "
                        <script>
                            document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal diperbarui');
                        </script>
                    ";
                }
            }
        } else if($_POST['incomes_expenses'] == 'cGVuZ2VsdWFyYW4=' && $_POST['type'] == 'Z2FqaV9rYXJ5YXdhbg==') {
            if(!$_POST['id']){
                if(tambahGajiKaryawan($_POST)){
                    echo "
                        <script>
                            document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal ditambahkan');
                        </script>
                    ";
                }
            } else {
                if(updateGajiKaryawan($_POST)){
                    echo "
                        <script>
                            document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal diperbarui');
                        </script>
                    ";
                }
            }
        }
        
        else {
            if(!$_POST['id']){
                if(tambahIncomesExpenses($_POST)){
                    echo "
                            <script>
                                document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                            </script>
                        ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal ditambahkan');
                        </script>
                    ";
                }
            } else {
                if(updateIncomesExpenses($_POST)){
                    echo "
                            <script>
                                document.location.href = '?incomes-expenses=$incomes_expenses&type=$type';
                            </script>
                        ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal diperbarui');
                        </script>
                    ";
                }
            }
        }
        
    }
    
    $start_date = $_GET['start_date'] ? date('d M Y', strtotime($_GET['start_date'])) : date('d M Y');
    $end_date = $_GET['end_date'] ? date('d M Y', strtotime($_GET['end_date'])) : date('d M Y');
    $max_date = date('d M Y');

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Detail Operasional Pendapatan & Pengeluaran</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="bo">Home</a></li>
                      <li class="breadcrumb-item active">Barang</li>
                  </ol>
              </div>
            </div>
        </div>
    </section>


    <?php  
    	// $data = query("SELECT * FROM barang ORDER BY barang_id DESC");
    ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <form class="form-filter">
                                    <div class="form-group">
                                        <select class="form-control select-incomes-expenses" name="incomes-expenses">
                                            <option value="<?= base64_encode('pendapatan') ?>" <?= $_GET['incomes-expenses'] == base64_encode('pendapatan') ? 'selected' : '' ?>>Pendapatan</option>
                                            <option value="<?= base64_encode('pengeluaran') ?>" <?= $_GET['incomes-expenses'] == base64_encode('pengeluaran') ? 'selected' : '' ?>>Pengeluaran</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control select-type-incomes-expenses" name="type">
                                            <?php if($_GET['incomes-expenses'] == 'cGVuZ2VsdWFyYW4='): ?>
                                                <option value="<?= base64_encode('bensin') ?>" <?= $_GET['type'] == base64_encode('bensin') ? 'selected' : '' ?>>Bensin</option>
                                                <option value="<?= base64_encode('biaya_penyusutan') ?>" <?= $_GET['type'] == base64_encode('biaya_penyusutan') ? 'selected' : '' ?>>Biaya Penyusutan</option>
                                                <option value="<?= base64_encode('gaji_karyawan') ?>" <?= $_GET['type'] == base64_encode('gaji_karyawan') ? 'selected' : '' ?>>Gaji Karyawan</option>
                                                <option value="<?= base64_encode('listrik') ?>" <?= $_GET['type'] == base64_encode('listrik') ? 'selected' : '' ?>>Listrik</option>
                                                <option value="<?= base64_encode('perlengkapan_toko') ?>" <?= $_GET['type'] == base64_encode('perlengkapan_toko') ? 'selected' : '' ?>>Perlengkapan Toko</option>
                                                <option value="<?= base64_encode('tak_terduga') ?>" <?= $_GET['type'] == base64_encode('tak_terduga') ? 'selected' : '' ?>>Tak Terduga</option>
                                                <option value="<?= base64_encode('telepon_internet') ?>" <?= $_GET['type'] == base64_encode('telepon_internet') ? 'selected' : '' ?>>Telpon & Internet</option>
                                                <option value="<?= base64_encode('lain_lain') ?>" <?= $_GET['type'] == base64_encode('lain_lain') ? 'selected' : '' ?>>Lain-lain</option>
                                            <?php else: ?>
                                                <option value="<?= base64_encode('revenue') ?>"<?= $_GET['type'] == base64_encode('revenue') ? 'selected' : '' ?>>Revenue</option>
                                                <option value="<?= base64_encode('pendapatan_lain') ?>"<?= $_GET['type'] == base64_encode('pendapatan_lain') ? 'selected' : '' ?>>Pendapatan Lain</option>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control date-filter" placeholder="Filter Tanggal">
                                        <input type="hidden" name="start_date" class="form-control hidden start-date" value="<?= date('Y-m-d', strtotime($start_date)) ?>">
                                        <input type="hidden" name="end_date" class="form-control hidden end-date" value="<?= date('Y-m-d', strtotime($end_date)) ?>">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8 text-right">
                                <div class="tambah-data">
                                    <button class="btn btn-primary btn-add">Tambah Data</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-auto mt-4">
                            <table class="table table-bordered table-striped incomes-expenses-table pendapatan-revenue-table <?= ($_GET['incomes-expenses'] == 'cGVuZGFwYXRhbg==') | (!$_GET['incomes-expenses'] && !$_GET['type']) ? '' : 'd-none' ?>">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Total</th>
                                        <th>Real Income</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Grand Total</th>
                                        <th class="text-right grand-total">Rp. 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <table class="table table-bordered table-striped incomes-expenses-table pengeluaran-table <?= $_GET['incomes-expenses'] == 'cGVuZ2VsdWFyYW4=' && $_GET['type'] != 'Z2FqaV9rYXJ5YXdhbg==' && $_GET['type'] != 'cGVybGVuZ2thcGFuX3Rva28=' ? '' : 'd-none' ?>">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Grand Total</th>
                                        <th class="text-right grand-total">Rp. 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <table class="table table-bordered table-striped incomes-expenses-table pengeluaran-perlengkapan-toko-table <?= $_GET['incomes-expenses'] == 'cGVuZ2VsdWFyYW4=' && $_GET['type'] == 'cGVybGVuZ2thcGFuX3Rva28=' ? '' : 'd-none' ?>">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Total</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Grand Total</th>
                                        <th class="text-right grand-total">Rp. 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <table class="table table-bordered table-striped incomes-expenses-table pengeluaran-gaji-table <?= $_GET['incomes-expenses'] == 'cGVuZ2VsdWFyYW4=' && $_GET['type'] == 'Z2FqaV9rYXJ5YXdhbg==' ? '' : 'd-none' ?>">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Day</th>
                                        <th>Salary</th>
                                        <th>KDDH</th>
                                        <th>Bonus Omset</th>
                                        <th>Overtime</th>
                                        <th>Total</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Grand Total</th>
                                        <th class="text-right grand-total">Rp. 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </div>
</div>
<div class="modal fade" id="modal-revenue" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" class="form-pendapatan-revenue">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-pendapatan-title">...</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" value="" class="input-type">
                    <input type="hidden" name="incomes_expenses" value="" class="input-incomes-expenses">
                    <input type="hidden" name="id" value="" class="input-id" id="id">
                    <div class="form-group">
                        <label for="jenis-pendapatan">Jenis Pendapatan</label>
                        <input type="text" id="jenis-pendapatan" class="form-control input-jenis-pendapatan" disabled>
                    </div>
                    <div class="form-group">
                        <label for="tanggal-transaksi">Tanggal Transaksi</label>
                        <input type="date" id="tanggal-transaksi" class="form-control" name="tanggal" value="<?= $date_now ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" class="form-control" name="nama" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" id="total" class="form-control price-format" name="total" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="real-income">Real Income</label>
                        <input type="text" id="real-income" class="form-control price-format" name="real_income" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="payment-type">Payment Type</label>
                        <select name="jenis_pembayaran" id="payment-type" class="form-control" required>
                            <option value="">-Select Payment Type-</option>
                            <option value="<?= base64_encode('Cash') ?>">Cash</option>
                            <option value="<?= base64_encode('Debit') ?>">Debit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-pengeluaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-pengeluaran-title">...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="form-pengeluaran">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="type" value="" class="input-type">
                        <input type="hidden" name="incomes_expenses" value="" class="input-incomes-expenses">
                        <input type="hidden" name="id" value="" class="input-id" id="id">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="jenis-pengeluaran">Jenis Pengeluaran</label>
                            <input type="text" id="jenis-pengeluaran" class="form-control input-jenis-pengeluaran" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal-transaksi">Tanggal Transaksi</label>
                            <input type="date" id="tanggal-transaksi" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" class="form-control" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qty">Qty</label>
                            <input type="number" id="qty" name="qty" class="form-control qty-pengeluaran" min="1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga">Harga</label>
                            <input type="text" id="harga" name="harga" class="form-control price-format harga-pengeluaran" min="0" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total">Total</label>
                            <input type="text" id="total" name="total" class="form-control price-format total-pengeluaran bg-white" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="payment-type">Payment Type</label>
                            <select name="jenis_pembayaran" id="payment-type" class="form-control" required>
                                <option value="">-Select Payment Type-</option>
                                <option value="<?= base64_encode('Cash') ?>">Cash</option>
                                <option value="<?= base64_encode('Debit') ?>">Debit</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="penambahan-aset d-none">
                        <label>Penambahan Aset</label>
                        <table class="table table-bordered table-penambahan-aset">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="30%">Harga</th>
                                    <th width="30%">Qty</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td><input type="text" class="form-control nama-barang" minlength="3"></td>
                                    <td><input type="text" class="form-control harga-barang price-format"></td>
                                    <td><input type="number" class="form-control qty-barang" min="1"></td>
                                    <td class="text-center row-action"><button class="btn btn-success btn-add-asset" type="button" disabled><i class="fa fa-plus"></i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-show-perlengkapan-toko" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-perlengkapan-toko-title">...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="form-show-perlengkapan-toko">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="type" value="" class="input-type">
                        <input type="hidden" name="incomes_expenses" value="" class="input-incomes-expenses">
                        <input type="hidden" name="id" value="" class="input-id" id="id">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="jenis-pengeluaran">Jenis Pengeluaran</label>
                            <input type="text" id="jenis-pengeluaran" class="form-control input-jenis-pengeluaran" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal-transaksi">Tanggal Transaksi</label>
                            <input type="date" id="tanggal-transaksi" class="form-control bg-white" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nama">Nama</label>
                            <input type="text" id="nama" class="form-control bg-white" autocomplete="off" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qty">Qty</label>
                            <input type="number" id="qty" class="form-control qty-pengeluaran" min="1" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga">Harga</label>
                            <input type="text" id="harga" class="form-control price-format harga-pengeluaran" min="0" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total">Total</label>
                            <input type="text" id="total" class="form-control price-format total-pengeluaran bg-white" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="payment-type">Payment Type</label>
                            <select id="payment-type" class="form-control bg-white" disabled>
                                <option value="">-Select Payment Type-</option>
                                <option value="<?= base64_encode('Cash') ?>">Cash</option>
                                <option value="<?= base64_encode('Debit') ?>">Debit</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="penambahan-aset d-none">
                        <label>Penambahan Aset</label>
                        <table class="table table-bordered table-detail-penambahan-aset">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="30%">Harga</th>
                                    <th width="30%">Qty</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-pengeluaran-gaji" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-pengeluaran-gaji-title">...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="form-pengeluaran-gaji">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="type" value="" class="input-type">
                        <input type="hidden" name="incomes_expenses" value="" class="input-incomes-expenses">
                        <input type="hidden" name="id" value="" class="input-id" id="id">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6"></div>
                        <div class="form-group col-md-6">
                            <label for="">Periode</label>
                            <input type="text" name="periode" class="form-control periode" id="periode" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Karyawan</label>
                            <select name="karyawan" id="karyawan" class="form-control" required>
                                <option value="">Nama Karyawan</option>
                                <?php
                                    $karyawan = mysqli_query($conn, "SELECT * FROM karyawan");
                                    while($rk = mysqli_fetch_assoc($karyawan)):
                                ?>
                                    <option value="<?= base64_encode($rk['id']) ?>"><?= $rk['nama_karyawan'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">KDDH</label>
                            <input type="text" name="kddh" class="form-control kddh price-format" id="kddh" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Day</label>
                            <input type="number" min="1" name="day" class="form-control day" id="day" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Bonus Omset</label>
                            <input type="text" name="bonus_omset" class="form-control bonus-omset price-format" id="bonus-omset" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Salary</label>
                            <input type="text" min="1" name="salary" class="form-control salary price-format" id="salary" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Overtime</label>
                            <input type="text" name="overtime" class="form-control overtime price-format" id="overtime" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="payment-type">Payment Type</label>
                            <select name="jenis_pembayaran" id="payment-type" class="form-control" required>
                                <option value="">-Select Payment Type-</option>
                                <option value="<?= base64_encode('Cash') ?>">Cash</option>
                                <option value="<?= base64_encode('Debit') ?>">Debit</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include '_footer.php'; ?>
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
    $(function(){
        initLoad();
        initCleaveJS();

        $(".periode").datepicker( {
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        });

        $('.date-filter').daterangepicker({
            locale: {
                format: 'DD MMMM YYYY'
            },
            startDate: '<?= $start_date ?>',
            endDate: '<?= $end_date ?>'
        },
        function(start, end, label) {
            $('.start-date').val(start.format('YYYY-MM-DD'));
            $('.end-date').val(end.format('YYYY-MM-DD'));
        });

        $('.select-incomes-expenses').on('change', function(){
            const t = $(this);
            const v = t.val();

            let ieType = '<option value="<?= base64_encode('revenue') ?>">Revenue</option><option value="<?= base64_encode('pendapatan_lain') ?>">Pendapatan Lain</option>';
            let defIeType = '<?= base64_encode('revenue') ?>';

            if(v === 'cGVuZ2VsdWFyYW4='){
                defIeType = "<?= base64_encode('bensin') ?>";
                ieType = `<option value="<?= base64_encode('bensin') ?>">Bensin</option>
                          <option value="<?= base64_encode('biaya_penyusutan') ?>">Biaya Penyusutan</option>
                          <option value="<?= base64_encode('gaji_karyawan') ?>">Gaji Karyawan</option>
                          <option value="<?= base64_encode('listrik') ?>">Listrik</option>
                          <option value="<?= base64_encode('perlengkapan_toko') ?>">Perlengkapan Toko</option>
                          <option value="<?= base64_encode('tak_terduga') ?>">Tak Terduga</option>
                          <option value="<?= base64_encode('telepon_internet') ?>">Telpon & Internet</option>
                          <option value="<?= base64_encode('lain_lain') ?>">Lain-lain</option>`;
            }
            
            $('.select-type-incomes-expenses').html(ieType);
            $('.select-type-incomes-expenses').val(defIeType).change();
            getIncomesExpensesDatas();
        });

        $('.select-type-incomes-expenses').on('change', function(){
            const t = $(this);
            const v = t.val();
            const selIe = $('.select-incomes-expenses');
            const ieTable = $('.incomes-expenses-table');

            ieTable.addClass('d-none');
            if(selIe.val() === 'cGVuZGFwYXRhbg=='){
                $('.pendapatan-revenue-table').removeClass('d-none').find('tbody').html('<tr><td colspan="6" class="text-center">Mengambil data...</td></tr>');
                $('.pendapatan-revenue-table').find('.grand-total').text('Rp. 0');
            }

            else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                $('.pengeluaran-table').removeClass('d-none').find('tbody').html('<tr><td colspan="6" class="text-center">Mengambil data...</td></tr>');
                $('.pengeluaran-table').find('.grand-total').text('Rp. 0');
                if(v === '<?= base64_encode('gaji_karyawan') ?>'){
                    $('.pengeluaran-table').addClass('d-none');
                    $('.pengeluaran-gaji-table').removeClass('d-none').find('tbody').html('<tr><td colspan="9" class="text-center">Mengambil data...</td></tr>');
                    $('.pengeluaran-gaji-table').find('.grand-total').text('Rp. 0');
                }

                else if(v === '<?= base64_encode('perlengkapan_toko') ?>'){
                    $('.pengeluaran-table').addClass('d-none');
                    $('.pengeluaran-perlengkapan-toko-table').removeClass('d-none').find('tbody').html('<tr><td colspan="9" class="text-center">Mengambil data...</td></tr>');
                    $('.pengeluaran-perlengkapan-toko-table').find('.grand-total').text('Rp. 0');
                }
            }

            getIncomesExpensesDatas();
        });

        $('.table-penambahan-aset tfoot tr td .nama-barang').on('change keyup', function(){
            cekInputPenambahanAset()
        });

        $('.table-penambahan-aset tfoot tr td .qty-barang').on('change keyup', function(){
            cekInputPenambahanAset()
        });

        $('.table-penambahan-aset tfoot tr td .harga-barang').on('change keyup', function(){
            cekInputPenambahanAset()
        });

        $('.table-penambahan-aset tbody').on('change keyup', 'tr td .qty-barang', function(){
            $('.total-pengeluaran').val(numeral(hitungTotalPenambahanAset()).format('0,0'));
        });

        $('.table-penambahan-aset tbody').on('change keyup', 'tr td .harga-barang', function(){
            $('.total-pengeluaran').val(numeral(hitungTotalPenambahanAset()).format('0,0'));
        });

        function cekInputPenambahanAset(){
            $('.table-penambahan-aset tfoot tr td .btn-add-asset').prop('disabled', true);
            if($('.table-penambahan-aset tfoot tr td .nama-barang').val() !== '' && $('.table-penambahan-aset tfoot tr td .qty-barang').val() !== ''){
                if($('.table-penambahan-aset tfoot tr td .qty-barang').val() > 0 && $('.table-penambahan-aset tfoot tr td .nama-barang').val().length >= 3 && $('.table-penambahan-aset tfoot tr td .harga-barang').val().length >= 1)
                    $('.table-penambahan-aset tfoot tr td .btn-add-asset').prop('disabled', false);
            }
        }

        $('.btn-add-asset').on('click', function(){
            const namaBarang = $(this).closest('tr').find('nama-barang').val();
            const qtyBarang = $(this).closest('tr').find('qty-barang').val();

            let cloneEl = $('.table-penambahan-aset tfoot tr').clone();
            cloneEl.appendTo('.table-penambahan-aset tbody');
            cloneEl.find('.row-action').html('<button class="btn btn-danger btn-delete-asset"><i class="fa fa-trash"></i></button>');
            cloneEl.find('.form-control').prop('required', true);
            cloneEl.find('.form-control.nama-barang').prop('name', 'nama_barang[]');
            cloneEl.find('.form-control.qty-barang').prop('name', 'qty_barang[]');
            cloneEl.find('.form-control.harga-barang').prop('name', 'harga_barang[]');
            $('.table-penambahan-aset tfoot tr').find('.form-control').val('');
            $('.table-penambahan-aset tfoot tr').find('.btn-add-asset').prop('disabled', true);
            $('.total-pengeluaran').val(hitungTotalPenambahanAset());
            initCleaveJS();
        });

        $('.table-penambahan-aset tbody').on('click', 'tr td .btn-delete-asset', function(){
            $(this).closest('tr').remove();
        })

        $('.btn-add').on('click', function(){
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            const inputType = $('.input-type');
            const inputIe = $('.input-incomes-expenses');

            inputType.val('');
            if(selIe.val() === 'cGVuZGFwYXRhbg=='){
                $('#modal-revenue').modal('show');
                $('#modal-pendapatan-title').text('Tambah Data Incomes');
                $('.form-pendapatan-revenue').find('#tanggal-transaksi').val('<?= date('Y-m-d') ?>');
                $('.input-jenis-pendapatan').val(selTypeIe.find('option:selected').text());
            }

            else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                $('.penambahan-aset').addClass('d-none');
                $('.qty-pengeluaran').prop('disabled', false).prop('required', true).removeAttr('name', 'qty');
                $('.harga-pengeluaran').prop('disabled', false).prop('required', true).removeAttr('name', 'harga');
                if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                    $('#modal-pengeluaran-gaji').modal('show');
                    $('#modal-pengeluaran-gaji-title').text('Tambah Data Gaji Karyawan');
                } else {
                    $('#modal-pengeluaran').modal('show');
                    $('#modal-pengeluaran-title').text('Tambah Data Expenses');
                    $('.form-pengeluaran').find('#tanggal-transaksi').val('<?= date('Y-m-d') ?>');
                    $('.input-jenis-pengeluaran').val(selTypeIe.find('option:selected').text());
                    
                    if(selTypeIe.val() === 'cGVybGVuZ2thcGFuX3Rva28=' ){
                        $('.penambahan-aset').removeClass('d-none');
                        $('.qty-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
                        $('.harga-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
                    }
                }
            }

            inputType.val(selTypeIe.val());
            inputIe.val(selIe.val());
        });

        $('.table tbody').on('click', 'tr td .btn-edit', function(){
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            const inputType = $('.input-type');
            const inputIe = $('.input-incomes-expenses');
            const id = $(this).data('id');

            inputType.val('');
            if(selIe.val() === 'cGVuZGFwYXRhbg=='){
                $('#modal-revenue').modal('show');
                $('#modal-pendapatan-title').text('Edit Data Incomes');
                $('.input-jenis-pendapatan').val(selTypeIe.find('option:selected').text());
            }

            else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                $('.penambahan-aset').addClass('d-none');
                $('.qty-pengeluaran').prop('disabled', false).prop('required', true).removeAttr('name', 'qty');
                $('.harga-pengeluaran').prop('disabled', false).prop('required', true).removeAttr('name', 'harga');
                if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                    $('#modal-pengeluaran-gaji').modal('show');
                    $('#modal-pengeluaran-gaji-title').text('Edit Data Gaji Karyawan');
                } else {
                    $('.input-jenis-pengeluaran').val(selTypeIe.find('option:selected').text());
                    $('#modal-pengeluaran-title').text('Edit Data Expensess');
                    $('#modal-pengeluaran').modal('show');

                    if(selTypeIe.val() === 'cGVybGVuZ2thcGFuX3Rva28=' ){
                        $('.penambahan-aset').removeClass('d-none');
                        $('.qty-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
                        $('.harga-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
                    }
                }
            }

            inputType.val(selTypeIe.val());
            inputIe.val(selIe.val());

            getIncomesExpensesData(id)
        });

        $('.table tbody').on('click', 'tr td .btn-show-perlengkapan-toko', function(){
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            const inputType = $('.input-type');
            const inputIe = $('.input-incomes-expenses');
            const id = $(this).data('id');

            inputType.val('');
            if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                $('.input-jenis-pengeluaran').val(selTypeIe.find('option:selected').text());
                $('#modal-show-perlengkapan-toko').modal('show');
                $('#modal-perlengkapan-toko-title').text('Detail Perlengkapan Toko');
                $('.penambahan-aset').addClass('d-none');
                $('.penambahan-aset').removeClass('d-none');
                $('.qty-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
                $('.harga-pengeluaran').prop('disabled', true).prop('required', false).removeAttr('name');
            }

            inputType.val(selTypeIe.val());
            inputIe.val(selIe.val());

            getIncomesExpensesData(id, true);
        });

        $('.table tbody').on('click', 'tr td .btn-delete', function(){
            const id = $(this).data('id');
            if(confirm('Hapus Incomes/Expenses ?')){
                $(this).closest('td').find('.form-delete').submit();
            }
        });

        $('.date-filter').on('change', function(){
            getIncomesExpensesDatas();
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).find("input,textarea,select").val('').end();
            $('.table-penambahan-aset tbody').html('');
        });

        $('.qty-pengeluaran').on('change keyup', function(){
            $('.total-pengeluaran').val(numeral(hitungTotalPengeluaran()).format('0,0'));
        });

        $('.harga-pengeluaran').on('change keyup', function(){
            $('.total-pengeluaran').val(numeral(hitungTotalPengeluaran()).format('0,0'));
        });

        function initLoad(){
            getIncomesExpensesDatas();
        }

        function getIncomesExpensesDatas()
        {
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            $.ajax({
                type: "POST",
                url: 'incomes-expenses-data',
                data: $('.form-filter').serialize(),
                beforeSend: function(data){
                    if(selIe.val() === 'cGVuZGFwYXRhbg=='){
                        $('.pendapatan-revenue-table tbody').html('<tr><td class="text-center" colspan="6">Mengambil data...</td>')
                    } else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                        if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                            $('.pengeluaran-gaji-table tbody').html('<tr><td class="text-center" colspan="9">Mengambil data...</td>')
                        } else {
                            $('.pengeluaran-table tbody').html('<tr><td class="text-center" colspan="6">Mengambil data...</td>')
                        }
                    }
                },
                success: function(response){
                    if(response.success){
                        if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val()){
                            let tbody = '<tr><td class="text-center" colspan="6">Tidak ada data</td>';
                            if(response.data.length > 0){
                                tbody = '';
                                let no = 1;
                                $.each(response.data, function(i, item){
                                    tbody += `<tr>
                                                <td>${no++}</td>
                                                <td>${item.tanggal}</td>
                                                <td>${item.nama}</td>
                                                <td class="text-right">${item.total_idr_format}</td>
                                                <td class="text-right">${item.real_income_idr_format}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary btn-edit" data-id="${item.id}"><i class="fa fa-pencil"></i></button>
                                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                                    <form action="" method="POST" class="form-delete"><input type="hidden" name="delete" value="${item.id}" /><input type="hidden" name="type" value="${selTypeIe.val()}" /><input type="hidden" name="incomes_expenses" value="${selIe.val()}" /></form>
                                                </td>
                                              </tr>`;
                                });
                            }

                            $('.pendapatan-revenue-table tbody').html(tbody);
                            $('.pendapatan-revenue-table').find('.grand-total').text(response.grand_total_idr_format);
                        } else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                            if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                                let tbody = '<tr><td class="text-center" colspan="9">Tidak ada data</td>';
                                if(response.data.length > 0){
                                    tbody = '';
                                    let no = 1;
                                    $.each(response.data, function(i, item){
                                        tbody += `<tr>
                                                    <td>${no++}</td>
                                                    <td>${item.nama}</td>
                                                    <td>${item.day}</td>
                                                    <td class="text-right">${item.salary_idr_format}</td>
                                                    <td class="text-right">${item.kddh_idr_format}</td>
                                                    <td class="text-right">${item.bonus_omset_idr_format}</td>
                                                    <td class="text-right">${item.overtime_idr_format}</td>
                                                    <td class="text-right">${item.total_idr_format}</td>
                                                    <td class="text-center">
                                                        <a href="slip-gaji-karyawan?id=${item.gk}" class="btn btn-sm btn-success" target="_blank" title="Slip Gaji"><i class="fa fa-file-text"></i> Slip Gaji</a>
                                                        <button class="btn btn-sm btn-primary btn-edit" data-id="${item.id}"><i class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                                        <form action="" method="POST" class="form-delete"><input type="hidden" name="delete" value="${item.id}" /><input type="hidden" name="type" value="${selTypeIe.val()}" /><input type="hidden" name="incomes_expenses" value="${selIe.val()}" /></form>
                                                    </td>
                                                </tr>`;
                                    });
                                }

                                $('.pengeluaran-gaji-table tbody').html(tbody)
                                $('.pengeluaran-gaji-table').find('.grand-total').text(response.grand_total_idr_format);
                            } 
                            else if(selTypeIe.val() === '<?= base64_encode('perlengkapan_toko') ?>') {
                                let tbody = '<tr><td class="text-center" colspan="4">Tidak ada data</td>';
                                if(response.data.length > 0){
                                    tbody = '';
                                    let no = 1;
                                    $.each(response.data, function(i, item){
                                        tbody += `<tr>
                                                    <td>${no++}</td>
                                                    <td>${item.nama}</td>
                                                    <td class="text-right">${item.total_idr_format}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-success btn-show-perlengkapan-toko" data-id="${item.id}"><i class="fa fa-eye"></i></button>
                                                        <button class="btn btn-sm btn-primary btn-edit" data-id="${item.id}"><i class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                                        <form action="" method="POST" class="form-delete"><input type="hidden" name="delete" value="${item.id}" /><input type="hidden" name="type" value="${selTypeIe.val()}" /><input type="hidden" name="incomes_expenses" value="${selIe.val()}" /></form>
                                                    </td>
                                                </tr>`;
                                    });
                                }

                                $('.pengeluaran-perlengkapan-toko-table tbody').html(tbody)
                                $('.pengeluaran-perlengkapan-toko-table').find('.grand-total').text(response.grand_total_idr_format);
                            }
                            
                            else {
                                let tbody = '<tr><td class="text-center" colspan="6">Tidak ada data</td>';
                                if(response.data.length > 0){
                                    tbody = '';
                                    let no = 1;
                                    $.each(response.data, function(i, item){
                                        tbody += `<tr>
                                                    <td>${no++}</td>
                                                    <td>${item.nama}</td>
                                                    <td>${item.harga_idr_format}</td>
                                                    <td class="text-right">${item.qty}</td>
                                                    <td class="text-right">${item.total_idr_format}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-primary btn-edit" data-id="${item.id}"><i class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                                        <form action="" method="POST" class="form-delete"><input type="hidden" name="delete" value="${item.id}" /><input type="hidden" name="type" value="${selTypeIe.val()}" /><input type="hidden" name="incomes_expenses" value="${selIe.val()}" /></form>
                                                    </td>
                                                </tr>`;
                                    });
                                }

                                $('.pengeluaran-table tbody').html(tbody)
                                $('.pengeluaran-table').find('.grand-total').text(response.grand_total_idr_format);
                            }
                        }
                    }
                } 
            })
        }

        function getIncomesExpensesData(id, isShow = false)
        {
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            $.ajax({
                type: "POST",
                url: 'incomes-expenses-data',
                data: {'incomes-expenses': selIe.val() , 'type': selTypeIe.val(), 'id' : id},
                success: function(response){
                    if(response.success){
                        if(selIe.val() === 'cGVuZGFwYXRhbg=='){
                            $('.form-pendapatan-revenue').find('#id').val(response.data.id);
                            $('.form-pendapatan-revenue').find('#nama').val(response.data.nama);
                            $('.form-pendapatan-revenue').find('#tanggal-transaksi').val(response.data.tanggal);
                            $('.form-pendapatan-revenue').find('#total').val(numeral(response.data.total).format('0,0'));
                            $('.form-pendapatan-revenue').find('#real-income').val(numeral(response.data.real_income).format('0,0'));
                            $('.form-pendapatan-revenue').find('#payment-type').val(response.data.jenis_pembayaran).change();
                        } else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                            if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                                $('.form-pengeluaran-gaji').find('#id').val(response.data.id);
                                $('.form-pengeluaran-gaji').find('#periode').val(response.data.periode);
                                $('.form-pengeluaran-gaji').find('#karyawan').val(response.data.karyawan).change();
                                $('.form-pengeluaran-gaji').find('#kddh').val(numeral(response.data.kddh).format('0,0'));
                                $('.form-pengeluaran-gaji').find('#day').val(response.data.day);
                                $('.form-pengeluaran-gaji').find('#bonus-omset').val(numeral(response.data.bonus_omset).format('0,0'));
                                $('.form-pengeluaran-gaji').find('#salary').val(numeral(response.data.salary).format('0,0'));
                                $('.form-pengeluaran-gaji').find('#overtime').val(numeral(response.data.overtime).format('0,0'));
                                $('.form-pengeluaran-gaji').find('#payment-type').val(response.data.jenis_pembayaran).change();
                            } else {
                                if(!isShow){
                                    $('.form-pengeluaran').find('#id').val(response.data.id);
                                    $('.form-pengeluaran').find('#nama').val(response.data.nama);
                                    $('.form-pengeluaran').find('#tanggal-transaksi').val(response.data.tanggal);
                                    $('.form-pengeluaran').find('#total').val(numeral(response.data.total).format('0,0'));
                                    $('.form-pengeluaran').find('#harga').val(numeral(response.data.harga).format('0,0'));
                                    $('.form-pengeluaran').find('#qty').val(response.data.qty);
                                    $('.form-pengeluaran').find('#payment-type').val(response.data.jenis_pembayaran).change();
                                    if(selTypeIe.val() === '<?= base64_encode('perlengkapan_toko') ?>'){
                                        if(response.data.aset.length > 0){
                                            let rowAset = '';

                                            $.each(response.data.aset, function(i, item){
                                                rowAset += `<tr>
                                                                <td><input type="text" class="form-control nama-barang" minlength="3" required name="nama_barang[]" value="${item.nama_barang}"></td>
                                                                <td><input type="text" class="form-control price-format harga-barang" min="1" required name="harga_barang[]" value="${numeral(item.harga).format('0,0')}"></td>
                                                                <td><input type="number" class="form-control qty-barang" min="1" required name="qty_barang[]" value="${item.qty}"></td>
                                                                <td class="text-center row-action"><button class="btn btn-danger btn-delete-asset"><i class="fa fa-trash"></i></button></td>
                                                            </tr>`
                                                
                                            });

                                            $('.table-penambahan-aset tbody').html(rowAset);
                                            initCleaveJS();
                                        }
                                    }
                                } else {
                                    $('.form-show-perlengkapan-toko').find('#id').val(response.data.id);
                                    $('.form-show-perlengkapan-toko').find('#nama').val(response.data.nama);
                                    $('.form-show-perlengkapan-toko').find('#tanggal-transaksi').val(response.data.tanggal);
                                    $('.form-show-perlengkapan-toko').find('#total').val(numeral(response.data.total).format('0,0'));
                                    $('.form-show-perlengkapan-toko').find('#harga').val(numeral(response.data.harga).format('0,0'));
                                    $('.form-show-perlengkapan-toko').find('#qty').val(response.data.qty);
                                    $('.form-show-perlengkapan-toko').find('#payment-type').val(response.data.jenis_pembayaran).change();
                                    if(selTypeIe.val() === '<?= base64_encode('perlengkapan_toko') ?>'){
                                        if(response.data.aset.length > 0){
                                            let rowAset = '';

                                            $.each(response.data.aset, function(i, item){
                                                rowAset += `<tr>
                                                                <td><input type="text" class="form-control nama-barang bg-white" disabled value="${item.nama_barang}"></td>
                                                                <td><input type="text" class="form-control price-format harga-barang bg-white" disabled value="${numeral(item.harga).format('0,0')}"></td>
                                                                <td><input type="number" class="form-control qty-barang bg-white" disabled value="${item.qty}"></td>
                                                            </tr>`
                                                
                                            });

                                            $('.table-detail-penambahan-aset tbody').html(rowAset);
                                            initCleaveJS();
                                        }
                                    }
                                }
                            }
                        }
                    }
                } 
            })
        }

        function hitungTotalPengeluaran(){
            const harga = $('.harga-pengeluaran').val().replace(/,/g, '');
            const qty = $('.qty-pengeluaran').val();
            return parseFloat(harga) * parseFloat(qty);
        }

        function hitungTotalPenambahanAset(){
            let total = 0;
            $('.table-penambahan-aset tbody tr').each(function(){
                const t = $(this);
                const qty = t.find('.qty-barang').val().replace(/,/g, '');
                const harga = t.find('.harga-barang').val().replace(/,/g, '');
                const totalHarga = parseInt(qty) * parseFloat(harga);
                total += totalHarga;
            });

            return total;
        }

        function initCleaveJS(){
            $('.price-format').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });
        }
    });
</script>
</body>
</html>