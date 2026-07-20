@echo off
REM Keeps print-agent.ps1 running, restarting it if it ever exits/crashes.
REM Set this to run via Windows Task Scheduler, trigger "At log on" (or "At
REM startup"), so it survives reboots without anyone opening a terminal.
REM No PHP/XAMPP needed for this version - only PowerShell (built into
REM Windows) and SumatraPDF.exe.
cd /d "%~dp0"
:loop
powershell.exe -ExecutionPolicy Bypass -File "%~dp0print-agent.ps1"
echo print-agent.ps1 exited, restarting in 5s...
timeout /t 5 /nobreak >nul
goto loop
