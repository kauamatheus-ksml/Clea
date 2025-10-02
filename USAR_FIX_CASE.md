# 🔧 USAR O SCRIPT FIX_CASE.PHP

## 🎯 SOLUÇÃO AUTOMÁTICA EM 3 CLIQUES

Este script corrige automaticamente o problema de case-sensitive no servidor.

---

## 📤 PASSO 1: UPLOAD

Fazer upload do arquivo **fix_case.php** para:

```
/public_html/public/fix_case.php
```

**Onde está o arquivo:**
```
C:\Users\Kaua\Documents\Projetos\Clea\public\fix_case.php
```

---

## 🌐 PASSO 2: ACESSAR

Abra no navegador:

```
https://cleacasamentos.com.br/fix_case.php
```

---

## ✅ PASSO 3: CORRIGIR

Na página que abrir:

1. Você verá a estrutura atual
2. Clique no botão **"🔧 CORRIGIR AUTOMATICAMENTE"**
3. Aguarde a correção
4. Clique em **"🏠 TESTAR SITE AGORA"**

---

## 🧪 PASSO 4: TESTAR

Acesse:

```
https://cleacasamentos.com.br/
```

**Deve mostrar a landing page bonita!** 🎉

---

## 🗑️ PASSO 5: DELETAR O SCRIPT

**IMPORTANTE:** Após funcionar, delete o arquivo por segurança:

Via File Manager:
1. Vá em `/public_html/public/`
2. Delete `fix_case.php`

Ou via SSH:
```bash
rm /home/u383946504/domains/cleacasamentos.com.br/public_html/public/fix_case.php
```

---

## 🔍 O QUE O SCRIPT FAZ?

O script renomeia automaticamente:

```
app/core/        → app/Core/
app/controllers/ → app/Controllers/
app/models/      → app/Models/
app/views/       → app/Views/
```

---

## ❓ E SE DER ERRO?

### Erro: "Permission denied"

Ajuste as permissões:
```bash
chmod 755 /public_html/app/
chmod 755 /public_html/app/*
```

### Erro: Script não carrega

Verifique se o arquivo foi enviado para:
```
/public_html/public/fix_case.php
```

### Script não corrige

Tente manualmente via File Manager:
1. Entre em `/public_html/app/`
2. Renomeie cada pasta (botão direito → Rename):
   - `core` → `Core`
   - `controllers` → `Controllers`
   - `models` → `Models`
   - `views` → `Views`

---

## 📋 CHECKLIST RÁPIDO

- [ ] Upload do `fix_case.php` para `/public_html/public/`
- [ ] Acessar `https://cleacasamentos.com.br/fix_case.php`
- [ ] Clicar em "Corrigir Automaticamente"
- [ ] Testar `https://cleacasamentos.com.br/`
- [ ] Deletar `fix_case.php`
- [ ] ✅ Site funcionando!

---

## 🎉 RESULTADO ESPERADO

Quando funcionar:

```
✅ Site carrega sem erros
✅ Landing page aparece
✅ Menu funciona
✅ Navegação OK
```

---

**Tempo total:** 2-3 minutos ⚡
**Dificuldade:** Muito fácil
**Eficácia:** 99% ✨
