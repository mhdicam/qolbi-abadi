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

        if($_POST['incomes_expenses'] == 'cGVuZGFwYXRhbg==' && $_POST['type'] == 'cmV2ZW51ZQ=='){
            if(!$_POST['id']){
                if(tambahRevenue($_POST)){
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
                if(updateRevenue($_POST)){
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

    $date_now = date('Y-m-d');
    $date_filter = $_GET['date'] ? $_GET['date'] : $date_now;

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
                            <div class="col-md-3">
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
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" class="form-control date-filter" name="date" placeholder="Filter Tanggal" value="<?= $date_filter ?>">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-9 text-right">
                                <div class="tambah-data">
                                    <button class="btn btn-primary btn-add">Tambah Data</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-auto mt-4">
                            <table class="table table-bordered table-striped incomes-expenses-table pendapatan-revenue-table <?= ($_GET['incomes-expenses'] == 'cGVuZGFwYXRhbg==' && $_GET['type'] == 'cmV2ZW51ZQ==') | (!$_GET['incomes-expenses'] && !$_GET['type']) ? '' : 'd-none' ?>">
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
                            <table class="table table-bordered table-striped incomes-expenses-table pengeluaran-table <?= $_GET['incomes-expenses'] == 'cGVuZ2VsdWFyYW4=' && $_GET['type'] != 'Z2FqaV9rYXJ5YXdhbg==' ? '' : 'd-none' ?>">
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
                        <label for="tanggal-transaksi">Tanggal Transaksi</label>
                        <input type="date" id="tanggal-transaksi" class="form-control" name="tanggal" value="<?= $date_now ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" class="form-control" name="nama" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="number" id="total" class="form-control" name="total" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="real-income">Real Income</label>
                        <input type="number" id="real-income" class="form-control" name="real_income" min="0" required>
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
                            <input type="number" id="qty" name="qty" class="form-control" min="1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga">Harga</label>
                            <input type="number" id="harga" name="harga" class="form-control" min="0" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total">Total</label>
                            <input type="number" id="total" name="total" class="form-control" min="0" required>
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
                            <input type="number" min="0" name="kddh" class="form-control kddh" id="kddh" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Day</label>
                            <input type="number" min="1" name="day" class="form-control day" id="day" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Bonus Omset</label>
                            <input type="number" min="0" name="bonus_omset" class="form-control bonus-omset" id="bonus-omset" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Salary</label>
                            <input type="number" min="1" name="salary" class="form-control salary" id="salary" autocomplete="off" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Overtime</label>
                            <input type="number" min="0" name="overtime" class="form-control overtime" id="overtime" autocomplete="off">
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
<script>
    $(function(){
        initLoad();
        $(".periode").datepicker( {
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        });
        $('.select-incomes-expenses').on('change', function(){
            const t = $(this);
            const v = t.val();

            let ieType = '<option value="<?= base64_encode('revenue') ?>">Revenue</option>';
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
            if(selIe.val() === 'cGVuZGFwYXRhbg==' && v === '<?= base64_encode('revenue') ?>'){
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
            }

            getIncomesExpensesDatas();
        });

        $('.btn-add').on('click', function(){
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            const inputType = $('.input-type');
            const inputIe = $('.input-incomes-expenses');

            inputType.val('');
            if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val() === '<?= base64_encode('revenue') ?>'){
                $('#modal-revenue').modal('show');
                $('#modal-pendapatan-title').text('Tambah Data Incomes');
                $('.form-pendapatan-revenue').find('#tanggal-transaksi').val('<?= date('Y-m-d') ?>');
            }

            else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                    $('#modal-pengeluaran-gaji').modal('show');
                    $('#modal-pengeluaran-gaji-title').text('Tambah Data Gaji Karyawan');
                } else {
                    $('#modal-pengeluaran').modal('show');
                    $('#modal-pengeluaran-title').text('Tambah Data Expenses');
                    $('.form-pengeluaran').find('#tanggal-transaksi').val('<?= date('Y-m-d') ?>');
                    $('.input-jenis-pengeluaran').val(selTypeIe.find('option:selected').text());
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
            if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val() === '<?= base64_encode('revenue') ?>'){
                $('#modal-revenue').modal('show');
                $('#modal-pendapatan-title').text('Edit Data Incomes');
            }

            else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                    $('#modal-pengeluaran-gaji').modal('show');
                    $('#modal-pengeluaran-gaji-title').text('Edit Data Gaji Karyawan');
                } else {
                    $('.input-jenis-pengeluaran').val(selTypeIe.find('option:selected').text());
                    $('#modal-pengeluaran-title').text('Edit Data Expensess');
                    $('#modal-pengeluaran').modal('show');
                }
            }

            inputType.val(selTypeIe.val());
            inputIe.val(selIe.val());

            getIncomesExpensesData(id)
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
            $(this).find("input,textarea,select").val('').end()
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
                    if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val() === '<?= base64_encode('revenue') ?>'){
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
                        if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val() === '<?= base64_encode('revenue') ?>'){
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
                            } else {
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

        function getIncomesExpensesData(id)
        {
            const selIe = $('.select-incomes-expenses');
            const selTypeIe = $('.select-type-incomes-expenses');
            $.ajax({
                type: "POST",
                url: 'incomes-expenses-data',
                data: {'incomes-expenses': selIe.val() , 'type': selTypeIe.val(), 'id' : id},
                success: function(response){
                    if(response.success){
                        if(selIe.val() === 'cGVuZGFwYXRhbg==' && selTypeIe.val() === '<?= base64_encode('revenue') ?>'){
                            $('.form-pendapatan-revenue').find('#id').val(response.data.id);
                            $('.form-pendapatan-revenue').find('#nama').val(response.data.nama);
                            $('.form-pendapatan-revenue').find('#tanggal-transaksi').val(response.data.tanggal);
                            $('.form-pendapatan-revenue').find('#total').val(response.data.total);
                            $('.form-pendapatan-revenue').find('#real-income').val(response.data.real_income);
                            $('.form-pendapatan-revenue').find('#payment-type').val(response.data.jenis_pembayaran).change();
                        } else if(selIe.val() === 'cGVuZ2VsdWFyYW4='){
                            if(selTypeIe.val() === '<?= base64_encode('gaji_karyawan') ?>'){
                                $('.form-pengeluaran-gaji').find('#id').val(response.data.id);
                                $('.form-pengeluaran-gaji').find('#periode').val(response.data.periode);
                                $('.form-pengeluaran-gaji').find('#karyawan').val(response.data.karyawan).change();
                                $('.form-pengeluaran-gaji').find('#kddh').val(response.data.kddh);
                                $('.form-pengeluaran-gaji').find('#day').val(response.data.day);
                                $('.form-pengeluaran-gaji').find('#bonus-omset').val(response.data.bonus_omset);
                                $('.form-pengeluaran-gaji').find('#salary').val(response.data.salary);
                                $('.form-pengeluaran-gaji').find('#overtime').val(response.data.overtime);
                                $('.form-pengeluaran-gaji').find('#payment-type').val(response.data.jenis_pembayaran).change();
                            } else {
                                $('.form-pengeluaran').find('#id').val(response.data.id);
                                $('.form-pengeluaran').find('#nama').val(response.data.nama);
                                $('.form-pengeluaran').find('#tanggal-transaksi').val(response.data.tanggal);
                                $('.form-pengeluaran').find('#total').val(response.data.total);
                                $('.form-pengeluaran').find('#harga').val(response.data.harga);
                                $('.form-pengeluaran').find('#qty').val(response.data.qty);
                                $('.form-pengeluaran').find('#payment-type').val(response.data.jenis_pembayaran).change();
                            }
                        }
                    }
                } 
            })
        }
    });
</script>
</body>
</html>