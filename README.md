# Discofor - Platform Academic para Publicação e Debates

Plataforma web acadêmica para publicação de artigos, interação social e debates em tempo real com painel administrativo completo.

## Tecnologias

- **Backend**: Laravel 11+ (PHP 8.2+)
- **Frontend**: Blade Templates + Bootstrap 5
- **Banco de Dados**: MySQL
- **Real-time Chat**: Laravel Echo + Pusher (WebSockets)
- **Autenticação**: Laravel Breeze
- **APIs**: RESTful com Laravel Sanctum

## Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js 18+ (para assets)
- Git

## Instalação

### 1. Clone o repositório
```bash
git clone <repository-url>
cd discofor
```

### 2. Instale as dependências
```bash
composer install
npm install
```

### 3. Configure o arquivo .env
```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` com suas configurações:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=discofor
DB_USERNAME=root
DB_PASSWORD=sua_senha

PUSHER_APP_ID=seu_pusher_id
PUSHER_APP_KEY=seu_pusher_key
PUSHER_APP_SECRET=seu_pusher_secret
```

### 4. Configure o banco de dados
```bash
php artisan migrate
php artisan db:seed
```

### 5. Execute o servidor
```bash
php artisan serve
npm run dev
```

A aplicação estará disponível em `http://localhost:8000`

## Estrutura do Projeto

```
├── app/
│   ├── Models/           # Modelos Eloquent
│   ├── Http/Controllers/ # Controllers
│   ├── Http/Requests/    # Form Requests
│   ├── Listeners/        # Event Listeners
│   └── Services/         # Business Logic
├── routes/
│   ├── web.php           # Web Routes
│   └── api.php           # API Routes
├── resources/
│   ├── views/            # Blade Templates
│   ├── css/              # Estilos
│   └── js/               # JavaScript
├── database/
│   ├── migrations/       # Migrations
│   └── seeders/          # Seeders
└── config/               # Configurações
```

## Funcionalidades

### Módulo de Artigos
- Criar, editar e deletar artigos
- Aprovação por administrador
- Sistema de tags
- Upload de imagens
- Feed com filtros e pesquisa

### Comentários e Curtidas
- Comentar em artigos
- Sistema de curtidas com AJAX
- Edição e deleção de próprios comentários

### Debates em Tempo Real
- Chat em tempo real com WebSockets
- Histórico de mensagens
- Notificações sonoras

### Notificações
- Notificações em tempo real
- Badge com contador
- Marca como lida

### Painel do Usuário
- Dashboard com estatísticas
- Histórico de atividade
- Perfil personalizável

### Painel Administrativo
- Gestão de usuários
- Aprovação de artigos
- Estatísticas globais
- Gráficos e relatórios

## Roles e Permissões

### User (Usuário)
- Criar artigos (pendentes de aprovação)
- Comentar em artigos
- Curtir artigos
- Participar de debates
- Editar próprio perfil

### Admin (Administrador)
- Acesso ao painel administrativo
- Aprovar/rejeitar artigos
- Gerenciar usuários
- Ver estatísticas
- Moderar comentários

## Segurança

- CSRF Protection em todos os forms
- Rate limiting no login
- Policies do Laravel para autorização
- Form Requests para validação centralizada
- Hash de senhas com bcrypt
- Soft deletes para dados sensíveis

## API REST

Endpoints disponíveis em `/api/` com autenticação Sanctum:

- `GET /api/articles` - Listar artigos
- `GET /api/articles/{slug}` - Detalhes de artigo
- `POST /api/articles` - Criar artigo
- `POST /api/comments` - Criar comentário
- `POST /api/likes` - Curtir artigo
- `GET /api/debates` - Listar debates
- `POST /api/messages` - Enviar mensagem

## Desenvolvimento

### Criar uma nova migration
```bash
php artisan make:migration create_table_name
```

### Criar um novo model
```bash
php artisan make:model ModelName
```

### Criar um novo controller
```bash
php artisan make:controller ControllerName
```

### Executar testes
```bash
php artisan test
```

## Boas Práticas

1. **Commits**: Use mensagens descritivas e em português
2. **Code**: Siga as convenções PSR-12
3. **Database**: Use migrations para todas as mudanças
4. **Models**: Adicione relacionamentos e métodos úteis
5. **Controllers**: Mantenha a lógica limpa, use Services
6. **Validação**: Use Form Requests ao invés de validar no controller

## Troubleshooting

### Erro de permissão no storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Banco de dados não encontrado
```bash
php artisan db:create
php artisan migrate
```

### Assets não compilam
```bash
npm install
npm run dev
```

## Contribuindo

1. Crie um branch para sua feature (`git checkout -b feature/AmazingFeature`)
2. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
3. Push para o branch (`git push origin feature/AmazingFeature`)
4. Abra um Pull Request

## Licença

MIT License - Veja LICENSE.md para detalhes

## Suporte

Para questões e suporte, abra uma issue no repositório.
