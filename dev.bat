@echo off
echo Starting Laravel...
start cmd /k "cd backend && php artisan serve --port=8182 --host=0.0.0.0"

echo Starting Nuxt...
start cmd /k "cd frontend && npm run dev"

echo Both processes started.
pause


