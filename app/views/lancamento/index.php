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


        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h3 class="card-title">Lista de lançamentos</h3>
              </div>
              <div class="col-sm-6">
                <form ction="<?php echo URL_BASE ?>lancamento/index" method="post" class="form-inline justify-content-md-end">
                  <label class="mr-2 mb-2 mb-sm-0"><strong>Pesquisa por data:</strong></label>
                  <input type="date" value="<?php echo $datas['inicio'] ?>" name="inicio" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                  <input type="date" value="<?php echo $datas['fim'] ?>" name="fim" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                  <button type="submit" class="btn btn-primary btn-sm mb-2 mb-sm-0">Carregar</button>
                </form>
              </div>
            </div>
          </div>
        </section>

        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-striped table-sm">
            <thead>
              <tr>
                <th>Descricao</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Tipo</th>
                <th style="width: 80px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($dados) && is_array($dados)) { ?>

                <?php foreach ($dados as $lancamento) { ?>
                  <tr>
                    <td><?= $lancamento->descricao ?? '' ?></td>
                    <td>
                      <?= isset($lancamento->valor)
                        ? 'R$ ' . number_format($lancamento->valor, 2, ',', '.')
                        : '' ?>
                    </td>
                    <td>
                      <?= !empty($lancamento->data)
                        ? date('d/m/Y', strtotime($lancamento->data))
                        : '' ?>
                    </td>

                    <td>
                      <span class="badge <?= ($lancamento->tipo ?? '') === 'CREDITO' ? 'bg-primary' : 'bg-danger' ?>">
                        <?= ($lancamento->tipo ?? '') === 'CREDITO' ? 'CREDITO' : 'DEBITO' ?>
                      </span>
                    </td>
                    <td>
                      <a href="/lancamento/visualizar/<?= $lancamento->id ?>" class="btn btn-sm btn-success">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="/lancamento/excluir/<?= $lancamento->id ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir?')">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>

              <?php } else { ?>

                <tr>
                  <td colspan="5" class="text-center text-muted">Nenhum registro encontrado.</td>
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