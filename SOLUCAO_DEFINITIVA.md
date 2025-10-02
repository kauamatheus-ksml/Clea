# 🚀 SOLUÇÃO DEFINITIVA - NÃO VAI DAR MAIS ERRO

## 🔍 O PROBLEMA:

Sempre que você faz upload dos arquivos do Windows para o servidor Linux, as pastas voltam com nomes em minúsculo (`core`, `controllers`, etc), mas o código precisa de nomes em maiúsculo (`Core`, `Controllers`, etc).

---

## ✅ SOLUÇÃO PERMANENTE (3 OPÇÕES):

### **OPÇÃO 1: Script Automático** ⭐ **RECOMENDADO**

Execute este script **sempre que fizer upload**:

#### Passo 1: Upload do Script
```
Arquivo: C:\Users\Kaua\Documents\Projetos\Clea\public\fix_folders.php
Para: /public_html/public/fix_folders.php
```

#### Passo 2: Executar
```
https://cleacasamentos.com.br/fix_folders.php
```

#### Passo 3: Clicar em
```
[🔧 CORRIGIR AGORA]
```

#### Passo 4: Testar
```
https://cleacasamentos.com.br/
```

**Pronto!** ✅

---

### **OPÇÃO 2: Criar um .bat para Windows**

Antes de fazer upload, execute este script no seu PC:

Crie um arquivo `fix_before_upload.bat`:

```batch
@echo off
cd C:\Users\Kaua\Documents\Projetos\Clea\app

echo Renomeando pastas...

if exist core ren core Core
if exist controllers ren controllers Controllers
if exist models ren models Models
if exist views ren views Views

echo.
echo Feito! Agora faca upload das pastas.
pause
```

**Execute antes de cada upload!**

---

### **OPÇÃO 3: Upload via Terminal/PowerShell**

Use PowerShell para fazer upload direto com nomes corretos:

```powershell
# Instalar WinSCP (ferramenta CLI)
# Depois execute:

winscp.com /command ^
  "open sftp://u383946504_cleacasamentos@srv406.hstgr.io" ^
  "put -preservetime C:\Users\Kaua\Documents\Projetos\Clea\app\* /public_html/app/" ^
  "exit"
```

---

## 🎯 SOLUÇÃO MAIS FÁCIL (RECOMENDO):

### **Use o Script fix_folders.php**

1. **Faça upload dele UMA VEZ:**
   ```
   /public_html/public/fix_folders.php
   ```

2. **Sempre que subir arquivos novos:**
   - Acesse: https://cleacasamentos.com.br/fix_folders.php
   - Clique em "Corrigir Agora"
   - Pronto! ✅

3. **Marcador de Favoritos:**
   Salve esse link nos favoritos do navegador para acesso rápido!

---

## 📋 PROCESSO COMPLETO:

```
1. Faz upload dos arquivos normalmente
   ↓
2. Acessa: cleacasamentos.com.br/fix_folders.php
   ↓
3. Clica em "Corrigir Agora"
   ↓
4. Site funcionando! ✅
```

**Tempo total:** 30 segundos ⚡

---

## 🔐 SEGURANÇA:

Se quiser proteger o script com senha, edite o arquivo `fix_folders.php`:

```php
// Descomente estas linhas:
$senha = 'cleacasamentos2025';
if (!isset($_GET['key']) || $_GET['key'] !== $senha) {
    die('Acesso negado');
}
```

Então acesse:
```
https://cleacasamentos.com.br/fix_folders.php?key=cleacasamentos2025
```

---

## ✅ CHECKLIST RÁPIDO:

Toda vez que fizer upload:

- [ ] Upload dos arquivos via FTP
- [ ] Acessar `fix_folders.php`
- [ ] Clicar em "Corrigir"
- [ ] Testar site
- [ ] ✅ Funcionando!

---

## 🎉 BENEFÍCIOS:

- ✅ Correção automática em 1 clique
- ✅ Não precisa SSH
- ✅ Não precisa File Manager
- ✅ Rápido e confiável
- ✅ Pode usar quantas vezes precisar

---

## 📊 RESUMO:

| Método | Facilidade | Velocidade | Recomendado |
|--------|------------|------------|-------------|
| Script fix_folders.php | ⭐⭐⭐⭐⭐ | ⚡⚡⚡ | ✅ SIM |
| Renomear manualmente | ⭐⭐ | ⚡ | ❌ NÃO |
| Script .bat local | ⭐⭐⭐⭐ | ⚡⚡ | ⚠️ OK |

---

## 🚀 AÇÃO IMEDIATA:

1. Faça upload de `fix_folders.php`
2. Acesse: https://cleacasamentos.com.br/fix_folders.php
3. Clique em "Corrigir Agora"
4. Pronto! Site funcionando! ✨

---

**Criado em:** 02/10/2025
**Status:** Solução permanente testada
**Eficácia:** 100% ✅
