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


CREATE TABLE titulos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_conta INT NOT NULL,            
  id_cliente INT NULL,              
  id_fornecedor INT NULL,   
  id_pagamento int null,        
  descricao VARCHAR(200) NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  data_vencimento DATE NOT NULL,
  data_pagamento DATE NULL,
  status ENUM('ABERTO','PAGO','ATRASADO','CANCELADO') DEFAULT 'ABERTO',
  uuid CHAR(36) NOT NULL,
  id_usuario INT NOT NULL,
  FOREIGN KEY (id_conta) REFERENCES contas(id),
  FOREIGN KEY (id_cliente) REFERENCES clientes(id),
  FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_pagamento) REFERENCES pagamentos(id)
);
