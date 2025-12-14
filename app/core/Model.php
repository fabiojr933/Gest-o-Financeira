<?php

namespace app\core;

use Exception;

abstract class Model{
    protected $db;
    protected $tabela;
    
    public function __construct() {
        $this->db = Conexao::getConexao();
    }
  
}

