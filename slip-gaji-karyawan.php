<?php 
    include '_header.php';
    if ( $levelLogin !== "super admin") {
        echo "
        <script>
            document.location.href = 'bo';
        </script>
        ";
    }
  
    $toko = query("SELECT * FROM toko WHERE toko_cabang = $sessionCabang");
    foreach ( $toko as $row ) :
        $toko_nama = $row['toko_nama'];
        $toko_kota = $row['toko_kota'];
        $toko_tlpn = $row['toko_tlpn'];
        $toko_wa   = $row['toko_wa'];
        $toko_print= $row['toko_print'];
    endforeach;

    $id = base64_decode($_GET['id']);
    $query_gaji_karyawan = mysqli_query($conn, "SELECT * FROM gaji_karyawan a LEFT JOIN karyawan b ON a.id_karyawan = b.id WHERE a.id='$id'");
    $gaji_karyawan = mysqli_fetch_assoc($query_gaji_karyawan);

    if(!$gaji_karyawan){
        echo '<script>location.href="incomes-expenses?incomes-expenses=cGVuZ2VsdWFyYW4=&type=Z2FqaV9rYXJ5YXdhbg=="</script>';
    }
?>
<style>
    .table td, .table th {border: unset !important;padding:3px 7px}
    @media print {
        body {-webkit-print-color-adjust: exact;}
    }
</style>

<section class="laporan-laba-bersih">
    <div class="container">
        <div style="border: solid 2px #000">
            <table class="table" style="border:unset;border-bottom:solid 2px #000">
                <tbody>
                    <tr>
                        <td class="text-right" width="20%">
                            <img src="dist/img/bakmie-lana-logo-150px.jpg" alt="" style="height:100px;width:100px">
                        </td>
                        <td valign="center">
                            <div class="llb-header">
                                <div class="llb-header-parent" style="padding-top:5%;color:#1E476C">
                                    BAKMIE LANA
                                </div>
                            </div>
                        </td>
                        <td class="text-right" width="20%"></td>
                    </tr>
                </tbody>
            </table>

            <div class="laporan-laba-bersih-detail" style="padding-top: unset;">
                <table class="table" style="border:unset">
                    <tbody>
                        <tr>
                            <td style="width:30%">PERIODE</td>
                            <td>:</td>
                            <td colspan="2" class="text-right"><?= date('M-y', strtotime($gaji_karyawan['periode'])) ?></td>
                            <td style="width:20%"></td>
                        </tr>
                        <tr>
                            <td>NAME</td>
                            <td>:</td>
                            <td colspan="4"><?= $gaji_karyawan['nama_karyawan'] ?></td>
                        </tr>
                        <tr>
                            <td>POSITION</td>
                            <td>:</td>
                            <td colspan="4"><?= $gaji_karyawan['jabatan'] ?></td>
                        </tr>
                        <tr>
                            <td>STATUS</td>
                            <td>:</td>
                            <td colspan="4"><?= mb_substr($gaji_karyawan['status'], 0, 1) ?></td>
                        </tr>
                        <tr><td colspan="5">&nbsp;</td></tr>
                        <tr>
                            <th colspan="5" style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px">SALARY</th>
                        </tr>
                        <tr>
                            <td>DAY</td>
                            <td style="width:1%">:</td>
                            <td style="width:1%"></td>
                            <td class="text-right"><?= $gaji_karyawan['day'] ?></td>
                            <td style="width:20%"></td>
                        </tr>
                        <tr>
                            <td>TOTAL SALARY</td>
                            <td>:</td>
                            <td>Rp</td>
                            <td class="text-right"><?= number_format($gaji_karyawan['salary']) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>KADEUDEUH</td>
                            <td>:</td>
                            <td>Rp</td>
                            <td class="text-right"><?= number_format($gaji_karyawan['kddh']) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>BONUS OMSET</td>
                            <td>:</td>
                            <td>Rp</td>
                            <td class="text-right"><?= number_format($gaji_karyawan['bonus_omset']) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="5" style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px">OVERTIME</th>
                        </tr>
                        <tr>
                            <td style="padding-left:20px">KILOGRAM</td>
                            <td>:</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td>:</td>
                            <td>Rp</td>
                            <td class="text-right"><?= number_format($gaji_karyawan['overtime']) ?></td>
                            <td></td>
                        </tr>
                        <tr><td colspan="4">&nbsp;</td></tr>
                        <tr>
                            <td>ROUNDED</td>
                            <td>:</td>
                            <td>Rp</td>
                            <td class="text-right">0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px;"><i>TAKE HOME PAY</i></th>
                            <th style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px"></th>
                            <th style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px"><i>Rp</i></th>
                            <th style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px" class="text-right"><i><?= number_format($gaji_karyawan['thp']) ?></i></th>
                            <td style="border:unset; background-color: #B3CCE7 !important;padding:3px 5px"></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                * Terimakasih sudah membantu bakmie lana
                <br>
                <br>
                <table class="table" style="border:unset">
                    <tr>
                        <td class="text-center">PARTNER</td>
                        <td class="text-center">MANAGEMENT</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 30px;">&nbsp;</td>
                        <td></td>
                    </tr>
                        <td class="text-center"><?= $gaji_karyawan['nama_karyawan'] ?></td>
                        <td class="text-center">Ayam Hainan</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>


</body>
</html>
<script>
  window.print(); 
</script>