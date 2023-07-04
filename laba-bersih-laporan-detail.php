<?php 
  include '_header.php'; 
?>
<?php  
  if ( $levelLogin !== "super admin") {
    echo "
      <script>
        document.location.href = 'bo';
      </script>
    ";
  }  
?>

<?php  
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
?>

<?php  
    $toko = query("SELECT * FROM toko WHERE toko_cabang = $sessionCabang");
?>
<?php foreach ( $toko as $row ) : ?>
    <?php 
      $toko_nama = $row['toko_nama'];
      $toko_kota = $row['toko_kota'];
      $toko_tlpn = $row['toko_tlpn'];
      $toko_wa   = $row['toko_wa'];
      $toko_print= $row['toko_print']; 
    ?>
<?php endforeach; ?>


<!-- Total penjualan -->
<?php  
    $totalPenjualan = 0;
    $queryInvoice = $conn->query("SELECT invoice.invoice_id, invoice.invoice_date, invoice.invoice_cabang, invoice.invoice_total_beli, invoice.invoice_sub_total, invoice.penjualan_invoice
      FROM invoice 
      WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang = 0 && invoice_piutang_lunas = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
    ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPenjualan += $rowProduct['invoice_sub_total'];
    }
  ?>
<!-- End Total penjualan  -->


<!-- Total HPP -->
<?php  
    $totalHpp = 0;
    // $queryInvoice = $conn->query("SELECT invoice.invoice_id, invoice.invoice_date, invoice.invoice_cabang, invoice.invoice_total_beli, invoice.invoice_sub_total, invoice.penjualan_invoice
    //   FROM invoice 
    //   WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
    // ");
    // while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
    //   $totalHpp += $rowProduct['invoice_total_beli'];
    // }
    $queryInvoice = $conn->query("SELECT invoice_total, invoice_pembelian_cabang, invoice_hutang, invoice_date FROM invoice_pembelian WHERE invoice_pembelian_cabang = '".$sessionCabang."' && invoice_hutang = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowInvoice = mysqli_fetch_array($queryInvoice)) {
      $totalHpp += $rowInvoice['invoice_total'];
    }
  ?>
<!-- End Total HPP -->



<!-- Total Piutang Cicilan -->
<?php  
    $totalPiutang = 0;
      $queryInvoice = $conn->query("SELECT piutang.piutang_id, piutang.piutang_date, piutang.piutang_nominal, piutang.piutang_cabang
        FROM piutang 
        WHERE piutang_cabang = '".$sessionCabang."' && piutang_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
      ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPiutang += $rowProduct['piutang_nominal'];
    }
  ?>
<!-- End Total Piutang Cicilan -->

<!-- Total Piutang Kembalian -->
<?php  
    $totalPiutangKembalian = 0;
    $queryInvoice = $conn->query("SELECT piutang_kembalian.pl_id, piutang_kembalian.pl_date, piutang_kembalian.pl_nominal, piutang_kembalian.pl_cabang
        FROM piutang_kembalian 
        WHERE pl_cabang = '".$sessionCabang."' && pl_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
      ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPiutangKembalian += $rowProduct['pl_nominal'];
    }
  ?>
<!-- End Total Piutang Kembalian -->

<!-- Piutang = Total Piutang - Total Piutang Kembalian -->
<?php  
  $piutang = $totalPiutang - $totalPiutangKembalian;
?>
<!-- End Piutang = Total Piutang - Total Piutang Kembalian -->

<!-- Total Hutang Cicilan -->
<?php  
    $totalHutang = 0;
      $queryInvoice = $conn->query("SELECT hutang.hutang_id, hutang.hutang_date, hutang.hutang_nominal, hutang.hutang_cabang
        FROM hutang 
        WHERE hutang_cabang = '".$sessionCabang."' && hutang_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
      ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalHutang += $rowProduct['hutang_nominal'];
    }
  ?>
<!-- End Total Hutang Cicilan -->

<!-- Total Hutang Kembalian -->
<?php  
    $totalHutangKembalian = 0;
    $queryInvoice = $conn->query("SELECT hutang_kembalian.hl_id, hutang_kembalian.hl_date, hutang_kembalian.hl_nominal, hutang_kembalian.hl_cabang
        FROM hutang_kembalian 
        WHERE hl_cabang = '".$sessionCabang."' && hl_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'
      ");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalHutangKembalian += $rowProduct['hl_nominal'];
    }
  ?>
<!-- End Total Hutang Kembalian -->

<!-- Hutang = Total Hutang - Total Hutang Kembalian -->
<?php  
  $hutang = $totalHutang - $totalHutangKembalian;
?>
<!-- End Hutang = Total Hutang - Total Hutang Kembalian -->


<?php
  $lb_pendapatan_lain = 0;
  $lb_pengeluaran_gaji = 0;
  $labaBersih = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE lb_cabang = $sessionCabang AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
  // $labaBersihGaji = mysqli_query($conn, "SELECT * FROM laba_bersih WHERE lb_cabang = $sessionCabang AND MONTH(tanggal) BETWEEN '".$bulan_awal."' AND '".$bulan_akhir."' AND YEAR(tanggal) BETWEEN '".$tahun_awal."' AND '".$tahun_akhir."'");
  
  $and_where_pend_peng = " AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'";
  $pendapatan_lain = mysqli_query($conn, "SELECT SUM(real_income) as total FROM laba_bersih_detail WHERE incomes_expenses='pendapatan'" . $and_where_pend_peng)->fetch_assoc();
  
  $pengeluaran_internet = mysqli_query($conn, "SELECT SUM(total) as total FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='telepon_internet'" . $and_where_pend_peng)->fetch_assoc();

  $lb_pendapatan_lain = $pendapatan_lain['total'];
  $lb_pengeluaran_gaji = sumPengeluaran('gaji_karyawan', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_listrik = sumPengeluaran('listrik', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_tlpn_internet = sumPengeluaran('telepon_internet', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_perlengkapan_toko = sumPengeluaran('perlengkapan_toko', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_biaya_penyusutan = sumPengeluaran('biaya_penyusutan', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_bensin = sumPengeluaran('bensin', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_tak_terduga = sumPengeluaran('tak_terduga', $tanggal_awal, $tanggal_akhir);
  $lb_pengeluaran_lain = sumPengeluaran('lain_lain', $tanggal_awal, $tanggal_akhir);

  function sumPengeluaran($jenis, $tgl_awal, $tgl_akhir){
    global $conn;
    global $sessionCabang;
    $and_where_pend_peng = " AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";
    $pengeluaran = mysqli_query($conn, "SELECT SUM(total) as total FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='" . $jenis . "'" . $and_where_pend_peng)->fetch_assoc();

    return $pengeluaran['total'];
  }

?>
    <section class="laporan-laba-bersih">
        <div class="container">
            <div class="llb-header">
                  <div class="llb-header-parent">
                    <?= $toko_nama; ?>
                  </div>
                  <div class="llb-header-address">
                    <?= $toko_kota; ?>
                  </div>
                  <div class="llb-header-contact">
                    <ul>
                        <li><b>No.tlpn:</b> <?= $toko_tlpn; ?></li>&nbsp;&nbsp;
                        <li><b>Wa:</b> <?= $toko_wa; ?></li>
                    </ul>
                  </div>
              </div>


              <div class="laporan-laba-bersih-detail">
                  <div class="llbd-title">
                      Laporan LABA BERSIH Periode <?= tanggal_indo($tanggal_awal); ?> - <?= tanggal_indo($tanggal_akhir); ?>
                  </div>
                  <table class="table">
                    <thead>
                      <tr>
                        <th colspan="2">1. Pendapatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>a. Sub Total Penjualan</td>
                        <td>Rp <?= number_format($totalPenjualan, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>b. Piutang (Cicilan)</td>
                        <td>Rp <?= number_format($piutang, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          c. Pendapatan Lain
                        </td>
                        <td>Rp <?= number_format($lb_pendapatan_lain, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td><b>Total Pendapatan</b></td>
                        <td>
                            <?php  
                              $totalPendapatan = $totalPenjualan + $piutang + $lb_pendapatan_lain;
                              echo "<b>Rp ".number_format($totalPendapatan, 0, ',', '.')."</b>";
                            ?> 
                        </td>
                      </tr>

                      <tr>
                        <th colspan="2">2. HPP</th>
                      </tr>
                      <tr>
                        <td>a. HPP (Harga Pokok Penjualan)</td>
                        <td>Rp <?= number_format($totalHpp, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td><b>Laba / Rugi Kotor</b></td>
                        <td>
                            <?php  
                              $labaRugiKotor = $totalPendapatan - $totalHpp;
                              echo "<b>Rp ".number_format($labaRugiKotor, 0, ',', '.')."</b>";
                            ?>
                        </td>
                      </tr>

                      <tr>
                        <th colspan="2">3. Biaya Pengeluaran</th>
                      </tr>
                      <tr>
                        <td>
                          a. Total Gaji Pegawai
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_gaji, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          b. Biaya Listrik 1 Bulan
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_listrik, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          c. Telepon & Internet
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_tlpn_internet, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          d. Perlengkapan Toko
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_perlengkapan_toko, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          e. Biaya Penyusutan
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_biaya_penyusutan, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          f. Transportasi & Bensin
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_bensin, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          g. Biaya Tak Terduga
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_tak_terduga, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          h. Pengeluaran Lain
                        </td>
                        <td>Rp <?= number_format($lb_pengeluaran_lain, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td>i. Hutang (Cicilan)</td>
                        <td>Rp <?= number_format($hutang, 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <td><b>Total Biaya Pengeluaran</b></td>
                        <td>
                            <?php  
                              $totalBiayaPengeluaran = $lb_pengeluaran_gaji + $lb_pengeluaran_listrik + $lb_pengeluaran_tlpn_internet + $lb_pengeluaran_perlengkapan_toko + $lb_pengeluaran_biaya_penyusutan + $lb_pengeluaran_bensin + $lb_pengeluaran_tak_terduga + $lb_pengeluaran_lain + $hutang;
                              echo "<b>Rp ".number_format($totalBiayaPengeluaran, 0, ',', '.' )."</b>";
                            ?>
                        </td>
                      </tr>
                      <tr>
                        <th>Laba Bersih</th>
                        <th>
                            <?php  
                                $labaBersih = $labaRugiKotor - $totalBiayaPengeluaran;
                                echo "Rp ".number_format($labaBersih, 0, ',', '.');
                            ?>
                        </th>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <div class="text-center">
                © <?= date("Y"); ?> Copyright icammohammad All rights reserved.
              </div>
        </div>
    </section>


</body>
</html>
<script>
  window.print();
</script>