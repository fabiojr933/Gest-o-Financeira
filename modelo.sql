/*
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `ativo` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
);

CREATE TABLE contas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  tipo ENUM('RECEITA','DESPESA') NOT NULL,
  natureza ENUM('FIXO','VARIAVEL') NOT NULL,
  ativo CHAR(1) NOT NULL DEFAULT 'S',
  data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  uuid CHAR(36) NOT NULL
);


CREATE TABLE `pagamentos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `clientes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `fornecedores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE lancamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  descricao VARCHAR(255) NOT NULL,
  data DATE NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  tipo ENUM('DEBITO','CREDITO') NOT NULL,
  id_conta INT NOT NULL,
  id_usuario INT NOT NULL,
  id_pagamento INT NOT NULL,
  uuid CHAR(36) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (id_conta) REFERENCES contas(id),
  FOREIGN KEY (id_pagamento) REFERENCES pagamentos(id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);


CREATE TABLE contas_pagar (
  id INT AUTO_INCREMENT PRIMARY KEY, 
  descricao VARCHAR(200) NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  qtde_parcelas INT NOT NULL,
  parcelado CHAR(1) NOT NULL, -- S / N
  data_emissao DATETIME DEFAULT CURRENT_TIMESTAMP,
  uuid CHAR(36) NOT NULL,
  id_usuario INT NOT NULL,
  id_fornecedor INT NULL, 
  FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)  
);

CREATE TABLE contas_pagar_parcelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_conta_pagar INT NOT NULL, 
  id_conta INT NOT NULL,
  id_pagamento INT NULL,
  numero_parcela INT NOT NULL,   
  valor DECIMAL(10,2) NOT NULL,
  data_vencimento DATE NOT NULL,
  data_pagamento DATE NULL,
  pago_por varchar(120) null,
  status ENUM('ABERTO','PAGO') DEFAULT 'ABERTO',
  FOREIGN KEY (id_conta) REFERENCES contas(id),
  FOREIGN KEY (id_conta_pagar) REFERENCES contas_pagar(id),
  FOREIGN KEY (id_pagamento) REFERENCES pagamentos(id)
);
*/

-- 1. Tabelas Base (Sem Chaves Estrangeiras)
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `ativo` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE `contas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(150) NOT NULL,
  `tipo` ENUM('RECEITA','DESPESA') NOT NULL,
  `natureza` ENUM('FIXO','VARIAVEL') NOT NULL,
  `ativo` CHAR(1) NOT NULL DEFAULT 'S',
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uuid` CHAR(36) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `pagamentos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `clientes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `fornecedores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(130) NOT NULL,  
  `uuid` CHAR(36) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- 2. Tabelas com Relacionamentos (Chaves Estrangeiras)
CREATE TABLE `lancamentos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `descricao` VARCHAR(255) NOT NULL,
  `data` DATE NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `tipo` ENUM('DEBITO','CREDITO') NOT NULL,
  `id_conta` INT NOT NULL,
  `id_usuario` INT NOT NULL,
  `id_pagamento` INT NOT NULL,
  `uuid` CHAR(36) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_lanc_conta` FOREIGN KEY (`id_conta`) REFERENCES `contas` (`id`),
  CONSTRAINT `fk_lanc_pagto` FOREIGN KEY (`id_pagamento`) REFERENCES `pagamentos` (`id`),
  CONSTRAINT `fk_lanc_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `contas_pagar` (
  `id` INT AUTO_INCREMENT PRIMARY KEY, 
  `descricao` VARCHAR(200) NOT NULL,
  `valor_total` DECIMAL(10,2) NOT NULL,
  `qtde_parcelas` INT NOT NULL,
  `parcelado` CHAR(1) NOT NULL,
  `data_emissao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `uuid` CHAR(36) NOT NULL,
  `id_usuario` INT NOT NULL,
  `id_fornecedor` INT NULL, 
  CONSTRAINT `fk_pagar_forn` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_pagar_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)  
) ENGINE=InnoDB;

CREATE TABLE `contas_pagar_parcelas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_conta_pagar` INT NOT NULL, 
  `id_conta` INT NOT NULL,
  `id_pagamento` INT NULL,
  `numero_parcela` INT NOT NULL,   
  `valor` DECIMAL(10,2) NOT NULL,
  `data_vencimento` DATE NOT NULL,
  `data_pagamento` DATE NULL,
  `pago_por` VARCHAR(120) NULL,
  `status` ENUM('ABERTO','PAGO') DEFAULT 'ABERTO',
  CONSTRAINT `fk_parc_conta` FOREIGN KEY (`id_conta`) REFERENCES `contas` (`id`),
  CONSTRAINT `fk_parc_principal` FOREIGN KEY (`id_conta_pagar`) REFERENCES `contas_pagar` (`id`),
  CONSTRAINT `fk_parc_pagto` FOREIGN KEY (`id_pagamento`) REFERENCES `pagamentos` (`id`)
) ENGINE=InnoDB;

