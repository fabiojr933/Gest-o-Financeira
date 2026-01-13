<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">

      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h3 class="card-title"></h3>
            </div>
            <div class="col-sm-6">
              <form ction="<?php echo URL_BASE ?>dashboard" method="post" class="form-inline justify-content-md-end">
                <label class="mr-2 mb-2 mb-sm-0"><strong>Pesquisa por data:</strong></label>
                <input type="date" value="<?php echo $datas['inicio'] ?>" name="inicio" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                <input type="date" value="<?php echo $datas['fim'] ?>" name="fim" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                <button type="submit" class="btn btn-primary btn-sm mb-2 mb-sm-0">Carregar</button>
              </form>
            </div>
          </div>
        </div>
      </section>

      <div class="row">
        <!-- RECEITAS -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h2><?= isset($graficoTotaisTipo['RECEITA']) ? 'R$ ' . number_format($graficoTotaisTipo['RECEITA'], 2, ',', '.') : '00,00' ?></h2>
              <p>Receitas</p>
            </div>
            <div class="icon">
              <i class="fas fa-arrow-trend-up"></i>
            </div>
            <a href="#" class="small-box-footer">
              Ver detalhes <i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- DESPESAS -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h2><?= isset($graficoTotaisTipo['DESPESA']) ? 'R$ ' . number_format($graficoTotaisTipo['DESPESA'], 2, ',', '.') : '00,00' ?></h2>
              <p>Despesas</p>
            </div>
            <div class="icon">
              <i class="fas fa-arrow-trend-down"></i>
            </div>
            <a href="#" class="small-box-footer">
              Ver detalhes <i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- CONTAS A RECEBER -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h2><?= isset($contasReceberAbertas->total) ? 'R$ ' . number_format($contasReceberAbertas->total, 2, ',', '.') : '00,00' ?></h2>
              <p>Contas a Receber</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="#" class="small-box-footer">
              Ver títulos <i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>

        <!-- CONTAS A PAGAR -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h2><?= isset($contasPagarAbertas->total) ? 'R$ ' . number_format($contasPagarAbertas->total, 2, ',', '.') : '00,00' ?></h2>
              <p>Contas a Pagar</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-circle-xmark"></i>
            </div>
            <a href="#" class="small-box-footer">
              Ver vencimentos <i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Grafico</h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <div id="graficoTotaisTipo" style="min-height: 250px; height: 250px; max-height: 250px;"></div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>

        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Grafico</h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <div id="graficoTotaisFluxo" style="min-height: 250px; height: 250px; max-height: 250px;"></div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>




      <div class="row">
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Grafico</h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <div id="totaisCondicaoPagamento" style="min-height: 250px; height: 250px; max-height: 250px;"></div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>

        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Grafico</h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <div id="totaisUsuario" style="min-height: 250px; height: 250px; max-height: 250px;"></div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>


    </div>
  </section>
</div>

<?php
$labels = array_keys($graficoTotaisTipo);
$values = array_values($graficoTotaisTipo);
?>

<script>
  (function() {
    var labels = <?= json_encode($labels) ?>;
    var values = <?= json_encode($values) ?>;
    var el = document.getElementById('graficoTotaisTipo');

    // ✅ SEM DADOS
    if (!labels.length) {
      el.innerHTML = `
      <div class="empty-chart">
        <i class="fas fa-chart-pie"></i>
        <p>Sem dados para o período selecionado</p>
      </div>
    `;
      return;
    }

    var chart = echarts.init(el);

    chart.setOption({
      title: {
        text: 'Despesa x Receita',
        left: 'center'
      },
      tooltip: {
        trigger: 'item',
        formatter: '{b}: R$ {c}'
      },
      legend: {
        bottom: 0
      },
      series: [{
        type: 'pie',
        radius: '60%',
        data: labels.map((l, i) => ({
          name: l,
          value: values[i]
        }))
      }]
    });

    window.addEventListener('resize', () => chart.resize());
  })();
</script>

<?php
$labelsTotaisFluxo = array_keys($graficoTotaisFluxo);
$valuesTotaisFluxo = array_values($graficoTotaisFluxo);
?>

<script>
  (function() {
    var labels = <?= json_encode($labelsTotaisFluxo) ?>;
    var values = <?= json_encode($valuesTotaisFluxo) ?>;
    var el = document.getElementById('graficoTotaisFluxo');

    // ✅ SEM DADOS
    if (!labels.length) {
      el.innerHTML = `
      <div class="empty-chart">
        <i class="fas fa-chart-bar"></i>
        <p>Nenhuma movimentação encontrada</p>
      </div>
    `;
      return;
    }

    var chart = echarts.init(el);

    chart.setOption({
      title: {
        text: 'Fluxo Financeiro',
        left: 'center'
      },
      tooltip: {
        trigger: 'axis',
        formatter: '{b}: R$ {c}'
      },
      xAxis: {
        type: 'category',
        data: labels
      },
      yAxis: {
        type: 'value'
      },
      series: [{
        type: 'bar',
        data: values
      }]
    });

    window.addEventListener('resize', () => chart.resize());
  })();
</script>



<?php
$labelsPagamento = array_keys($totaisCondicaoPagamento);
$valuesPagamento = array_values($totaisCondicaoPagamento);
?>


<script>
  (function() {
    var labels = <?= json_encode($labelsPagamento) ?>;
    var values = <?= json_encode($valuesPagamento) ?>;
    var el = document.getElementById('totaisCondicaoPagamento');

    // ✅ SEM DADOS
    if (!labels.length) {
      el.innerHTML = `
      <div class="empty-chart">
        <i class="fas fa-chart-bar"></i>
        <p>Nenhuma movimentação encontrada</p>
      </div>
    `;
      return;
    }

    var chart = echarts.init(el);

    chart.setOption({
      title: {
        text: 'Despesas por condição de pagamento',
        left: 'center'
      },
      tooltip: {
        trigger: 'axis',
        formatter: '{b}: R$ {c}'
      },
      xAxis: {
        type: 'category',
        data: labels
      },
      yAxis: {
        type: 'value'
      },
      series: [{
        type: 'bar',
        data: values
      }]
    });

    window.addEventListener('resize', () => chart.resize());
  })();
</script>




<?php
$labelsUsuario = array_keys($totaisUsuario);
$valuesUsuario = array_values($totaisUsuario);
?>

<script>
  (function() {
    var labels = <?= json_encode($labelsUsuario) ?>;
    var values = <?= json_encode($valuesUsuario) ?>;
    var el = document.getElementById('totaisUsuario');

    // ✅ SEM DADOS
    if (!labels.length) {
      el.innerHTML = `
      <div class="empty-chart">
        <i class="fas fa-chart-pie"></i>
        <p>Sem dados para o período selecionado</p>
      </div>
    `;
      return;
    }

    var chart = echarts.init(el);

    chart.setOption({
      title: {
        text: 'Despesa por usuario',
        left: 'center'
      },
      tooltip: {
        trigger: 'item',
        formatter: '{b}: R$ {c}'
      },
      legend: {
        bottom: 0
      },
      series: [{
        type: 'pie',
        radius: '60%',
        data: labels.map((l, i) => ({
          name: l,
          value: values[i]
        }))
      }]
    });

    window.addEventListener('resize', () => chart.resize());
  })();
</script>