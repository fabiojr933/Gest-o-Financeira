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

        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Preencha os dados abaixo</h3>
            </div>
            <form action="<?php echo URL_BASE ?>receber/salvar" method="post">

              <div class="card-body">

                <div class="row">
                  <div class="col-sm-12">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="descricao" placeholder="Descricao" required>
                  </div>
                </div>



                <div class="row">
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>É parcelado?</label>
                      <select class="form-control select2" style="width: 100%;" id="id_parcelado" name="id_parcelado" required>
                        <option value="S">Sim</option>
                        <option value="N" selected>Não</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Tipo</label>
                      <select class="form-control select2" style="width: 100%;" id="tipo" name="tipo" required>
                        <option value="ABERTO" selected>ABERTO</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Cliente</label>
                      <select class="form-control select2" style="width: 100%;" name="id_cliente" required>
                        <?php foreach ($cliente as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Usuario</label>
                      <select class="form-control select2" style="width: 100%;" name="id_usuario" required>
                        <?php foreach ($usuario as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-sm-3" id="valor">
                    <label for="valor">Valor Total</label>
                    <input type="text" class="form-control" name="valor_total" id="valor_total" placeholder="R$ 0,00" required>
                  </div>
                  <div class="col-sm-3">
                    <label for="parcela">Parcela</label>
                    <input type="number" class="form-control" name="parcela" id="parcela" required>
                  </div>

                  <div class="col-sm-6" id="bloco-despesa">
                    <div class="form-group">
                      <label>Receita</label>
                      <select class="form-control select2" style="width: 100%;" name="origem_id" required>
                        <?php foreach ($receita as $data) { ?>
                          <option value="<?= $data->id ?>"><?= $data->nome ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>

                <div id="parcelas-container" class="mt-3"></div>
              </div>


          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="btnSalvar">Salvar</button>
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
  $(document).ready(function() {
    // Aplica a máscara ao campo de saldo
    $('#valor input[name="valor_total"]').inputmask('currency', {
      radixPoint: ',',
      groupSeparator: '.',
      allowMinus: false, // Descomente esta linha se quiser permitir números negativos
      prefix: '',
      autoUnmask: true
    });
  });
</script>

<script>
  function calcularParcelas(valorTotal, numParcelas) {
    let totalEmCentavos = Math.round(valorTotal * 100);
    let valorParcelaInteira = Math.floor(totalEmCentavos / numParcelas);
    let resto = totalEmCentavos - (valorParcelaInteira * numParcelas);

    let parcelas = [];
    for (let i = 0; i < numParcelas; i++) {
      let valorParcela = valorParcelaInteira;
      if (i < resto) valorParcela += 1;
      parcelas.push((valorParcela / 100).toFixed(2));
    }

    return parcelas;
  }

  function formatarValor(valor) {
    return 'R$ ' + valor.replace('.', ',');
  }

  function gerarParcelas() {
    const idParcelado = $('#id_parcelado').val();
    const parcelaInput = $('#parcela');
    const container = $('#parcelas-container');

    if (idParcelado === 'N') {
      parcelaInput.val(1).prop('readonly', true); // ✅ Coloca valor 1 e bloqueia o campo
      //   container.empty(); // Limpa as parcelas se "Não"
      //  return;
    } else {
      parcelaInput.prop('readonly', false); // ✅ Libera o campo se "Sim"
    }

    const numParcelas = parseInt(parcelaInput.val());
    const valorTotalTexto = $('#valor_total').val().replace(/\D/g, '');

    if (!valorTotalTexto || isNaN(numParcelas) || numParcelas <= 0) {
      container.empty();
      return;
    }

    const valorTotal = parseFloat(valorTotalTexto) / 100;
    const parcelas = calcularParcelas(valorTotal, numParcelas);

    container.empty();

    parcelas.forEach((valor, index) => {
      container.append(`
        <div class="row mb-2">
          <div class="col-sm-6">
            <label>Valor da parcela ${index + 1}</label>
            <input type="text" class="form-control" name="valor_parcela[]" value="${formatarValor(valor)}" required>
          </div>
          <div class="col-sm-3">
            <label>Parcela ${index + 1}</label>
            <input type="text" class="form-control" name="numero_parcela[]" value="${index + 1}x" readonly required>
          </div>
          <div class="col-sm-3">
            <label>Vencimento da parcela ${index + 1}</label>
            <input type="date" class="form-control" name="vencimento[]" required>
          </div>
        </div>
      `);
    });
  }

  // Quando muda o select "É parcelado?"
  $('#id_parcelado').on('change', function() {
    gerarParcelas();
  });

  // Quando altera valor ou número de parcelas
  $('#parcela, #valor_total').on('blur', function() {
    gerarParcelas();
  });
</script>

<script>
  function parseValorBR(valor) {
    return parseFloat(valor.replace('R$', '').replace(/\./g, '').replace(',', '.')) || 0;
  }

  $('#btnSalvar').on('click', function() {
    const valorTotal = parseValorBR($('#valor_total').val());
    let somaParcelas = 0;

    $('input[name="valor_parcela[]"]').each(function() {
      somaParcelas += parseValorBR($(this).val());
    });

    const valorTotalArredondado = parseFloat(valorTotal.toFixed(2));
    const somaParcelasArredondado = parseFloat(somaParcelas.toFixed(2));

    if (valorTotalArredondado !== somaParcelasArredondado) {
      alert('❌ Erro: A soma das parcelas (' +
        somaParcelasArredondado.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        }) +
        ') não bate com o valor total (' +
        valorTotalArredondado.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        }) +
        ').');
      return false; // ⚠️ Impede envio
    }

    const vencimentos = $('input[name="vencimento[]"]').map(function() {
      return $(this).val();
    }).get();

    let dataAnterior = null;
    for (let i = 0; i < vencimentos.length; i++) {
      const dataAtual = vencimentos[i];

      if (!dataAtual) {
        alert(`❌ Preencha a data da parcela ${i + 1}.`);
        return false; // ⚠️ Impede envio
      }

      const dataAtualDate = new Date(dataAtual);

      if (dataAnterior && dataAtualDate < dataAnterior) {
        alert(`❌ A data da parcela ${i + 1} (${dataAtual}) não pode ser menor que a parcela ${i}.`);
        return false; // ⚠️ Impede envio
      }

      dataAnterior = dataAtualDate;
    }

    // ✅ Se chegou até aqui, pode salvar:
    //  alert('✅ Validação OK! Pode salvar.');
    //   $('form').submit();
  });
</script>