# 📤 UPLOAD DOS ARQUIVOS CORRIGIDOS

## ✅ PROBLEMA IDENTIFICADO E CORRIGIDO

**Erro Original:**
```
Fatal error: Class "App\Core\Router" not found
```

**Causa:**
- A pasta estava nomeada `core` (minúsculo)
- O namespace usa `Core` (maiúsculo)
- Linux é case-sensitive (diferencia maiúsculas de minúsculas)

**Correção Aplicada:**
- ✅ Pasta renomeada de `app/core/` para `app/Core/`
- ✅ Agora está compatível com Linux/servidor

---

## 🚀 AÇÃO NECESSÁRIA: RE-UPLOAD

Você precisa fazer **re-upload** da pasta `app/` com a estrutura correta.

### **IMPORTANTE: DELETE A PASTA ANTIGA PRIMEIRO**

#### Via File Manager (Hostinger):

1. Acesse **File Manager**
2. Navegue até `/public_html/`
3. **DELETE** a pasta `app/` antiga
4. Faça upload da nova pasta `app/`

#### Via FileZilla:

1. Conecte no servidor
2. Vá em `/public_html/`
3. Clique com botão direito em `app/` → **Delete**
4. Arraste a nova pasta `app/` do seu computador

---

## 📁 ESTRUTURA CORRETA (CASE-SENSITIVE)

```
/public_html/
├── app/
│   ├── Controllers/         ✅ C maiúsculo
│   │   ├── AdminController.php
│   │   ├── ClientController.php
│   │   ├── VendorController.php
│   │   └── PublicController.php
│   │
│   ├── Core/               ⭐ C maiúsculo (IMPORTANTE!)
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   ├── Router.php
│   │   └── Url.php
│   │
│   ├── Models/             ✅ M maiúsculo
│   │   ├── Client.php
│   │   ├── User.php
│   │   ├── Vendor.php
│   │   └── Wedding.php
│   │
│   ├── Views/              ✅ V maiúsculo
│   │   ├── admin/
│   │   ├── client/
│   │   ├── vendor/
│   │   ├── public/
│   │   └── layouts/
│   │
│   └── helpers.php         ✅ minúsculo
│
├── config/
│   └── config.php
│
├── public/
│   ├── index.php
│   ├── login.php
│   ├── diagnostico.php
│   └── test_config.php
│
└── migration_leads.sql
```

---

## ✅ CHECKLIST DE UPLOAD

Marque conforme for fazendo:

### Passo 1: Preparação
- [ ] Abrir FileZilla ou File Manager
- [ ] Conectar no servidor
- [ ] Navegar até `/public_html/`

### Passo 2: Limpeza
- [ ] **DELETE** a pasta `app/` antiga do servidor
- [ ] Confirmar que foi deletada

### Passo 3: Upload
- [ ] Fazer upload da pasta `app/` completa
- [ ] Aguardar upload completo (pode levar alguns minutos)

### Passo 4: Verificação
- [ ] Verificar que existe `/public_html/app/Core/` (com C maiúsculo)
- [ ] Verificar que existe `/public_html/app/Core/Router.php`

### Passo 5: Teste
- [ ] Acessar: `https://cleacasamentos.com.br/`
- [ ] Verificar se carrega sem erros

---

## 🧪 TESTES APÓS UPLOAD

### Teste 1: Página Principal
```
URL: https://cleacasamentos.com.br/
Esperado: Landing page bonita (sem erro)
```

### Teste 2: Diagnóstico
```
URL: https://cleacasamentos.com.br/diagnostico.php
Esperado: Página de diagnóstico com testes verdes
```

### Teste 3: Fornecedores
```
URL: https://cleacasamentos.com.br/vendors
Esperado: Lista de fornecedores
```

---

## 🐛 SE AINDA DER ERRO

### Erro: "Class not found"

**Verifique:**
1. A pasta é `app/Core/` (com C maiúsculo)?
2. Os arquivos existem dentro dela?
3. As permissões estão corretas (755 para pastas, 644 para arquivos)?

**Teste via diagnóstico:**
```
https://cleacasamentos.com.br/diagnostico.php
```

Ele mostrará quais arquivos estão faltando.

---

### Erro: "Permission denied"

**Solução:**
```bash
# Via File Manager:
Selecionar pasta app/ → Permissions → 755
Marcar "Apply to subdirectories"
```

---

### Erro: "Config file not found"

**Verifique:**
1. Arquivo `config/config.php` existe?
2. Permissão está correta (644)?

---

## 📊 ARQUIVOS QUE DEVEM EXISTIR NO SERVIDOR

Após o upload, verifique se existem:

```
✅ /public_html/app/Core/Router.php
✅ /public_html/app/Core/Database.php
✅ /public_html/app/Core/Auth.php
✅ /public_html/app/Core/Url.php
✅ /public_html/app/Controllers/PublicController.php
✅ /public_html/app/helpers.php
✅ /public_html/config/config.php
✅ /public_html/public/index.php
```

---

## 🎯 QUANDO FUNCIONAR

Você verá a landing page sem erros:

```
╔════════════════════════════════════════╗
║           🎨 Clea                      ║
║  [Início] [Fornecedores] [Sobre]      ║
╠════════════════════════════════════════╣
║                                        ║
║   Seu Casamento dos Sonhos            ║
║        Começa Aqui                     ║
║                                        ║
║   Planejamento elegante...            ║
║                                        ║
║   [Comece Agora] [Explorar]           ║
║                                        ║
╠════════════════════════════════════════╣
║  100+      100%       24/7             ║
║  Fornecedores Satisfação Suporte      ║
╚════════════════════════════════════════╝
```

**Site funcionando perfeitamente!** 🎉

---

## 📝 RESUMO

**O que foi corrigido:**
- ✅ Pasta `app/core/` → `app/Core/`
- ✅ Compatibilidade Linux garantida
- ✅ Case-sensitive resolvido

**O que você precisa fazer:**
1. DELETE pasta `app/` do servidor
2. Upload da pasta `app/` corrigida
3. Testar `https://cleacasamentos.com.br/`

**Tempo estimado:** 5 minutos

---

**Criado em:** 02/10/2025
**Status:** ✅ Correção aplicada localmente
**Ação:** Upload necessário
