<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Mengambil data form
$nama     = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$no_hp    = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$umur     = isset($_POST['umur']) ? trim($_POST['umur']) : '';
$github   = isset($_POST['github']) ? trim($_POST['github']) : '';
$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';
$peserta  = isset($_POST['peserta']) ? $_POST['peserta'] : '';
$pesan    = isset($_POST['pesan']) ? trim($_POST['pesan']) : '';

$data = array(
    'nama' => $nama,
    'email' => $email,
    'no_hp' => $no_hp,
    'umur' => $umur,
    'github' => $github,
    'kategori' => $kategori,
    'peserta' => $peserta,
    'pesan' => $pesan
);

$errors = array();

// 1. Validasi Nama (3-50 karakter)
if ($nama === '') {
    $errors['nama'] = 'Nama wajib diisi.';
} elseif (strlen($nama) < 3 || strlen($nama) > 50) {
    $errors['nama'] = 'Nama harus terdiri dari 3-50 karakter.';
}

// 2. Validasi Email
if ($email === '') {
    $errors['email'] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format email tidak valid.';
}

// 3. Validasi Nomor HP menggunakan REGEXP
if ($no_hp === '') {
    $errors['no_hp'] = 'Nomor HP wajib diisi.';
} elseif (!preg_match('/^(08)[0-9]{8,11}$/', $no_hp)) {
    $errors['no_hp'] = 'Nomor HP harus diawali 08 dan berisi 10-13 digit.';
}

// 4. Validasi Umur (17-19 tahun)
if ($umur === '') {
    $errors['umur'] = 'Umur wajib diisi.';
} elseif (!filter_var($umur, FILTER_VALIDATE_INT) || $umur < 17 || $umur > 19) {
    $errors['umur'] = 'Peringatan! Umur peserta harus antara 17-19 tahun.';
}

// 5. Validasi URL GitHub (Opsional)
if ($github !== '') {
    if (!filter_var($github, FILTER_VALIDATE_URL) ||
        !preg_match('/^https:\/\/github\.com\/[a-zA-Z0-9-]+\/?$/', $github)) {
        $errors['github'] = 'Masukkan URL profil GitHub yang valid.';
    }
}

// 6. Validasi Kategori menggunakan Whitelist
$allowedKategori = array(
    'Web Development',
    'Mobile Development',
    'UI/UX Design'
);

if (!in_array($kategori, $allowedKategori, true)) {
    $errors['kategori'] = 'Kategori lomba tidak valid.';
}

// 7. Validasi Peserta menggunakan Whitelist
$allowedPeserta = array('Individu', 'Tim');

if (!in_array($peserta, $allowedPeserta, true)) {
    $errors['peserta'] = 'Jenis peserta tidak valid.';
}

// Menyimpan input agar tidak hilang
$_SESSION['old'] = $data;
$_SESSION['errors'] = $errors;

// Jika ada error, kembali ke form
if (!empty($errors)) {
    header('Location: index.php');
    exit;
}

// Jika validasi berhasil
unset($_SESSION['old'], $_SESSION['errors']);

$_SESSION['success'] = true;

header('Location: index.php');
exit;
