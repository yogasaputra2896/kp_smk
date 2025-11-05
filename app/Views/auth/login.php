<?= $this->extend($config->viewLayout) ?>
<?= $this->section('title') ?>Login<?= $this->endSection() ?>
<?= $this->section('main') ?>

<div class="row h-100">
  <div class="col-lg-5 col-12">
    <div id="auth-left">

      <div class="auth-logo">
        <a href="#"><img src="<?= base_url('assets/images/logo/logo.png') ?>" alt="Logo"></a>
      </div>
      <h1 class="auth-title">TSMK</h1>
	  <p class="auth-subtitle mb-5">Trustway System Management Kepabeanan</p>

      <?= view('Myth\Auth\Views\_message_block') ?>

      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Login input -->
        <div class="form-group position-relative has-icon-left mb-4">
          <input type="<?= $config->validFields === ['email'] ? 'email' : 'text' ?>"
                 class="form-control form-control-xl <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                 name="login"
                 placeholder="<?= $config->validFields === ['email'] ? lang('Auth.email') : lang('Auth.emailOrUsername') ?>">
          <div class="form-control-icon">
            <i class="bi bi-person"></i>
          </div>
          <div class="invalid-feedback">
            <?= session('errors.login') ?>
          </div>
        </div>

        <!-- Password -->
        <div class="form-group position-relative has-icon-left mb-4">
          <input type="password"
                 class="form-control form-control-xl <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                 name="password"
                 placeholder="<?= lang('Auth.password') ?>">
          <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
          <div class="invalid-feedback">
            <?= session('errors.password') ?>
          </div>
        </div>

        <?php if ($config->allowRemembering): ?>
        <div class="form-check form-check-lg d-flex align-items-end">
          <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                 <?php if (old('remember')) : ?> checked <?php endif ?>>
          <label class="form-check-label text-gray-600" for="remember">
            <?= lang('Auth.rememberMe') ?>
          </label>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
          <?= lang('Auth.loginAction') ?>
        </button>
      </form>

      <div class="text-center mt-5 text-lg fs-4">
        <?php if ($config->allowRegistration) : ?>
          <p class="text-gray-600"><?= lang('Auth.needAnAccount') ?>
            <a href="<?= url_to('register') ?>" class="font-bold"><?= lang('Auth.register') ?></a>
          </p>
        <?php endif; ?>
        <?php if ($config->activeResetter): ?>
          <p><a class="font-bold" href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
        <?php endif; ?>
      </div>

    </div>
  </div>
  <div class="col-lg-7 d-none d-lg-block">
    <div id="auth-right">
      <!-- bisa kasih background image -->
    </div>
  </div>
</div>

<?= $this->endSection() ?>
