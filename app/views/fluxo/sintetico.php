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





            <section class="content">
                <div class="card">

                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h3 class="card-title"></h3>
                                </div>
                                <div class="col-sm-6">
                                    <form ction="<?php echo URL_BASE ?>fluxo/sintetico" method="post" class="form-inline justify-content-md-end">
                                        <label class="mr-2 mb-2 mb-sm-0"><strong>Pesquisa por data:</strong></label>
                                        <input type="date" value="<?php echo $datas['inicio'] ?>" name="inicio" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                                        <input type="date" value="<?php echo $datas['fim'] ?>" name="fim" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" required>
                                        <button type="submit" class="btn btn-primary btn-sm mb-2 mb-sm-0">Carregar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-primary">
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" class="text-center">DEMONSTRAÇÃO DE RESULTADO DO EXERCÍCIO</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr style="background: lightgrey">
                                                    <td><b> RECEITA</b></td>
                                                    <td><b>TOTAL</b></td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td style="padding-left: 50px;"><strong>(+) Receita Fixa</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php if (!empty($dados) && is_array($dados)) { ?>
                                                    <?php foreach ($dados as $receitaFixa) { ?>
                                                        <?php if ($receitaFixa->tipo == 'RECEITA' & $receitaFixa->natureza == 'FIXO') {  ?>
                                                            <tr>
                                                                <td style="padding-left: 50px;">(+) <?= $receitaFixa->fluxo ?></td>
                                                                <td style="color: blue;"> <?= isset($receitaFixa->total)
                                                                                                ? 'R$ ' . number_format($receitaFixa->total, 2, ',', '.')
                                                                                                : '' ?></td>
                                                                <td></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td style="padding-left: 50px;">(+) Nenhum registro encontrado.</td>
                                                        <td style="color: blue;">R$ 00,00</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td style="padding-left: 50px;"><strong>(+) Receita Variavel</strong></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>


                                                    <?php if (!empty($dados) && is_array($dados)) { ?>
                                                        <?php foreach ($dados as $receitaVariavel) { ?>
                                                            <?php if ($receitaVariavel->tipo == 'RECEITA' & $receitaVariavel->natureza == 'VARIAVEL') {  ?>
                                                                <tr>
                                                                    <td style="padding-left: 50px;">(+) <?= $receitaVariavel->fluxo ?></td>
                                                                    <td style="color: blue;"> <?= isset($receitaVariavel->total)
                                                                                                    ? 'R$ ' . number_format($receitaVariavel->total, 2, ',', '.')
                                                                                                    : '' ?></td>
                                                                    <td></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td style="padding-left: 50px;">(+) Nenhum registro encontrado.</td>
                                                            <td style="color: blue;">R$ 00,00</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                        <?php } ?>
                                                        <tr style="background: lightgrey">
                                                            <td><b>(=) TOTAL RECEITA FIXA</b></td>
                                                            <td><b> R$ <?= number_format($totais['RECEITA']['FIXO'] ?? 0, 2, ',', '.'); ?></b></td>
                                                            <td></td>
                                                        </tr>

                                                        <tr style="background: lightgrey">
                                                            <td><b>(=) TOTAL RECEITA VARIAVEL</b></td>
                                                            <td><b> R$ <?= number_format($totais['RECEITA']['VARIAVEL'] ?? 0, 2, ',', '.'); ?></b></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-left: 50px;"> </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr style="background: lightgrey">
                                                            <td><b> DESPESAS</b></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding-left: 50px;"><strong>(-) Despesas Fixa</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>

                                                        <?php if (!empty($dados) && is_array($dados)) { ?>
                                                            <?php foreach ($dados as $despesaFixa) { ?>
                                                                <?php if ($despesaFixa->tipo == 'DESPESA' & $despesaFixa->natureza == 'FIXO') {  ?>
                                                                    <tr>
                                                                        <td style="padding-left: 50px;">(-) <?= $despesaFixa->fluxo ?></td>
                                                                        <td style="color: red;"> <?= isset($despesaFixa->total)
                                                                                                        ? 'R$ ' . number_format($despesaFixa->total, 2, ',', '.')
                                                                                                        : '' ?></td>
                                                                        <td></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td style="padding-left: 50px;">(+) Nenhum registro encontrado.</td>
                                                                <td style="color: red;">R$ 00,00</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                            <?php } ?>

                                                            <tr>
                                                                <td style="padding-left: 50px;"><strong>(-) Despesas Variavel</strong></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>

                                                            <?php if (!empty($dados) && is_array($dados)) { ?>
                                                                <?php foreach ($dados as $despesaVariavel) { ?>
                                                                    <?php if ($despesaVariavel->tipo == 'DESPESA' & $despesaVariavel->natureza == 'VARIAVEL') {  ?>
                                                                        <tr>
                                                                            <td style="padding-left: 50px;">(-) <?= $despesaVariavel->fluxo ?></td>
                                                                            <td style="color: red;"> <?= isset($despesaVariavel->total)
                                                                                                            ? 'R$ ' . number_format($despesaVariavel->total, 2, ',', '.')
                                                                                                            : '' ?></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td style="padding-left: 50px;">(+) Nenhum registro encontrado.</td>
                                                                    <td style="color: red;">R$ 00,00</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                <?php } ?>

                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) TOTAL DESPESAS FIXA</b></td>
                                                                    <td><b>R$ <?= number_format($totais['DESPESA']['FIXO'] ?? 0, 2, ',', '.'); ?></b></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) TOTAL DESPESAS VARIAVEL</b></td>
                                                                    <td><b>R$ <?= number_format($totais['DESPESA']['VARIAVEL'] ?? 0, 2, ',', '.'); ?></b></td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding-left: 50px;"> </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 50px;"> </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) (-) TOTAL DE DESPESAS</b></td>
                                                                    <td><b> R$ <?= number_format(($totais['DESPESA']['FIXO'] ?? 0) + ($totais['DESPESA']['VARIAVEL'] ?? 0), 2, ',', '.'); ?></b></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) (+) TOTAL DE RECEITAS</b></td>
                                                                    <td><b> R$ <?= number_format(($totais['RECEITA']['FIXO'] ?? 0) + ($totais['RECEITA']['VARIAVEL'] ?? 0), 2, ',', '.'); ?></b></td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding-left: 50px;"> </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) LUCRO LÍQUIDO</b></td>
                                                                    <td>
                                                                        <b>
                                                                            R$ <?= number_format(
                                                                                    (
                                                                                        ($totais['RECEITA']['FIXO'] ?? 0) +
                                                                                        ($totais['RECEITA']['VARIAVEL'] ?? 0)
                                                                                    ) - (
                                                                                        ($totais['DESPESA']['FIXO'] ?? 0) +
                                                                                        ($totais['DESPESA']['VARIAVEL'] ?? 0)
                                                                                    ),
                                                                                    2,
                                                                                    ',',
                                                                                    '.'
                                                                                ); ?>
                                                                        </b>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>


                                                                <tr>
                                                                    <td style="padding-left: 50px;"> </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
<!--
                                                                <tr style="background: lightgrey">
                                                                    <td><b> RESERVA FINANCEIRA</b></td>
                                                                    <td><b>TOTAL</b></td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding-left: 50px;">(+) Fundo de Emergência</td>
                                                                    <td style="color: blue;">R$ 10.000,00</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 50px;">(+) Reserva para Expansão</td>
                                                                    <td style="color: blue;">R$ 5.000,00</td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr style="background: lightgrey">
                                                                    <td><b>(=) TOTAL RESERVA FINANCEIRA</b></td>
                                                                    <td>
                                                                        <b style="color: blue;">R$ 15.000,00</b>
                                                                    </td>
                                                                    <td></td>
                                                                </tr> -->

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
</div>

</section>
</div>