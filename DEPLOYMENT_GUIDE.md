# Guia de Deploy - Clea Casamentos

## 🚀 Configuração do Ambiente

### Pré-requisitos
- **PHP:** 8.1 ou superior
- **Servidor Web:** Apache 2.4+ ou Nginx 1.18+
- **Banco de Dados:** MySQL 8.0+ ou MariaDB 10.4+
- **Composer:** 2.0+ para gerenciamento de dependências
- **SSL Certificate:** Para HTTPS (obrigatório em produção)

### Extensões PHP Necessárias
```bash
php-pdo
php-pdo-mysql
php-session
php-json
php-mbstring
php-openssl
```

## 📁 Estrutura de Deploy

### Desenvolvimento Local
```bash
# 1. Clonar repositório
git clone [repository-url] clea_casamentos
cd clea_casamentos

# 2. Instalar dependências
composer install

# 3. Configurar ambiente
cp .env.example .env
# Editar .env com credenciais locais

# 4. Configurar banco de dados
mysql -u root -p < u383946504_cleacasamentos.sql

# 5. Configurar servidor web (Apache)
# DocumentRoot deve apontar para /public
```

### Produção
```bash
# 1. Upload dos arquivos (exceto vendor/)
rsync -avz --exclude='vendor/' --exclude='.git/' ./ user@server:/var/www/clea/

# 2. No servidor de produção
cd /var/www/clea
composer install --no-dev --optimize-autoloader

# 3. Configurar .env para produção
cp .env.example .env
# Configurar variáveis de produção

# 4. Configurar permissões
chown -R www-data:www-data /var/www/clea
chmod -R 755 /var/www/clea
```

## ⚙️ Configuração do Servidor Web

### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName cleacasamentos.com.br
    ServerAlias www.cleacasamentos.com.br
    DocumentRoot /var/www/clea/public

    <Directory /var/www/clea/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName cleacasamentos.com.br
    ServerAlias www.cleacasamentos.com.br
    DocumentRoot /var/www/clea/public

    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/ca_bundle.crt

    <Directory /var/www/clea/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com"
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name cleacasamentos.com.br www.cleacasamentos.com.br;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name cleacasamentos.com.br www.cleacasamentos.com.br;

    root /var/www/clea/public;
    index index.php;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security Headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload";

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 🗄️ Configuração do Banco de Dados

### Arquivo .env
```env
# Configuração do Banco de Dados
DB_HOST=srv406.hstgr.io
DB_NAME=u383946504_cleacasamentos
DB_USER=u383946504_cleacasamentos
DB_PASS=SUA_SENHA_AQUI

# Configuração da Aplicação
APP_URL=https://www.cleacasamentos.com.br
APP_ENV=production
```

### Comandos de Setup do Banco
```sql
-- Criar usuário e banco (se necessário)
CREATE DATABASE u383946504_cleacasamentos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'u383946504_cleacasamentos'@'%' IDENTIFIED BY 'SUA_SENHA_AQUI';
GRANT ALL PRIVILEGES ON u383946504_cleacasamentos.* TO 'u383946504_cleacasamentos'@'%';
FLUSH PRIVILEGES;

-- Importar estrutura
mysql -u u383946504_cleacasamentos -p u383946504_cleacasamentos < u383946504_cleacasamentos.sql
```

## 🔐 Configurações de Segurança

### Permissões de Arquivo
```bash
# Proprietário dos arquivos
chown -R www-data:www-data /var/www/clea

# Permissões recomendadas
find /var/www/clea -type d -exec chmod 755 {} \;
find /var/www/clea -type f -exec chmod 644 {} \;

# .env deve ser protegido
chmod 600 /var/www/clea/.env

# Vendor e cache podem precisar de write
chmod -R 775 /var/www/clea/vendor
```

### Firewall (UFW)
```bash
# Permitir apenas portas necessárias
ufw allow ssh
ufw allow 'Apache Full'  # ou 'Nginx Full'
ufw enable
```

### Backup Automatizado
```bash
# Script de backup (/etc/cron.daily/clea-backup)
#!/bin/bash
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backups/clea"
DB_NAME="u383946504_cleacasamentos"

# Backup do banco
mysqldump -u root -p$MYSQL_ROOT_PASSWORD $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup dos arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/clea --exclude=/var/www/clea/vendor

# Manter apenas 7 dias de backup
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

## 📊 Monitoramento

### Log Files Importantes
```bash
# Logs do Apache
/var/log/apache2/error.log
/var/log/apache2/access.log

# Logs do PHP
/var/log/php8.1-fpm.log

# Logs do MySQL
/var/log/mysql/error.log
```

### Monitoramento Básico
```bash
# Verificar status dos serviços
systemctl status apache2
systemctl status mysql
systemctl status php8.1-fpm

# Verificar espaço em disco
df -h

# Verificar memória
free -h

# Verificar conectividade do banco
mysql -u u383946504_cleacasamentos -p -e "SELECT 1"
```

## 🚨 Troubleshooting

### Problemas Comuns

#### 1. Erro 500 - Internal Server Error
```bash
# Verificar logs do Apache
tail -f /var/log/apache2/error.log

# Verificar permissões
ls -la /var/www/clea/

# Verificar .htaccess
cat /var/www/clea/public/.htaccess
```

#### 2. Conexão com Banco Falha
```bash
# Testar conexão diretamente
mysql -h srv406.hstgr.io -u u383946504_cleacasamentos -p

# Verificar .env
cat /var/www/clea/.env | grep DB_
```

#### 3. Autoload não Funciona
```bash
# Regenerar autoload
cd /var/www/clea
composer dump-autoload
```

### Comandos de Diagnóstico
```bash
# Verificar configuração PHP
php -m | grep -E "(pdo|mysql|session)"

# Verificar se composer está funcionando
composer --version

# Verificar se rewrite está habilitado (Apache)
apache2ctl -M | grep rewrite
```

## 📝 Checklist de Deploy

### Pré-Deploy
- [ ] Backup do banco de dados atual
- [ ] Backup dos arquivos atuais
- [ ] Verificar dependências do Composer
- [ ] Testar em ambiente de staging

### Deploy
- [ ] Upload dos arquivos
- [ ] `composer install --no-dev`
- [ ] Configurar `.env` para produção
- [ ] Importar/atualizar banco de dados
- [ ] Configurar permissões de arquivo
- [ ] Verificar SSL e DNS

### Pós-Deploy
- [ ] Testar login/autenticação
- [ ] Verificar dashboards (Admin/Client)
- [ ] Testar conexão com banco
- [ ] Verificar logs de erro
- [ ] Monitorar performance
- [ ] Backup pós-deploy

---

**Última atualização:** 29/09/2025
**Versão do guia:** 1.0
**Ambiente testado:** Ubuntu 20.04 LTS + Apache 2.4 + PHP 8.1 + MySQL 8.0