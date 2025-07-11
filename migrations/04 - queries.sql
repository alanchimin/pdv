USE pdv;

select * from unidade_medida order by unidade_medida_id desc;
select * from categoria order by categoria_id desc;
select * from produto order by produto_id desc;
select * from forma_pagamento order by forma_pagamento_id desc;
select * from usuario order by usuario_id desc;
select * from pedido order by pedido_id desc;
select * from item order by item_id desc;
select * from tela order by tela_id desc;
select * from usuario_tela order by usuario_id desc, tela_id desc;
