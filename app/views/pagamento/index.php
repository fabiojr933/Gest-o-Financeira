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
          <h3 class="card-title">Lista de condicao pagamentos</h3>
          <a href="<?php echo URL_BASE ?>pagamento/novo" class="btn btn-primary btn-sm float-right"> <i class="fas fa-plus"></i> Novo</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-striped table-sm">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Ativo</th>
                <th style="width: 80px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($dados) && is_array($dados)) { ?>

                <?php foreach ($dados as $pagamento) { ?>
                  <tr>
                    <td><?= $pagamento->nome ?? '' ?></td>                    
                    <td>
                      <span class="badge <?= ($pagamento->ativo ?? '') === 'S' ? 'bg-primary' : 'bg-danger' ?>">
                        <?= ($pagamento->ativo ?? '') === 'S' ? 'Ativo' : 'Inativo' ?>
                      </span>
                    </td>
                    <td>
                      <a href="/pagamento/editar/<?= $pagamento->id ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="/pagamento/excluir/<?= $pagamento->id ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir?')">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>

              <?php } else { ?>

                <tr>
                  <td colspan="3" class="text-center text-muted">Nenhum registro encontrado.</td>
                </tr>

              <?php } ?>
            </tbody>

          </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>

</div>
</section>
</div>