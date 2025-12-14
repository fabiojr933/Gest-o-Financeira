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
          <a href="<?php echo URL_BASE ?>lancamento/index" class="btn btn-primary btn-sm float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>


        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Preencha os dados abaixo</h3>
            </div>
            <form action="<?php echo URL_BASE ?>lancamento/salvar" method="post">

              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Descriçao</label>
                      <input type="text" class="form-control" placeholder="Descrição" name="descricao" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Pagamento</label>
                      <select class="form-control select2" name="id_pagamento" required>
                        <option value="">Selecione</option>
                        <?php foreach ($pagamento as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Data conciliação</label>
                      <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" placeholder="Data" name="data" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group" id="valor">
                      <label>Valor</label>
                      <input type="text" class="form-control" placeholder="Valor" name="valor" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Usuario</label>
                      <select class="form-control select2" name="id_usuario" required>
                        <option value="">Selecione</option>
                        <?php foreach ($usuario as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Tipo</label>
                      <select class="form-control" name="tipo" id="tipo" onchange="alteraTipo()" required>
                        <option value="DEBITO"> Debito </option>
                        <option value="CREDITO"> Credito </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row" id="id_despesa">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Despesa</label>
                      <select class="form-control select2" name="id_conta" id="select_despesa">
                        <option value="">Selecione</option>
                        <?php foreach ($despesa as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row" id="id_receita">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Receita</label>
                      <select class="form-control select2" name="id_conta" id="select_receita">
                        <option value="">Selecione</option>
                        <?php foreach ($receita as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
</section>
</div>



<script>
  function alteraTipo() {
    const tipo = document.getElementById('tipo').value;

    const blocoDespesa = document.getElementById('id_despesa');
    const blocoReceita = document.getElementById('id_receita');

    const selectDespesa = document.getElementById('select_despesa');
    const selectReceita = document.getElementById('select_receita');

    if (tipo === 'DEBITO') {
      // Mostra despesa
      blocoDespesa.hidden = false;
      selectDespesa.disabled = false;

      // Esconde receita
      blocoReceita.hidden = true;
      selectReceita.disabled = true;
    } else {
      // Mostra receita
      blocoReceita.hidden = false;
      selectReceita.disabled = false;

      // Esconde despesa
      blocoDespesa.hidden = true;
      selectDespesa.disabled = true;
    }
  }

  alteraTipo();
</script>