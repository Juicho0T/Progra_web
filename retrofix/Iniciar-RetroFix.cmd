@echo off
cd /d "%~dp0"
echo RetroFix: http://127.0.0.1:8080
echo Inicia MySQL desde XAMPP antes de usar la pagina.
echo Mantener esta ventana abierta. Ctrl+C para detener.
"C:\xampp\php\php.exe" -S 127.0.0.1:8080 -t . dev-router.php
pause
