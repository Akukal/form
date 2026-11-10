
<?php
session_start();

$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? false;

unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['success']);

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Lomba Coding</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 30px 15px;
        }

        .container {
            max-width: 550px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        h2 {
            text-align: center;
            color: #243771;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="radio"] {
            width: auto;
        }

        .radio-group label {
            display: inline-block;
            margin-right: 15px;
            font-weight: normal;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #243771;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #1a2855;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registrasi Lomba Coding</h2>
    <p>Silakan isi formulir pendaftaran berikut.</p>

    <?php if ($success): ?>
        <div class="success">
            Registrasi berhasil! Data Anda berhasil divalidasi.
        </div>
    <?php endif; ?>

    <form action="logic.php" method="POST">

        <div class="form-group">
            <label>Nama *</label>
            <input type="text" name="nama"
                value="<?= e($old['nama'] ?? '') ?>">
            <?php if (isset($errors['nama'])): ?>
                <div class="error"><?= e($errors['nama']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email"
                value="<?= e($old['email'] ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?= e($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Nomor HP *</label>
            <input type="text" name="no_hp"
                placeholder="08xxxxxxxxxx"
                value="<?= e($old['no_hp'] ?? '') ?>">
            <?php if (isset($errors['no_hp'])): ?>
                <div class="error"><?= e($errors['no_hp']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Umur *</label>
            <input type="number" name="umur"
                value="<?= e($old['umur'] ?? '') ?>">
            <?php if (isset($errors['umur'])): ?>
                <div class="error"><?= e($errors['umur']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>URL Repositori GitHub (Opsional)</label>
            <input type="url" name="github"
                placeholder="https://github.com/username"
                value="<?= e($old['github'] ?? '') ?>">
            <?php if (isset($errors['github'])): ?>
                <div class="error"><?= e($errors['github']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Kategori Lomba *</label>
            <select name="kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kategori = ['Web Development', 'Mobile Development', 'UI/UX Design'];
                foreach ($kategori as $item):
                ?>
                    <option value="<?= e($item) ?>"
                        <?= ($old['kategori'] ?? '') === $item ? 'selected' : '' ?>>
                        <?= e($item) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['kategori'])): ?>
                <div class="error"><?= e($errors['kategori']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Jenis Peserta *</label>
            <div class="radio-group">
                <?php foreach (['Individu', 'Tim'] as $item): ?>
                    <label>
                        <input type="radio" name="peserta"
                            value="<?= e($item) ?>"
                            <?= ($old['peserta'] ?? '') === $item ? 'checked' : '' ?>>
                        <?= e($item) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if (isset($errors['peserta'])): ?>
                <div class="error"><?= e($errors['peserta']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Pesan (Opsional)</label>
            <textarea name="pesan" rows="3"><?= e($old['pesan'] ?? '') ?></textarea>
        </div>

        <button type="submit" name="submit">Daftar Sekarang</button>

    </form>
</div>

</body>
</html>
