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


        <div class="card-footer clearfix">
          <a href="<?php echo URL_BASE ?>pagar/aberta" class="btn btn-primary btn-sm float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>


        <!-- Main content -->
        <div class="invoice p-3 mb-3">
          <!-- title row -->
          <div class="row">
            <div class="col-12">
              <h4>
                <i class="fas fa-globe"></i> lançamento N° <?= $dados->id ?>.
                <small style="color: red;" class="float-right">Data vencimento: <?= !empty($dados->data_vencimento) ? date('d/m/Y', strtotime($dados->data_vencimento)) : '' ?></small>
              </h4>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <form action="<?php echo URL_BASE ?>pagar/pagamento" method="post">
            <div class="row invoice-info">
              <input type="hidden" value="<?= $dados->id_parcela ?>" name="id_parcela"/>
              <div class="col-sm-4 invoice-col">

                <address>
                  <strong style="color: red;">Tipo = <?= $dados->status ?></strong><br>
                  Data emissão: <?= !empty($dados->data_emissao) ? date('d/m/Y', strtotime($dados->data_emissao)) : '' ?><br>
                  Data vencimento: <?= !empty($dados->data_vencimento) ? date('d/m/Y', strtotime($dados->data_vencimento)) : '' ?><br>
                  Descrição = <?= $dados->descricao ?><br>
                  Parcela = <?= $dados->numero_parcela ?><br>
                  Valor = <?= isset($dados->valor) ? 'R$ ' . number_format($dados->valor, 2, ',', '.') : '' ?><br>
                  Usuario = <?= $dados->usuario ?><br>
                  Fluxo = <?= $dados->conta ?><br>
                </address>
              </div>

              <div class="col-sm-8 invoice-col">
                <div class="form-group">
                  <label>Forma de pagamento</label>
                  <select class="form-control select2" name="id_pagamento" required>
                    <option value="">Selecione</option>
                    <?php foreach ($pagamento as $data) { ?>
                      <option value="<?= $data->id ?>"><?= $data->nome ?> </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Selecione de fluxo</label>
                  <select class="form-control select2" name="id_conta" required>
                    <option value="">Selecione</option>

                    <?php foreach ($despesa as $data) { ?>
                      <option
                        value="<?= $data->id ?>"
                        <?= (isset($dados->id_conta) && $data->id == $dados->id_conta) ? 'selected' : ''; ?>>
                        <?= $data->nome ?>
                      </option>
                    <?php } ?>

                  </select>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Pagar</button>
              </div>

            </div>
          </form>
        </div>


      </div>
    </div>
</div>
</section>
</div>