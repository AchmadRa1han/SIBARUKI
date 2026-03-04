<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportRtlh extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-rtlh';
    protected $description = 'Import RTLH lengkap termasuk kolom No di semua tabel';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // --- 1. PASTIKAN SKEMA ---
        CLI::write('Sinkronisasi skema No...', 'yellow');
        $tables = ['rtlh_penerima', 'rtlh_rumah', 'rtlh_kondisi_rumah'];
        foreach ($tables as $table) {
            if (!$db->fieldExists('No', $table)) {
                $forge->addColumn($table, ['No' => ['type' => 'INT', 'constraint' => 11, 'null' => true]]);
            }
        }
        $db->query("ALTER TABLE rtlh_penerima MODIFY jenis_kelamin VARCHAR(20) NULL");

        // --- 2. PREPARASI ---
        CLI::write('Menyiapkan referensi...', 'yellow');
        $refResults = $db->table('ref_master')->get()->getResultArray();
        $refMap = [];
        foreach ($refResults as $ref) { $refMap[$ref['kategori']][strtoupper(trim($ref['nama_pilihan']))] = $ref['id']; }

        $desaResults = $db->table('kode_desa')->get()->getResultArray();
        $desaMap = [];
        foreach ($desaResults as $d) { $desaMap[strtoupper(trim($d['desa_nama']))] = $d['desa_id']; }

        // --- 3. CLEAN ---
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) { $db->table($table)->truncate(); }
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        $filePath = WRITEPATH . 'repository/RTLH.csv';
        $file = fopen($filePath, 'r');
        for ($i=0; $i<5; $i++) fgetcsv($file, 0, ';');

        $count = 0;
        $db->transStart();

        $bulanIndo = [
            'JANUARI' => '01', 'FEBRUARI' => '02', 'MARET' => '03', 'APRIL' => '04', 'MEI' => '05', 'JUNI' => '06',
            'JULI' => '07', 'AGUSTUS' => '08', 'SEPTEMBER' => '09', 'OKTOBER' => '10', 'NOVEMBER' => '11', 'DESEMBER' => '12'
        ];

        while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
            if (empty($row[1]) || empty($row[3]) || !is_numeric($row[0])) continue;

            $noUrut = trim($row[0]);
            $nama = strtoupper(trim($row[1]));
            $nik = trim($row[3], " \t\n\r\0\x0B';");
            
            // Parsing TTL
            $ttlRaw = trim($row[4] ?? '');
            $tempat = ''; $tanggal = null;
            if (preg_match('/^(.*?)[,\s]+(\d{1,2}.*)$/', $ttlRaw, $matches)) {
                $tempat = strtoupper(trim($matches[1]));
                $dateStr = strtoupper(trim($matches[2]));
                foreach ($bulanIndo as $indo => $num) { $dateStr = str_ireplace($indo, $num, $dateStr); }
                $dateStr = preg_replace('/[^\d]+/', '-', $dateStr);
                $d = \DateTime::createFromFormat('d-m-Y', $dateStr) ?: \DateTime::createFromFormat('j-n-Y', $dateStr);
                if ($d) $tanggal = $d->format('Y-m-d');
            } else { $tempat = strtoupper($ttlRaw); }

            // Deteksi JK
            $jkRaw = strtoupper(trim($row[11] ?? ''));
            $jk = (stripos($jkRaw, 'PEREMPUAN') !== false || $jkRaw === 'P') ? 'PEREMPUAN' : 'LAKI-LAKI';
            if (empty($jkRaw)) {
                $femaleKeywords = ['FATMA', 'NUR', 'SITI', 'MARNI', 'WATI', 'INA', 'ANI', 'DARMA'];
                foreach ($femaleKeywords as $kw) { if (stripos($nama, $kw) !== false) { $jk = 'PEREMPUAN'; break; } }
            }

            // A. INSERT PENERIMA
            $db->table('rtlh_penerima')->insert([
                'No' => $noUrut,
                'nik' => $nik,
                'no_kk' => trim($row[2], " \t\n\r\0\x0B';"),
                'nama_kepala_keluarga' => $nama,
                'tempat_lahir' => $tempat,
                'tanggal_lahir' => $tanggal,
                'jenis_kelamin' => $jk,
                'pendidikan_id' => $refMap['PENDIDIKAN'][strtoupper(trim($row[10]))] ?? null,
                'pekerjaan_id' => $refMap['PEKERJAAN'][strtoupper(trim($row[12]))] ?? null,
                'penghasilan_per_bulan' => trim($row[13]),
                'jumlah_anggota_keluarga' => (int)$row[8]
            ]);

            // B. INSERT RUMAH
            $db->table('rtlh_rumah')->insert([
                'No' => $noUrut,
                'nik_pemilik' => $nik,
                'desa' => $row[6],
                'desa_id' => $desaMap[strtoupper(trim($row[6]))] ?? null,
                'alamat_detail' => trim($row[5]),
                'kepemilikan_rumah' => trim($row[14]),
                'aset_rumah_di_lokasi_lain' => trim($row[15]),
                'kepemilikan_tanah' => trim($row[16]),
                'sumber_penerangan' => trim($row[17]),
                'sumber_penerangan_detail' => trim($row[18] ?? ''),
                'bantuan_perumahan' => trim($row[19]),
                'jenis_kawasan' => trim($row[20]),
                'fungsi_ruang' => trim($row[21]),
                'luas_rumah_m2' => (float)str_replace(['M', ' '], '', $row[36]),
                'luas_lahan_m2' => (float)str_replace(['M', ' '], '', $row[37]),
                'jumlah_penghuni_jiwa' => (int)$row[38],
                'sumber_air_minum' => trim($row[39]),
                'jarak_sam_ke_tpa_tinja' => trim($row[40]),
                'kamar_mandi_dan_jamban' => trim($row[41]),
                'jenis_jamban_kloset' => trim($row[42]),
                'jenis_tpa_tinja' => trim($row[43])
            ]);

            $idSurvei = $db->insertID();

            // C. INSERT KONDISI
            $db->table('rtlh_kondisi_rumah')->insert([
                'No' => $noUrut,
                'id_survei' => $idSurvei,
                'st_pondasi' => $refMap['KONDISI'][strtoupper(trim($row[22]))] ?? null,
                'st_kolom' => $refMap['KONDISI'][strtoupper(trim($row[23]))] ?? null,
                'st_rangka_atap' => $refMap['KONDISI'][strtoupper(trim($row[24]))] ?? null,
                'st_plafon' => $refMap['KONDISI'][strtoupper(trim($row[25]))] ?? null,
                'st_balok' => $refMap['KONDISI'][strtoupper(trim($row[26]))] ?? null,
                'st_sloof' => $refMap['KONDISI'][strtoupper(trim($row[27]))] ?? null,
                'st_jendela' => $refMap['KONDISI'][strtoupper(trim($row[28]))] ?? null,
                'st_ventilasi' => $refMap['KONDISI'][strtoupper(trim($row[29]))] ?? null,
                'mat_lantai' => $refMap['MATERIAL_LANTAI'][strtoupper(trim($row[30]))] ?? null,
                'st_lantai' => $refMap['KONDISI'][strtoupper(trim($row[31]))] ?? null,
                'mat_dinding' => $refMap['MATERIAL_DINDING'][strtoupper(trim($row[32]))] ?? null,
                'st_dinding' => $refMap['KONDISI'][strtoupper(trim($row[33]))] ?? null,
                'mat_atap' => $refMap['MATERIAL_ATAP'][strtoupper(trim($row[34]))] ?? null,
                'st_atap' => $refMap['KONDISI'][strtoupper(trim($row[35]))] ?? null
            ]);

            $count++;
        }

        $db->transComplete();
        fclose($file);
        CLI::write("✅ Berhasil mengimpor $count data RTLH. Kolom No terisi di semua tabel.", 'green');
    }
}
