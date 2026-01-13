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
          <h3 class="card-title">Lista de contas pagas</h3>
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
                <th>Cliente</th>
                <th>Documento</th>
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

                <?php foreach ($dados as $receber) { ?>
                  <tr>
                    <td><strong><?= $receber->cliente ?? '' ?></strong></td>
                    <td><strong>Documento <?= $receber->id ?? '' ?></strong></td>
                    <td><?= $receber->descricao ?? '' ?></td>
                    <td>
                      <?= !empty($receber->data_emissao)
                        ? date('d/m/Y', strtotime($receber->data_emissao))
                        : '' ?>
                    </td>
                    <td>
                      <?= !empty($receber->data_vencimento)
                        ? date('d/m/Y', strtotime($receber->data_vencimento))
                        : '' ?>
                    </td>
                    <td>
                      <span class="badge bg-primary">
                        <?= $receber->status  ?>
                      </span>
                    </td>
                    <td><?= $receber->numero_parcela ?? '' ?></td>
                    <td>
                      <?= isset($receber->valor)
                        ? 'R$ ' . number_format($receber->valor, 2, ',', '.')
                        : '' ?>
                    </td>
                    <td><?= $receber->conta ?? '' ?></td>

                    <td>
                      <a href="/receber/visualizar/<?= $receber->id_parcela ?>" class="btn btn-sm btn-primary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Visualizar parcela">
                        <i class="fas fa-eye"></i>
                      </a>
                      <form action="/receber/cancelarRecebimento"
                        method="post"
                        class="d-inline">
                        <input type="hidden" name="id_parcela" value="<?= $receber->id_parcela ?>" />
                        <button type="submit"
                          class="btn btn-sm btn-success"
                          title="Cancelar recebimento"
                          onclick="return confirm('Tem certeza que deseja cancelar o recebimento?')">
                          <i class="fas fa-times"></i>
                        </button>
                    </td>
                  </tr>
                <?php } ?>

              <?php } else { ?>
                <td>
                  <tr>
                    <td colspan="9" class="text-center text-muted">Nenhum registro encontrado.
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