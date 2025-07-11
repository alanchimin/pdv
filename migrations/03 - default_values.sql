-- Inserindo unidades de medida
INSERT INTO unidade_medida (nome, simbolo) VALUES
('Unidade', 'Un'),
('Quilograma', 'Kg'),
('Litro', 'L'),
('Pacote', 'Pct');

-- Inserindo categorias de produtos
INSERT INTO categoria (categoria_id, nome, icone) VALUES
(1, 'Mercearia', 'fa-solid fa-shopping-cart'),
(2, 'Hortifruti', 'fa-solid fa-leaf'),
(3, 'Bebidas', 'fa-solid fa-coffee'),
(4, 'Limpeza', 'fa-solid fa-broom'),
(5, 'Padaria', 'fa-solid fa-bread-slice'),
(6, 'Congelados', 'fa-solid fa-snowflake'),
(7, 'Laticínios', 'fa-solid fa-cheese'),
(8, 'Carnes', 'fa-solid fa-drumstick-bite'),
(9, 'Higiene Pessoal', 'fa-solid fa-soap'),
(10, 'Pet Shop', 'fa-solid fa-paw'),
(11, 'Utilidades', 'fa-solid fa-wrench');

-- Inserindo produtos
-- Inserindo produtos com unidade_medida_id corrigido
INSERT INTO produto (produto_id, nome, imagem, unidade_medida_id, valor_unitario, desconto, categoria_id) VALUES
(1, 'Arroz 5kg', 'https://m.media-amazon.com/images/I/71rBEHnIkXL._UF894,1000_QL80_.jpg', 1, 24.90, NULL, 1),
(2, 'Feijão Carioca 1kg', 'https://carrefourbrfood.vtexassets.com/arquivos/ids/194917/466506_1.jpg?v=637272434027000000', 1, 8.50, NULL, 1),
(3, 'Açúcar Refinado 1kg', 'https://kioskbrazil.com/cdn/shop/files/1D5C4868-0765-41D0-9603-764B82B043E9.jpg?v=1741288887', 1, 4.90, 7.0, 1),
(4, 'Óleo de Soja 900ml', 'https://superprix.vteximg.com.br/arquivos/ids/176449-600-600/Oleo-de-Soja-Soya-900ml.png?v=636470371263970000', 1, 6.80, 0.50, 1),
(5, 'Farinha de Trigo 1kg', 'https://m.media-amazon.com/images/I/61IDVb04R7L.jpg', 1, 4.50, NULL, 1),
(6, 'Sal Refinado 1kg', 'https://www.extramercado.com.br/img/uploads/1/632/454632.jpg', 1, 2.20, 0.30, 1),
(7, 'Café Torrado e Moído 500g', 'https://mercafefaststore.vtexassets.com/arquivos/ids/554740/3C-TORRADO-MOIDO-TRADICIONAL-2.png?v=638666756478230000', 1, 10.90, NULL, 1),

(8, 'Maçã Gala', 'https://img.imageboss.me/fourwinds/width/425/dpr:2/shop/files/gala-apple-tree.jpg?v=1729795761', 2, 6.20, NULL, 2),
(9, 'Banana Prata', 'https://www.saborbrasil.it/wp-content/uploads/2021/06/banana-prata_www.ateliergourmand.com_-1.jpg', 2, 5.00, NULL, 2),
(10, 'Tomate', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCgJniepBc7BW-h_RyrsUX5nLgSlBDW-FsH4Sa3YcMs4_oMUtZvtJCNQ&s', 2, 8.00, 8.0, 2),
(11, 'Alface Crespa', 'https://c8.alamy.com/comp/P4149T/folha-de-alface-folha-de-alface-crespa-folha-verde-hortalia-P4149T.jpg', 2, 3.50, NULL, 2),
(12, 'Cenoura', 'https://static01.nyt.com/images/2023/03/17/multimedia/sd17komolaferex-carrot/sdg-buffalo-white-beans-wgzh-jumbo.jpg', 2, 4.20, 0.50, 2),
(13, 'Batata Inglesa', 'https://mercadoorganico.com/6428-large_default/batata-inglesa-organica-500g-osm.jpg', 2, 4.00, NULL, 2),
(14, 'Cebola', 'https://static.wikia.nocookie.net/juan-and-gustavos-multiverses/images/0/0e/Cebola-organica-500g.jpg/revision/latest?cb=20240419134218', 2, 3.90, NULL, 2),

(15, 'Cerveja Lata 350ml', 'https://coopsp.vtexassets.com/arquivos/ids/234164-800-auto?v=638336929199870000&width=800&height=auto&aspect=true', 1, 3.20, NULL, 3),
(16, 'Refrigerante 2L', 'https://www.pngkey.com/png/detail/403-4033794_refrigerante-guarana-png-guarana-antarctica-2-litros.png', 1, 7.50, NULL, 3),
(17, 'Suco de Laranja 1L', 'https://www.padariapampulha.com.br/wp-content/uploads/2023/12/95587.png', 1, 6.90, NULL, 3),
(18, 'Água Mineral 500ml', 'https://images.tcdn.com.br/img/img_prod/1347852/agua_mineral_500ml_47_1_442ee0555504435d411491f8cc14bebe.png', 1, 1.80, NULL, 3),
(19, 'Vinho Tinto Suave 750ml', 'http://brazilianshop.net/cdn/shop/files/Mioranza_Sweet_Red_Wine_750ml_Serra_Gaucha_-_Brazil_-_Brazilian_Shop-1.jpg?v=1750782237', 1, 24.90, 2.90, 3),

(20, 'Detergente Neutro', 'https://www.vermeister.com/sito/wp-content/uploads/2023/06/detergente_neutro.jpg', 1, 2.50, NULL, 4),
(21, 'Sabão em Pó 1kg', 'https://images.tcdn.com.br/img/img_prod/767437/sabao_em_po_omo_pacote_1kg_1017_1_20200408102937.jpg', 1, 9.90, 1.20, 4),
(22, 'Água Sanitária 1L', 'https://m.media-amazon.com/images/I/6187zjxAWkL.jpg', 1, 3.20, NULL, 4),
(23, 'Desinfetante 500ml', 'https://images.tcdn.com.br/img/img_prod/679238/desinfetante_500ml_3475_1_20200515090045.jpg', 1, 2.90, 0.40, 4),
(24, 'Esponja de Limpeza', 'https://domplastic.com.br/wp-content/uploads/2023/05/90_esponja_de_limpeza_dupla_face_scotch_brite_unitaria_1323_1_785ba206937b5161f91b21dadc17090a.jpg', 1, 1.50, NULL, 4),
(25, 'Papel Higiênico 4 rolos', 'https://www.santher.com.br/wp-content/uploads/2022/09/Papel-Higienico-personal-FD-4-ROLOS-20M.png', 1, 5.90, 7.0, 4),

(26, 'Pão Francês', 'https://cdn.2rscms.com.br/imgcache/5054/uploads/5054/layout/Linha%20Gold%20Paes/pao-frances-12h-gg.png.webp', 2, 14.00, NULL, 5),
(27, 'Pão de Forma Tradicional', 'https://www.extramercado.com.br/img/uploads/1/899/574899.jpg', 2, 6.80, NULL, 5),
(28, 'Bolo de Cenoura com Cobertura', 'https://tamiresmota.com/wp-content/uploads/2024/09/bolo-de-cenoura-com-cobertura-cremosa-de-chocolate-tamires-mota-1.jpg', 2, 12.90, 9.0, 5),
(29, 'Sonho com Creme', 'https://i.ytimg.com/vi/sdoU0Q1HYqA/maxresdefault.jpg', 2, 4.50, NULL, 5),
(30, 'Croissant de Presunto e Queijo', 'https://static1.confeiteirasdobrasil.com.br/articles/5/15/57/@/1936-croissant-de-presunto-e-queijo-article_gallery-1.jpg', 2, 5.20, NULL, 5),

(31, 'Pizza Calabresa Congelada', 'https://www.salgadosdempresarial.com.br/wp-content/uploads/2019/04/pizza-calabresa-congelada.jpg', 1, 17.90, NULL, 6),
(32, 'Lasanha Bolonhesa 600g', 'https://www.receitasnestle.com.br/sites/default/files/lasanha-bolonhesa.jpg', 1, 12.90, 7.0, 6),
(33, 'Hambúrguer Bovino Congelado (4 un)', 'https://img.kalunga.com.br/fotosdeprodutos/6695419/1.jpg', 1, 10.50, NULL, 6),

(34, 'Leite Integral 1L', 'https://cdn.panelinha.com.br/receita/1466389458507-leite-integral.jpg', 1, 4.60, NULL, 7),
(35, 'Queijo Mussarela (100g)', 'https://www.todamateria.com.br/upload/queijo-mussarela.jpg', 1, 4.90, NULL, 7),
(36, 'Iogurte Morango 170g', 'https://img.itdg.com.br/tdg/images/recipes/000/057/124/346131/346131_original.jpg', 1, 2.80, NULL, 7),

(37, 'Frango Inteiro Congelado', 'https://cdn.panelinha.com.br/receita/1455008309921-frango-inteiro-congelado.jpg', 2, 8.20, NULL, 8),
(38, 'Carne Moída (patinho)', 'https://www.receitasnestle.com.br/sites/default/files/carne-moida.jpg', 2, 26.90, NULL, 8),
(39, 'Linguiça Toscana', 'https://cdn.panelinha.com.br/receita/1448900779437-linguica-toscana.jpg', 2, 18.50, NULL, 8),

(40, 'Sabonete em Barra', 'https://www.baruel.com.br/wp-content/uploads/2024/11/Sabonete-Barra-Suave-70g-Baruel-Baby.png', 1, 2.20, 0.20, 9),
(41, 'Shampoo 350ml', 'http://fanola.hair/cdn/shop/files/fanola-volume-shampoo-350ml-f1096342000-625683.png?v=1751037705', 1, 9.80, NULL, 9),
(42, 'Creme Dental 90g', 'https://a-static.mlcdn.com.br/1500x1500/colgate-mpa-caries-menta-refrescante-creme-dental-90g/convenienciapablito/e3a13dba9abb11ee99814201ac185040/bbec09d7eed63c3988eef881443b56d5.jpeg', 1, 3.60, NULL, 9),
(43, 'Desodorante Roll-on', 'https://cdn.panelinha.com.br/receita/1453927876506-desodorante-rollon.jpg', 1, 7.90, 5.0, 9),

(44, 'Ração Seca Cães Adultos 1kg', 'https://images.tcdn.com.br/img/img_prod/658115/racao_para_caes_adultos_1kg_3463_1_20200515084451.jpg', 1, 12.50, NULL, 10),
(45, 'Ração Gatos Castrados 1kg', 'https://images.tcdn.com.br/img/img_prod/658116/racao_para_gatos_castrados_1kg_3464_1_20200515084515.jpg', 1, 15.90, 10.0, 10),
(46, 'Biscoito Canino 500g', 'https://cdn.awsli.com.br/600x450/35/35346/produto/40073682/biscoito-canino-500g-22e746a0b9.jpg', 1, 8.90, NULL, 10),

(47, 'Velas Parafina (8 un)', 'https://m.media-amazon.com/images/I/517H+-jDO0L.jpg', 1, 4.50, NULL, 11),
(48, 'Fósforos Caixa Grande', 'https://t10917.vteximg.com.br/arquivos/ids/168864-1000-1000/FOSFORO-CAIXA-GRANDE-200PALITOS-FIAT-LUX_IMG1.jpg?v=638742977403870000', 1, 1.50, NULL, 11),
(49, 'Pilha AA (2 un)', 'https://images.ludimusic.com/Imagens/Catalogo/Produtos/224422/pilha-toshiba-lr6gcp-bp-2-aa-alkaline-high-power-emb-2-unidades_1_922.jpg', 1, 5.90, 5.0, 11),
(50, 'Pano de Prato', 'https://live.staticflickr.com/2311/2326021204_bde17c2771_z.jpg', 1, 3.80, NULL, 11);

-- Inserindo formas de pagamento
INSERT INTO forma_pagamento (nome) VALUES
('Dinheiro'),
('Cartão de Crédito'),
('Cartão de Débito'),
('PIX'),
('Vale Alimentação'),
('Vale Refeição');

-- Inserir usuário admin com senha "1" usando bcrypt (hash gerado via PHP)
INSERT INTO usuario (usuario, senha) VALUES (
    'admin',
    '$2y$10$GMoks1SDe31zmw5OYW8vZOTVKeuugT0P5PCDfL/fF2NmZMtOOLmfW'
);
