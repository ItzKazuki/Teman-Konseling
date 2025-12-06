#!/usr/bin/env bash

# Fallback ke sh kalau bash tidak ada
if [ -z "$BASH_VERSION" ] && [ -z "$ZSH_VERSION" ]; then
    echo "Shell bukan bash/zsh, fallback ke sh..."
fi

echo "Starting Laravel..."
(
    cd backend || exit 1
    php artisan serve --port=8182 --host=0.0.0.0
) &

echo "Starting Nuxt..."
(
    cd frontend || exit 1
    npm run dev
) &

# Tunggu semua background job selesai
wait

