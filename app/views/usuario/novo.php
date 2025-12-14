<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-footer clearfix">
          <a href="<?php echo URL_BASE ?>usuario/index" class="btn btn-primary btn-sm float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>


        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Preencha os dados abaixo</h3>
            </div>
            <form action="<?php echo URL_BASE ?>usuario/salvar" method="post">

              <div class="card-body">
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" class="form-control" placeholder="Nome" name="nome">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="text" class="form-control" placeholder="Email" name="email">
                    </div>
                  </div>
                </div>
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Telefone</label>
                      <input type="text" class="form-control telefone" placeholder="telefone" name="telefone">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Senha</label>
                      <input type="password" class="form-control" placeholder="Senha" name="senha">
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