<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Trustway Management System Kepabeanan (TMSK) - Sistem login internal.">
  <title><?= $this->renderSection('title') ?> | TSMK</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-icons/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/pages/auth.css') ?>">

  <?= $this->renderSection('pageStyles') ?>
</head>

<body>
  <div id="auth">
    <?= $this->renderSection('main') ?>
  </div>

  <!-- JS hanya Bootstrap, tanpa main.js -->
  <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
  <?= $this->renderSection('pageScripts') ?>
</body>

</html>
