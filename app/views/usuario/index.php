<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="card">

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


        <div class="card-footer">
          <h3 class="card-title">Lista de usuario</h3>
          <a href="<?php echo URL_BASE ?>usuario/novo" class="btn btn-primary btn-sm float-right"> <i class="fas fa-plus"></i> Novo</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-striped table-sm">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ativo</th>
                <th style="width: 80px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dados as $usuario) { ?>
                <tr>
                  <td><?php echo $usuario->nome ?></td>
                  <td><?php echo $usuario->email ?></td>
                  <td>
                    <span class="badge <?= $usuario->ativo === 'S' ? 'bg-primary' : 'bg-danger' ?>">
                      <?= $usuario->ativo === 'S' ? 'Ativo' : 'Inativo' ?>
                    </span>
                  </td>

                  <td>
                    <a href="/usuario/editar/<?php echo $usuario->id ?>" class="btn btn-sm btn-warning" title="Editar">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="/usuario/excluir/<?php echo $usuario->id ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir?')">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </td>
                <?php } ?>
                </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>

</div>
</section>
</div>