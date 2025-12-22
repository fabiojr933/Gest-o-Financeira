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
          <h3 class="card-title">Lista de contas vencidas</h3>
          <!--  <nav class="main-header navbar navbar-expand">
            <ul class="navbar-nav ml-auto d-flex align-items-center" style="gap: 8px;">
              <form action="<?php echo URL_BASE ?>lancamento/index" method="post" class="d-flex align-items-center" style="gap: 8px;">
               <input type="date" value="<?php echo $datas['inicio'] ?>" name="inicio" class="form-control form-control-sm" required>
            <input type="date" value="<?php echo $datas['fim'] ?>" name="fim" class="form-control form-control-sm" required>
                <button type="submit" class="btn btn-primary btn-sm">Carregar</button>
              </form>
            </ul>
          </nav> -->
        </div>


        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabelaContas" class="table table-striped table-sm">
            <thead>
              <tr>
                <th>fornecedor</th>
                <th>Descricao</th>
                <th>Data emissao</th>
                <th>Data vencimento</th>
                <th>status</th>
                <th>Parcela</th>
                <th>valor</th>
                <th>conta</th>
                <th style="width: 100px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($dados) && is_iterable($dados) && count($dados) > 0) { ?>

                <?php foreach ($dados as $pagar) { ?>
                  <tr>
                    <td><?= $pagar->fornecedor ?? '' ?></td>
                    <td><?= $pagar->descricao ?? '' ?></td>
                    <td>
                      <?= !empty($pagar->data_emissao)
                        ? date('d/m/Y', strtotime($pagar->data_emissao))
                        : '' ?>
                    </td>
                    <td>
                      <?= !empty($pagar->data_vencimento)
                        ? date('d/m/Y', strtotime($pagar->data_vencimento))
                        : '' ?>
                    </td>
                    <td>
                      <span class="badge bg-danger">
                        <?= $pagar->status  ?>
                      </span>
                    </td>
                    <td><?= $pagar->numero_parcela ?? '' ?></td>
                    <td>
                      <?= isset($pagar->valor)
                        ? 'R$ ' . number_format($pagar->valor, 2, ',', '.')
                        : '' ?>
                    </td>
                    <td><?= $pagar->conta ?? '' ?></td>

                    <td>
                      <a href="/pagar/visualizar/<?= $pagar->id_parcela ?>" class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Visualizar parcela">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="/pagar/pagar/<?= $pagar->id_parcela ?>" class="btn btn-sm btn-success"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Pagar">
                        <i class="fas fa-money-bill"></i>
                      </a>
                      <a href="/pagar/excluir/<?= $pagar->id_parcela ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir?')"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Excluir">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>

              <?php } else { ?>

                <td>
                  <tr>
                    <td colspan="9" class="text-center text-muted">Nenhum registro encontrado.</td>
                  </tr>
                </td>

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