
<?php
session_start();

$old = isset($_SESSION['old']) ? $_SESSION['old'] : array();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
$success = isset($_SESSION['success']) ? $_SESSION['success'] : false;

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
                value="<?php echo e(isset($old['nama']) ? $old['nama'] : ''); ?>">
            <?php if (isset($errors['nama'])): ?>
                <div class="error"><?php echo e($errors['nama']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email"
                value="<?php echo e(isset($old['email']) ? $old['email'] : ''); ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?php echo e($errors['email']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Nomor HP</label>
            <input type="text" name="no_hp"
                placeholder="08xxxxxxxxxx"
                value="<?php echo e(isset($old['no_hp']) ? $old['no_hp'] : ''); ?>">
            <?php if (isset($errors['no_hp'])): ?>
                <div class="error"><?php echo e($errors['no_hp']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Umur *</label>
            <input type="number" name="umur"
                value="<?php echo e(isset($old['umur']) ? $old['umur'] : ''); ?>">
            <?php if (isset($errors['umur'])): ?>
                <div class="error"><?php echo e($errors['umur']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>URL Repositori GitHub (Opsional)</label>
            <input type="url" name="github"
                placeholder="https://github.com/username/repo"
                value="<?php echo e(isset($old['github']) ? $old['github'] : ''); ?>">
            <?php if (isset($errors['github'])): ?>
                <div class="error"><?php echo e($errors['github']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Kategori Lomba *</label>
            <select name="kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kategori = array('Web Development', 'Mobile Development', 'UI/UX Design');
                foreach ($kategori as $item):
                ?>
                    <option value="<?php echo e($item); ?>"
                        <?php echo (isset($old['kategori']) && $old['kategori'] === $item) ? 'selected' : ''; ?>>
                        <?php echo e($item); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['kategori'])): ?>
                <div class="error"><?php echo e($errors['kategori']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Jenis Peserta *</label>
            <div class="radio-group">
                <?php foreach (array('Individu', 'Tim') as $item): ?>
                    <label>
                        <input type="radio" name="peserta"
                            value="<?php echo e($item); ?>"
                            <?php echo (isset($old['peserta']) && $old['peserta'] === $item) ? 'checked' : ''; ?>>
                        <?php echo e($item); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if (isset($errors['peserta'])): ?>
                <div class="error"><?php echo e($errors['peserta']); ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" name="submit">Daftar Sekarang</button>

    </form>
</div>

</body>
</html>
