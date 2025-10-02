# 🚨 RESOLVER ERRO 403 - AÇÃO IMEDIATA

## 🎯 SOLUÇÃO RÁPIDA EM 3 PASSOS

### **PASSO 1: Acessar o Diagnóstico**

Tente acessar estes links **nesta ordem**:

1️⃣ **Primeiro tente:**
```
https://cleacasamentos.com.br/public/diagnostico.php
```

2️⃣ **Se não funcionar, tente:**
```
https://cleacasamentos.com.br/public/index.php
```

3️⃣ **Se nenhum funcionar:**
   - O problema é mais sério (permissões ou Apache)
   - Vá direto para o PASSO 2

---

### **PASSO 2: Configurar DocumentRoot (SOLUÇÃO DEFINITIVA)**

Esta é a **melhor solução**. Configure uma única vez e nunca mais terá problemas.

#### 📱 No Painel da Hostinger:

1. **Login** em https://hpanel.hostinger.com/
2. Clique em **Websites** (no menu lateral esquerdo)
3. Selecione o site **cleacasamentos.com.br**
4. Clique na aba **Advanced** (ou **Avançado**)
5. Procure por **Document Root** (ou **Raiz do Documento**)
6. Você verá algo como:
   ```
   /home/u383946504/public_html
   ```
7. **ALTERE PARA:**
   ```
   /home/u383946504/public_html/public
   ```
   (Adicione `/public` no final)
8. Clique em **Save** ou **Salvar**
9. **Aguarde 1-2 minutos** para propagar

#### ✅ Teste:
```
https://cleacasamentos.com.br/
```
Deve mostrar a landing page! 🎉

---

### **PASSO 3: Se NÃO CONSEGUIR mudar DocumentRoot**

Alguns planos da Hostinger não permitem alterar o DocumentRoot. Neste caso, use redirecionamento:

#### 📤 Upload de 2 Arquivos:

**Via FileZilla ou File Manager da Hostinger:**

1. **Arquivo 1:** `.htaccess` na raiz
   - Fazer upload para: `/public_html/.htaccess`
   - Arquivo já está pronto em: `C:\Users\Kaua\Documents\Projetos\Clea\.htaccess`

2. **Arquivo 2:** `index.php` na raiz
   - Fazer upload para: `/public_html/index.php`
   - Arquivo já está pronto em: `C:\Users\Kaua\Documents\Projetos\Clea\index.php`

#### 🔄 O que esses arquivos fazem:
- `.htaccess` → Redireciona automaticamente para `/public/`
- `index.php` → Garante que sempre carregue o sistema correto

#### ✅ Teste:
```
https://cleacasamentos.com.br/
```
Deve redirecionar e funcionar! 🎉

---

## 🆘 SE AINDA DER ERRO 403

### Verifique as Permissões:

#### Via File Manager (Hostinger):

1. Acesse **File Manager** no painel
2. Navegue até `/public_html/`
3. Clique com botão direito na pasta **public**
4. Escolha **Permissions** ou **Permissões**
5. Configure:
   - **Número:** `755`
   - Marque: **Apply to subdirectories**
6. Clique **OK**

7. Agora faça o mesmo para os **arquivos** dentro de `public/`:
   - Selecione `index.php`
   - Permissions → `644`

#### Via SSH (se disponível):

```bash
cd /home/u383946504/public_html
chmod 755 public/
chmod 644 public/index.php
chmod 644 public/.htaccess
```

---

## 📊 ESTRUTURA DE ARQUIVOS FINAL

```
/home/u383946504/public_html/
├── .htaccess                    ⬅️ NOVO (redireciona)
├── index.php                    ⬅️ NOVO (redireciona)
├── app/
├── config/
├── public/                      ⬅️ ESTA É A PASTA PRINCIPAL!
│   ├── .htaccess
│   ├── index.php               ⬅️ Ponto de entrada
│   ├── diagnostico.php         ⬅️ Para diagnóstico
│   ├── test_config.php
│   ├── login.php
│   └── ...
└── migration_leads.sql
```

---

## ✅ CHECKLIST DE VERIFICAÇÃO

Após aplicar a solução:

- [ ] https://cleacasamentos.com.br/ abre (sem 403)
- [ ] https://cleacasamentos.com.br/diagnostico.php mostra diagnóstico
- [ ] https://cleacasamentos.com.br/vendors funciona
- [ ] https://cleacasamentos.com.br/test_config.php mostra testes
- [ ] HTTPS está funcionando (cadeado verde)

---

## 🎯 QUAL MÉTODO VOCÊ USOU?

Marque qual funcionou:

- [ ] ✅ **PASSO 2** - Configurei DocumentRoot para `/public_html/public`
- [ ] ✅ **PASSO 3** - Upload de `.htaccess` e `index.php` na raiz
- [ ] ✅ **Permissões** - Ajustei permissões via File Manager

---

## 📞 AINDA COM PROBLEMAS?

Se nada funcionou:

1. **Verifique se os arquivos foram enviados:**
   - `/public_html/public/index.php` existe?
   - `/public_html/public/.htaccess` existe?

2. **Teste acesso direto:**
   ```
   https://cleacasamentos.com.br/public/diagnostico.php
   ```
   Se funcionar → o problema é só DocumentRoot

3. **Entre em contato com suporte Hostinger:**
   - Peça para ativar `mod_rewrite`
   - Peça para verificar permissões
   - Peça ajuda para configurar DocumentRoot para `/public_html/public`

---

## 🎉 QUANDO FUNCIONAR

Você verá uma linda landing page com:
- ✅ Header com logo "Clea"
- ✅ Hero section rosa
- ✅ Estatísticas de fornecedores
- ✅ Cards de recursos
- ✅ Fornecedores em destaque

**Aí sim está pronto!** 🚀

---

**Criado em:** 02/10/2025
**Status:** Aguardando correção
**Prioridade:** 🔴 ALTA
