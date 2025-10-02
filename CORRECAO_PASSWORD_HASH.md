# 🔧 CORREÇÃO: Column 'password' not found

## ❌ Erro Encontrado:
```
Column not found: 1054 Unknown column 'password' in 'INSERT INTO'
```

## ✅ Causa:
A tabela `users` tem a coluna `password_hash`, mas o código estava tentando inserir em `password`.

## 🔧 Correção Aplicada:
Arquivo corrigido: `app/Controllers/PublicController.php` (linha 148)

**Antes:**
```php
INSERT INTO users (name, email, password, role, is_active, created_at)
```

**Depois:**
```php
INSERT INTO users (name, email, password_hash, role, is_active, created_at)
```

---

## 📤 AÇÃO NECESSÁRIA: RE-UPLOAD

Você precisa fazer **re-upload** apenas do arquivo corrigido:

### Via FileZilla ou File Manager:

**Arquivo:**
```
C:\Users\Kaua\Documents\Projetos\Clea\app\Controllers\PublicController.php
```

**Para:**
```
/public_html/app/Controllers/PublicController.php
```

**⚠️ SOBRESCREVER** o arquivo existente no servidor.

---

## ✅ Após o Upload:

### Teste o Registro:

1. Acesse: https://cleacasamentos.com.br/register
2. Preencha o formulário:
   - **Nome:** Teste Usuario
   - **Email:** teste@exemplo.com
   - **Senha:** 123456
   - **Tipo:** Sou Noivo(a)
3. Clique em **Criar Conta**
4. **Esperado:** "Conta criada com sucesso!"

---

## 🧪 Testes Completos:

### Teste 1: Registro de Cliente
```
URL: https://cleacasamentos.com.br/register
Tipo: Sou Noivo(a)
Resultado: ✅ Conta criada
```

### Teste 2: Registro de Fornecedor
```
URL: https://cleacasamentos.com.br/register
Tipo: Sou Fornecedor
Preencher: Nome da Empresa, Categoria, Cidade
Resultado: ✅ Conta e perfil criados
```

### Teste 3: Login
```
URL: https://cleacasamentos.com.br/login.php
Email: teste@exemplo.com
Senha: 123456
Resultado: ✅ Login bem-sucedido
```

---

## 📊 Outros Arquivos que Podem Precisar de Correção:

Verifique se outros arquivos usam `password` ao invés de `password_hash`:

### Via File Manager ou SSH:
```bash
grep -r "INSERT INTO users" /public_html/app/
grep -r "'password'" /public_html/app/
```

Se encontrar outros arquivos com o mesmo problema, corrija também.

---

## ✅ Checklist:

- [ ] Fazer upload de `PublicController.php` corrigido
- [ ] Testar registro de cliente
- [ ] Testar registro de fornecedor
- [ ] Testar login com conta criada
- [ ] Verificar se não há outros arquivos com o mesmo erro

---

## 🎉 Resultado Esperado:

Após a correção:
- ✅ Registro de usuários funciona
- ✅ Login funciona
- ✅ Sistema de autenticação completo operacional

---

**Status:** Correção pronta para upload
**Tempo estimado:** 2 minutos
**Última atualização:** 02/10/2025
