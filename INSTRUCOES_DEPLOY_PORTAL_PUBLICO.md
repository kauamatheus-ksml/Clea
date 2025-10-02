# 🚀 Instruções de Deploy - Portal Público

## 📋 Checklist de Deploy

### 1. Preparação Local

✅ **Executar script de correção de links:**
```bash
php fix_links.php
```

Este script atualiza automaticamente todos os links nas views para usar URLs absolutas.

### 2. Arquivos para Upload

📁 **Novos arquivos criados:**
- `app/Controllers/PublicController.php`
- `app/Views/public/home.php`
- `app/Views/public/vendors.php`
- `app/Views/public/vendor-detail.php`
- `app/Views/public/about.php`
- `app/Views/public/contact.php`
- `app/Views/public/register.php`
- `app/Core/Url.php`
- `app/helpers.php`
- `migration_leads.sql`

📝 **Arquivos modificados:**
- `public/index.php` (roteador principal)
- `public/.htaccess` (redirecionamento HTTPS)
- `config/config.php` (configuração de URLs)

### 3. Upload via FTP/SFTP

Faça upload dos arquivos para o servidor:

```
Servidor: srv406.hstgr.io (via FTP/SFTP)
Diretório: /public_html/ ou /httpdocs/

Estrutura:
/public_html/
├── app/
│   ├── Controllers/
│   │   └── PublicController.php (NOVO)
│   ├── Views/
│   │   └── public/ (NOVO - pasta completa)
│   ├── Core/
│   │   └── Url.php (NOVO)
│   └── helpers.php (NOVO)
├── config/
│   └── config.php (MODIFICADO)
├── public/
│   ├── index.php (MODIFICADO)
│   └── .htaccess (MODIFICADO)
└── migration_leads.sql (TEMPORÁRIO)
```

### 4. Criar Tabela de Leads no Banco

**Via phpMyAdmin (Hostinger):**
1. Acesse o painel da Hostinger
2. Vá em **Databases** → **phpMyAdmin**
3. Selecione o banco `u383946504_cleacasamentos`
4. Clique em **SQL**
5. Cole o conteúdo do arquivo `migration_leads.sql`
6. Clique em **Executar**

**Ou via SSH:**
```bash
mysql -h srv406.hstgr.io -u u383946504_cleacasamentos -p u383946504_cleacasamentos < migration_leads.sql
```

### 5. Configurar Domínio (Hostinger)

**Verificar configuração do domínio:**

1. **DNS configurado corretamente:**
   - Tipo A: `cleacasamentos.com.br` → IP do servidor
   - Tipo A: `www.cleacasamentos.com.br` → IP do servidor

2. **DocumentRoot apontando para /public:**
   - No painel Hostinger, vá em **Websites**
   - Selecione o domínio `cleacasamentos.com.br`
   - Em **Advanced** → **Document Root**, configure para: `/public_html/public`

3. **SSL/HTTPS:**
   - Ative o SSL gratuito Let's Encrypt no painel Hostinger
   - O `.htaccess` já está configurado para forçar HTTPS

### 6. Testar Funcionamento

Acesse as seguintes URLs para testar:

✅ **Páginas Públicas:**
- https://cleacasamentos.com.br/ (Landing page)
- https://cleacasamentos.com.br/vendors (Galeria de fornecedores)
- https://cleacasamentos.com.br/about (Sobre)
- https://cleacasamentos.com.br/contact (Contato)
- https://cleacasamentos.com.br/register (Registro)

✅ **Páginas Autenticadas (já existentes):**
- https://cleacasamentos.com.br/login.php
- https://cleacasamentos.com.br/client/dashboard
- https://cleacasamentos.com.br/vendor/dashboard
- https://cleacasamentos.com.br/admin/dashboard

### 7. Verificações Pós-Deploy

□ Landing page carrega corretamente
□ Todos os links funcionam (não retornam 404)
□ Filtros de fornecedores funcionam
□ Formulário de contato salva no banco
□ Formulário de registro cria usuários
□ HTTPS funciona (sem erros de certificado)
□ Redirecionamento automático para HTTPS

### 8. Troubleshooting

**Se a página não carregar (500 Error):**
```bash
# Verificar permissões
chmod -R 755 /public_html
chmod 644 /public_html/public/index.php
```

**Se os links não funcionarem:**
1. Verifique se o mod_rewrite está ativo no Apache
2. Verifique se o `.htaccess` foi enviado corretamente
3. Limpe o cache do navegador

**Se o banco não conectar:**
1. Verifique as credenciais em `config/config.php`
2. Teste a conexão com `teste_conexao.php`

**Se aparecer erro de sessão:**
```bash
# Criar diretório de sessões se não existir
mkdir -p /tmp/php_sessions
chmod 777 /tmp/php_sessions
```

## 📊 Status do Deploy

- [ ] Arquivos enviados via FTP
- [ ] Migração do banco executada
- [ ] DocumentRoot configurado para /public
- [ ] SSL ativado
- [ ] Site testado e funcionando
- [ ] Links verificados

## 🎯 Resultado Esperado

Após o deploy completo:
- ✅ https://cleacasamentos.com.br/ mostra a landing page
- ✅ Todos os links do menu funcionam
- ✅ Sistema de registro funciona
- ✅ Formulário de contato funciona
- ✅ Páginas autenticadas continuam funcionando

## 📞 Suporte

Em caso de dúvidas ou problemas:
1. Verifique os logs de erro do Apache
2. Consulte o painel da Hostinger
3. Execute `teste_conexao.php` para verificar banco

---

**Data:** 02/10/2025
**Versão:** 1.0-Beta
**Status:** Pronto para deploy 🚀
