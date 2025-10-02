# 🎯 ONDE CONFIGURAR NO PAINEL HOSTINGER

## 🔍 LOCALIZANDO AS CONFIGURAÇÕES

### **1. Acessar o Painel**

```
URL: https://hpanel.hostinger.com/
Login: seu_email@email.com
Senha: sua_senha
```

---

### **2. Configurar DocumentRoot (PRINCIPAL)**

#### Caminho no Painel:

```
Login → Websites → cleacasamentos.com.br → Advanced
```

#### Passo a Passo Detalhado:

**1️⃣ Menu Lateral Esquerdo:**
```
Websites
├── cleacasamentos.com.br  ⬅️ CLIQUE AQUI
└── Configurações
```

**2️⃣ Abas no Topo:**
```
[Overview] [Advanced] [Email] [Databases] [Backups]
             ↑
        CLIQUE AQUI
```

**3️⃣ Seção "Advanced":**
```
┌─────────────────────────────────────────┐
│ 📁 Document Root                        │
├─────────────────────────────────────────┤
│ Current: /home/u383946504/public_html   │
│                                         │
│ [____________________________] [Change] │
│                                         │
│ Altere para:                            │
│ /home/u383946504/public_html/public     │
│                                         │
│                    [Save] [Cancel]      │
└─────────────────────────────────────────┘
```

#### ⚠️ IMPORTANTE:
- **Adicione `/public` no final do caminho**
- **NÃO apague** o resto do caminho
- **NÃO coloque** barra no final: `/public/` ❌
- **Correto:** `/public` ✅

---

### **3. Fazer Upload de Arquivos**

#### Via File Manager (Recomendado):

**1️⃣ Acessar File Manager:**
```
Login → File Manager
```

**2️⃣ Navegar para a pasta:**
```
Diretórios:
/
└── public_html  ⬅️ VOCÊ ESTÁ AQUI
    ├── .htaccess          ⬅️ CRIAR/EDITAR
    ├── index.php          ⬅️ CRIAR/EDITAR
    ├── app/
    ├── config/
    └── public/           ⬅️ PRINCIPAL
        ├── .htaccess
        ├── index.php
        ├── diagnostico.php
        └── ...
```

**3️⃣ Upload de Arquivos:**
```
Botão "Upload" no topo
→ Selecionar arquivo
→ Upload para /public_html/
```

#### Via FileZilla (Alternativo):

**Configuração:**
```
Host: srv406.hstgr.io
Username: u383946504_cleacasamentos
Password: Aaku_2004@
Port: 21 (FTP) ou 22 (SFTP)
```

**Arrastar arquivos para:**
```
Servidor (lado direito):
/public_html/        ⬅️ .htaccess e index.php aqui
/public_html/public/ ⬅️ Sistema completo aqui
```

---

### **4. Configurar Permissões**

#### Via File Manager:

**1️⃣ Selecionar pasta `public`:**
```
public_html
└── public  ⬅️ CLIQUE COM BOTÃO DIREITO
```

**2️⃣ No menu que aparece:**
```
┌────────────────────┐
│ Open               │
│ Rename             │
│ Delete             │
│ Copy               │
│ Permissions  ⬅️ CLIQUE
│ Compress           │
│ Extract            │
└────────────────────┘
```

**3️⃣ Janela de Permissões:**
```
┌─────────────────────────────────────┐
│ Change Permissions                  │
├─────────────────────────────────────┤
│                                     │
│ Numeric: [755]                      │
│                                     │
│ Owner:  [✓] Read [✓] Write [✓] Execute │
│ Group:  [✓] Read [ ] Write [✓] Execute │
│ Public: [✓] Read [ ] Write [✓] Execute │
│                                     │
│ [✓] Apply to subdirectories         │
│                                     │
│         [OK] [Cancel]               │
└─────────────────────────────────────┘
```

#### Configurações Corretas:

| Item | Pasta | Arquivo |
|------|-------|---------|
| Permissão | 755 | 644 |
| Owner | rwx (7) | rw- (6) |
| Group | r-x (5) | r-- (4) |
| Public | r-x (5) | r-- (4) |

---

### **5. Ativar SSL (HTTPS)**

#### Caminho:

```
Login → SSL → Manage SSL
```

#### Passo a Passo:

**1️⃣ Menu Lateral:**
```
Websites
Domains
Email
Databases
SSL  ⬅️ CLIQUE AQUI
```

**2️⃣ Selecionar Domínio:**
```
┌────────────────────────────────────────┐
│ Select Domain:                         │
│ [cleacasamentos.com.br ▼]              │
└────────────────────────────────────────┘
```

**3️⃣ Opções de SSL:**
```
┌────────────────────────────────────────┐
│ ✓ Free Let's Encrypt SSL  ⬅️ SELECIONE │
│   Premium SSL                          │
│   Custom SSL                           │
│                                        │
│          [Install SSL]                 │
└────────────────────────────────────────┘
```

**4️⃣ Aguardar:**
```
Installing SSL certificate...
⏳ Aguarde 2-5 minutos

✅ SSL certificate installed successfully!
```

---

### **6. Executar Migração do Banco**

#### Via phpMyAdmin:

**1️⃣ Acessar phpMyAdmin:**
```
Login → Databases → phpMyAdmin
```

**2️⃣ Selecionar Banco:**
```
Lado esquerdo:
└── u383946504_cleacasamentos  ⬅️ CLIQUE
```

**3️⃣ Aba SQL:**
```
[Structure] [SQL] [Search] [Query] [Export]
             ↑
        CLIQUE AQUI
```

**4️⃣ Colar SQL:**
```
┌────────────────────────────────────────┐
│ Run SQL query/queries on database:     │
├────────────────────────────────────────┤
│                                        │
│ CREATE TABLE IF NOT EXISTS `leads` (   │
│   `id` int(11) NOT NULL AUTO_INCREMENT,│
│   ...                                  │
│ );                                     │
│                                        │
│                            [Go]        │
└────────────────────────────────────────┘
```

**5️⃣ Verificar:**
```
✅ 1 row affected (0.05 sec)

Ou

✅ Table 'leads' created successfully
```

---

## 📋 ORDEM DE EXECUÇÃO

Faça nesta ordem:

1. ✅ **Configurar DocumentRoot** (mais importante)
2. ✅ **Ativar SSL** (segurança)
3. ✅ **Executar migração** (banco de dados)
4. ✅ **Ajustar permissões** (se necessário)
5. ✅ **Testar site**

---

## 🔍 VERIFICAÇÕES FINAIS

Após tudo configurado, teste:

### Teste 1: Acesso Principal
```
https://cleacasamentos.com.br/
Esperado: Landing page
```

### Teste 2: Diagnóstico
```
https://cleacasamentos.com.br/diagnostico.php
Esperado: Todos os testes verdes
```

### Teste 3: Páginas Públicas
```
https://cleacasamentos.com.br/vendors
https://cleacasamentos.com.br/about
https://cleacasamentos.com.br/contact
Esperado: Páginas funcionando
```

### Teste 4: Login
```
https://cleacasamentos.com.br/login.php
Esperado: Página de login
```

---

## 🆘 PROBLEMAS COMUNS

### "Não encontro DocumentRoot"

**Solução:**
- Tente em **Website Settings** → **Advanced Settings**
- Ou entre em contato com suporte Hostinger
- Use a solução alternativa (.htaccess + index.php na raiz)

### "Mudei DocumentRoot mas ainda dá 403"

**Soluções:**
1. Aguarde 2-5 minutos (propagação)
2. Limpe cache do navegador (Ctrl+Shift+Delete)
3. Teste em modo anônimo
4. Verifique permissões da pasta `public/`

### "Upload não funciona"

**Soluções:**
1. Use File Manager ao invés de FTP
2. Verifique espaço em disco disponível
3. Verifique se não ultrapassou limite de arquivos

---

## ✅ QUANDO TUDO ESTIVER CERTO

Você verá:
- ✅ https://cleacasamentos.com.br/ com landing page bonita
- ✅ Cadeado verde (HTTPS ativo)
- ✅ Menu de navegação funcionando
- ✅ Todos os links clicáveis
- ✅ Diagnóstico 100% verde

**Pronto para usar!** 🎉

---

**Última atualização:** 02/10/2025
**Painel:** Hostinger hPanel v2.0
