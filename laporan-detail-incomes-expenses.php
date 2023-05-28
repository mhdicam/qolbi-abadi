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

<style>
  .table tr th, .table tr td {
    padding: 0;
  }
</style>

<?php  
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
  $bulan_awal = date('m', strtotime($tanggal_awal));
  $bulan_akhir = date('m', strtotime($tanggal_akhir));
  $tahun_awal = date('Y', strtotime($tanggal_awal));
  $tahun_akhir = date('Y', strtotime($tanggal_akhir));
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
                          
                          $row_revenue .= '<tr>
                                              <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                              <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                           </tr>';
                      ?>
                      <tr>
                        <td class="p-2" width="60%">a. Revenue</td>
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

                          $row_pendapatan_lain .= '<tr>
                                                        <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                        <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                    </tr>';
                      ?>
                      <tr>
                        <td class="p-2">b. Pendapatan Lain</td>
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
                            <div class="col-11 text-right"><?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th colspan="2" class="p-2">2. Pengeluaran</th>
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
                                                              WHERE a.incomes_expenses='pengeluaran' AND a.jenis='gaji_karyawan' AND a.cabang='$sessionCabang' AND a.created_at BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."'");

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
                          $row_gaji_karyawan .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
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
                          $row_listrik .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
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
                          $row_internet .= '<tr>
                                                    <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                    <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                </tr>';
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
                          $row_perlengkapan_toko .= '<tr>
                                                        <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                        <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                                    </tr>';
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
                          $row_penyusutan .= '<tr>
                                                  <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                  <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                              </tr>';
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
                          $row_transport .= '<tr>
                                                  <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                  <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                              </tr>';
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
                          $row_tak_terduga .= '<tr>
                                                  <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                  <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                              </tr>';
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
                          $row_lain_lain .= '<tr>
                                                  <td class="px-1" style="border-top:unset;border-bottom:unset">&nbsp;</td>
                                                  <td style="border-top:unset;border-bottom:unset" class="px-1">&nbsp;</td>
                                              </tr>';
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
                            $labaBersih = $total_pendapatan - $total_pengeluaran;
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