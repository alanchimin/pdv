CREATE TABLE unidade_medida (
    unidade_medida_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    simbolo VARCHAR(10) NOT NULL
);

CREATE TABLE categoria (
    categoria_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    icone VARCHAR(255)
);

CREATE TABLE produto (
    produto_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    imagem VARCHAR(255),
    tipo_imagem ENUM('upload', 'url') NOT NULL DEFAULT 'url',
    unidade_medida_id INT NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    desconto DECIMAL(10,2),
    tipo_desconto ENUM('percentual', 'reais'),
    categoria_id INT NOT NULL,
    FOREIGN KEY (unidade_medida_id) REFERENCES unidade_medida(unidade_medida_id),
    FOREIGN KEY (categoria_id) REFERENCES categoria(categoria_id)
);

CREATE TABLE forma_pagamento (
    forma_pagamento_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE pedido (
    pedido_id INT AUTO_INCREMENT PRIMARY KEY,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    forma_pagamento_id INT NOT NULL,
    FOREIGN KEY (forma_pagamento_id) REFERENCES forma_pagamento(forma_pagamento_id)
);

CREATE TABLE item (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    quantidade INT NOT NULL,
    desconto_valor DECIMAL(10,2) DEFAULT 0,
    valor_unitario DECIMAL(10,2) NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    produto_id INT NOT NULL,
    pedido_id INT NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produto(produto_id),
    FOREIGN KEY (pedido_id) REFERENCES pedido(pedido_id)
);

CREATE TABLE usuario (
    usuario_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
