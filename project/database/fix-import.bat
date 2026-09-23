@echo off
chcp 65001 >nul
title SQL Import Fix — MySQL #1118
echo.
echo  MySQL Error #1118 fix — SQL dump patcher
echo  ========================================
echo.

if "%~1"=="" (
    set /p SQLFILE="SQL file path drag-drop or paste here: "
) else (
    set SQLFILE=%~1
)

set SQLFILE=%SQLFILE:"=%

if not exist "%SQLFILE%" (
    echo.
    echo  ERROR: File not found: %SQLFILE%
    pause
    exit /b 1
)

echo.
echo  Patching: %SQLFILE%
echo.

c:\xampp\php\php.exe "%~dp0patch_sql_for_import.php" "%SQLFILE%"

echo.
pause
