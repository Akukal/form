<?php
require __DIR__ . '/logic.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Lomba Coding</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 560px;
      margin: 32px auto;
      padding: 0 16px;
      line-height: 1.5;
    }

    h1 {
      font-size: 24px;
    }

    .field {
      margin-bottom: 16px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 4px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="url"],
    select {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
    }

    .hint {
      color: #555;
      font-size: 13px;
      margin: 4px 0 0;
    }

    .error {
      color: #c00;
      font-size: 13px;
      margin: 4px 0 0;
    }

    .invalid {
      border: 1px solid #c00;
    }

    .success {
      background: #e6f4ea;
      border: 1px solid #2e7d32;
      padding: 16px;
      margin-bottom: 20px;
    }

    .success h2 {
      margin-top: 0;
      color: #2e7d32;
    }

    button {
      padding: 10px 20px;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <h1>Registrasi Lomba Coding</h1>
  <p>Isi semua field bertanda <strong>*</strong>. Umur pendaftar harus <strong>17–19 tahun</strong>.</p>

  <?php if (!empty($errors['umum'])): ?>
    <p class="error"><?= esc($errors['umum']) ?></p>
  <?php endif; ?>

  <?php if ($success && $saved): ?>
    <div class="success">
      <h2>Pendaftaran berhasil disimpan!</h2>
      <p>Terima kasih, <strong><?= esc($saved['nama']) ?></strong>. Data kamu:</p>
      <ul>
        <li>Nama: <?= esc($saved['nama']) ?></li>
        <li>Email: <?= esc($saved['email']) ?></li>
        <li>Nomor HP: <?= esc($saved['hp']) ?></li>
        <li>Umur: <?= esc($saved['umur']) ?> tahun</li>
        <li>Repositori GitHub: <?= esc($saved['github']) ?></li>
        <li>Kategori: <?= esc($saved['kategori']) ?></li>
        <li>Peserta: <?= esc($saved['peserta']) ?></li>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="index.php" novalidate>

    <!-- Nama: wajib, 3-50 karakter -->
    <div class="field">
      <label for="nama">Nama *</label>
      <input type="text" id="nama" name="nama" required minlength="3" maxlength="50"
        value="<?= esc($old['nama']) ?>"
        class="<?= isset($errors['nama']) ? 'invalid' : '' ?>">
      <p class="hint">3–50 karakter.</p>
      <?php if (isset($errors['nama'])): ?>
        <p class="error"><?= esc($errors['nama']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Email: wajib -->
    <div class="field">
      <label for="email">Email *</label>
      <input type="email" id="email" name="email" required
        value="<?= esc($old['email']) ?>"
        class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['email'])): ?>
        <p class="error"><?= esc($errors['email']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Nomor HP: wajib, REGEXP di logic.php -->
    <div class="field">
      <label for="hp">Nomor HP *</label>
      <input type="tel" id="hp" name="hp" required
        pattern="(\+62|62|0)8[0-9]{8,11}"
        placeholder="08xxxxxxxxxx atau +628xxxxxxxxxx"
        value="<?= esc($old['hp']) ?>"
        class="<?= isset($errors['hp']) ? 'invalid' : '' ?>">
      <p class="hint">Format Indonesia: 08xxxxxxxxxx atau +628xxxxxxxxxx.</p>
      <?php if (isset($errors['hp'])): ?>
        <p class="error"><?= esc($errors['hp']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Umur: wajib, 17-19 -->
    <div class="field">
      <label for="umur">Umur *</label>
      <input type="number" id="umur" name="umur" required min="17" max="19"
        value="<?= esc($old['umur']) ?>"
        class="<?= isset($errors['umur']) ? 'invalid' : '' ?>">
      <p class="hint">Harus 17–19 tahun. Di luar rentang, pendaftaran tidak bisa disimpan.</p>
      <?php if (isset($errors['umur'])): ?>
        <p class="error"><?= esc($errors['umur']) ?></p>
      <?php endif; ?>
    </div>

    <!-- URL GitHub: opsional -->
    <div class="field">
      <label for="github">URL Repositori GitHub (opsional)</label>
      <input type="url" id="github" name="github"
        placeholder="https://github.com/username/nama-repo"
        value="<?= esc($old['github']) ?>"
        class="<?= isset($errors['github']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['github'])): ?>
        <p class="error"><?= esc($errors['github']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Kategori: select, whitelist di logic.php -->
    <div class="field">
      <label for="kategori">Kategori *</label>
      <select id="kategori" name="kategori" required
        class="<?= isset($errors['kategori']) ? 'invalid' : '' ?>">
        <option value="">-- Pilih kategori --</option>
        <?php foreach ($kategori_list as $kategori): ?>
          <option value="<?= esc($kategori) ?>"
            <?= $old['kategori'] === $kategori ? 'selected' : '' ?>>
            <?= esc($kategori) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (isset($errors['kategori'])): ?>
        <p class="error"><?= esc($errors['kategori']) ?></p>
      <?php endif; ?>
    </div>

    <!-- Peserta: radio, whitelist di logic.php -->
    <div class="field">
      <span><strong>Peserta *</strong></span><br>
      <?php foreach ($peserta_list as $peserta): ?>
        <label style="display:inline; font-weight:normal; margin-right:16px;">
          <input type="radio" name="peserta" value="<?= esc($peserta) ?>"
            <?= $old['peserta'] === $peserta ? 'checked' : '' ?> required>
          <?= esc($peserta) ?>
        </label>
      <?php endforeach; ?>
      <?php if (isset($errors['peserta'])): ?>
        <p class="error"><?= esc($errors['peserta']) ?></p>
      <?php endif; ?>
    </div>

    <button type="submit">Daftar</button>
    <button type="reset">Reset</button>

  </form>

</body>

</html>