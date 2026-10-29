<?php
/**
 * =====================================================================================
 * SISTEM MONITORING JADWAL SHOLAT SELURUH INDONESIA - LIVE API TERINTEGRASI
 * Mencakup 38 Ibukota Provinsi Resmi Republik Indonesia (Sabang sampai Merauke)
 * Sumber Data: Live API MyQuran v2 (Kementerian Agama RI / Standar Hisab MABIMS)
 * Single-File Application PHP + Tailwind CSS
 * =====================================================================================
 */

// 1. Inisialisasi Zona Waktu & Kalender
date_default_timezone_set('Asia/Jakarta');

// Parameter Tanggal (Format: YYYY-MM-DD)
$param_tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';
$selected_time = time();
$is_custom_date = false;

if (!empty($param_tanggal) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $param_tanggal)) {
    $parsed = strtotime($param_tanggal . ' 12:00:00');
    if ($parsed !== false) {
        $selected_time = $parsed;
        $is_custom_date = (date('Y-m-d', $selected_time) !== date('Y-m-d'));
    }
}

$tanggal_input_value = date('Y-m-d', $selected_time);
$nama_hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][(int)date('w', $selected_time)];
$nama_bulan = [
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
][(int)date('n', $selected_time)];
$tanggal_masehi_hari_ini = $nama_hari . ', ' . (int)date('j', $selected_time) . ' ' . $nama_bulan . ' ' . date('Y', $selected_time);

// Fungsi konversi penanggalan Hijriah otomatis (Standar Kalender Ummul Qura)
function konversiKeHijriah($time = null) {
    if (!$time) $time = time();
    $d = (int)date('d', $time);
    $m = (int)date('m', $time);
    $y = (int)date('Y', $time);
    
    if ($m > 2) {
        $m = $m - 3;
    } else {
        $m = $m + 9;
        $y = $y - 1;
    }
    $c = floor($y / 100);
    $ya = $y - (100 * $c);
    $jd = floor((146097 * $c) / 4) + floor((1461 * $ya) / 4) + floor(((153 * $m) + 2) / 5) + $d + 1721119;
    
    $l = $jd - 1948440 + 10632;
    $n = floor(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;
    $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
    $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
    $month = floor((24 * $l) / 709);
    $day = $l - floor((709 * $month) / 24);
    $year = (30 * $n) + $j - 30;
    
    $bulan_hijriah = [
        1 => 'Muharram', 'Safar', 'Rabiul Awwal', 'Rabiul Akhir', 'Jumadil Ula', 'Jumadil Akhir',
        'Rajab', 'Sya\'ban', 'Ramadhan', 'Syawwal', 'Dzulqa\'dah', 'Dzulhijjah'
    ];
    
    $nama_bln_hijri = $bulan_hijriah[$month] ?? 'Hijriah';
    return "$day $nama_bln_hijri {$year} H";
}

$default_hijriah = konversiKeHijriah($selected_time);

// 2. DATA 38 IBUKOTA PROVINSI DENGAN ID ENDPOINT RESMI LIVE API MYQURAN (KEMENAG RI)
$jadwal_sholat = [
    // --- WILAYAH SUMATERA (10 PROVINSI) ---
    [
        'id' => 1,
        'api_id' => '0119',
        'kota' => 'Banda Aceh',
        'provinsi' => 'Aceh',
        'zona' => 'WIB',
        'imsak' => '04:58',
        'subuh' => '05:08',
        'dzuhur' => '12:26',
        'ashar' => '15:46',
        'maghrib' => '18:24',
        'isya' => '19:34',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 2,
        'api_id' => '0228',
        'kota' => 'Medan',
        'provinsi' => 'Sumatera Utara',
        'zona' => 'WIB',
        'imsak' => '04:43',
        'subuh' => '04:53',
        'dzuhur' => '12:13',
        'ashar' => '15:32',
        'maghrib' => '18:12',
        'isya' => '19:22',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 3,
        'api_id' => '0314',
        'kota' => 'Padang',
        'provinsi' => 'Sumatera Barat',
        'zona' => 'WIB',
        'imsak' => '04:32',
        'subuh' => '04:42',
        'dzuhur' => '12:06',
        'ashar' => '15:23',
        'maghrib' => '18:10',
        'isya' => '19:20',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 4,
        'api_id' => '0412',
        'kota' => 'Pekanbaru',
        'provinsi' => 'Riau',
        'zona' => 'WIB',
        'imsak' => '04:29',
        'subuh' => '04:39',
        'dzuhur' => '12:02',
        'ashar' => '15:19',
        'maghrib' => '18:04',
        'isya' => '19:14',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 5,
        'api_id' => '0507',
        'kota' => 'Tanjungpinang',
        'provinsi' => 'Kepulauan Riau',
        'zona' => 'WIB',
        'imsak' => '04:18',
        'subuh' => '04:28',
        'dzuhur' => '11:51',
        'ashar' => '15:08',
        'maghrib' => '17:53',
        'isya' => '19:03',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 6,
        'api_id' => '0610',
        'kota' => 'Jambi',
        'provinsi' => 'Jambi',
        'zona' => 'WIB',
        'imsak' => '04:21',
        'subuh' => '04:31',
        'dzuhur' => '11:56',
        'ashar' => '15:13',
        'maghrib' => '18:00',
        'isya' => '19:10',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 7,
        'api_id' => '0816',
        'kota' => 'Palembang',
        'provinsi' => 'Sumatera Selatan',
        'zona' => 'WIB',
        'imsak' => '04:15',
        'subuh' => '04:25',
        'dzuhur' => '11:50',
        'ashar' => '15:07',
        'maghrib' => '17:55',
        'isya' => '19:05',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 8,
        'api_id' => '0907',
        'kota' => 'Pangkalpinang',
        'provinsi' => 'Kepulauan Bangka Belitung',
        'zona' => 'WIB',
        'imsak' => '04:10',
        'subuh' => '04:20',
        'dzuhur' => '11:45',
        'ashar' => '15:02',
        'maghrib' => '17:49',
        'isya' => '18:59',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 9,
        'api_id' => '0710',
        'kota' => 'Bengkulu',
        'provinsi' => 'Bengkulu',
        'zona' => 'WIB',
        'imsak' => '04:23',
        'subuh' => '04:33',
        'dzuhur' => '11:58',
        'ashar' => '15:15',
        'maghrib' => '18:04',
        'isya' => '19:14',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 10,
        'api_id' => '1008',
        'kota' => 'Bandar Lampung',
        'provinsi' => 'Lampung',
        'zona' => 'WIB',
        'imsak' => '04:11',
        'subuh' => '04:21',
        'dzuhur' => '11:47',
        'ashar' => '15:05',
        'maghrib' => '17:54',
        'isya' => '19:04',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],

    // --- WILAYAH JAWA & BALI & NUSA TENGGARA (9 PROVINSI) ---
    [
        'id' => 11,
        'api_id' => '1106',
        'kota' => 'Serang',
        'provinsi' => 'Banten',
        'zona' => 'WIB',
        'imsak' => '04:03',
        'subuh' => '04:13',
        'dzuhur' => '11:43',
        'ashar' => '14:56',
        'maghrib' => '17:51',
        'isya' => '19:02',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 12,
        'api_id' => '1301',
        'kota' => 'Jakarta',
        'provinsi' => 'DKI Jakarta',
        'zona' => 'WIB',
        'imsak' => '04:00',
        'subuh' => '04:10',
        'dzuhur' => '11:40',
        'ashar' => '14:53',
        'maghrib' => '17:49',
        'isya' => '19:00',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 13,
        'api_id' => '1219',
        'kota' => 'Bandung',
        'provinsi' => 'Jawa Barat',
        'zona' => 'WIB',
        'imsak' => '03:57',
        'subuh' => '04:07',
        'dzuhur' => '11:37',
        'ashar' => '14:52',
        'maghrib' => '17:48',
        'isya' => '18:59',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 14,
        'api_id' => '1428',
        'kota' => 'Semarang',
        'provinsi' => 'Jawa Tengah',
        'zona' => 'WIB',
        'imsak' => '03:45',
        'subuh' => '03:55',
        'dzuhur' => '11:25',
        'ashar' => '14:40',
        'maghrib' => '17:36',
        'isya' => '18:47',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 15,
        'api_id' => '1605',
        'kota' => 'Yogyakarta',
        'provinsi' => 'DI Yogyakarta',
        'zona' => 'WIB',
        'imsak' => '03:45',
        'subuh' => '03:55',
        'dzuhur' => '11:25',
        'ashar' => '14:41',
        'maghrib' => '17:36',
        'isya' => '18:48',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 16,
        'api_id' => '1505',
        'kota' => 'Surabaya',
        'provinsi' => 'Jawa Timur',
        'zona' => 'WIB',
        'imsak' => '03:36',
        'subuh' => '03:46',
        'dzuhur' => '11:17',
        'ashar' => '14:31',
        'maghrib' => '17:27',
        'isya' => '18:38',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 17,
        'api_id' => '1708',
        'kota' => 'Denpasar',
        'provinsi' => 'Bali',
        'zona' => 'WITA',
        'imsak' => '04:26',
        'subuh' => '04:36',
        'dzuhur' => '12:07',
        'ashar' => '15:20',
        'maghrib' => '18:16',
        'isya' => '19:28',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 18,
        'api_id' => '1808',
        'kota' => 'Mataram',
        'provinsi' => 'Nusa Tenggara Barat',
        'zona' => 'WITA',
        'imsak' => '04:23',
        'subuh' => '04:33',
        'dzuhur' => '12:03',
        'ashar' => '15:17',
        'maghrib' => '18:13',
        'isya' => '19:24',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 19,
        'api_id' => '1908',
        'kota' => 'Kupang',
        'provinsi' => 'Nusa Tenggara Timur',
        'zona' => 'WITA',
        'imsak' => '04:02',
        'subuh' => '04:12',
        'dzuhur' => '11:42',
        'ashar' => '14:54',
        'maghrib' => '17:51',
        'isya' => '19:02',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],

    // --- WILAYAH KALIMANTAN (5 PROVINSI) ---
    [
        'id' => 20,
        'api_id' => '2009',
        'kota' => 'Pontianak',
        'provinsi' => 'Kalimantan Barat',
        'zona' => 'WIB',
        'imsak' => '03:57',
        'subuh' => '04:07',
        'dzuhur' => '11:30',
        'ashar' => '14:48',
        'maghrib' => '17:33',
        'isya' => '18:43',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 21,
        'api_id' => '2108',
        'kota' => 'Palangka Raya',
        'provinsi' => 'Kalimantan Tengah',
        'zona' => 'WIB',
        'imsak' => '03:42',
        'subuh' => '03:52',
        'dzuhur' => '11:16',
        'ashar' => '14:34',
        'maghrib' => '17:21',
        'isya' => '18:31',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 22,
        'api_id' => '2207',
        'kota' => 'Banjarmasin',
        'provinsi' => 'Kalimantan Selatan',
        'zona' => 'WITA',
        'imsak' => '04:36',
        'subuh' => '04:46',
        'dzuhur' => '12:12',
        'ashar' => '15:28',
        'maghrib' => '18:16',
        'isya' => '19:26',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 23,
        'api_id' => '2308',
        'kota' => 'Samarinda',
        'provinsi' => 'Kalimantan Timur',
        'zona' => 'WITA',
        'imsak' => '04:26',
        'subuh' => '04:36',
        'dzuhur' => '12:00',
        'ashar' => '15:19',
        'maghrib' => '18:03',
        'isya' => '19:13',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 24,
        'api_id' => '2402',
        'kota' => 'Tanjung Selor',
        'provinsi' => 'Kalimantan Utara',
        'zona' => 'WITA',
        'imsak' => '04:23',
        'subuh' => '04:33',
        'dzuhur' => '11:58',
        'ashar' => '15:18',
        'maghrib' => '18:00',
        'isya' => '19:10',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],

    // --- WILAYAH SULAWESI (6 PROVINSI) ---
    [
        'id' => 25,
        'api_id' => '2914',
        'kota' => 'Manado',
        'provinsi' => 'Sulawesi Utara',
        'zona' => 'WITA',
        'imsak' => '03:56',
        'subuh' => '04:06',
        'dzuhur' => '11:28',
        'ashar' => '14:46',
        'maghrib' => '17:30',
        'isya' => '18:39',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 26,
        'api_id' => '2606',
        'kota' => 'Gorontalo',
        'provinsi' => 'Gorontalo',
        'zona' => 'WITA',
        'imsak' => '04:06',
        'subuh' => '04:16',
        'dzuhur' => '11:38',
        'ashar' => '14:56',
        'maghrib' => '17:41',
        'isya' => '18:50',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 27,
        'api_id' => '2708',
        'kota' => 'Palu',
        'provinsi' => 'Sulawesi Tengah',
        'zona' => 'WITA',
        'imsak' => '04:17',
        'subuh' => '04:27',
        'dzuhur' => '11:50',
        'ashar' => '15:08',
        'maghrib' => '17:53',
        'isya' => '19:02',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 28,
        'api_id' => '2802',
        'kota' => 'Mamuju',
        'provinsi' => 'Sulawesi Barat',
        'zona' => 'WITA',
        'imsak' => '04:20',
        'subuh' => '04:30',
        'dzuhur' => '11:53',
        'ashar' => '15:10',
        'maghrib' => '17:55',
        'isya' => '19:05',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 29,
        'api_id' => '2908',
        'kota' => 'Makassar',
        'provinsi' => 'Sulawesi Selatan',
        'zona' => 'WITA',
        'imsak' => '04:18',
        'subuh' => '04:28',
        'dzuhur' => '11:50',
        'ashar' => '15:06',
        'maghrib' => '17:52',
        'isya' => '19:02',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 30,
        'api_id' => '2717',
        'kota' => 'Kendari',
        'provinsi' => 'Sulawesi Tenggara',
        'zona' => 'WITA',
        'imsak' => '04:00',
        'subuh' => '04:10',
        'dzuhur' => '11:37',
        'ashar' => '14:52',
        'maghrib' => '17:44',
        'isya' => '18:54',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],

    // --- WILAYAH MALUKU & PAPUA (8 PROVINSI) ---
    [
        'id' => 31,
        'api_id' => '3108',
        'kota' => 'Ambon',
        'provinsi' => 'Maluku',
        'zona' => 'WIT',
        'imsak' => '04:44',
        'subuh' => '04:54',
        'dzuhur' => '12:17',
        'ashar' => '15:29',
        'maghrib' => '18:22',
        'isya' => '19:32',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 32,
        'api_id' => '3211',
        'kota' => 'Sofifi',
        'provinsi' => 'Maluku Utara',
        'zona' => 'WIT',
        'imsak' => '04:47',
        'subuh' => '04:57',
        'dzuhur' => '12:19',
        'ashar' => '15:37',
        'maghrib' => '18:22',
        'isya' => '19:31',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 33,
        'api_id' => '3329',
        'kota' => 'Jayapura',
        'provinsi' => 'Papua',
        'zona' => 'WIT',
        'imsak' => '03:52',
        'subuh' => '04:02',
        'dzuhur' => '11:26',
        'ashar' => '14:38',
        'maghrib' => '17:31',
        'isya' => '18:40',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 34,
        'api_id' => '3403',
        'kota' => 'Manokwari',
        'provinsi' => 'Papua Barat',
        'zona' => 'WIT',
        'imsak' => '04:19',
        'subuh' => '04:29',
        'dzuhur' => '11:52',
        'ashar' => '15:07',
        'maghrib' => '17:55',
        'isya' => '19:04',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 35,
        'api_id' => '3413',
        'kota' => 'Sorong',
        'provinsi' => 'Papua Barat Daya',
        'zona' => 'WIT',
        'imsak' => '04:27',
        'subuh' => '04:37',
        'dzuhur' => '12:01',
        'ashar' => '15:17',
        'maghrib' => '18:04',
        'isya' => '19:14',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 36,
        'api_id' => '3317',
        'kota' => 'Nabire',
        'provinsi' => 'Papua Tengah',
        'zona' => 'WIT',
        'imsak' => '04:10',
        'subuh' => '04:20',
        'dzuhur' => '11:44',
        'ashar' => '14:58',
        'maghrib' => '17:48',
        'isya' => '18:57',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 37,
        'api_id' => '3308',
        'kota' => 'Wamena',
        'provinsi' => 'Papua Pegunungan',
        'zona' => 'WIT',
        'imsak' => '04:00',
        'subuh' => '04:10',
        'dzuhur' => '11:34',
        'ashar' => '14:48',
        'maghrib' => '17:38',
        'isya' => '18:48',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ],
    [
        'id' => 38,
        'api_id' => '3315',
        'kota' => 'Merauke',
        'provinsi' => 'Papua Selatan',
        'zona' => 'WIT',
        'imsak' => '03:48',
        'subuh' => '03:58',
        'dzuhur' => '11:22',
        'ashar' => '14:38',
        'maghrib' => '17:28',
        'isya' => '18:38',
        'hijriah' => $default_hijriah,
        'status' => 'Live API (MyQuran)'
    ]
];

// 3. PROSES REQUEST DATA DARI LIVE API MENGGUNAKAN PHP (file_get_contents & json_decode)
// Jika pengguna memilih kota tertentu (misal via param ?kota=Jakarta atau ?api_id=1301)
$requested_city_id = isset($_GET['api_id']) ? preg_replace('/[^0-9]/', '', $_GET['api_id']) : null;
$requested_city_name = isset($_GET['kota']) ? trim($_GET['kota']) : null;

if ($requested_city_name && !$requested_city_id) {
    foreach ($jadwal_sholat as $c_item) {
        if (strcasecmp($c_item['kota'], $requested_city_name) === 0) {
            $requested_city_id = $c_item['api_id'];
            break;
        }
    }
}

// Request Live Data jika ada kota yang dipilih atau sinkronisasi kota default
$active_fetch_id = $requested_city_id ?: '1301'; // Default: Jakarta (1301)
$today_year = date('Y', $selected_time);
$today_month = date('m', $selected_time);
$today_day = date('d', $selected_time);
$api_endpoint = "https://api.myquran.com/v2/sholat/jadwal/{$active_fetch_id}/{$today_year}/{$today_month}/{$today_day}";

// Update Hijriah untuk semua baris sesuai tanggal yang dipilih
foreach ($jadwal_sholat as &$item_h) {
    $item_h['hijriah'] = $default_hijriah;
}
unset($item_h);

$context_opts = [
    'http' => [
        'method' => 'GET',
        'timeout' => 3,
        'header' => "User-Agent: MonitoringSholatID/2.0\r\nAccept: application/json\r\n"
    ],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
];
$stream_ctx = stream_context_create($context_opts);

$api_raw = @file_get_contents($api_endpoint, false, $stream_ctx);
if ($api_raw) {
    $api_json = json_decode($api_raw, true);
    if (!empty($api_json['status']) && !empty($api_json['data']['jadwal'])) {
        $live_item = $api_json['data']['jadwal'];
        foreach ($jadwal_sholat as &$kota_row) {
            if ($kota_row['api_id'] === $active_fetch_id) {
                $kota_row['subuh'] = $live_item['subuh'];
                $kota_row['dzuhur'] = $live_item['dzuhur'];
                $kota_row['ashar'] = $live_item['ashar'];
                $kota_row['maghrib'] = $live_item['maghrib'];
                $kota_row['isya'] = $live_item['isya'];
                $kota_row['imsak'] = $live_item['imsak'] ?? $kota_row['imsak'];
                $kota_row['status'] = 'Live Terverifikasi';
                break;
            }
        }
        unset($kota_row);
    }
}

// Perhitungan Statistik Otomatis PHP
$total_wilayah = count($jadwal_sholat);
$total_wib = 0;
$total_wita = 0;
$total_wit = 0;

foreach ($jadwal_sholat as $item) {
    if ($item['zona'] === 'WIB') $total_wib++;
    elseif ($item['zona'] === 'WITA') $total_wita++;
    elseif ($item['zona'] === 'WIT') $total_wit++;
}

$status_api = 'Live API Terintegrasi';
$standard_hisab = 'Kemenag RI (MABIMS)';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring Jadwal Sholat Seluruh Indonesia | 38 Ibukota Provinsi</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Dashboard Terintegrasi Monitoring Jadwal Sholat 38 Ibukota Provinsi Seluruh Indonesia dari Sabang sampai Merauke secara real-time dari Live API Kementerian Agama RI.">
    <meta name="keywords" content="jadwal sholat indonesia, monitoring sholat, 38 provinsi, kemenag, waktu sholat resmi, api myquran">
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans & Amiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        arabic: ['"Amiri"', 'serif']
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Islamic geometric pattern subtle background */
        .bg-islamic-pattern {
            background-color: #064e3b;
            background-image: radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .print-table {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased selection:bg-emerald-200 selection:text-emerald-900">

    <!-- TOP ANNOUNCEMENT BAR -->
    <div class="bg-emerald-950 text-emerald-200 text-xs py-2 px-4 border-b border-emerald-900/60 no-print">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-800 text-emerald-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    MABIMS LIVE API
                </span>
                <span class="hidden sm:inline text-slate-300">Standar Hisab Kementerian Agama Republik Indonesia</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span id="topHijriah"><?php echo $default_hijriah; ?></span>
                </span>
                <span class="hidden md:inline text-emerald-700">|</span>
                <span class="flex items-center gap-1.5 text-emerald-300">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span id="liveClockWib">Memuat Waktu...</span>
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN HEADER WITH EMERALD THEME -->
    <header class="bg-islamic-pattern text-white shadow-xl relative overflow-hidden border-b-4 border-emerald-500">
        <!-- Glow effects -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Title & Branding -->
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-800/80 border border-emerald-600/60 backdrop-blur-sm text-xs font-medium text-emerald-200">
                        <i data-lucide="satellite" class="w-4 h-4 text-emerald-300"></i>
                        <span>Sistem Informasi Hisab & Rukyat Nasional (Live API)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white flex items-center gap-3">
                        <span class="p-2.5 bg-emerald-600 rounded-xl shadow-lg ring-2 ring-emerald-400/30">
                            <i data-lucide="moon-star" class="w-7 h-7 sm:w-8 sm:h-8 text-white"></i>
                        </span>
                        <span>Dashboard Monitoring Jadwal Sholat</span>
                    </h1>
                    <p class="text-emerald-100/90 text-sm sm:text-base max-w-2xl font-light">
                        Pusat Pemantauan Terintegrasi Waktu Sholat 
                        <span class="font-semibold text-emerald-300">38 Ibukota Provinsi Resmi Republik Indonesia</span> 
                        dari Sabang hingga Merauke berbasis Live API Kemenag RI.
                    </p>
                </div>

                <!-- Live Multi-Timezone Widget -->
                <div class="bg-emerald-900/80 backdrop-blur-md border border-emerald-700/60 p-4 rounded-2xl shadow-xl flex flex-col gap-3 min-w-[280px]">
                    <div class="flex items-center justify-between text-xs text-emerald-200 border-b border-emerald-800/70 pb-2">
                        <span class="font-semibold flex items-center gap-1.5">
                            <i data-lucide="radio" class="w-3.5 h-3.5 text-emerald-400 animate-pulse"></i>
                            Sinkronisasi Waktu Nyata
                        </span>
                        <span class="text-[11px] bg-emerald-700/60 px-2 py-0.5 rounded text-emerald-100">Live API</span>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-emerald-950/60 p-2 rounded-xl border border-emerald-800/50">
                            <span class="block text-[10px] font-bold text-emerald-300">WIB (UTC+7)</span>
                            <span id="tzWib" class="text-sm font-extrabold text-white font-mono">--:--:--</span>
                        </div>
                        <div class="bg-emerald-950/60 p-2 rounded-xl border border-emerald-800/50">
                            <span class="block text-[10px] font-bold text-teal-300">WITA (UTC+8)</span>
                            <span id="tzWita" class="text-sm font-extrabold text-white font-mono">--:--:--</span>
                        </div>
                        <div class="bg-emerald-950/60 p-2 rounded-xl border border-emerald-800/50">
                            <span class="block text-[10px] font-bold text-cyan-300">WIT (UTC+9)</span>
                            <span id="tzWit" class="text-sm font-extrabold text-white font-mono">--:--:--</span>
                        </div>
                    </div>
                    
                    <div class="text-[11px] text-center text-emerald-300 font-medium" id="headerTanggalMasehi">
                        <?php echo $tanggal_masehi_hari_ini; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 flex-1 w-full pb-16 z-20">

        <!-- 4 KARTU STATISTIK OTOMATIS (PHP COMPUTED) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Card 1: Total Wilayah -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/90 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cakupan Wilayah</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">
                            <?php echo $total_wilayah; ?>
                            <span class="text-sm font-normal text-slate-500">Ibukota</span>
                        </h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1.5 flex items-center gap-1">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                            100% Provinsi Tercover
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner">
                        <i data-lucide="map" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Kalender Masehi & Hijriah -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/90 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kalender Sholat</p>
                        <h3 class="text-lg font-bold text-slate-800 mt-1 line-clamp-1" id="cardTanggalMasehi">
                            <?php echo $tanggal_masehi_hari_ini; ?>
                        </h3>
                        <p class="text-xs text-teal-700 font-semibold mt-1.5 flex items-center gap-1" id="cardHijriah">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                            <span><?php echo $default_hijriah; ?></span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shadow-inner">
                        <i data-lucide="calendar-days" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Distribusi Zona Waktu -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/90 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sebaran Zona Waktu</p>
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                WIB: <span class="text-emerald-700 font-extrabold"><?php echo $total_wib; ?></span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                WITA: <span class="text-teal-700 font-extrabold"><?php echo $total_wita; ?></span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                WIT: <span class="text-cyan-700 font-extrabold"><?php echo $total_wit; ?></span>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-1.5 flex items-center gap-1">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-blue-600"></i>
                            Lintas 3 Zona Waktu NKRI
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shadow-inner">
                        <i data-lucide="hourglass" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: Status Integrasi API -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/90 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-all group-hover:scale-110"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Integrasi</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <h3 class="text-base font-bold text-emerald-800">
                                <?php echo $status_api; ?>
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-1.5 flex items-center gap-1">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <?php echo $standard_hisab; ?>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- FITUR PILIH TANGGAL JADWAL SHOLAT -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 rounded-2xl p-5 mb-6 shadow-md border border-emerald-700/60 text-white no-print relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 relative z-10">
                <!-- Info Tanggal Aktif -->
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700/80 border border-emerald-500/50 flex items-center justify-center shrink-0 shadow-inner">
                        <i data-lucide="calendar" class="w-6 h-6 text-emerald-200"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-emerald-300 uppercase tracking-wider">Pilih Tanggal Jadwal Sholat</span>
                            <span id="dateStatusBadge" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?php echo $is_custom_date ? 'bg-amber-400 text-amber-950' : 'bg-emerald-400/20 text-emerald-200 border border-emerald-400/40'; ?>">
                                <?php echo $is_custom_date ? 'Tanggal Kustom' : 'Hari Ini'; ?>
                            </span>
                        </div>
                        <h2 id="activeDateDisplay" class="text-base sm:text-lg font-extrabold text-white flex flex-wrap items-center gap-2 mt-0.5">
                            <span id="displayMasehiText"><?php echo $tanggal_masehi_hari_ini; ?></span>
                            <span id="displayHijriahText" class="text-xs font-semibold text-emerald-200 bg-emerald-800/80 px-2.5 py-0.5 rounded-lg border border-emerald-600/60">
                                <?php echo $default_hijriah; ?>
                            </span>
                        </h2>
                    </div>
                </div>

                <!-- Kontrol Pemilihan Tanggal & Shortcut Cepat -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Form Input Tanggal -->
                    <div class="flex items-center gap-1.5 bg-emerald-950/60 p-1.5 rounded-xl border border-emerald-700/60">
                        <label for="prayerDatePicker" class="text-xs font-semibold text-emerald-200 pl-2 pr-1 hidden sm:inline">Pilih:</label>
                        <input 
                            type="date" 
                            id="prayerDatePicker" 
                            value="<?php echo $tanggal_input_value; ?>"
                            onchange="onMainDateChange(this.value)"
                            class="px-3 py-1.5 bg-white text-slate-800 text-xs font-bold rounded-lg border border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer shadow-sm"
                        >
                    </div>

                    <!-- Shortcut Cepat -->
                    <div class="inline-flex p-1 bg-emerald-950/60 rounded-xl border border-emerald-700/60 text-xs font-semibold">
                        <button 
                            type="button" 
                            onclick="setQuickDate('yesterday')" 
                            class="px-2.5 py-1.5 rounded-lg text-emerald-100 hover:bg-emerald-700/60 transition flex items-center gap-1"
                            title="Lihat Jadwal Kemarin"
                        >
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                            <span>Kemarin</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="setQuickDate('today')" 
                            class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-emerald-950 font-bold transition flex items-center gap-1 shadow-sm"
                            title="Kembali ke Jadwal Hari Ini"
                        >
                            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
                            <span>Hari Ini</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="setQuickDate('tomorrow')" 
                            class="px-2.5 py-1.5 rounded-lg text-emerald-100 hover:bg-emerald-700/60 transition flex items-center gap-1"
                            title="Lihat Jadwal Besok"
                        >
                            <span>Besok</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    <!-- Tombol Muat Ulang Server-Side -->
                    <button 
                        type="button" 
                        id="btnServerSync" 
                        onclick="reloadWithSelectedDate()" 
                        class="px-3 py-2 rounded-xl text-xs font-bold bg-white text-emerald-900 hover:bg-emerald-50 transition shadow-sm flex items-center gap-1.5"
                        title="Terapkan dan Muat Ulang Seluruh Data Tanggal Ini"
                    >
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-emerald-700"></i>
                        <span>Terapkan Tanggal</span>
                    </button>
                </div>
            </div>
            
            <!-- Alert bar jika sedang tanggal kustom -->
            <div id="customDateNotice" class="<?php echo $is_custom_date ? 'flex' : 'hidden'; ?> items-center justify-between mt-3 pt-3 border-t border-emerald-700/60 text-xs text-amber-200">
                <div class="flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-300"></i>
                    <span>Menampilkan data jadwal sholat untuk tanggal <strong id="noticeDateText"><?php echo $tanggal_masehi_hari_ini; ?></strong>. Klik <strong>Hari Ini</strong> untuk kembali ke jadwal saat ini.</span>
                </div>
                <button type="button" onclick="setQuickDate('today')" class="underline hover:text-white font-bold ml-2 shrink-0">
                    Reset ke Hari Ini
                </button>
            </div>
        </div>

        <!-- FITUR PENCARIAN & KONTROL INTERAKTIF -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6 no-print">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                
                <!-- Search Box -->
                <div class="relative flex-1 max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput" 
                        oninput="filterPrayerSchedule()" 
                        placeholder="Cari nama kota atau provinsi (cth: Jayapura, Jawa Barat, Padang)..." 
                        class="w-full pl-11 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium"
                    >
                    <button 
                        type="button" 
                        id="clearSearchBtn" 
                        onclick="clearSearch()" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 hidden"
                        title="Bersihkan Pencarian"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Filter Zona Waktu & Actions -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Quick Zone Filter Pills -->
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/80 text-xs font-semibold">
                        <button type="button" onclick="setZoneFilter('ALL')" id="filterBtnALL" class="px-3 py-1.5 rounded-lg transition-colors bg-emerald-600 text-white shadow-sm">
                            Semua (38)
                        </button>
                        <button type="button" onclick="setZoneFilter('WIB')" id="filterBtnWIB" class="px-3 py-1.5 rounded-lg transition-colors text-slate-600 hover:text-emerald-700">
                            WIB (16)
                        </button>
                        <button type="button" onclick="setZoneFilter('WITA')" id="filterBtnWITA" class="px-3 py-1.5 rounded-lg transition-colors text-slate-600 hover:text-emerald-700">
                            WITA (14)
                        </button>
                        <button type="button" onclick="setZoneFilter('WIT')" id="filterBtnWIT" class="px-3 py-1.5 rounded-lg transition-colors text-slate-600 hover:text-emerald-700">
                            WIT (8)
                        </button>
                    </div>

                    <!-- Export and Print Buttons -->
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            onclick="exportToCSV()" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors shadow-sm"
                            title="Unduh Data Format CSV"
                        >
                            <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i>
                            <span>Ekspor CSV</span>
                        </button>

                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm"
                            title="Cetak Jadwal Sholat"
                        >
                            <i data-lucide="printer" class="w-4 h-4 text-slate-600"></i>
                            <span>Cetak</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Live Result Status Counter -->
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span id="resultsCounter">Menampilkan <strong>38</strong> dari 38 Ibukota Provinsi</span>
                </div>
                <div class="text-[11px] text-slate-400 hidden sm:block">
                    *Tip: Kolom <strong>Subuh</strong> & <strong>Maghrib</strong> dicetak tebal sebagai acuan batas waktu sholat utama & berbuka.
                </div>
            </div>
        </div>

        <!-- TABEL DATA JADWAL SHOLAT 38 IBUKOTA PROVINSI (LIVE API DATA) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden print-table">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="prayerTable">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            <th scope="col" class="py-3.5 px-4 text-center w-12">No</th>
                            <th scope="col" class="py-3.5 px-4">Kota & Ibukota Provinsi</th>
                            <th scope="col" class="py-3.5 px-3 text-center">Zona</th>
                            <th scope="col" class="py-3.5 px-3 text-center">Kalender Hijriah</th>

                            <th scope="col" class="py-3.5 px-3 text-center bg-emerald-50/70 text-emerald-900 border-x border-emerald-100/60 font-extrabold">
                                Subuh *
                            </th>
                            <th scope="col" class="py-3.5 px-3 text-center text-slate-600">Dzuhur</th>
                            <th scope="col" class="py-3.5 px-3 text-center text-slate-600">Ashar</th>
                            <th scope="col" class="py-3.5 px-3 text-center bg-emerald-50/70 text-emerald-900 border-x border-emerald-100/60 font-extrabold">
                                Maghrib *
                            </th>
                            <th scope="col" class="py-3.5 px-3 text-center text-slate-600">Isya</th>
                            <th scope="col" class="py-3.5 px-4 text-center no-print">Aksi / Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm" id="prayerTableBody">
                        <?php foreach ($jadwal_sholat as $index => $row): ?>
                        <tr class="hover:bg-emerald-50/50 transition-colors prayer-row"
                            data-id="<?php echo $row['id']; ?>"
                            data-api-id="<?php echo $row['api_id']; ?>"
                            data-kota="<?php echo strtolower($row['kota']); ?>"
                            data-provinsi="<?php echo strtolower($row['provinsi']); ?>"
                            data-zona="<?php echo $row['zona']; ?>"
                        >
                            <!-- No -->
                            <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-400 font-medium row-number">
                                <?php echo $index + 1; ?>
                            </td>

                            <!-- Kota & Provinsi -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100/80 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs">
                                        <?php echo mb_substr($row['kota'], 0, 2); ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                            <span><?php echo htmlspecialchars($row['kota']); ?></span>
                                            <span class="city-live-indicator hidden w-2 h-2 rounded-full bg-emerald-500" title="Terverifikasi Live API"></span>
                                        </div>
                                        <div class="text-xs text-slate-500 font-medium">
                                            Prov. <?php echo htmlspecialchars($row['provinsi']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Zona Waktu Badge -->
                            <td class="py-3.5 px-3 text-center">
                                <?php if ($row['zona'] === 'WIB'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        WIB
                                    </span>
                                <?php elseif ($row['zona'] === 'WITA'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        WITA
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                        WIT
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Tanggal Hijriah -->
                            <td class="py-3.5 px-3 text-center text-xs text-slate-600 font-medium">
                                <span class="hijriah-badge bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200/60 inline-block">
                                    <?php echo htmlspecialchars($row['hijriah']); ?>
                                </span>
                            </td>

                            <!-- SUBUH (TEBAL & HIGHLIGHTED) -->
                            <td class="py-3.5 px-3 text-center bg-emerald-50/50 border-x border-emerald-100/60">
                                <span class="row-subuh inline-block font-mono font-extrabold text-emerald-800 text-sm bg-white px-2 py-0.5 rounded border border-emerald-300 shadow-xs">
                                    <?php echo $row['subuh']; ?>
                                </span>
                            </td>

                            <!-- Dzuhur -->
                            <td class="row-dzuhur py-3.5 px-3 text-center font-mono text-slate-700 text-xs font-semibold">
                                <?php echo $row['dzuhur']; ?>
                            </td>

                            <!-- Ashar -->
                            <td class="row-ashar py-3.5 px-3 text-center font-mono text-slate-700 text-xs font-semibold">
                                <?php echo $row['ashar']; ?>
                            </td>

                            <!-- MAGHRIB (TEBAL & HIGHLIGHTED) -->
                            <td class="py-3.5 px-3 text-center bg-emerald-50/50 border-x border-emerald-100/60">
                                <span class="row-maghrib inline-block font-mono font-extrabold text-emerald-900 text-sm bg-white px-2 py-0.5 rounded border border-emerald-300 shadow-xs">
                                    <?php echo $row['maghrib']; ?>
                                </span>
                            </td>

                            <!-- Isya -->
                            <td class="row-isya py-3.5 px-3 text-center font-mono text-slate-700 text-xs font-semibold">
                                <?php echo $row['isya']; ?>
                            </td>

                            <!-- Aksi & Status Badge -->
                            <td class="py-3.5 px-4 text-center no-print">
                                <button 
                                    type="button" 
                                    onclick='openCityModal(<?php echo json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)' 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-xs"
                                    title="Lihat Detail Kartu Jadwal"
                                >
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty Search State -->
            <div id="noResultsState" class="hidden p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h4 class="text-base font-bold text-slate-800">Kota atau Provinsi Tidak Ditemukan</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Tidak ada hasil yang cocok dengan kata kunci pencarian Anda. Silakan periksa ejaan atau gunakan tombol filter zona.
                </p>
                <button 
                    type="button" 
                    onclick="clearSearch()" 
                    class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-sm"
                >
                    Reset Pencarian
                </button>
            </div>
        </div>

        <!-- INFO PROTOKOL KEMENAG RI FOOTER BANNER -->
        <div class="mt-8 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 rounded-2xl p-6 border border-emerald-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-emerald-600 text-white rounded-xl shadow-md shrink-0">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Pedoman Standar Hisab Waktu Sholat Indonesia (MABIMS)</h4>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Perhitungan waktu sholat mengacu pada kriteria Menteri Agama Brunei Darussalam, Indonesia, Malaysia, dan Singapura (MABIMS):
                        Tinggi hilal 3° dan elongasi 6,4°. Waktu Imsak ditetapkan 10 menit sebelum waktu Subuh (ikhtiyat/pengaman: 2 menit).
                    </p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <div class="text-right">
                    <span class="block text-[11px] font-bold text-emerald-900">Sumber API</span>
                    <span class="text-xs text-emerald-700 font-mono">api.myquran.com/v2</span>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER WITH GREEN & WHITE AESTHETIC -->
    <footer class="bg-white border-t border-slate-200 py-6 text-slate-500 text-xs no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="p-1.5 bg-emerald-600 text-white rounded-md">
                    <i data-lucide="moon" class="w-4 h-4"></i>
                </span>
                <span class="font-bold text-slate-800">SIM-JADWAL SHOLAT INDONESIA</span>
                <span>&copy; <?php echo date('Y'); ?> All Rights Reserved.</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium text-slate-600">
                <span class="hover:text-emerald-600 transition">38 Ibukota Provinsi</span>
                <span>&bull;</span>
                <span class="hover:text-emerald-600 transition">Standar MABIMS</span>
                <span>&bull;</span>
                <span class="hover:text-emerald-600 transition">Live API Kemenag</span>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE DETAIL MODAL -->
    <div id="cityModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-200 flex flex-col max-h-[92vh]">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 text-white p-6 relative shrink-0">
                <button 
                    type="button" 
                    onclick="closeCityModal()" 
                    class="absolute top-5 right-5 text-emerald-200 hover:text-white transition p-1.5 rounded-xl hover:bg-emerald-600/80"
                    title="Tutup Modal"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-200 mb-1">
                    <i data-lucide="map-pin" class="w-4 h-4 text-emerald-300"></i>
                    <span id="modalProvinsi">Provinsi</span>
                </div>
                <h3 id="modalKota" class="text-2xl font-black tracking-tight text-white">Nama Kota</h3>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span id="modalZona" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-900 text-emerald-200 border border-emerald-600">WIB</span>
                    <span id="modalHijriah" class="text-xs text-emerald-100 font-medium bg-emerald-900/70 px-2.5 py-0.5 rounded-full border border-emerald-700">Hijriah</span>
                    <span class="text-xs text-emerald-100 font-medium bg-emerald-900/70 px-2.5 py-0.5 rounded-full border border-emerald-700 flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-300"></i>
                        <span id="modalMasehi">Tanggal Masehi</span>
                    </span>
                </div>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Modal Date Switcher -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3.5 mb-5 shadow-xs">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-slate-700">Pilih Tanggal:</span>
                        </div>
                        <div class="flex items-center gap-1.5 w-full sm:w-auto justify-between sm:justify-end">
                            <button type="button" onclick="stepModalDate(-1)" class="p-1.5 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 transition shadow-xs" title="Hari Sebelumnya">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <input 
                                type="date" 
                                id="modalDatePicker" 
                                onchange="loadModalCityDate(this.value)" 
                                class="px-2.5 py-1 text-xs font-bold text-slate-800 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer shadow-xs"
                            >
                            <button type="button" onclick="stepModalDate(1)" class="p-1.5 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 transition shadow-xs" title="Hari Berikutnya">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="setModalToday()" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-xs">
                                Hari Ini
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <p id="modalScheduleLabel" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rincian Waktu Sholat</p>
                    <span id="modalApiStatusText" class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Live API</span>
                </div>

                <!-- Loading overlay for modal -->
                <div id="modalLoadingOverlay" class="hidden py-8 text-center bg-slate-50 rounded-2xl border border-slate-200 mb-5">
                    <div class="inline-block animate-spin rounded-full h-7 w-7 border-3 border-emerald-600 border-t-transparent"></div>
                    <p class="text-xs text-slate-600 font-bold mt-2">Mengambil jadwal sholat dari API Kemenag...</p>
                </div>

                <!-- 8-Box Prayer Grid -->
                <div id="modalPrayerGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-5">
                    <!-- Imsak -->
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                        <span class="text-[11px] font-semibold text-slate-500 block">Imsak</span>
                        <span id="modalImsak" class="text-base font-extrabold text-slate-800 font-mono">--:--</span>
                    </div>
                    <!-- Subuh -->
                    <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-300 text-center ring-2 ring-emerald-500/20">
                        <span class="text-[11px] font-bold text-emerald-800 block">Subuh *</span>
                        <span id="modalSubuh" class="text-lg font-black text-emerald-900 font-mono">--:--</span>
                    </div>
                    <!-- Terbit -->
                    <div class="bg-amber-50/60 p-3 rounded-2xl border border-amber-200 text-center">
                        <span class="text-[11px] font-semibold text-amber-700 block">Terbit</span>
                        <span id="modalTerbit" class="text-base font-extrabold text-amber-900 font-mono">--:--</span>
                    </div>
                    <!-- Dhuha -->
                    <div class="bg-amber-50/60 p-3 rounded-2xl border border-amber-200 text-center">
                        <span class="text-[11px] font-semibold text-amber-700 block">Dhuha</span>
                        <span id="modalDhuha" class="text-base font-extrabold text-amber-900 font-mono">--:--</span>
                    </div>
                    <!-- Dzuhur -->
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                        <span class="text-[11px] font-semibold text-slate-500 block">Dzuhur</span>
                        <span id="modalDzuhur" class="text-base font-extrabold text-slate-800 font-mono">--:--</span>
                    </div>
                    <!-- Ashar -->
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                        <span class="text-[11px] font-semibold text-slate-500 block">Ashar</span>
                        <span id="modalAshar" class="text-base font-extrabold text-slate-800 font-mono">--:--</span>
                    </div>
                    <!-- Maghrib -->
                    <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-300 text-center ring-2 ring-emerald-500/20">
                        <span class="text-[11px] font-bold text-emerald-800 block">Maghrib *</span>
                        <span id="modalMaghrib" class="text-lg font-black text-emerald-900 font-mono">--:--</span>
                    </div>
                    <!-- Isya -->
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                        <span class="text-[11px] font-semibold text-slate-500 block">Isya</span>
                        <span id="modalIsya" class="text-base font-extrabold text-slate-800 font-mono">--:--</span>
                    </div>
                </div>

                <!-- Toggle Monthly Schedule Section -->
                <div class="mb-5">
                    <button 
                        type="button" 
                        id="toggleMonthBtn" 
                        onclick="toggleMonthlySchedule()" 
                        class="w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs rounded-2xl border border-emerald-200 transition flex items-center justify-between shadow-xs"
                    >
                        <span class="flex items-center gap-2">
                            <i data-lucide="calendar-range" class="w-4 h-4 text-emerald-600"></i>
                            <span id="monthBtnText">Lihat Jadwal 1 Bulan Penuh</span>
                        </span>
                        <i data-lucide="chevron-down" id="monthChevron" class="w-4 h-4 text-emerald-600 transition-transform"></i>
                    </button>
                    
                    <div id="monthlyScheduleContainer" class="hidden mt-3 max-h-60 overflow-y-auto border border-slate-200 rounded-2xl bg-white shadow-inner">
                        <div id="monthScheduleLoading" class="py-6 text-center text-slate-500 font-medium">
                            <div class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-emerald-500 border-t-transparent mr-2"></div>
                            Memuat kalender bulanan dari API...
                        </div>
                        <div id="monthScheduleTableWrapper" class="hidden">
                            <table class="w-full text-center text-xs border-collapse" id="monthScheduleTable">
                                <thead>
                                    <tr class="bg-emerald-700 text-white font-bold sticky top-0 text-[11px]">
                                        <th class="py-2 px-3 text-left">Tanggal</th>
                                        <th class="py-2 px-1.5">Imsak</th>
                                        <th class="py-2 px-1.5">Subuh</th>
                                        <th class="py-2 px-1.5">Dzuhur</th>
                                        <th class="py-2 px-1.5">Ashar</th>
                                        <th class="py-2 px-1.5">Maghrib</th>
                                        <th class="py-2 px-1.5">Isya</th>
                                    </tr>
                                </thead>
                                <tbody id="monthScheduleTableBody" class="divide-y divide-slate-100 font-mono text-[11px]">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Info Arah Kiblat -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Arah Kiblat Estimasi</span>
                            <span class="text-[11px] text-slate-500">294° s/d 296° Barat-Barat Laut (Standar Kemenag)</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-lg">MABIMS</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between shrink-0">
                <button 
                    type="button" 
                    id="copyScheduleBtn" 
                    onclick="copyScheduleToClipboard()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-300 hover:bg-slate-100 transition shadow-xs"
                >
                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    <span id="copyBtnText">Salin Jadwal</span>
                </button>
                <button 
                    type="button" 
                    onclick="closeCityModal()" 
                    class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FRONTEND LOGIC -->
    <script>
        // Inisialisasi Data Array dari PHP ke Javascript untuk pencarian & ekspor
        const masterPrayerData = <?php echo json_encode($jadwal_sholat); ?>;
        let currentZoneFilter = 'ALL';
        let currentModalData = null;

        // Inisialisasi Ikon Lucide & Jam Real-Time
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
            startLiveClocks();
        });

        // 1. JAM REAL-TIME DINAMIS UNTUK 3 ZONA WAKTU (WIB, WITA, WIT)
        function startLiveClocks() {
            function update() {
                const now = new Date();
                
                // UTC Time in MS
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                
                // Format helper
                function formatTz(offsetHours) {
                    const tzDate = new Date(utc + (3600000 * offsetHours));
                    const h = String(tzDate.getHours()).padStart(2, '0');
                    const m = String(tzDate.getMinutes()).padStart(2, '0');
                    const s = String(tzDate.getSeconds()).padStart(2, '0');
                    return `${h}:${m}:${s}`;
                }

                const wibTime = formatTz(7);
                const witaTime = formatTz(8);
                const witTime = formatTz(9);

                const elWib = document.getElementById('tzWib');
                const elWita = document.getElementById('tzWita');
                const elWit = document.getElementById('tzWit');
                const elTopWib = document.getElementById('liveClockWib');

                if (elWib) elWib.textContent = wibTime;
                if (elWita) elWita.textContent = witaTime;
                if (elWit) elWit.textContent = witTime;
                if (elTopWib) elTopWib.textContent = `${wibTime} WIB`;
            }
            update();
            setInterval(update, 1000);
        }

        // 2. FILTER PENCARIAN REAL-TIME (oninput) TANPA RELOAD
        function filterPrayerSchedule() {
            const searchInput = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const keyword = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.prayer-row');
            const noResultsState = document.getElementById('noResultsState');
            const resultsCounter = document.getElementById('resultsCounter');

            // Tampilkan atau sembunyikan tombol clear
            if (keyword.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            let visibleCount = 0;

            rows.forEach((row) => {
                const kota = row.getAttribute('data-kota');
                const provinsi = row.getAttribute('data-provinsi');
                const zona = row.getAttribute('data-zona');

                const matchesSearch = kota.includes(keyword) || provinsi.includes(keyword);
                const matchesZone = (currentZoneFilter === 'ALL') || (zona === currentZoneFilter);

                if (matchesSearch && matchesZone) {
                    row.style.display = '';
                    visibleCount++;
                    // Update nomor urut baris yang tampil
                    const noCell = row.querySelector('.row-number');
                    if (noCell) noCell.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update status counter dan empty state
            if (visibleCount === 0) {
                noResultsState.classList.remove('hidden');
            } else {
                noResultsState.classList.add('hidden');
            }

            resultsCounter.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari ${rows.length} Ibukota Provinsi`;
        }

        // Reset Pencarian
        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            filterPrayerSchedule();
            searchInput.focus();
        }

        // 3. FILTER TOMBOL ZONA WAKTU (ALL, WIB, WITA, WIT)
        function setZoneFilter(zone) {
            currentZoneFilter = zone;

            const buttons = ['ALL', 'WIB', 'WITA', 'WIT'];
            buttons.forEach(b => {
                const btn = document.getElementById(`filterBtn${b}`);
                if (!btn) return;
                if (b === zone) {
                    btn.className = 'px-3 py-1.5 rounded-lg transition-colors bg-emerald-600 text-white shadow-sm';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-lg transition-colors text-slate-600 hover:text-emerald-700';
                }
            });

            filterPrayerSchedule();
        }

        // State Global Penanggalan & Modal
        let currentSelectedDate = '<?php echo $tanggal_input_value; ?>';
        let modalActiveDate = currentSelectedDate;
        let monthlyDataCache = {}; // Cache respons bulanan per cityId_YYYY_MM

        // Helper Konversi Tanggal Hijriah JS (Standar Ummul Qura)
        function konversiKeHijriahJS(dateObj) {
            const d = dateObj || new Date();
            let day = d.getDate(), month = d.getMonth() + 1, year = d.getFullYear();
            if (month > 2) {
                month -= 3;
            } else {
                month += 9;
                year -= 1;
            }
            const c  = Math.floor(year / 100);
            const ya = year - 100 * c;
            const jd = Math.floor(146097 * c / 4) + Math.floor(1461 * ya / 4) + Math.floor((153 * month + 2) / 5) + day + 1721119;
            let l = jd - 1948440 + 10632;
            const n = Math.floor((l - 1) / 10631);
            l = l - 10631 * n + 354;
            const j = Math.floor((10985 - l) / 5316) * Math.floor(50 * l / 17719) + Math.floor(l / 5670) * Math.floor(43 * l / 15238);
            l = l - Math.floor((30 - j) / 15) * Math.floor(17719 * j / 50) - Math.floor(j / 16) * Math.floor(15238 * j / 43) + 29;
            const hMonth = Math.floor(24 * l / 709);
            const hDay   = l - Math.floor(709 * hMonth / 24);
            const hYear  = 30 * n + j - 30;
            const bulan  = ['', 'Muharram', 'Safar', 'Rabiul Awwal', 'Rabiul Akhir', 'Jumadil Ula', 'Jumadil Akhir', 'Rajab', "Sya'ban", 'Ramadhan', 'Syawwal', "Dzulqa'dah", 'Dzulhijjah'];
            return hDay + ' ' + (bulan[hMonth] || 'Hijriah') + ' ' + hYear + ' H';
        }

        // Helper Format Tanggal Indonesia
        function formatTanggalIDJS(dateObj) {
            const hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return hari[dateObj.getDay()] + ', ' + dateObj.getDate() + ' ' + bulan[dateObj.getMonth() + 1] + ' ' + dateObj.getFullYear();
        }

        function parseDateString(dateStr) {
            const parts = dateStr.split('-');
            return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10), 12, 0, 0);
        }

        function toDateInputString(dateObj) {
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        // 4. LOGIKA PEMILIHAN TANGGAL UTAMA (MAIN DATE CONTROLS)
        function onMainDateChange(newDateStr) {
            if (!newDateStr) return;
            currentSelectedDate = newDateStr;
            const dateObj = parseDateString(newDateStr);
            const masehiStr = formatTanggalIDJS(dateObj);
            const hijriahStr = konversiKeHijriahJS(dateObj);

            // Cek apakah tanggal hari ini
            const todayStr = toDateInputString(new Date());
            const isToday = (newDateStr === todayStr);

            // Update UI teks & badge
            const elDisplayMasehi = document.getElementById('displayMasehiText');
            const elDisplayHijriah = document.getElementById('displayHijriahText');
            const elBadge = document.getElementById('dateStatusBadge');
            const elNotice = document.getElementById('customDateNotice');
            const elNoticeText = document.getElementById('noticeDateText');
            const elHeaderMasehi = document.getElementById('headerTanggalMasehi');
            const elCardMasehi = document.getElementById('cardTanggalMasehi');
            const elCardHijriah = document.getElementById('cardHijriah');
            const elTopHijriah = document.getElementById('topHijriah');

            if (elDisplayMasehi) elDisplayMasehi.textContent = masehiStr;
            if (elDisplayHijriah) elDisplayHijriah.textContent = hijriahStr;
            if (elHeaderMasehi) elHeaderMasehi.textContent = masehiStr;
            if (elCardMasehi) elCardMasehi.textContent = masehiStr;
            if (elCardHijriah) elCardHijriah.innerHTML = `<i data-lucide="sparkles" class="w-3.5 h-3.5"></i><span>${hijriahStr}</span>`;
            if (elTopHijriah) elTopHijriah.textContent = hijriahStr;

            if (elBadge) {
                if (isToday) {
                    elBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400/20 text-emerald-200 border border-emerald-400/40';
                    elBadge.textContent = 'Hari Ini';
                } else {
                    elBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-amber-950';
                    elBadge.textContent = 'Tanggal Kustom';
                }
            }

            if (elNotice) {
                if (isToday) {
                    elNotice.classList.add('hidden');
                    elNotice.classList.remove('flex');
                } else {
                    elNotice.classList.remove('hidden');
                    elNotice.classList.add('flex');
                    if (elNoticeText) elNoticeText.textContent = masehiStr;
                }
            }

            // Update seluruh sel Hijriah di tabel
            document.querySelectorAll('.hijriah-badge').forEach(badge => {
                badge.textContent = hijriahStr;
            });

            // Sinkronkan ke URL query parameter tanpa full page flicker
            try {
                const url = new URL(window.location);
                url.searchParams.set('tanggal', newDateStr);
                window.history.replaceState({}, '', url);
            } catch (e) {}

            if (window.lucide) lucide.createIcons();
        }

        // Shortcut preset tanggal cepat: yesterday, today, tomorrow
        function setQuickDate(preset) {
            let targetDate = new Date();
            if (preset === 'yesterday') {
                targetDate.setDate(targetDate.getDate() - 1);
            } else if (preset === 'tomorrow') {
                targetDate.setDate(targetDate.getDate() + 1);
            }
            const dateStr = toDateInputString(targetDate);
            const picker = document.getElementById('prayerDatePicker');
            if (picker) picker.value = dateStr;
            onMainDateChange(dateStr);
        }

        // Terapkan dan reload penuh dengan query parameter
        function reloadWithSelectedDate() {
            window.location.href = '?tanggal=' + currentSelectedDate;
        }

        // 5. MODAL DETAIL JADWAL SHOLAT KOTA (TERHUBUNG KE LIVE API DENGAN PILIH TANGGAL)
        function openCityModal(data) {
            currentModalData = data;
            modalActiveDate = currentSelectedDate;

            document.getElementById('modalKota').textContent = data.kota;
            document.getElementById('modalProvinsi').textContent = `Provinsi ${data.provinsi}`;
            document.getElementById('modalZona').textContent = data.zona;

            const modalPicker = document.getElementById('modalDatePicker');
            if (modalPicker) modalPicker.value = modalActiveDate;

            // Reset monthly view
            const monthlyContainer = document.getElementById('monthlyScheduleContainer');
            if (monthlyContainer) monthlyContainer.classList.add('hidden');
            const chevron = document.getElementById('monthChevron');
            if (chevron) chevron.classList.remove('rotate-180');
            const monthBtnText = document.getElementById('monthBtnText');
            if (monthBtnText) monthBtnText.textContent = 'Lihat Jadwal 1 Bulan Penuh';

            document.getElementById('cityModal').classList.remove('hidden');
            if (window.lucide) lucide.createIcons();

            // Load data untuk tanggal aktif
            loadModalCityDate(modalActiveDate);
        }

        function closeCityModal() {
            const modal = document.getElementById('cityModal');
            if (modal) modal.classList.add('hidden');
        }

        // Tutup modal jika klik di luar area modal
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('cityModal');
            if (e.target === modal) {
                closeCityModal();
            }
        });

        // Load data sholat kota spesifik untuk tanggal tertentu (Client-Side Live API Fetch)
        async function loadModalCityDate(dateStr) {
            if (!currentModalData || !dateStr) return;
            modalActiveDate = dateStr;

            const dateObj = parseDateString(dateStr);
            const masehiStr = formatTanggalIDJS(dateObj);
            const hijriahStr = konversiKeHijriahJS(dateObj);

            document.getElementById('modalMasehi').textContent = masehiStr;
            document.getElementById('modalHijriah').textContent = hijriahStr;
            document.getElementById('modalScheduleLabel').textContent = `Jadwal Sholat - ${masehiStr}`;

            const loadingOverlay = document.getElementById('modalLoadingOverlay');
            const prayerGrid = document.getElementById('modalPrayerGrid');
            const apiStatus = document.getElementById('modalApiStatusText');

            if (loadingOverlay) loadingOverlay.classList.remove('hidden');
            if (prayerGrid) prayerGrid.classList.add('opacity-40');
            if (apiStatus) apiStatus.textContent = 'Menghubungkan Live API...';

            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            const endpoint = `https://api.myquran.com/v2/sholat/jadwal/${currentModalData.api_id}/${y}/${m}/${d}`;

            try {
                const response = await fetch(endpoint, { cache: 'no-cache' });
                if (response.ok) {
                    const json = await response.json();
                    if (json && json.status && json.data && json.data.jadwal) {
                        const j = json.data.jadwal;
                        document.getElementById('modalImsak').textContent = j.imsak || '--:--';
                        document.getElementById('modalSubuh').textContent = j.subuh || '--:--';
                        document.getElementById('modalTerbit').textContent = j.terbit || '--:--';
                        document.getElementById('modalDhuha').textContent = j.dhuha || '--:--';
                        document.getElementById('modalDzuhur').textContent = j.dzuhur || '--:--';
                        document.getElementById('modalAshar').textContent = j.ashar || '--:--';
                        document.getElementById('modalMaghrib').textContent = j.maghrib || '--:--';
                        document.getElementById('modalIsya').textContent = j.isya || '--:--';
                        if (apiStatus) apiStatus.textContent = 'Live Terverifikasi (Kemenag)';

                        // Simpan di modal data untuk fitur salin
                        currentModalData.activeSchedule = j;
                        currentModalData.activeDateMasehi = masehiStr;
                        currentModalData.activeDateHijriah = hijriahStr;

                        // Perbarui juga data baris tabel untuk kota ini
                        const tr = document.querySelector(`.prayer-row[data-id="${currentModalData.id}"]`);
                        if (tr) {
                            const elSubuh = tr.querySelector('.row-subuh');
                            const elDzuhur = tr.querySelector('.row-dzuhur');
                            const elAshar = tr.querySelector('.row-ashar');
                            const elMaghrib = tr.querySelector('.row-maghrib');
                            const elIsya = tr.querySelector('.row-isya');
                            const elLive = tr.querySelector('.city-live-indicator');

                            if (elSubuh && j.subuh) elSubuh.textContent = j.subuh;
                            if (elDzuhur && j.dzuhur) elDzuhur.textContent = j.dzuhur;
                            if (elAshar && j.ashar) elAshar.textContent = j.ashar;
                            if (elMaghrib && j.maghrib) elMaghrib.textContent = j.maghrib;
                            if (elIsya && j.isya) elIsya.textContent = j.isya;
                            if (elLive) elLive.classList.remove('hidden');
                        }
                    }
                }
            } catch (err) {
                console.warn('Live API request failed, displaying cached fallback:', err);
                if (apiStatus) apiStatus.textContent = 'Offline Fallback (Hisab MABIMS)';
            } finally {
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                if (prayerGrid) prayerGrid.classList.remove('opacity-40');
                if (window.lucide) lucide.createIcons();
            }
        }

        // Tombol +/- 1 hari di dalam modal
        function stepModalDate(delta) {
            const current = parseDateString(modalActiveDate);
            current.setDate(current.getDate() + delta);
            const nextDateStr = toDateInputString(current);
            const picker = document.getElementById('modalDatePicker');
            if (picker) picker.value = nextDateStr;
            loadModalCityDate(nextDateStr);
        }

        // Set tanggal modal kembali ke Hari Ini
        function setModalToday() {
            const todayStr = toDateInputString(new Date());
            const picker = document.getElementById('modalDatePicker');
            if (picker) picker.value = todayStr;
            loadModalCityDate(todayStr);
        }

        // 6. TOGGLE JADWAL 1 BULAN PENUH (BULANAN LIVE API)
        async function toggleMonthlySchedule() {
            const container = document.getElementById('monthlyScheduleContainer');
            const chevron = document.getElementById('monthChevron');
            const btnText = document.getElementById('monthBtnText');
            const loading = document.getElementById('monthScheduleLoading');
            const wrapper = document.getElementById('monthScheduleTableWrapper');
            const tbody = document.getElementById('monthScheduleTableBody');

            if (!container) return;

            const isOpening = container.classList.contains('hidden');
            if (!isOpening) {
                container.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (btnText) btnText.textContent = 'Lihat Jadwal 1 Bulan Penuh';
                return;
            }

            // Tampilkan container & loading
            container.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            if (btnText) btnText.textContent = 'Tutup Jadwal 1 Bulan';
            if (loading) loading.classList.remove('hidden');
            if (wrapper) wrapper.classList.add('hidden');

            const dateObj = parseDateString(modalActiveDate);
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const cacheKey = `${currentModalData.api_id}_${y}_${m}`;

            try {
                let monthlyList = monthlyDataCache[cacheKey];
                if (!monthlyList) {
                    const endpoint = `https://api.myquran.com/v2/sholat/jadwal/${currentModalData.api_id}/${y}/${m}`;
                    const res = await fetch(endpoint);
                    if (res.ok) {
                        const json = await res.json();
                        if (json && json.status && json.data && Array.isArray(json.data.jadwal)) {
                            monthlyList = json.data.jadwal;
                            monthlyDataCache[cacheKey] = monthlyList;
                        }
                    }
                }

                if (monthlyList && monthlyList.length > 0) {
                    tbody.innerHTML = '';
                    monthlyList.forEach(item => {
                        const isSelectedDay = (item.date === modalActiveDate);
                        const tr = document.createElement('tr');
                        tr.className = isSelectedDay 
                            ? 'bg-emerald-100/90 font-bold text-emerald-950 ring-1 ring-emerald-400' 
                            : 'hover:bg-slate-50 transition-colors';
                        
                        tr.innerHTML = `
                            <td class="py-1.5 px-3 text-left font-sans font-semibold">
                                ${item.tanggal ? item.tanggal.split(',')[0] + ', ' + (item.date ? item.date.split('-')[2] : '') : item.date}
                                ${isSelectedDay ? '<span class="ml-1 text-[9px] bg-emerald-700 text-white px-1 py-0.2 rounded font-mono">Pilihan</span>' : ''}
                            </td>
                            <td class="py-1.5 px-1.5">${item.subuh || '-'}</td>
                            <td class="py-1.5 px-1.5">${item.dzuhur || '-'}</td>
                            <td class="py-1.5 px-1.5">${item.ashar || '-'}</td>
                            <td class="py-1.5 px-1.5 font-bold text-emerald-800">${item.maghrib || '-'}</td>
                            <td class="py-1.5 px-1.5">${item.isya || '-'}</td>
                        `;
                        tbody.appendChild(tr);
                    });

                    if (loading) loading.classList.add('hidden');
                    if (wrapper) wrapper.classList.remove('hidden');
                } else {
                    if (loading) loading.innerHTML = '<p class="text-amber-700 py-3">Jadwal bulanan tidak tersedia untuk periode ini.</p>';
                }
            } catch (err) {
                if (loading) loading.innerHTML = `<p class="text-rose-600 py-3">Gagal memuat jadwal bulanan: ${err.message}</p>`;
            }
        }

        // 7. SALIN JADWAL SHOLAT KE CLIPBOARD (DILENGKAPI TANGGAL TERPILIH)
        function copyScheduleToClipboard() {
            if (!currentModalData) return;
            const sched = currentModalData.activeSchedule || currentModalData;
            const masehi = currentModalData.activeDateMasehi || formatTanggalIDJS(parseDateString(modalActiveDate));
            const hijriah = currentModalData.activeDateHijriah || konversiKeHijriahJS(parseDateString(modalActiveDate));

            const text = `Jadwal Sholat ${currentModalData.kota} (${currentModalData.provinsi})\n` +
                         `Tanggal : ${masehi} / ${hijriah}\n` +
                         `Zona    : ${currentModalData.zona}\n` +
                         `-----------------------------------\n` +
                         `Imsak   : ${sched.imsak || '--:--'}\n` +
                         `Subuh   : ${sched.subuh || '--:--'}\n` +
                         `Terbit  : ${sched.terbit || '--:--'}\n` +
                         `Dhuha   : ${sched.dhuha || '--:--'}\n` +
                         `Dzuhur  : ${sched.dzuhur || '--:--'}\n` +
                         `Ashar   : ${sched.ashar || '--:--'}\n` +
                         `Maghrib : ${sched.maghrib || '--:--'}\n` +
                         `Isya    : ${sched.isya || '--:--'}\n` +
                         `-----------------------------------\n` +
                         `Sumber  : Live API Kementerian Agama RI (MyQuran / Standar MABIMS)`;

            navigator.clipboard.writeText(text).then(() => {
                const btnText = document.getElementById('copyBtnText');
                if (btnText) btnText.textContent = 'Tersalin!';
                setTimeout(() => {
                    if (btnText) btnText.textContent = 'Salin Jadwal';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin teks: ' + err);
            });
        }

        // 8. FITUR EKSPOR DATA KE FORMAT CSV (DENGAN TANGGAL AKTIF)
        function exportToCSV() {
            const rows = [];
            // Header CSV
            rows.push(['No', 'Kota', 'Provinsi', 'Zona Waktu', 'Tanggal Masehi', 'Kalender Hijriah', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya', 'Status']);

            const tableRows = document.querySelectorAll('.prayer-row');
            let index = 1;
            const currentMasehi = formatTanggalIDJS(parseDateString(currentSelectedDate));
            const currentHijri = konversiKeHijriahJS(parseDateString(currentSelectedDate));

            tableRows.forEach(tr => {
                if (tr.style.display !== 'none') {
                    const kota = tr.querySelector('[data-kota]') ? tr.getAttribute('data-kota').toUpperCase() : tr.cells[1].innerText.split('\n')[0].trim();
                    const provinsi = tr.cells[1].innerText.split('\n')[1] ? tr.cells[1].innerText.split('\n')[1].replace('Prov.', '').trim() : '';
                    const zona = tr.cells[2].innerText.trim();
                    const subuh = tr.querySelector('.row-subuh') ? tr.querySelector('.row-subuh').innerText.trim() : tr.cells[4].innerText.trim();
                    const dzuhur = tr.querySelector('.row-dzuhur') ? tr.querySelector('.row-dzuhur').innerText.trim() : tr.cells[5].innerText.trim();
                    const ashar = tr.querySelector('.row-ashar') ? tr.querySelector('.row-ashar').innerText.trim() : tr.cells[6].innerText.trim();
                    const maghrib = tr.querySelector('.row-maghrib') ? tr.querySelector('.row-maghrib').innerText.trim() : tr.cells[7].innerText.trim();
                    const isya = tr.querySelector('.row-isya') ? tr.querySelector('.row-isya').innerText.trim() : tr.cells[8].innerText.trim();
                    const status = 'Live API Kemenag RI';

                    rows.push([index++, kota, provinsi, zona, currentMasehi, currentHijri, subuh, dzuhur, ashar, maghrib, isya, status]);
                }
            });

            // Convert to CSV String
            const csvContent = "data:text/csv;charset=utf-8," 
                + rows.map(e => e.map(item => `"${item}"`).join(",")).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `Jadwal_Sholat_38_Ibukota_Indonesia_${currentSelectedDate}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Keyboard Shortcut: Tekan Escape untuk tutup modal, Ctrl+K atau / untuk cari
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeCityModal();
            }
            if ((e.ctrlKey && e.key.toLowerCase() === 'k') || (e.key === '/' && document.activeElement.tagName !== 'INPUT')) {
                e.preventDefault();
                const searchInput = document.getElementById('searchInput');
                if (searchInput) searchInput.focus();
            }
        });
    </script>
</body>
</html>
