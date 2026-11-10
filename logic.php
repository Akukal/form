<?php
/**
 * logic.php — Logika validasi & penyimpanan "Registrasi Lomba Coding"
 *
 * Dipanggil dari index.php (tampilan) dengan:
 *   require __DIR__ . '/logic.php';
 *
 * File ini TIDAK mencetak HTML. Ia hanya menyiapkan variabel
 * yang dipakai index.php:
 *   $kategori_list  — whitelist kategori (select)
 *   $peserta_list   — whitelist peserta (radio)
 *   $old            — isian terakhir, dipakai agar isian tidak hilang
 *   $errors         — pesan error per field, mis. $errors['nama']
 *   $success        — true hanya jika SEMUA validasi lolos
 *   $saved          — data yang berhasil disimpan (null jika gagal)
 */

// ---------- Whitelist ----------
$kategori_list = [
    'Web Development',
    'Mobile Development',
    'Data Science & AI',
    'Game Development',
];

$peserta_list = [
    'Individu',
    'Tim',
];

// ---------- State awal (dipakai index.php saat GET maupun POST) ----------
$errors  = [];
$success = false;
$saved   = null;

$old = [
    'nama'     => '',
    'email'    => '',
    'hp'       => '',
    'umur'     => '',
    'github'   => '',
    'kategori' => '',
    'peserta'  => '',
];

/**
 * Helper output aman — dipakai index.php untuk semua echo
 * nilai dari user, supaya <script> dll. tampil sebagai teks.
 */
function esc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Panjang karakter nama: pakai mb_strlen kalau ekstensinya ada,
// kalau tidak ada (php-cli minimal) pakai strlen sebagai fallback.
function panjang($value): int
{
    return function_exists('mb_strlen')
        ? mb_strlen($value, 'UTF-8')
        : strlen($value);
}

// ---------- Proses hanya saat form di-submit (POST) ----------
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    // Ambil + rapikan input. trim() dulu, validasi setelahnya.
    $old['nama']     = trim($_POST['nama'] ?? '');
    $old['email']    = trim($_POST['email'] ?? '');
    $old['hp']       = trim($_POST['hp'] ?? '');
    $old['umur']     = trim($_POST['umur'] ?? '');
    $old['github']   = trim($_POST['github'] ?? '');
    $old['kategori'] = trim($_POST['kategori'] ?? '');
    $old['peserta']  = trim($_POST['peserta'] ?? '');

    // --- 1. Nama: wajib, 3–50 karakter ---
    if ($old['nama'] === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif (panjang($old['nama']) < 3 || panjang($old['nama']) > 50) {
        $errors['nama'] = 'Nama harus 3–50 karakter (saat ini '
            . panjang($old['nama']) . ' karakter).';
    }

    // --- 2. Email: wajib, format valid ---
    if ($old['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }

    // --- 3. Nomor HP: wajib, REGEXP nomor Indonesia ---
    // Boleh: 08xxxxxxxxxx, 628xxxxxxxxxx, atau +628xxxxxxxxxx
    // (angka 8 di depan, lalu 8–11 digit angka)
    if ($old['hp'] === '') {
        $errors['hp'] = 'Nomor HP wajib diisi.';
    } else {
        // Izinkan pemisah yang biasa diketik user, lalu uji REGEXP-nya.
        $hp_bersih = str_replace([' ', '-', '.'], '', $old['hp']);
        if (!preg_match('/^(?:\+62|62|0)8[0-9]{8,11}$/', $hp_bersih)) {
            $errors['hp'] = 'Nomor HP tidak valid. Gunakan format 08xxxxxxxxxx atau +628xxxxxxxxxx.';
        } else {
            $old['hp'] = $hp_bersih;
        }
    }

    // --- 4. Umur: wajib, bilangan bulat, rentang 17–19 tahun ---
    // Di luar rentang = error, jadi $success tetap false dan
    // data TIDAK disimpan (lihat blok penyimpanan di bawah).
    if ($old['umur'] === '') {
        $errors['umur'] = 'Umur wajib diisi.';
    } elseif (filter_var($old['umur'], FILTER_VALIDATE_INT) === false) {
        $errors['umur'] = 'Umur harus berupa angka.';
    } elseif ((int) $old['umur'] < 17 || (int) $old['umur'] > 19) {
        $errors['umur'] = 'Umur harus 17–19 tahun. Di luar rentang tersebut pendaftaran tidak bisa disimpan.';
    }

    // --- 5. URL Repositori GitHub: opsional, tapi jika diisi harus URL valid ---
    if ($old['github'] !== '') {
        $host = parse_url($old['github'], PHP_URL_HOST);
        $path = parse_url($old['github'], PHP_URL_PATH) ?? '';
        // Harus URL valid, host-nya github.com, dan ada /username/repo
        $is_github_repo = filter_var($old['github'], FILTER_VALIDATE_URL)
            && in_array(strtolower($host ?? ''), ['github.com', 'www.github.com'], true)
            && preg_match('#^/[^/]+/[^/]+#', $path);

        if (!$is_github_repo) {
            $errors['github'] = 'URL repositori tidak valid. Contoh: https://github.com/username/nama-repo';
        }
    }

    // --- 6. Kategori (select): wajib, harus ada di whitelist ---
    if ($old['kategori'] === '') {
        $errors['kategori'] = 'Kategori wajib dipilih.';
    } elseif (!in_array($old['kategori'], $kategori_list, true)) {
        $errors['kategori'] = 'Kategori tidak valid.';
    }

    // --- 7. Peserta (radio): wajib, harus ada di whitelist ---
    if ($old['peserta'] === '') {
        $errors['peserta'] = 'Jenis peserta wajib dipilih.';
    } elseif (!in_array($old['peserta'], $peserta_list, true)) {
        $errors['peserta'] = 'Jenis peserta tidak valid.';
    }

    // --- Simpan HANYA jika tidak ada error sama sekali ---
    if (empty($errors)) {
        $saved = [
            'nama'     => $old['nama'],
            'email'    => $old['email'],
            'hp'       => $old['hp'],
            'umur'     => (int) $old['umur'],
            'github'   => $old['github'] !== '' ? $old['github'] : '-',
            'kategori' => $old['kategori'],
            'peserta'  => $old['peserta'],
            'waktu'    => date('Y-m-d H:i:s'),
        ];

        // Simpan sebagai satu baris JSON per pendaftar.
        $file = __DIR__ . '/data/pendaftar.json';
        $baris = json_encode($saved, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;

        if (file_put_contents($file, $baris, FILE_APPEND | LOCK_EX) === false) {
            $errors['umum'] = 'Pendaftaran valid, tetapi gagal disimpan. Coba lagi.';
            $saved = null;
        } else {
            $success = true;
        }
    }
}
