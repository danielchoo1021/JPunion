@echo off
REM Keeps the print agent running, restarting it if it ever exits/crashes.
REM Set this to run via Windows Task Scheduler, trigger "At log on" (or "At
REM startup" if it should run without anyone logging in), so it survives
REM reboots without you needing to open a terminal.
cd /d "%~dp0"
:loop
"C:\xampp\php\php.exe" artisan print:agent
echo print:agent exited, restarting in 5s...
timeout /t 5 /nobreak >nul
goto loop
