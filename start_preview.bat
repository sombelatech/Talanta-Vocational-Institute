@echo off
cd /d "%~dp0"
:: Start a simple static server on port 8001 and open browser
where python >nul 2>&1
if errorlevel 1 (
  echo Python is not found in PATH. Please install Python 3 and rerun this script.
  pause
  exit /b 1
)
start http://localhost:8001
python -m http.server 8001
