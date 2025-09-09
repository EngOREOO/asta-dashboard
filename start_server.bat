@echo off
echo 🚀 Starting ASTA Login Server...
echo.
echo Choose your server option:
echo 1. Python Server (Recommended)
echo 2. Node.js Server
echo 3. Exit
echo.
set /p choice="Enter your choice (1-3): "

if "%choice%"=="1" (
    echo.
    echo 🐍 Starting Python server on port 3000...
    python server.py
) else if "%choice%"=="2" (
    echo.
    echo 📦 Starting Node.js server on port 3000...
    node server.js
) else if "%choice%"=="3" (
    echo.
    echo 👋 Goodbye!
    pause
    exit
) else (
    echo.
    echo ❌ Invalid choice. Please run again and select 1, 2, or 3.
    pause
)
