# 🔧 Correção do Erro 403 Forbidden

## 🔍 Diagnóstico

O erro 403 Forbidden acontece por um destes motivos:
1. ❌ Permissões incorretas nos arquivos
2. ❌ DocumentRoot não configurado corretamente
3. ❌ Arquivo index.php não encontrado
4. ❌ .htaccess bloqueando acesso

## ✅ Solução Rápida (3 Opções)

### **Opção 1: Configurar DocumentRoot (RECOMENDADO)**

Esta é a melhor solução. Configure o DocumentRoot para apontar diretamente para a pasta `/public`.

#### No Painel Hostinger:

1. Acesse **Websites** no menu lateral
2. Clique no domínio **cleacasamentos.com.br**
3. Vá em **Advanced** ou **Avançado**
4. Procure por **Document Root** ou **Raiz do Documento**
5. Altere de:
   ```
   /public_html
   ```
   Para:
   ```
   /public_html/public
   ```
6. Clique em **Save** ou **Salvar**
7. Aguarde 1-2 minutos para propagar

#### Teste:
```
Acesse: https://cleacasamentos.com.br/
Deve carregar a landing page
```

---

### **Opção 2: Usar Redirecionamento via .htaccess**

Se não conseguir mudar o DocumentRoot, use redirecionamento.

#### Passo 1: Fazer upload do `.htaccess` na raiz

Fazer upload do arquivo `.htaccess` (criado na raiz do projeto) para `/public_html/.htaccess`

**Conteúdo do arquivo:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirecionar tudo para a pasta public
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Passo 2: Fazer upload do `index.php` na raiz

Fazer upload do arquivo `index.php` (criado na raiz do projeto) para `/public_html/index.php`

**Conteúdo do arquivo:**
```php
<?php
header('Location: /public/index.php');
exit;
```

#### Teste:
```
Acesse: https://cleacasamentos.com.br/
Deve redirecionar para https://cleacasamentos.com.br/public/index.php
```

---

### **Opção 3: Ajustar Permissões**

Se as opções anteriores não funcionarem, o problema pode ser permissões.

#### Via File Manager (Painel Hostinger):

1. Acesse **File Manager**
2. Navegue até `/public_html`
3. Selecione a pasta **public**
4. Clique com botão direito → **Permissions** ou **Permissões**
5. Configure:
   - **Folders (pastas)**: 755 (rwxr-xr-x)
   - **Files (arquivos)**: 644 (rw-r--r--)
6. Marque **Apply to subdirectories** ou **Aplicar a subdiretórios**
7. Clique em **Save**

#### Via SSH (se disponível):

```bash
cd /public_html
chmod -R 755 public/
find public/ -type f -exec chmod 644 {} \;
find public/ -type d -exec chmod 755 {} \;
```

---

## 🎯 Qual Opção Escolher?

| Situação | Solução Recomendada |
|----------|---------------------|
| Tenho acesso ao painel Hostinger | **Opção 1** (DocumentRoot) |
| Não consigo mudar DocumentRoot | **Opção 2** (Redirecionamento) |
| Nenhuma das anteriores funciona | **Opção 3** (Permissões) |

---

## 📋 Checklist de Verificação

Após aplicar a solução, verifique:

- [ ] https://cleacasamentos.com.br/ carrega (sem 403)
- [ ] https://cleacasamentos.com.br/vendors funciona
- [ ] https://cleacasamentos.com.br/about funciona
- [ ] https://cleacasamentos.com.br/test_config.php mostra testes

---

## 🐛 Troubleshooting Adicional

### Se AINDA der 403 após Opção 1:

**Verifique se existe arquivo `.htaccess` bloqueando:**

1. Acesse File Manager
2. Vá em `/public_html/public/`
3. Verifique se existe `.htaccess`
4. Abra e certifique-se que o conteúdo é:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Redireciona se não for um arquivo ou diretório real
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # Envia a requisição para index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

### Se AINDA der 403 após Opção 2:

**O problema pode ser o mod_rewrite inativo:**

1. Entre em contato com suporte Hostinger
2. Peça para ativar `mod_rewrite` no Apache
3. Ou teste sem rewrite:
   ```
   https://cleacasamentos.com.br/public/index.php
   ```

### Se aparecer lista de arquivos (Index of /):

**Adicione um arquivo `index.html` temporário:**

Crie em `/public_html/index.html`:
```html
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="0; url=/public/index.php">
</head>
<body>
    <p>Redirecionando...</p>
</body>
</html>
```

---

## 📞 Estrutura de Arquivos Esperada

```
/public_html/                    (raiz do servidor)
├── .htaccess                    (redireciona para /public)
├── index.php                    (redireciona para /public/index.php)
├── app/                         (código da aplicação)
├── config/                      (configurações)
├── public/                      ⭐ ESTA É A PASTA PRINCIPAL
│   ├── .htaccess               (regras de rewrite)
│   ├── index.php               (ponto de entrada)
│   ├── login.php
│   ├── test_config.php
│   └── ... (outros arquivos públicos)
└── migration_leads.sql
```

---

## ✅ Solução Definitiva Aplicada

Marque a solução que funcionou:

- [ ] **Opção 1**: DocumentRoot configurado para `/public_html/public` ✅
- [ ] **Opção 2**: Redirecionamento via .htaccess ✅
- [ ] **Opção 3**: Permissões ajustadas ✅

---

## 🎉 Teste Final

Quando funcionar, você deve ver:

1. **https://cleacasamentos.com.br/**
   - ✅ Landing page bonita
   - ✅ Menu de navegação
   - ✅ Seção de fornecedores em destaque

2. **https://cleacasamentos.com.br/test_config.php**
   - ✅ Todos os testes em verde
   - ✅ Banco de dados conectado

---

**Última atualização:** 02/10/2025
**Status:** Pronto para correção 🔧
