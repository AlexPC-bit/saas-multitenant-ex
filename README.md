# Business Manager

SaaS multi-tenant de gestão para pequenas empresas — clientes, produtos e vendas, com isolamento completo de dados entre empresas e autenticação em duas etapas.

🔗 [Ver projeto em produção](https://saas-multitenant-ex.onrender.com) · 📄 [Documentação de regras de negócio](docs/regras-de-negocio.pdf)

## Por que esse projeto

Esse foi o primeiro projeto que construí reconstruindo meu portfólio depois de um tempo afastado do mercado. Escolhi multi-tenancy de propósito, queria provar que sei lidar com o problema central desse tipo de arquitetura — garantir que os dados de uma empresa jamais vazem pra outra.

## Stack

- Laravel 11 (PHP)
- Breeze para autenticação de sessão (telas) + Sanctum para autenticação de API (tokens)
- Laravel Fortify para autenticação em duas etapas (2FA)
- SQLite

## O que o sistema faz

- Registro cria automaticamente uma Empresa e o primeiro usuário como `owner`
- CRUD completo de Clientes e Produtos
- Registro de Vendas com múltiplos itens e cálculo automático de total
- Dashboard com métricas (vendas do mês, produtos mais vendidos), cacheado por empresa
- Job em fila pra geração de relatório mensal
- Autenticação em duas etapas via app autenticador (Google Authenticator, Authy)
- Rate limiting nas rotas autenticadas
- API REST completa, além da interface web — os dois caminhos passam pelas mesmas regras de negócio e pelo mesmo isolamento de tenant

## Rodando localmente

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```


