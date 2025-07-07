-- Inserindo unidades de medida
INSERT INTO unidade_medida (nome, simbolo) VALUES
('Unidade', 'un'),
('Quilograma', 'kg'),
('Litro', 'l'),
('Pacote', 'pct');

-- Inserindo categorias de produtos
INSERT INTO categoria (categoria_id, nome) VALUES
(1, 'Mercearia'),
(2, 'Hortifruti'),
(3, 'Bebidas'),
(4, 'Limpeza'),
(5, 'Padaria'),
(6, 'Congelados'),
(7, 'Laticínios'),
(8, 'Carnes'),
(9, 'Higiene Pessoal'),
(10, 'Pet Shop'),
(11, 'Utilidades'),
(12, 'Padaria Doce');

-- Inserindo produtos
INSERT INTO produto (produto_id, nome, imagem, unidade_medida_id, valor_unitario, categoria_id) VALUES
(1, 'Arroz 5kg', NULL, 4, 24.90, 1),
(2, 'Feijão Carioca 1kg', NULL, 4, 8.50, 1),
(3, 'Açúcar Refinado 1kg', NULL, 4, 4.90, 1),
(4, 'Óleo de Soja 900ml', NULL, 3, 6.80, 1),
(5, 'Farinha de Trigo 1kg', NULL, 4, 4.50, 1),
(6, 'Sal Refinado 1kg', NULL, 4, 2.20, 1),
(7, 'Café Torrado e Moído 500g', NULL, 4, 10.90, 1),

(8, 'Maçã Gala', NULL, 2, 6.20, 2),
(9, 'Banana Prata', NULL, 2, 5.00, 2),
(10, 'Tomate', NULL, 2, 8.00, 2),
(11, 'Alface Crespa', NULL, 1, 3.50, 2),
(12, 'Cenoura', NULL, 2, 4.20, 2),
(13, 'Batata Inglesa', NULL, 2, 4.00, 2),
(14, 'Cebola', NULL, 2, 3.90, 2),

(15, 'Cerveja Lata 350ml', NULL, 1, 3.20, 3),
(16, 'Refrigerante 2L', NULL, 3, 7.50, 3),
(17, 'Suco de Laranja 1L', NULL, 3, 6.90, 3),
(18, 'Água Mineral 500ml', NULL, 3, 1.80, 3),
(19, 'Vinho Tinto Suave 750ml', NULL, 3, 24.90, 3),

(20, 'Detergente Neutro', NULL, 1, 2.50, 4),
(21, 'Sabão em Pó 1kg', NULL, 4, 9.90, 4),
(22, 'Água Sanitária 1L', NULL, 3, 3.20, 4),
(23, 'Desinfetante 500ml', NULL, 3, 2.90, 4),
(24, 'Esponja de Limpeza', NULL, 1, 1.50, 4),
(25, 'Papel Higiênico 4 rolos', NULL, 4, 5.90, 4),

(26, 'Pão Francês', NULL, 2, 14.00, 5),
(27, 'Pão de Forma Tradicional', NULL, 4, 6.80, 5),

(28, 'Bolo de Cenoura com Cobertura', NULL, 1, 12.90, 12),
(29, 'Sonho com Creme', NULL, 1, 4.50, 12),
(30, 'Croissant de Presunto e Queijo', NULL, 1, 5.20, 12),

(31, 'Pizza Calabresa Congelada', NULL, 1, 17.90, 6),
(32, 'Lasanha Bolonhesa 600g', NULL, 1, 12.90, 6),
(33, 'Hambúrguer Bovino Congelado (4 un)', NULL, 1, 10.50, 6),

(34, 'Leite Integral 1L', NULL, 3, 4.60, 7),
(35, 'Queijo Mussarela (100g)', NULL, 2, 4.90, 7),
(36, 'Iogurte Morango 170g', NULL, 1, 2.80, 7),

(37, 'Frango Inteiro Congelado', NULL, 2, 8.20, 8),
(38, 'Carne Moída (patinho)', NULL, 2, 26.90, 8),
(39, 'Linguiça Toscana', NULL, 2, 18.50, 8),

(40, 'Sabonete em Barra', NULL, 1, 2.20, 9),
(41, 'Shampoo 350ml', NULL, 3, 9.80, 9),
(42, 'Creme Dental 90g', NULL, 1, 3.60, 9),
(43, 'Desodorante Roll-on', NULL, 1, 7.90, 9),

(44, 'Ração Seca Cães Adultos 1kg', NULL, 4, 12.50, 10),
(45, 'Ração Gatos Castrados 1kg', NULL, 4, 15.90, 10),
(46, 'Biscoito Canino 500g', NULL, 4, 8.90, 10),

(47, 'Velas Parafina (8 un)', NULL, 4, 4.50, 11),
(48, 'Fósforos Caixa Grande', NULL, 1, 1.50, 11),
(49, 'Pilha AA (2 un)', NULL, 1, 5.90, 11),
(50, 'Pano de Prato', NULL, 1, 3.80, 11);

-- Inserindo formas de pagamento
INSERT INTO forma_pagamento (nome) VALUES
('Dinheiro'),
('Cartão de Crédito'),
('Cartão de Débito'),
('PIX'),
('Vale Alimentação');

-- Inserir usuário admin com senha "1234" usando bcrypt (hash gerado via PHP)
INSERT INTO usuario (usuario, senha) VALUES (
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);
