<?php 
  include '_header.php'; 
?>
<?php  
  $allowAccess = ['super admin', 'admin'];
  if (!in_array($levelLogin, $allowAccess)) {
    echo "
      <script>
        document.location.href = 'bo';
      </script>
    ";
  }  
?>

<style>
  .table tr th, .table tr td {
    padding: 0;
  }
</style>

<?php  
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
  // $bulan_awal = date('m', strtotime($tanggal_awal));
  // $bulan_akhir = date('m', strtotime($tanggal_akhir));
  // $tahun_awal = date('Y', strtotime($tanggal_awal));
  // $tahun_akhir = date('Y', strtotime($tanggal_akhir));
?>

<?php  
    $toko = query("SELECT * FROM toko WHERE toko_cabang = $sessionCabang");
    foreach ( $toko as $row ) {
      $toko_nama = $row['toko_nama'];
      $toko_kota = $row['toko_kota'];
      $toko_tlpn = $row['toko_tlpn'];
      $toko_wa   = $row['toko_wa'];
      $toko_print= $row['toko_print']; 
    }

    /** TOTAL PENJUALAN */
    $totalPenjualan = 0;
    $queryInvoice = $conn->query("SELECT invoice.invoice_id, invoice.invoice_date, invoice.invoice_cabang, invoice.invoice_total_beli, invoice.invoice_sub_total, invoice.penjualan_invoice
                                  FROM invoice 
                                  WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang = 0 && invoice_piutang_lunas = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPenjualan += $rowProduct['invoice_sub_total'];
    }
    /** END TOTAL PENJUALAN */

    /** TOTAL HPP */
    $totalHpp = 0;
    // $queryInvoice = $conn->query("SELECT invoice.invoice_id, invoice.invoice_date, invoice.invoice_cabang, invoice.invoice_total_beli, invoice.invoice_sub_total, invoice.penjualan_invoice
    //                               FROM invoice 
    //                               WHERE invoice_cabang = '".$sessionCabang."' && invoice_piutang = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    // while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
    //   $totalHpp += $rowProduct['invoice_total_beli'];
    // }
    
    $queryInvoice = $conn->query("SELECT invoice_total, invoice_pembelian_cabang, invoice_hutang, invoice_date FROM invoice_pembelian WHERE invoice_pembelian_cabang = '".$sessionCabang."' && invoice_hutang = 0 && invoice_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowInvoice = mysqli_fetch_array($queryInvoice)) {
      $totalHpp += $rowInvoice['invoice_total'];
    }
    /** END TOTAL HPP */

    /** TOTAL PIUTANG CICILAN */
    $totalPiutang = 0;
    $queryInvoice = $conn->query("SELECT piutang.piutang_id, piutang.piutang_date, piutang.piutang_nominal, piutang.piutang_cabang
                                  FROM piutang 
                                  WHERE piutang_cabang = '".$sessionCabang."' && piutang_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPiutang += $rowProduct['piutang_nominal'];
    }
    /** END TOTAL PIUTANG CICILAN */

    /** TOTAL PIUTANG KEMBALIAN */
    $totalPiutangKembalian = 0;
    $queryInvoice = $conn->query("SELECT piutang_kembalian.pl_id, piutang_kembalian.pl_date, piutang_kembalian.pl_nominal, piutang_kembalian.pl_cabang
                                  FROM piutang_kembalian 
                                  WHERE pl_cabang = '".$sessionCabang."' && pl_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalPiutangKembalian += $rowProduct['pl_nominal'];
    }
    /** END TOTAL PIUTANG KEMBALIAN */

    // Piutang = Total Piutang - Total Piutang Kembalian
    $piutang = $totalPiutang - $totalPiutangKembalian;

    /** TOTAL HUTANG CICILAN */
    $totalHutang = 0;
    $queryInvoice = $conn->query("SELECT hutang.hutang_id, hutang.hutang_date, hutang.hutang_nominal, hutang.hutang_cabang
                                  FROM hutang 
                                  WHERE hutang_cabang = '".$sessionCabang."' && hutang_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalHutang += $rowProduct['hutang_nominal'];
    }
    /** END TOTAL HUTANG CICILAN */

    /** TOTAL HUTANG KEMBALIAN */
    $totalHutangKembalian = 0;
    $queryInvoice = $conn->query("SELECT hutang_kembalian.hl_id, hutang_kembalian.hl_date, hutang_kembalian.hl_nominal, hutang_kembalian.hl_cabang
                                  FROM hutang_kembalian 
                                  WHERE hl_cabang = '".$sessionCabang."' && hl_date BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
    while ($rowProduct = mysqli_fetch_array($queryInvoice)) {
      $totalHutangKembalian += $rowProduct['hl_nominal'];
    }
    /** END TOTAL KEMBALIAN */

    // Hutang = Total Hutang - Total Hutang Kembalian
    $hutang = $totalHutang - $totalHutangKembalian;
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
                      Laporan Detail Incomes/Expenses Periode <?= tanggal_indo($tanggal_awal); ?> - <?= tanggal_indo($tanggal_akhir); ?>
                  </div>
                  <table class="table">
                    <tbody>
                      <tr>
                        <th colspan="2" class="p-2">1. Pendapatan</th>
                      </tr>
                      <tr>
                        <td class="p-2" width="60%">a. Sub Total Penjualan</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($totalPenjualan, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-2" width="60%">b. Piutang (Cicilan)</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($piutang, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>

                      <?php
                          $total_pendapatan = 0;
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pendapatan' AND jenis='revenue' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_revenue = '';
                          $total_revenue = 0;
                          
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_revenue += $r_pend['real_income'];
                            $row_revenue .= '<tr>
                                                <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                  <div class="row">
                                                    <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                    <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                    <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                  </div>
                                                </td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                  <div class="row">
                                                    <div class="col-1">Rp</div>
                                                    <div class="col-11 text-right">'. number_format($r_pend['real_income'], 0, ',', '.') . '</div>
                                                  </div>
                                                </td>
                                             </tr>';
                          }

                          $total_pendapatan += $total_revenue;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_revenue .= '<tr>
                                                <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                            </tr>';
                          }
                        ?>
                      <tr>
                        <td class="p-2" width="60%">c. Revenue</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_revenue, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <?= $row_revenue ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pendapatan' AND jenis='pendapatan_lain' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_pendapatan_lain = '';
                          $total_pendapatan_lain = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_pendapatan_lain += $r_pend['real_income'];
                            $row_pendapatan_lain .= '<tr>
                                                        <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                          <div class="row">
                                                            <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                            <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                            <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                          </div>
                                                        </td>
                                                        <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                          <div class="row">
                                                            <div class="col-1">Rp</div>
                                                            <div class="col-11 text-right">'. number_format($r_pend['real_income'], 0, ',', '.') . '</div>
                                                          </div>
                                                        </td>
                                               
                                                        </tr>';
                          }

                          $total_pendapatan += $total_pendapatan_lain;

                          if(mysqli_num_rows($pend_query) > 0){
                            $row_pendapatan_lain .= '<tr>
                                                          <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                          <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                      </tr>';
                          }

                          $totalPendapatan = $totalPenjualan + $piutang + $total_pendapatan;
                      ?>
                      <tr>
                        <td class="p-2">d. Pendapatan Lain</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_pendapatan_lain, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <?= $row_pendapatan_lain ?>

                      <tr>
                        <td class="p-2"><b>Total Pendapatan</b></td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($totalPendapatan, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th colspan="2" class="p-2">2. HPP</th>
                      </tr>
                      <tr>
                        <td class="p-2">a. Harga Pokok Penjualan (HPP)</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($totalHpp, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-2"><b>Laba / Rugi Kotor</b></td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <?php $labaRugiKotor = $totalPendapatan - $totalHpp ?>
                            <div class="col-11 text-right"><?= number_format($labaRugiKotor, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th colspan="2" class="p-2">3. Pengeluaran</th>
                      </tr>

                      <?php
                          // $pend_query = mysqli_query($conn, "SELECT a.*, b.*, c.nama_karyawan FROM laba_bersih_detail as a
                          //                                     LEFT JOIN gaji_karyawan as b ON a.id = b.laba_bersih_detail_id
                          //                                     LEFT JOIN karyawan as c ON c.id = b.id_karyawan
                          //                                     WHERE a.incomes_expenses='pengeluaran' AND a.jenis='gaji_karyawan' AND a.cabang='$sessionCabang' AND MONTH(a.tanggal) BETWEEN '".$bulan_awal."' AND '".$bulan_akhir."' AND YEAR(a.tanggal) BETWEEN '".$tahun_awal."' AND '".$tahun_akhir."'");

                          $total_pengeluaran = 0;

                          $pend_query = mysqli_query($conn, "SELECT a.*, b.*, c.nama_karyawan FROM laba_bersih_detail as a
                                                              LEFT JOIN gaji_karyawan as b ON a.id = b.laba_bersih_detail_id
                                                              LEFT JOIN karyawan as c ON c.id = b.id_karyawan
                                                              WHERE a.incomes_expenses='pengeluaran' AND a.jenis='gaji_karyawan' AND a.cabang='$sessionCabang'
                                                              AND DATE(a.tanggal) BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");

                          $row_gaji_karyawan = '';
                          $total_gaji_karyawan = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_gaji_karyawan += $r_pend['total'];
                            $row_gaji_karyawan .= '<tr>
                                                      <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                        <div class="row">
                                                          <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                          <div class="col-8 pl-0">'. $r_pend['nama_karyawan'] . '</div>
                                                          <div class="col-3 pl-0 text-right">Periode '. $r_pend['periode'] . '</div>
                                                        </div>
                                                      </td>
                                                      <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                        <div class="row">
                                                          <div class="col-1">Rp</div>
                                                          <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                        </div>
                                                      </td>
                                                  </tr>';
                          }

                          $total_pengeluaran += $total_gaji_karyawan;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_gaji_karyawan .= '<tr>
                                                      <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                      <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                  </tr>';
                          }
                      ?>
                      
                      <tr>
                        <td class="p-2">a. Total Gaji Pegawai</td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_gaji_karyawan, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>

                      <?= $row_gaji_karyawan ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='listrik' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          
                          $row_listrik = '';
                          $total_listrik = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_listrik += $r_pend['total'];
                            $row_listrik .= '<tr>
                                                <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                  <div class="row">
                                                    <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                    <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                    <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                  </div>
                                                </td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                  <div class="row">
                                                    <div class="col-1">Rp</div>
                                                    <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                  </div>
                                                </td>
                                            </tr>';
                          }

                          $total_pengeluaran += $total_listrik;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_listrik .= '<tr>
                                                <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                             </tr>';
                          }
                      ?>
                      <tr>
                        <td class="p-2">
                          b. Biaya Listrik 1 Bulan
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_listrik, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_listrik ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='telepon_internet' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_internet = '';
                          $total_internet = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_internet += $r_pend['total'];
                            $row_internet .= '<tr>
                                                <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                  <div class="row">
                                                    <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                    <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                    <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                  </div>
                                                </td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                  <div class="row">
                                                    <div class="col-1">Rp</div>
                                                    <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                  </div>
                                                </td>
                                            </tr>';
                          }

                          $total_pengeluaran += $total_internet;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_internet .= '<tr>
                                                <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                              </tr>';
                          }
                      ?>

                      <tr>
                        <td class="p-2">
                          c. Telepon & Internet
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_internet, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_internet ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='perlengkapan_toko' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_perlengkapan_toko = '';
                          $total_perlengkapan_toko = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_perlengkapan_toko += $r_pend['total'];
                            $row_perlengkapan_toko .= '<tr>
                                                          <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                            <div class="row">
                                                              <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                              <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                              <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                            </div>
                                                          </td>
                                                          <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                            <div class="row">
                                                              <div class="col-1">Rp</div>
                                                              <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                            </div>
                                                          </td>
                                                      </tr>';
                          }

                          $total_pengeluaran += $total_perlengkapan_toko;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_perlengkapan_toko .= '<tr>
                                                          <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                          <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                      </tr>';
                          }
                      ?>
                      <tr>
                        <td class="p-2">
                          d. Perlengkapan Toko
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_perlengkapan_toko, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>

                      <?= $row_perlengkapan_toko ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='biaya_penyusutan' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_penyusutan = '';
                          $total_penyusutan = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_penyusutan += $r_pend['total'];
                            $row_penyusutan .= '<tr>
                                                    <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                      <div class="row">
                                                        <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                        <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                        <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                      </div>
                                                    </td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                      <div class="row">
                                                        <div class="col-1">Rp</div>
                                                        <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                      </div>
                                                    </td>
                                                </tr>';
                          }

                          $total_pengeluaran += $total_penyusutan;
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_penyusutan .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
                          }
                      ?>

                      <tr>
                        <td class="p-2">
                          e. Biaya Penyusutan
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_penyusutan, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_penyusutan ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='bensin' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_transport = '';
                          $total_transport = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_transport += $r_pend['total'];
                            $row_transport .= '<tr>
                                                    <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                      <div class="row">
                                                        <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                        <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                        <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                      </div>
                                                    </td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                      <div class="row">
                                                        <div class="col-1">Rp</div>
                                                        <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                      </div>
                                                    </td>
                                                </tr>';
                          }

                          $total_pengeluaran += $total_transport;
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_transport .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
                          }
                      ?>

                      <tr>
                        <td class="p-2">
                          f. Transportasi & Bensin
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_transport, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_transport ?>
                      
                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='tak_terduga' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_tak_terduga = '';
                          $total_tak_terduga = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_tak_terduga += $r_pend['total'];
                            $row_tak_terduga .= '<tr>
                                                    <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                      <div class="row">
                                                        <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                        <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                        <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                      </div>
                                                    </td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                      <div class="row">
                                                        <div class="col-1">Rp</div>
                                                        <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                      </div>
                                                    </td>
                                                </tr>';
                          }

                          $total_pengeluaran += $total_tak_terduga;

                          if(mysqli_num_rows($pend_query) > 0){
                            $row_tak_terduga .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
                          }
                      ?>

                      <tr>
                        <td class="p-2">
                          g. Biaya Tak Terduga
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_tak_terduga, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_tak_terduga ?>

                      <?php
                          $pend_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='lain_lain' AND cabang='$sessionCabang' AND tanggal BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");
                          $row_lain_lain = '';
                          $total_lain_lain = 0;
                          while($r_pend = mysqli_fetch_assoc($pend_query)){
                            $total_lain_lain += $r_pend['total'];
                            $row_lain_lain .= '<tr>
                                                    <td class="px-2" style="border-top:unset;border-bottom:unset">
                                                      <div class="row">
                                                        <div class="col-1 ml-2 mr-0 pr-0" style="flex: 0 0 3%;max-width: 3%;">-</div>
                                                        <div class="col-8 pl-0">'. $r_pend['nama'] . '</div>
                                                        <div class="col-3 pl-0 text-right">'. $r_pend['tanggal'] . '</div>
                                                      </div>
                                                    </td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-2">
                                                      <div class="row">
                                                        <div class="col-1">Rp</div>
                                                        <div class="col-11 text-right">'. number_format($r_pend['total'], 0, ',', '.') . '</div>
                                                      </div>
                                                    </td>
                                                </tr>';
                          }

                          $total_pengeluaran += $total_lain_lain;
                          $total_pengeluaran += $hutang;
                          
                          if(mysqli_num_rows($pend_query) > 0){
                            $row_lain_lain .= '<tr>
                                                  <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                  <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                               </tr>';
                          }
                      ?>

                      <tr>
                        <td class="p-2">
                          h. Pengeluaran Lain
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_lain_lain, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      
                      <?= $row_lain_lain ?>
                      <tr>
                        <td class="p-2">
                          i. Hutang (Cicilan)
                        </td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($hutang, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="p-2"><b>Total Pengeluaran</b></td>
                        <td class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($total_pengeluaran, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <?php
                            $labaBersih = $labaRugiKotor - $total_pengeluaran;
                        ?>
                        <th class="p-2">Laba Bersih</th>
                        <th class="p-2 text-bold">
                          <div class="row">
                            <div class="col-1">Rp</div>
                            <div class="col-11 text-right"><?= number_format($labaBersih, 0, ',', '.'); ?></div>
                          </div>
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