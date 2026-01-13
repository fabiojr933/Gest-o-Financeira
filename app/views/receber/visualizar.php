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
          <a href="<?php echo $metedo ?>" class="btn btn-primary btn-sm float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
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
          <div class="row invoice-info">
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
          </div>
        </div>


      </div>
    </div>
</div>
</section>
</div>