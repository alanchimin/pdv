# 🛒 PDV Supermercado

Sistema de Ponto de Venda (PDV) para gerenciamento de pedidos e produtos.

---

## ⚙️ Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/alanchimin/pdv.git
cd pdv
```

### 2. Configurar o arquivo .env
```bash
cp .env.development .env
```

### 3. Rodar a aplicação (utilizando docker)
```bash
docker-compose up -d
```

### 4. A aplicação estará disponível em:
[http://localhost:8080](http://localhost:8080)

---

## 🔐 Autenticação

- Login baseado em sessão.
- Cada usuário tem permissões de acesso definidas por tela (`usuario_tela`).
- Usuários e permissões estão vinculados diretamente às rotas disponíveis.
- Usuários disponíveis:
    - Login: admin | Senha: 1 | Acesso total
    - Login: autoatendimento | Senha: 1 | Acesso limitado à tela de Pedidos

---

## 🧪 Funcionalidades

### ✅ Produtos
- Cadastro com upload ou URL de imagem
- Associação com categoria e unidade de medida
- Desconto por percentual
- Busca e ordenação via AJAX

### ✅ Pedidos
- Interface com lista de categorias e produtos
- Carrinho de compras com quantidade e desconto
- Modal de confirmação com resumo dos itens e forma de pagamento
- Geração de PDF do pedido (via Dompdf)

### ✅ Categorias
- Cadastro com seleção de ícone via dropdown
- Ícones organizados com busca dinâmica

### ✅ Unidades de Medida
- Cadastro com nome e símbolo
- Integração direta com o form de produto

---

## 🚀 Tecnologias Utilizadas

- **Backend**: PHP (orientado a objetos, padrão MVC)
- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery
- **Banco de Dados**: MySQL (via PDO)
- **Outros recursos**:
  - Font Awesome (ícones dinâmicos)
  - Bootstrap Toasts e Modals
  - Sistema de permissões baseado em telas por usuário
  - Docker

---

## 🧱 Modelagem do Banco de Dados

Tabelas principais:

- `usuario`, `tela`, `usuario_tela`
- `produto`, `categoria`, `unidade_medida`
- `pedido`, `item`, `forma_pagamento`

Ver arquivo `migrations/01 - create_database.sql`.

