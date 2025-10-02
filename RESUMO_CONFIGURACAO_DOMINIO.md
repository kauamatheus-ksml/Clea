# 🌐 Configuração do Domínio - Guia Rápido

## ✅ O Que Foi Feito

### 1. Configuração de URLs
- ✅ `.htaccess` configurado para redirecionar para `index.php`
- ✅ Forçar HTTPS em todas as páginas
- ✅ Sistema de URLs absolutas implementado
- ✅ Helper functions criadas (`url()`, `asset()`, etc)
- ✅ Todas as views atualizadas com links corretos

### 2. Configuração do Domínio
```php
// config/config.php
define('BASE_URL', 'https://cleacasamentos.com.br');
define('APP_URL', 'https://cleacasamentos.com.br');
```

### 3. Estrutura de URLs

**Páginas Públicas:**
- `https://cleacasamentos.com.br/` → Landing page
- `https://cleacasamentos.com.br/vendors` → Galeria de fornecedores
- `https://cleacasamentos.com.br/vendor-detail?id=X` → Detalhes do fornecedor
- `https://cleacasamentos.com.br/about` → Sobre
- `https://cleacasamentos.com.br/contact` → Contato
- `https://cleacasamentos.com.br/register` → Registro

**Páginas Autenticadas:**
- `https://cleacasamentos.com.br/login.php` → Login
- `https://cleacasamentos.com.br/client/dashboard` → Dashboard Cliente
- `https://cleacasamentos.com.br/vendor/dashboard` → Dashboard Fornecedor
- `https://cleacasamentos.com.br/admin/dashboard` → Dashboard Admin

## 🚀 Ações Necessárias no Servidor (Hostinger)

### Passo 1: Configurar DocumentRoot

No painel da Hostinger:
1. Acesse **Websites** → Seu domínio
2. Vá em **Advanced** → **Document Root**
3. Configure para: `/public_html/public` (ou `/httpdocs/public`)

**Importante:** O DocumentRoot DEVE apontar para a pasta `/public`, não para a raiz do projeto!

### Passo 2: Verificar mod_rewrite

O Apache precisa ter `mod_rewrite` ativado. Na Hostinger, geralmente já vem ativo.

Para verificar, crie um arquivo `test.php`:
```php
<?php
phpinfo();
// Procure por "mod_rewrite" na lista de módulos
```

### Passo 3: Upload dos Arquivos

**Via FileZilla/FTP:**
```
Host: srv406.hstgr.io
Username: u383946504_cleacasamentos
Porta: 21 (FTP) ou 22 (SFTP)

Estrutura no servidor:
/public_html/
├── app/ (toda a pasta)
├── config/ (toda a pasta)
├── public/ (toda a pasta - este é o DocumentRoot!)
│   ├── index.php (ponto de entrada)
│   └── .htaccess (regras de redirecionamento)
└── migration_leads.sql
```

### Passo 4: Executar Migração do Banco

**Via phpMyAdmin:**
1. Acesse phpMyAdmin no painel Hostinger
2. Selecione banco `u383946504_cleacasamentos`
3. Clique em **SQL**
4. Cole o conteúdo de `migration_leads.sql`:
```sql
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text,
  `status` enum('novo','contatado','convertido','perdido') DEFAULT 'novo',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
5. Clique **Executar**

### Passo 5: Configurar SSL (HTTPS)

No painel Hostinger:
1. Vá em **SSL** → **Manage SSL**
2. Ative o **Let's Encrypt SSL** (gratuito)
3. Aguarde a ativação (pode levar alguns minutos)
4. O `.htaccess` já está configurado para forçar HTTPS

### Passo 6: Verificar Permissões

**Via SSH ou File Manager:**
```bash
# Permissões recomendadas
chmod -R 755 /public_html
chmod 644 /public_html/public/index.php
chmod 644 /public_html/public/.htaccess
```

## 🧪 Testes

Após configurar, teste as seguintes URLs:

### Teste 1: Landing Page
```
URL: https://cleacasamentos.com.br/
Esperado: Landing page carrega com imagens e estilo
```

### Teste 2: Navegação
```
Clique em "Fornecedores" no menu
Esperado: Abre https://cleacasamentos.com.br/vendors
```

### Teste 3: Formulário de Contato
```
URL: https://cleacasamentos.com.br/contact
Preencha e envie o formulário
Esperado: Mensagem de sucesso e dados salvos no banco
```

### Teste 4: Registro
```
URL: https://cleacasamentos.com.br/register
Crie uma conta de teste
Esperado: Conta criada e redirecionamento para login
```

### Teste 5: Login
```
URL: https://cleacasamentos.com.br/login.php
Faça login com conta criada
Esperado: Redirecionamento para dashboard correto
```

## ❌ Problemas Comuns

### Erro 500 (Internal Server Error)
**Causa:** Permissões incorretas ou erro no `.htaccess`

**Solução:**
```bash
chmod 644 /public_html/public/.htaccess
# Verifique os logs de erro em: /public_html/logs/error.log
```

### Página em branco
**Causa:** Erro PHP não exibido

**Solução:**
```php
// Adicione temporariamente no início do index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### 404 Not Found
**Causa:** DocumentRoot não configurado corretamente

**Solução:**
- Verifique se o DocumentRoot aponta para `/public_html/public`
- Verifique se o arquivo `.htaccess` existe em `/public_html/public/`

### Links não funcionam
**Causa:** mod_rewrite não ativo

**Solução:**
- Verifique se `mod_rewrite` está ativo no Apache
- Teste com: `https://cleacasamentos.com.br/index.php/vendors` (deve funcionar)

## 📋 Checklist Final

- [ ] DocumentRoot configurado para `/public`
- [ ] Arquivos enviados via FTP
- [ ] Migração do banco executada
- [ ] SSL ativado (HTTPS)
- [ ] Permissões configuradas
- [ ] Landing page funciona
- [ ] Menu de navegação funciona
- [ ] Formulários funcionam
- [ ] Login funciona
- [ ] Dashboards funcionam

## 🎉 Resultado Final

Após todas as configurações:
- ✅ https://cleacasamentos.com.br mostra o site público
- ✅ Visitantes podem explorar fornecedores
- ✅ Visitantes podem criar contas
- ✅ Sistema completo funcional

---

**Configuração realizada em:** 02/10/2025
**Domínio:** https://cleacasamentos.com.br
**Servidor:** srv406.hstgr.io (Hostinger)
**Status:** ✅ Pronto para deploy
