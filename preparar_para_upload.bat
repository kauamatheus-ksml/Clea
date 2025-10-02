@echo off
echo ========================================
echo  PREPARAR ARQUIVOS PARA UPLOAD
echo  Clea Casamentos
echo ========================================
echo.

cd /d "%~dp0app"

echo Verificando pastas...
echo.

REM Renomear pastas para uppercase
if exist core (
    echo [*] Renomeando core -^> Core
    ren core Core_temp
    ren Core_temp Core
)

if exist controllers (
    echo [*] Renomeando controllers -^> Controllers
    ren controllers Controllers_temp
    ren Controllers_temp Controllers
)

if exist models (
    echo [*] Renomeando models -^> Models
    ren models Models_temp
    ren Models_temp Models
)

if exist views (
    echo [*] Renomeando views -^> Views
    ren views Views_temp
    ren Views_temp Views
)

echo.
echo ========================================
echo  PRONTO!
echo ========================================
echo.
echo As pastas foram renomeadas:
echo   - app/Core/
echo   - app/Controllers/
echo   - app/Models/
echo   - app/Views/
echo.
echo Agora voce pode fazer upload sem problemas!
echo.
pause
