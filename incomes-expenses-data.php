<?php
include '_header-artibut.php';

if($_POST && $_SESSION['user_id'] != null){
    header('Content-Type: application/json; charset=utf-8');
    $cabang = $sessionCabang;
    $date_now = date('Y-m-d');
    $date_filter = isset($_POST['date']) ? $_POST['date'] : $date_now;
    $start_date = isset($_POST['start_date']) ? date('Y-m-d', strtotime($_POST['start_date'])) : $date_now;
    $end_date = isset($_POST['end_date']) ? date('Y-m-d', strtotime($_POST['end_date'])) : $date_now;
    $start_month_only_filter = date('m', strtotime($start_date));
    $end_month_only_filter = date('m', strtotime($end_date));
    $start_year_only_filter = date('Y', strtotime($start_date));
    $end_year_only_filter = date('Y', strtotime($end_date));
    $incomes_expenses_decode = $_POST['incomes-expenses'] ? base64_decode($_POST['incomes-expenses']) : 'pendapatan';
    $jenis_decode = $_POST['type'] ? base64_decode($_POST['type']) : 'revenue';

    if(!isset($_POST['id'])){
        $arr_data = [];
        $grand_total = 0;

        if($incomes_expenses_decode == 'pengeluaran' && $jenis_decode == 'gaji_karyawan'){
        //     $incomes_expenses_query = mysqli_query($conn, "SELECT a.*, b.total, b.id as laba, c.nama_karyawan FROM gaji_karyawan as a
        //                                                     LEFT JOIN laba_bersih_detail as b ON a.laba_bersih_detail_id = b.id
        //                                                     LEFT JOIN karyawan as c ON c.id = a.id_karyawan
        //                                                     WHERE b.incomes_expenses='pengeluaran' AND b.jenis='gaji_karyawan' AND b.cabang='$cabang'
        //                                                     AND MONTH(b.tanggal) >= '$start_month_only_filter' AND MONTH(b.tanggal) <= '$end_month_only_filter'
        //                                                     AND YEAR(b.tanggal) >= '$start_year_only_filter' AND b.cabang='$cabang' AND YEAR(b.tanggal) <= '$end_year_only_filter'");
            $incomes_expenses_query = mysqli_query($conn, "SELECT a.*, b.tanggal ,b.total, b.created_at as created_at, b.jenis_pembayaran as jenis_pembayaran, b.id as laba, c.nama_karyawan FROM gaji_karyawan as a
                                                            LEFT JOIN laba_bersih_detail as b ON a.laba_bersih_detail_id = b.id
                                                            LEFT JOIN karyawan as c ON c.id = a.id_karyawan
                                                            WHERE b.incomes_expenses='pengeluaran' AND b.jenis='gaji_karyawan' AND b.cabang='$cabang'
                                                            AND DATE(b.tanggal) >= '$start_date' AND DATE(b.tanggal) <= '$end_date'
                                                            ");

            while($row = mysqli_fetch_assoc($incomes_expenses_query)){
                $arr_data[] = [
                    'id' => base64_encode($row['laba']),
                    'gk' => base64_encode($row['id']),
                    'tanggal' => $row['tanggal'],
                    'nama' => $row['nama_karyawan'],
                    'day' => $row['day'],
                    'kddh' => $row['kddh'],
                    'kddh_idr_format' => 'Rp. ' . number_format($row['kddh']),
                    'salary' => $row['salary'],
                    'salary_idr_format' => 'Rp. ' . number_format($row['salary']),
                    'bonus_omset' => $row['bonus_omset'],
                    'bonus_omset_idr_format' => 'Rp. ' . number_format($row['bonus_omset']),
                    'overtime' => $row['overtime'],
                    'overtime_idr_format' => 'Rp. ' . number_format($row['overtime']),
                    'total' => $row['total'],
                    'payment_type' => $row['jenis_pembayaran'],
                    'total_idr_format' => 'Rp. ' . number_format($row['total'])
                ];
                $grand_total += $row['total'];
            }

        } elseif($incomes_expenses_decode == 'pendapatan'){
            $incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pendapatan' AND jenis='$jenis_decode' AND cabang='$cabang' AND tanggal>='$start_date' AND tanggal<='$end_date'");
            while($row = mysqli_fetch_assoc($incomes_expenses_query)){
                $arr_data[] = [
                    'id' => base64_encode($row['id']),
                    'nama' => $row['nama'],
                    'tanggal' => $row['tanggal'],
                    'total' => $row['total'],
                    'payment_type' => $row['jenis_pembayaran'],
                    'total_idr_format' => 'Rp. ' . number_format($row['total']),
                    'real_income' => $row['real_income'],
                    'real_income_idr_format' => 'Rp. ' . number_format($row['real_income'])
                ];
                $grand_total += $row['real_income'];
            }
        } else {
            $incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='$jenis_decode' AND cabang='$cabang' AND tanggal>='$start_date' AND tanggal<='$end_date'");
            while($row = mysqli_fetch_assoc($incomes_expenses_query)){
                $arr_data[] = [
                    'id' => base64_encode($row['id']),
                    'nama' => $row['nama'],
                    'tanggal' => $row['tanggal'],
                    'total' => $row['total'],
                    'payment_type' => $row['jenis_pembayaran'],
                    'qty' => $row['qty'],
                    'total_idr_format' => 'Rp. ' . number_format($row['total']),
                    'harga' => $row['harga'],
                    'harga_idr_format' => 'Rp. ' . number_format($row['harga'])
                ];
                $grand_total += $row['total'];
            }
        }

        echo json_encode(['success' => true,'grand_total' => $grand_total, 'grand_total_idr_format' => 'Rp. ' . number_format($grand_total), 'data' => $arr_data]);
    } else {
        $id = base64_decode($_POST['id']);
        $success = false;
        $message = 'Data tidak ditemukan';
        $data = null;

        if($incomes_expenses_decode == 'pengeluaran' && $jenis_decode == 'gaji_karyawan'){
            $incomes_expenses_query = mysqli_query($conn, "SELECT a.*, b.tanggal, b.total, b.id as laba, b.jenis_pembayaran, b.harga, b.qty, b.total, b.real_income, c.nama_karyawan, c.id as id_karyawan FROM gaji_karyawan as a
                                                            LEFT JOIN laba_bersih_detail as b ON a.laba_bersih_detail_id = b.id
                                                            LEFT JOIN karyawan as c ON c.id = a.id_karyawan
                                                            WHERE b.incomes_expenses='pengeluaran' AND b.jenis='gaji_karyawan' AND b.cabang='$cabang' AND b.id='$id'");

            
        } elseif($incomes_expenses_decode == 'pendapatan'){
            $incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pendapatan' AND jenis='$jenis_decode' AND cabang='$cabang' AND id='$id'");
        } else {
            $incomes_expenses_query = mysqli_query($conn, "SELECT * FROM laba_bersih_detail WHERE incomes_expenses='pengeluaran' AND jenis='$jenis_decode' AND cabang='$cabang' AND id='$id'");
        }

        if(mysqli_num_rows($incomes_expenses_query) > 0){
            $success = true;
            $message = 'Data ditemukan';
            $data = mysqli_fetch_assoc($incomes_expenses_query);
            $data['jenis_pembayaran'] = base64_encode($data['jenis_pembayaran']);
            $data['id'] = base64_encode($data['id']);
            $data['total'] = number_format($data['total'], 0, ',', '');
            $data['harga'] = number_format($data['harga'], 0, ',', '');
            $data['qty'] = $data['qty'];
            $data['real_income'] = number_format($data['real_income'], 0, ',', '');

            if($jenis_decode == 'perlengkapan_toko'){
                $penambahan_aset_query = mysqli_query($conn, "SELECT * FROM penambahan_aset WHERE id_laba_bersih_detail='$id' AND cabang='$cabang'");
                $row_aset = [];
                while($row = mysqli_fetch_assoc($penambahan_aset_query)){
                    $row_aset[] = [
                        'nama_barang' => $row['nama_barang'],
                        'harga' => $row['harga'],
                        'qty' => $row['qty']
                    ];
                }
                $data['aset'] = $row_aset;
            }

            unset($data['cabang']);
            unset($data['incomes_expenses']);
            unset($data['jenis']);
            
            if($incomes_expenses_decode == 'pengeluaran' && $jenis_decode == 'gaji_karyawan'){
                $data['kddh'] = number_format($data['kddh'], 0, ',', '');
                $data['salary'] = number_format($data['salary'], 0, ',', '');
                $data['bonus_omset'] = number_format($data['bonus_omset'], 0, ',', '');
                $data['overtime'] = number_format($data['overtime'], 0, ',', '');
                $data['thp'] = number_format($data['thp'], 0, ',', '');
                $data['day'] = $data['day'];
                $data['periode'] = $data['periode'];
                $data['tanggal'] = $data['tanggal'];
                $data['karyawan'] = base64_encode($data['id_karyawan']);
                $data['id'] = base64_encode($data['laba']);
                unset($data['id_karyawan']);
            }

        }

        echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
    }
} else {

    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['status' => 401, 'message' => 'Unauthorized']);
    exit;
}

?>