@echo off
echo Installing TradingView Tools dependencies...
echo.

REM Check if virtual environment exists
if not exist "venv" (
    echo Creating virtual environment...
    python -m venv venv
    if errorlevel 1 (
        echo Error: Failed to create virtual environment
        pause
        exit /b 1
    )
    echo Virtual environment created successfully.
    echo.
)

REM Activate virtual environment
echo Activating virtual environment...
call venv\Scripts\activate.bat
if errorlevel 1 (
    echo Error: Failed to activate virtual environment
    pause
    exit /b 1
)

REM Install dependencies
echo Installing dependencies from requirements.txt...
call pip install -r requirements.txt
if errorlevel 1 (
    echo Error: Failed to install dependencies
    pause
    exit /b 1
)

echo.
echo Installation completed successfully!
echo.
echo To run the application:
echo 1. Activate the virtual environment: call activate_environment.bat
echo 2. Run: python main.py --stock BAYN,RHM,BMW,ENR,BAS,MBG
echo.
echo Make sure to configure your MySQL database settings in the .env file:
echo - DB_HOST=localhost
echo - DB_PORT=3306
echo - DB_USER=root
echo - DB_PASSWORD=
echo - DB_NAME=tradingview
echo.
pause
