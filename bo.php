<?php 
  include '_header.php';
  include '_nav.php';
  include '_sidebar.php'; 
?>
<?php  
  if ( $levelLogin === "kurir") {
    echo "
      <script>
        document.location.href = 'kurir-data';
      </script>
    ";
  }  
?>
  <?php  
    $invoice_date_year_month = date("Y-m");
    $penjualan = query("SELECT * FROM penjualan WHERE penjualan_cabang = $sessionCabang && penjualan_date_year_month = '".$invoice_date_year_month."' ");
  ?>

  <?php $jmlPenjualan = 0; ?>
  <?php foreach ( $penjualan as $row ) : ?>
    <?php $jmlPenjualan += $row['barang_qty']; ?>
  <?php endforeach; ?>

  <?php
    function hitungPendapatan($cabang, $bulan, $tahun){
      global $conn;
      $pendapatan_invoice = 0;
      $query_pendapatan = mysqli_query($conn, "SELECT SUM(invoice_total) AS total_pendapatan FROM invoice WHERE invoice_cabang = '". $cabang ."' AND invoice_piutang = 0 AND invoice_piutang_lunas = 0 AND MONTH(invoice_date) = '".$bulan."' AND YEAR(invoice_date) = '".$tahun."'");
      while($pendapatan = mysqli_fetch_array($query_pendapatan)){
        $pendapatan_invoice = $pendapatan['total_pendapatan'];
      }

      $total_piutang = 0;
      $piutang = mysqli_query($conn, "SELECT piutang.piutang_nominal, piutang.piutang_cabang FROM piutang WHERE piutang_cabang = '".$cabang."' AND MONTH(piutang_date) = '".$bulan. "' AND YEAR(piutang_date) = '".$tahun. "'");
      while ($row_piutang = mysqli_fetch_array($piutang)) {
        $total_piutang += $row_piutang['piutang_nominal'];
      }

      $total_piutang_kembalian = 0;
      $piutang_kembalian = mysqli_query($conn, "SELECT piutang_kembalian.pl_id, piutang_kembalian.pl_date, piutang_kembalian.pl_nominal, piutang_kembalian.pl_cabang FROM piutang_kembalian WHERE pl_cabang = '".$cabang."' AND MONTH(pl_date) = '".$bulan. "' AND YEAR(pl_date) = '".$tahun. "'");
      while ($row_piutang_kembalian = mysqli_fetch_array($piutang_kembalian)) {
        $total_piutang_kembalian += $row_piutang_kembalian['pl_nominal'];
      }

      $piutang = $total_piutang - $total_piutang_kembalian;
      
      $laba_pendapatan_lain = 0;
      $laba_bersih = mysqli_query($conn, "SELECT lb_pendapatan_lain FROM laba_bersih WHERE lb_cabang = '".$cabang."' AND MONTH(tanggal)='".$bulan."' AND YEAR(tanggal)='".$tahun."'");
      while ($row_laba_bersih = mysqli_fetch_array($laba_bersih)) {
        $laba_pendapatan_lain += $row_laba_bersih['lb_pendapatan_lain'];
      }

      $total_pendapatan = $pendapatan_invoice + $piutang + $laba_pendapatan_lain;
      return $total_pendapatan;
    }

    function hitungPengeluaran($cabang, $bulan, $tahun){
      global $conn;
      $lb_pengeluaran_gaji = 0;
      $lb_pengeluaran_listrik = 0;
      $lb_pengeluaran_tlpn_internet = 0;
      $lb_pengeluaran_perlengkapan_toko = 0;
      $lb_pengeluaran_biaya_penyusutan = 0;
      $lb_pengeluaran_bensin = 0;
      $lb_pengeluaran_tak_terduga = 0;
      $lb_pengeluaran_lain = 0;

      $laba_bersih = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE lb_cabang = '".$cabang."' AND MONTH(tanggal)='".$bulan."' AND YEAR(tanggal)='".$tahun."'");
      while ($row_laba_bersih = mysqli_fetch_array($laba_bersih)) {
        $lb_pengeluaran_gaji                += $row_laba_bersih['lb_pengeluaran_gaji'];
        $lb_pengeluaran_listrik             += $row_laba_bersih['lb_pengeluaran_listrik'];
        $lb_pengeluaran_tlpn_internet       += $row_laba_bersih['lb_pengeluaran_tlpn_internet'];
        $lb_pengeluaran_perlengkapan_toko   += $row_laba_bersih['lb_pengeluaran_perlengkapan_toko']; 
        $lb_pengeluaran_biaya_penyusutan    += $row_laba_bersih['lb_pengeluaran_biaya_penyusutan'];
        $lb_pengeluaran_bensin              += $row_laba_bersih['lb_pengeluaran_bensin'];
        $lb_pengeluaran_tak_terduga         += $row_laba_bersih['lb_pengeluaran_tak_terduga'];
        $lb_pengeluaran_lain                += $row_laba_bersih['lb_pengeluaran_lain']; 
      }
      
      $total_hutang = 0;
      $q_hutang = $conn->query("SELECT hutang.hutang_nominal, hutang.hutang_cabang FROM hutang WHERE hutang_cabang = '".$cabang."' AND MONTH(hutang_date)='".$bulan."' AND YEAR(hutang_date)='".$tahun."'");
      while ($row_hutang = mysqli_fetch_array($q_hutang)) {
        $total_hutang += $row_hutang['hutang_nominal'];
      }

      $total_hutang_kembalian = 0;
      $q_hutang_kembalian = $conn->query("SELECT hutang_kembalian.hl_nominal, hutang_kembalian.hl_cabang FROM hutang_kembalian WHERE hl_cabang = '".$cabang."' AND MONTH(hl_date)='".$bulan."' AND YEAR(hl_date)='".$tahun."'");
      while ($row_hutang_kembalian = mysqli_fetch_array($q_hutang_kembalian)) {
        $total_hutang_kembalian += $row_hutang_kembalian['hl_nominal'];
      }

      $hutang = $total_hutang - $total_hutang_kembalian;
      $total_pengeluaran = $lb_pengeluaran_gaji + $lb_pengeluaran_listrik + $lb_pengeluaran_tlpn_internet + $lb_pengeluaran_perlengkapan_toko + $lb_pengeluaran_biaya_penyusutan + $lb_pengeluaran_bensin + $lb_pengeluaran_tak_terduga + $lb_pengeluaran_lain + $hutang;

      return $total_pengeluaran;
    }

    $dataset_pemasukan = [];

    $query_toko = $sessionCabang == 0 ? "SELECT toko_nama, toko_cabang FROM toko" : "SELECT toko_nama, toko_cabang FROM toko WHERE toko_cabang=$sessionCabang";
    $q_toko = mysqli_query($conn, $query_toko);
    while($toko = mysqli_fetch_array($q_toko)){
      $arr_pemasukan_value = [];
      for($i = 1; $i < 13; $i++){
        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
        $arr_pemasukan_value[] = hitungPendapatan($toko['toko_cabang'], $i, date('Y'));
      }

      $dataset_pemasukan[] = [
        'label' => $toko['toko_nama'],
        'data' => $arr_pemasukan_value,
        'fill' => false,
        'backgroundColor' => 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')',
        'tension' => 0.1
      ];
    }

    $dataset_pengeluaran = [];
    $q_toko = mysqli_query($conn, $query_toko);
    while($toko = mysqli_fetch_array($q_toko)){
      $arr_pengeluaran_value = [];
      for($i = 1; $i < 13; $i++){
        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
        $arr_pengeluaran_value[] = hitungPengeluaran($toko['toko_cabang'], $i, date('Y'));
      }

      $dataset_pengeluaran[] = [
        'label' => $toko['toko_nama'],
        'data' => $arr_pengeluaran_value,
        'fill' => false,
        'backgroundColor' => 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')',
        'tension' => 0.1
      ];
    }

  ?>


  <!-- Total penjualan Nominal hari ini -->
  <?php  
    $totalPenjualanHariIni = 0;
    $tanggalHariIni = date("Y-m-d");
      $queryInvoice = $conn->query("SELECT invoice.invoice_id, invoice.invoice_date, invoice.invoice_cabang, invoice.invoice_sub_total, invoice.penjualan_invoice
        FROM invoice 
         WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang = 0 && invoice_piutang_lunas = 0 && invoice_date = '".$tanggalHariIni."' ORDER BY invoice_id DESC
      ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
    $totalPenjualanHariIni += $rowProduct['invoice_sub_total'];
  ?>
  <?php } ?>
  <!-- End Total penjualan Nominal hari ini -->

  <?php  

    // Barang
    $barang = mysqli_query($conn,"select * from barang where barang_cabang = ".$sessionCabang."");
    $jmlBarang = mysqli_num_rows($barang);

    // Invoice
    $invoice = mysqli_query($conn,"select * from invoice where invoice_cabang = '".$sessionCabang."' && invoice_piutang < 1 && invoice_date_year_month = '".$invoice_date_year_month."' ");
    $jmlInvoice = mysqli_num_rows($invoice);
  ?>


  <!-- Total Invoice hari ini -->
  <?php  
    $invoiceHariIni = mysqli_query($conn,"select * from invoice where invoice_cabang = '".$sessionCabang."' && invoice_date = '".$tanggalHariIni."' && invoice_piutang = 0 && invoice_piutang_lunas = 0 ");
    $jmlInvoiceHariIni = mysqli_num_rows($invoiceHariIni);
  ?>
  <!-- End Total Invoice hari ini -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard <b><?= $tipeToko; ?> <?= $dataTokoLogin['toko_kota']; ?></b></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="bo">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-6 col-md-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Rp <?= number_format($totalPenjualanHariIni, 0, ',', '.'); ?></h3>

                <p>Penjualan <b>Hari ini</b></p>
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              
            </div>
          </div>

          <div class="col-lg-6 col-md-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= singkat_angka($jmlInvoiceHariIni); ?></h3>
                <p>Invoice Penjualan Cash <b>Hari ini</b></p>
              </div>
              <div class="icon">
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <!-- small box -->
            <div class="small-box" style="background: #fff;">
              <div class="inner">
                <h3><?= $jmlPenjualan; ?></h3>

                <p><b>Total</b> Barang Terjual Bulan Ini</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-cart" style="color: #17a2b8;"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <!-- small box -->
            <div class="small-box" style="background: #fff;">
              <div class="inner">
                <h3><?= $jmlBarang; ?></h3>

                <p>Jumlah Barang</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-bag" style="color: #28a745;"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <!-- small box -->
            <div class="small-box" style="background: #fff;">
              <div class="inner">
                <h3><?= $jmlInvoice; ?></h3>

                <p><b>Total</b> Invoice Penjualan Bulan ini</p>
              </div>
              <div class="icon">
                <i class="fa fa-table" style="color: #dc3545;"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <section class="table-informasi">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Chart Pemasukan</b> <?= $sessionCabang == 0 ? 'Percabang' : '' ?></h3>
              </div>
              <div class="card-body">
                <canvas id="pemasukan-chart" height="100"></canvas>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Chart Pengeluaran</b> <?= $sessionCabang == 0 ? 'Percabang' : '' ?></h3>
              </div>
              <div class="card-body">
                <canvas id="pengeluaran-chart" height="100"></canvas>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><b>Data Pemasukan</b> <?= $sessionCabang == 0 ? 'Percabang' : '' ?></h3>
                </div>
                <div class="card-body">
                  <div class="table-auto">
                    <table id="" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Cabang</th>
                          <th>Pemasukan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $no = 1;
                          $query_pendapatan = $sessionCabang == 0 ? "SELECT toko_nama, toko_cabang, (SELECT SUM(invoice_total) AS total_pendapatan FROM invoice WHERE invoice_cabang = toko_cabang AND invoice_piutang = 0 AND invoice_piutang_lunas = 0 AND MONTH(invoice_date) = '". date('m'). "' AND YEAR(invoice_date) = '". date('Y'). "') AS total_pendapatan FROM toko" : "SELECT toko_nama, toko_cabang, (SELECT SUM(invoice_total) AS total_pendapatan FROM invoice WHERE invoice_cabang = toko_cabang AND invoice_piutang = 0 AND invoice_piutang_lunas = 0 AND MONTH(invoice_date) = '". date('m'). "' AND YEAR(invoice_date) = '". date('Y'). "') AS total_pendapatan FROM toko WHERE toko_cabang=$sessionCabang";
                          $q_pendapatan = mysqli_query($conn, $query_pendapatan);
                          while($pendapatan = mysqli_fetch_array($q_pendapatan)):
                            $pendapatan_invoice = $pendapatan['total_pendapatan'];

                            $total_piutang = 0;
                            $piutang = mysqli_query($conn, "SELECT piutang.piutang_id, piutang.piutang_date, piutang.piutang_nominal, piutang.piutang_cabang FROM piutang WHERE piutang_cabang = '".$pendapatan['toko_cabang']."' AND MONTH(piutang_date) = '". date('m'). "' AND YEAR(piutang_date) = '". date('Y'). "'");
                            while ($row_piutang = mysqli_fetch_array($piutang)) {
                              $total_piutang += $row_piutang['piutang_nominal'];
                            }

                            $total_piutang_kembalian = 0;
                            $piutang_kembalian = mysqli_query($conn, "SELECT piutang_kembalian.pl_id, piutang_kembalian.pl_date, piutang_kembalian.pl_nominal, piutang_kembalian.pl_cabang FROM piutang_kembalian WHERE pl_cabang = '".$pendapatan['toko_cabang']."' AND MONTH(pl_date) = '". date('m'). "' AND YEAR(pl_date) = '". date('Y'). "'");
                            while ($row_piutang_kembalian = mysqli_fetch_array($piutang_kembalian)) {
                              $total_piutang_kembalian += $row_piutang_kembalian['pl_nominal'];
                            }

                            $piutang = $total_piutang - $total_piutang_kembalian;
                            
                            $laba_pendapatan_lain = 0;
                            $laba_bersih = mysqli_query($conn, "SELECT lb_pendapatan_lain FROM laba_bersih WHERE lb_cabang = '".$pendapatan['toko_cabang']."' AND MONTH(tanggal) = '". date('m'). "' AND YEAR(tanggal) = '". date('Y'). "'");
                            while ($row_laba_bersih = mysqli_fetch_array($laba_bersih)) {
                              $laba_pendapatan_lain += $row_laba_bersih['lb_pendapatan_lain'];
                            }

                            $total_pendapatan = $pendapatan_invoice + $piutang + $laba_pendapatan_lain;
                        ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $pendapatan['toko_nama'] ?></td>
                              <td><?= number_format($total_pendapatan) ?></td>
                            </tr>
                        <?php endwhile ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><b>Data Pengeluaran</b> <?= $sessionCabang == 0 ? 'Percabang' : '' ?></h3>
                </div>
                <div class="card-body">
                  <div class="table-auto">
                    <table id="" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Cabang</th>
                          <th>Pengeluaran</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $no = 1;
                          $q_toko = mysqli_query($conn, $query_toko);
                          while($toko = mysqli_fetch_array($q_toko)):
                            $lb_pengeluaran_gaji = 0;
                            $lb_pengeluaran_listrik = 0;
                            $lb_pengeluaran_tlpn_internet = 0;
                            $lb_pengeluaran_perlengkapan_toko = 0;
                            $lb_pengeluaran_biaya_penyusutan = 0;
                            $lb_pengeluaran_bensin = 0;
                            $lb_pengeluaran_tak_terduga = 0;
                            $lb_pengeluaran_lain = 0;

                            $laba_bersih = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE lb_cabang = '".$toko['toko_cabang']."' AND MONTH(tanggal) = '". date('m'). "' AND YEAR(tanggal) = '". date('Y'). "'");
                            while ($row_laba_bersih = mysqli_fetch_array($laba_bersih)) {
                              $lb_pengeluaran_gaji                += $row_laba_bersih['lb_pengeluaran_gaji'];
                              $lb_pengeluaran_listrik             += $row_laba_bersih['lb_pengeluaran_listrik'];
                              $lb_pengeluaran_tlpn_internet       += $row_laba_bersih['lb_pengeluaran_tlpn_internet'];
                              $lb_pengeluaran_perlengkapan_toko   += $row_laba_bersih['lb_pengeluaran_perlengkapan_toko']; 
                              $lb_pengeluaran_biaya_penyusutan    += $row_laba_bersih['lb_pengeluaran_biaya_penyusutan'];
                              $lb_pengeluaran_bensin              += $row_laba_bersih['lb_pengeluaran_bensin'];
                              $lb_pengeluaran_tak_terduga         += $row_laba_bersih['lb_pengeluaran_tak_terduga'];
                              $lb_pengeluaran_lain                += $row_laba_bersih['lb_pengeluaran_lain']; 
                            }
                            
                            $total_hutang = 0;
                            $q_hutang = $conn->query("SELECT hutang.hutang_nominal, hutang.hutang_cabang FROM hutang WHERE hutang_cabang = '".$toko['toko_cabang']."' AND MONTH(hutang_date)='".date('m')."' AND YEAR(hutang_date)='".date('Y')."'");
                            while ($row_hutang = mysqli_fetch_array($q_hutang)) {
                              $total_hutang += $row_hutang['hutang_nominal'];
                            }

                            $total_hutang_kembalian = 0;
                            $q_hutang_kembalian = $conn->query("SELECT hutang_kembalian.hl_nominal, hutang_kembalian.hl_cabang FROM hutang_kembalian WHERE hl_cabang = '".$toko['toko_cabang']."' AND MONTH(hl_date)='".date('m')."' AND YEAR(hl_date)='".date('Y')."'");
                            while ($row_hutang_kembalian = mysqli_fetch_array($q_hutang_kembalian)) {
                              $total_hutang_kembalian += $row_hutang_kembalian['hl_nominal'];
                            }

                            $hutang = $total_hutang - $total_hutang_kembalian;
                            $total_pengeluaran = $lb_pengeluaran_gaji + $lb_pengeluaran_listrik + $lb_pengeluaran_tlpn_internet + $lb_pengeluaran_perlengkapan_toko + $lb_pengeluaran_biaya_penyusutan + $lb_pengeluaran_bensin + $lb_pengeluaran_tak_terduga + $lb_pengeluaran_lain + $hutang;
                        ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $toko['toko_nama'] ?></td>
                              <td><?= number_format($total_pengeluaran) ?></td>
                            </tr>
                        <?php endwhile ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>

          <div class="col-lg-6">
             <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><b>Data Barang</b> Terlaris</h3>
                </div>
              <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-auto">
                    <table id="" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Barang</th>
                        <th>Nama</th>
                        <th>Terjual</th>
                      </tr>
                      </thead>
                      <tbody>

                      <?php 
                        $i = 1; 
                        $queryProduct = $conn->query("SELECT barang.barang_id, barang.barang_kode, barang.barang_nama, barang.barang_harga, barang.barang_terjual, barang.barang_cabang
                                   FROM barang 
                                   WHERE barang_cabang = '".$sessionCabang."' && barang_terjual > 0  ORDER BY barang_terjual DESC LIMIT 5
                                   ");
                        while ($rowProduct = mysqli_fetch_array($queryProduct)) {
                      ?>
                      <tr>
                          <td><?= $i; ?></td>
                          <td><?= $rowProduct['barang_kode']; ?></td>
                          <td><?= $rowProduct['barang_nama']; ?></td>
                          <td>
                            <b><?= $rowProduct['barang_terjual']; ?></b>
                          </td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              <!-- /.card-body -->
              </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Data Stok</b> Terkecil</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-auto">
                  <table id="" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Barang</th>
                      <th>Nama</th>
                      <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php 
                      $i = 1; 
                      $queryProduct = $conn->query("SELECT barang.barang_id, barang.barang_kode, barang.barang_nama, barang.barang_harga, barang.barang_stock, barang.barang_cabang
                                 FROM barang 
                                 WHERE barang_cabang = '".$sessionCabang."' && barang_stock < 10 ORDER BY barang_stock ASC LIMIT 5
                                 ");
                      while ($rowProduct = mysqli_fetch_array($queryProduct)) {
                    ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $rowProduct['barang_kode']; ?></td>
                        <td><?= $rowProduct['barang_nama']; ?></td>
                        <td>
                          <b><?= $rowProduct['barang_stock']; ?></b>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              <!-- /.card-body -->
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Data Piutang</b> Jatuh Tempo Kurang dari 30 Hari</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-auto">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Invoice</th>
                      <th>Transaksi</th>
                      <th>Customer</th>
                      <th>Jatuh Tempo</th>
                      <th>Sub Total</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php 
                      $i = 1; 
                      $date_max  = date('Y-m-d', strtotime('30 days'));
                      $queryProduct = $conn->query("SELECT invoice.invoice_id, invoice.penjualan_invoice, invoice.invoice_date, invoice.invoice_sub_total, invoice.invoice_cabang, invoice.invoice_customer, invoice.invoice_piutang_jatuh_tempo, invoice.invoice_piutang_dp, invoice.invoice_bayar, invoice.invoice_kembali, customer.customer_id, customer.customer_nama, customer.customer_tlpn
                                 FROM invoice 
                                 JOIN customer ON invoice.invoice_customer = customer.customer_id
                                 WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang > 0 && invoice_piutang_jatuh_tempo <= '".$date_max."' ORDER BY invoice_id DESC
                                 ");
                      while ($rowProduct = mysqli_fetch_array($queryProduct)) {
                    ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $rowProduct['penjualan_invoice']; ?></td>
                        <td><?= tanggal_indo($rowProduct['invoice_date']); ?></td>
                        <td><?= $rowProduct['customer_nama']; ?></td>
                        <td>
                            <?php
                                // Jatuh Tempo
                                $piutangJatuhTempo = tanggal_indo($rowProduct['invoice_piutang_jatuh_tempo']);

                                // Durasi Hari
                                $tgl1 = new DateTime($tanggalHariIni);
                                $tgl2 = new DateTime($rowProduct['invoice_piutang_jatuh_tempo']);
                                $d = $tgl2->diff($tgl1)->days;

                                if ( $tanggalHariIni > $rowProduct['invoice_piutang_jatuh_tempo']) {
                                  $nh = "<span class='badge badge-danger'>Lewat ".$d." Hari</span>";
                                  $dWA = "Lewat ".$d." Hari";
                                  echo "<s>".$piutangJatuhTempo."<s> ".$nh;

                                } else {
                                  if ( $d > 20 ) {
                                     $nh = "<span class='badge badge-warning'>".$d." Hari Lagi</span>";
                                  } elseif ( $d <= 20 ) {
                                      $nh = "<span class='badge badge-danger'>".$d." Hari Lagi</span>";
                                  } else {
                                      $nh = "<span class='badge badge-danger'>".$d." Hari Lagi</span>";
                                  }
                                  $dWA = $d." Hari Lagi";
                                  echo $piutangJatuhTempo." ".$nh;  
                                }
                            ?>
                        </td>
                        <td>
                          Rp. <?= number_format($rowProduct['invoice_sub_total'], 0, ',', '.'); ?>
                        </td>
                        <td class="orderan-online-button">
                            <a href="penjualan-zoom?no=<?= base64_encode($rowProduct['invoice_id']); ?>" target="_blank">
                              <button class='btn btn-info' title='Lihat Data'>
                                <i class='fa fa-eye'></i>
                              </button>
                            </a>

                            <a href="piutang-cicilan?no=<?= base64_encode($rowProduct['invoice_id']); ?>">
                              <button class='btn btn-primary' title='Cicilan'>
                                <i class='fa fa-money'></i>
                              </button>
                            </a>

                            <?php  
                              $no_wa = substr_replace($rowProduct['customer_tlpn'],'62',0,1)
                            ?>
                            <a href="https://api.whatsapp.com/send?phone=<?= $no_wa; ?>&text=Halo <?= $rowProduct['customer_nama'];?>, Kami dari *<?= $dataTokoLogin['toko_nama']; ?> <?= $dataTokoLogin['toko_kota']; ?>* memberikan informasi bahwa transaksi *No Invoice <?= $rowProduct['penjualan_invoice'];?> dengan jumlah transaksi Rp <?= number_format($rowProduct['invoice_sub_total'], 0, ',', '.'); ?>* sudah mendekati jatuh tempo pada <?= $piutangJatuhTempo; ?> (<?= $dWA; ?> dimulai dari tanggal sekarang).%0A%0ASub Total: Rp <?= number_format($rowProduct['invoice_sub_total'], 0, ',', '.'); ?>%2C%0ADP: Rp <?= number_format($rowProduct['invoice_piutang_dp'], 0, ',', '.'); ?>%2C%0ADP ditambah Total Cicilan: Rp <?= number_format($rowProduct['invoice_bayar'], 0, ',', '.'); ?> %2C%0A*Sisa Piutang: Rp <?= number_format($rowProduct['invoice_kembali'], 0, ',', '.'); ?>*%2C%0A%0A%0AMohon Segera Dilunasi" target="_blank">
                              <button class='btn btn-success' title='Cicilan'>
                                <i class='fa fa-whatsapp'></i>
                              </button>
                            </a>

                            <a href="nota-cetak?no=<?= $rowProduct['invoice_id']; ?>" target="_blank">
                              <button class='btn btn-warning' title="Cetak Nota">
                                <i class='fa fa-print'></i>
                              </button>
                            </a>
                        </td>
                    </tr>
                    <?php $i++; ?>

                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              <!-- /.card-body -->
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Data Hutang</b> Jatuh Tempo Kurang dari 30 Hari</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-auto">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Invoice</th>
                      <th>Transaksi</th>
                      <th>Supplier</th>
                      <th>Jatuh Tempo</th>
                      <th>Sub Total</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php 
                      $i = 1; 
                      $date_max  = date('Y-m-d', strtotime('30 days'));
                      $queryProduct = $conn->query("SELECT invoice_pembelian.invoice_pembelian_id, 
                        invoice_pembelian.pembelian_invoice, 
                        invoice_pembelian.invoice_date, 
                        invoice_pembelian.invoice_total, 
                        invoice_pembelian.invoice_pembelian_cabang, 
                        invoice_pembelian.invoice_supplier, 
                        invoice_pembelian.invoice_hutang_jatuh_tempo, 
                        invoice_pembelian.invoice_hutang_dp, 
                        invoice_pembelian.invoice_bayar, 
                        invoice_pembelian.invoice_kembali, 
                        supplier.supplier_id, 
                        supplier.supplier_nama, 
                        supplier.supplier_company
                                 FROM invoice_pembelian 
                                 JOIN supplier ON invoice_pembelian.invoice_supplier = supplier.supplier_id
                                 WHERE invoice_pembelian_cabang = '".$sessionCabang."' && invoice_hutang > 0 && invoice_hutang_jatuh_tempo <= '".$date_max."' ORDER BY invoice_pembelian_id DESC
                                 ");
                      while ($rowProduct = mysqli_fetch_array($queryProduct)) {
                    ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $rowProduct['pembelian_invoice']; ?></td>
                        <td><?= tanggal_indo($rowProduct['invoice_date']); ?></td>
                        <td><?= $rowProduct['supplier_company']; ?> - <?= $rowProduct['supplier_nama']; ?></td>
                        <td>
                            <?php
                                // Jatuh Tempo
                                $hutangJatuhTempo = tanggal_indo($rowProduct['invoice_hutang_jatuh_tempo']);

                                // Durasi Hari
                                $tgl1a = new DateTime($tanggalHariIni);
                                $tgl2a = new DateTime($rowProduct['invoice_hutang_jatuh_tempo']);
                                $da = $tgl2a->diff($tgl1a)->days;

                                if ( $tanggalHariIni > $rowProduct['invoice_hutang_jatuh_tempo']) {
                                  $nha = "<span class='badge badge-danger'>Lewat ".$da." Hari</span>";
                                  echo "<s>".$hutangJatuhTempo."<s> ".$nha;

                                } else {
                                  if ( $da > 20 ) {
                                     $nha = "<span class='badge badge-warning'>".$da." Hari Lagi</span>";
                                  } elseif ( $da <= 20 ) {
                                      $nha = "<span class='badge badge-danger'>".$da." Hari Lagi</span>";
                                  } else {
                                      $nha = "<span class='badge badge-danger'>".$da." Hari Lagi</span>";
                                  }
                                  echo $hutangJatuhTempo." ".$nha;
                                } 
                            ?>
                        </td>
                        <td>
                          Rp. <?= number_format($rowProduct['invoice_total'], 0, ',', '.'); ?>
                        </td>
                        <td class="orderan-online-button">
                            <a href="pembelian-zoom?no=<?= base64_encode($rowProduct['invoice_pembelian_id']); ?>" target="_blank">
                              <button class='btn btn-info' title='Lihat Data'>
                                <i class='fa fa-eye'></i>
                              </button>
                            </a>

                            <a href="hutang-cicilan?no=<?= base64_encode($rowProduct['invoice_pembelian_id']); ?>">
                              <button class='btn btn-primary' title='Cicilan'>
                                <i class='fa fa-money'></i>
                              </button>
                            </a>

                            <a href="nota-cetak-pembelian?no=<?= $rowProduct['invoice_pembelian_id']; ?>" target="_blank">
                              <button class='btn btn-warning' title="Cetak Nota">
                                <i class='fa fa-print'></i>
                              </button>
                            </a>
                        </td>
                    </tr>
                    <?php $i++; ?>

                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              <!-- /.card-body -->
              </div>
            </div>
          </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <section class="kasir-bo">
    <a href="beli-langsung?customer=<?= base64_encode(0); ?>">
      <img src="dist/img/kasir.png" alt="POS Seniman Koding" class="img-fluid"> 
    </a>   
  </section>

<?php include '_footer.php'; ?>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.js" integrity="sha512-mlz/Fs1VtBou2TrUkGzX4VoGvybkD9nkeXWJm3rle0DPHssYYx4j+8kIS15T78ttGfmOjH0lLaBXGcShaVkdkg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  $(function () {
    $("#example1").DataTable();
    $("#example2").DataTable();

    const ctxPemasukan = document.getElementById('pemasukan-chart');
    const ctxPengeluaran = document.getElementById('pengeluaran-chart');
    const dataPemasukan = {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: <?= trim(json_encode($dataset_pemasukan), '"') ?>
                };
    const dataPengeluaran = {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: <?= trim(json_encode($dataset_pengeluaran), '"') ?>
                };

    const pemasukanChart = new Chart(ctxPemasukan, {
                          type: 'bar',
                          data: dataPemasukan,
                          options: {
                            responsive: true,
                            plugins: {
                              legend: {
                                position: 'top',
                              },
                              title: {
                                display: true,
                                text: 'Chart Pemasukan'
                              }
                            },
                            locale: 'en-IN',
                            scales: {
                              yAxes: [{
                                  display: true,
                                  ticks: {
                                      beginAtZero: true,
                                      callback: function(value, index, values) {
                                        if(parseInt(value) >= 1000){
                                          return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                        } else {
                                          return value;
                                        }
                                      }
                                  }
                              }]
                            }
                          }
                        });

    const pengeluaranChart = new Chart(ctxPengeluaran, {
                          type: 'bar',
                          data: dataPengeluaran,
                          options: {
                            responsive: true,
                            plugins: {
                              legend: {
                                position: 'top',
                              },
                              title: {
                                display: true,
                                text: 'Chart Pengeluaran'
                              }
                            },
                            locale: 'en-IN',
                            scales: {
                              yAxes: [{
                                  display: true,
                                  ticks: {
                                      beginAtZero: true,
                                      callback: function(value, index, values) {
                                        if(parseInt(value) >= 1000){
                                          return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                        } else {
                                          return value;
                                        }
                                      }
                                  }
                              }]
                            }
                          }
                        });
  });
</script>