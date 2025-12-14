<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">


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


      <div class="card">
        <div class="card-footer clearfix">
          <a href="<?php echo URL_BASE ?>receita/index" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Preencha os dados abaixo</h3>
            </div>
            <form action="<?php echo URL_BASE ?>receita/atualizar" method="post">

              <div class="card-body">
                <div class="row">
                  <input type="number" hidden value="<?= isset($dados->id) ? $dados->id : null ?>" name="id"></input>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" value="<?= isset($dados->nome) ? $dados->nome : '' ?>" class="form-control" placeholder="Nome" name="nome">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Ativo</label>
                      <select name="ativo" class="form-control">
                        <option value="S" <?= ($dados->ativo ?? '') === 'S' ? 'selected' : '' ?>>S</option>
                        <option value="N" <?= ($dados->ativo ?? '') === 'N' ? 'selected' : '' ?>>N</option>
                      </select>

                    </div>
                  </div>


                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                          class="custom-control-input"
                          id="fixa"
                          name="natureza"
                          value="1"
                          <?php echo (isset($dados->natureza) && $dados->natureza == 'FIXO') ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="fixa">Despesa Fixa</label>
                      </div>
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