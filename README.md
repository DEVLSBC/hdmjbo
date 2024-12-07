# HDMJBO - Site Interno

Este é um projeto para o desenvolvimento de um sistema interno para gestão e acesso de informações, utilizando **PHP**, **JavaScript**, **MySQL**, **HTML**, e **CSS**.

## 📋 Funcionalidades

- Sistema de login seguro (hashing de senhas).
- Dashboard para visualização de informações.
- Gerenciamento de usuários e permissões.
- Estrutura modular e organizada.

## 🚀 Tecnologias Utilizadas

- **Backend**: PHP
- **Banco de Dados**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Servidor Local**: XAMPP

## 🛠️ Estrutura de Pastas

```plaintext
hdmjbo/
├── assets/
│   ├── css/
│   ├── js/
│   └── imagens/
├── includes/
│   └── db.php
│   ├── footer.php
│   └── header.php
├── pages/
│   ├── login.php
│   ├── dashboard.php
│   └── logout.php
├── README.md
├── setup.php
└── index.php
```

## 🧩 Pré-requisitos
- PHP 7.4+
- Servidor local configurado (XAMPP, WAMP, ou LAMP)
- MySQL 5.7+

## 📝 Instalação
1. Clone este repositório:

```bash
git clone https://github.com/DEVLSBC/hdmjbo.git
```
2. Configure o banco de dados:
    - **APAGUE** o arquivo db.php da pasta includes
    - Utilize o arquivo setup.php para configurar o banco de dados. **APAGUE** o arquivo setup.php após configuração

4. Inicie o servidor local e acesse o projeto em http://localhost/hdmjbo/setup.php.
