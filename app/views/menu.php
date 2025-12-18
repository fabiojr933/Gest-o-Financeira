<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo URL_BASE ?>assets/index3.html" class="brand-link">
        <img src="<?php echo URL_BASE ?>assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="<?php echo URL_BASE ?>assets/#" class="d-block"><?php echo isset($_SESSION[SESSION_LOGIN]['nome']) ? $_SESSION[SESSION_LOGIN]['nome'] : "Visitante"; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>assets/#" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>

                        <p>
                            Cadastros
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="margin-left: 10px;">

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>usuario/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Usuario</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>despesa/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Despesa</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>receita/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Receita</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>pagamento/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Cond. de Pagamento</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>cliente/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Cliente</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>fornecedor/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Fornecedor</p>
                            </a>
                        </li>

                    </ul>
                </li>





                 <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>assets/#" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>

                        <p>
                            Lançamentos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="margin-left: 10px;">

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>lancamento/novo" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Novo Lançamento</p>
                            </a>
                        </li>   
                         <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>lancamento/index" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Listar Lançamentos</p>
                            </a>
                        </li>                      

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>assets/#" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>

                        <p>
                             Contas a Pagar
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="margin-left: 10px;">

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>pagar/novo" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Novo Lançamento</p>
                            </a>
                        </li>   
                         <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>pagar/aberta" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Em Aberto</p>
                            </a>
                        </li>    
                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Vencidas</p>
                            </a>
                        </li>  
                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Pagas</p>
                            </a>
                        </li>                    

                    </ul>
                </li>

                 <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>assets/#" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>

                        <p>
                             Contas a Receber
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="margin-left: 10px;">

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Novo Lançamento</p>
                            </a>
                        </li>   
                         <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Em Aberto</p>
                            </a>
                        </li>    
                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Vencidas</p>
                            </a>
                        </li>  
                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Recebidas</p>
                            </a>
                        </li>                    

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>assets/#" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>

                        <p>
                            Financeiro
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="margin-left: 10px;">

                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Fluxo financeiro</p>
                            </a>
                        </li>   
                         <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Despesas x Receitas</p>
                            </a>
                        </li>    
                        <li class="nav-item">
                            <a href="<?php echo URL_BASE ?>#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Pagar x Receber</p>
                            </a>
                        </li>                                           

                    </ul>
                </li>


               

                <li class="nav-item">
                    <a href="<?php echo URL_BASE ?>login/sair" class="nav-link">
                       <i class="fas fa-sign-out-alt"></i>                        
                        <p>
                            Sair
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>


