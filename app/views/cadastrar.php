<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Registration Page (v2)</title>


  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo URL_BASE ?>assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URL_BASE ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo URL_BASE ?>assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">

  <?php if ($msg = getFlash('success')): ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> <?= $msg ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <?php if ($msg = getFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle"></i> <?= $msg ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="<?php echo URL_BASE ?>assets/index2.html" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">

      
        <p class="login-box-msg">Preencha os dados abaixo</p>

        <form action="<?php echo URL_BASE ?>login/criarUsuario" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Nome" name="nome" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="number" class="form-control" placeholder="telefone" name="telefone">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="senha" name="senha" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="social-auth-links text-center">
            <button class="btn btn-block btn-primary" name="btnCadastrar">
              <i class=""></i>
              Cadastrar
            </button>
            <a href="<?php echo URL_BASE ?>login" class="btn btn-block btn-danger">
              <i class=""></i>
              Voltar
            </a>
          </div>
        </form>


      </div>
    </div>
  </div>

  <script src="<?php echo URL_BASE ?>assets/plugins/jquery/jquery.min.js"></script>
  <script src="<?php echo URL_BASE ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo URL_BASE ?>assets/dist/js/adminlte.min.js"></script>
</body>

</html>