# Makefile для edem
# Использование: make <команда>

# Настройки сервера
REMOTE_HOST = xn----8sbkccshgr4ce9k.xn--p1ai
REMOTE_USER = root
REMOTE_PATH = /var/www/edem

# Локальные пути
LOCAL_PRODUCTS = ./storage/app/public/products/
LOCAL_WHOLESALES = ./storage/app/public/wholesales/
REMOTE_PRODUCTS = $(REMOTE_PATH)/storage/app/public/products
REMOTE_WHOLESALES = $(REMOTE_PATH)/storage/app/public/wholesales

.PHONY: help sync sync-dry deploy deploy-branch deploy-develop ssh logs storage-link build db-tunnel

# Помощь (по умолчанию)
help:
	@echo "Доступные команды:"
	@echo "  make build        - Собрать фронтенд (npm run build)"
	@echo "  make sync         - Синхронизировать файлы продуктов на сервер"
	@echo "  make sync-dry     - Тестовый запуск (без реальной передачи)"
	@echo "  make deploy       - Собрать фронтенд и задеплоить на сервер"
	@echo "  make deploy-develop - Задеплоить ветку develop на сервер"
	@echo "  make deploy-branch BRANCH=<name> - Задеплоить выбранную ветку"
	@echo "  make ssh          - Подключиться к серверу по SSH"
	@echo "  make logs         - Посмотреть логи Docker на сервере"
	@echo "  make storage-link - Создать симлинк storage на сервере"
	@echo "  make db-tunnel    - Создать SSH туннель к БД (localhost:3307)"

# Сборка фронтенда
build:
	@echo "🔨 Сборка фронтенда..."
	npm run build
	@echo "✅ Сборка завершена!"

# Синхронизация файлов продуктов
sync:
	@echo "🚀 Синхронизация файлов продуктов..."
	@mkdir -p $(LOCAL_PRODUCTS)
	@mkdir -p $(LOCAL_WHOLESALES)
	@ssh $(REMOTE_USER)@$(REMOTE_HOST) "mkdir -p $(REMOTE_PRODUCTS)"
	@ssh $(REMOTE_USER)@$(REMOTE_HOST) "mkdir -p $(REMOTE_WHOLESALES)"
	@echo "📦 Синхронизация розничных товаров..."
	rsync -avz --progress $(LOCAL_PRODUCTS) $(REMOTE_USER)@$(REMOTE_HOST):$(REMOTE_PRODUCTS)/
	@echo "🌷 Синхронизация оптовых товаров..."
	rsync -avz --progress $(LOCAL_WHOLESALES) $(REMOTE_USER)@$(REMOTE_HOST):$(REMOTE_WHOLESALES)/
	@ssh $(REMOTE_USER)@$(REMOTE_HOST) "chown -R www-data:www-data $(REMOTE_PRODUCTS) $(REMOTE_WHOLESALES) && chmod -R 755 $(REMOTE_PRODUCTS) $(REMOTE_WHOLESALES)"
	@echo "✅ Готово!"

# Тестовый запуск синхронизации
sync-dry:
	@echo "🔍 Тестовый режим (файлы НЕ будут переданы)..."
	@mkdir -p $(LOCAL_PRODUCTS)
	@mkdir -p $(LOCAL_WHOLESALES)
	@echo "📦 Проверка розничных товаров..."
	rsync -avz --dry-run --progress $(LOCAL_PRODUCTS) $(REMOTE_USER)@$(REMOTE_HOST):$(REMOTE_PRODUCTS)/
	@echo "🌷 Проверка оптовых товаров..."
	rsync -avz --dry-run --progress $(LOCAL_WHOLESALES) $(REMOTE_USER)@$(REMOTE_HOST):$(REMOTE_WHOLESALES)/

# Деплой всего проекта (git pull + сборка образа + composer + миграции + рестарт)
deploy:
	@echo "🚀 Деплой на сервер..."
	@echo "📥 Получение изменений из Git..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && git pull"
	@echo "🐳 Пересборка образа app и пересоздание контейнера (на случай изменений в Dockerfile.prod)..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml up -d --build app"
	@echo "📚 Установка PHP зависимостей (composer install) и обновление package manifest..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app sh -lc 'rm -f bootstrap/cache/packages.php bootstrap/cache/services.php && composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader --no-scripts && php artisan package:discover --ansi && php artisan filament:assets && php artisan config:clear && php artisan clear-compiled'"
	@echo "🗃️  Выполнение миграций..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force"
	@echo "🔨 Сборка фронтенда..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && npm run build"
	@echo "✅ Деплой завершён!"

# Деплой выбранной ветки (по умолчанию develop)
deploy-branch:
	@echo "🚀 Деплой ветки '$(if $(BRANCH),$(BRANCH),develop)' на сервер..."
	@echo "📥 Переключение и обновление ветки..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && git fetch origin && git checkout $(if $(BRANCH),$(BRANCH),develop) && git pull origin $(if $(BRANCH),$(BRANCH),develop)"
	@echo "🐳 Пересборка образа app и пересоздание контейнера (на случай изменений в Dockerfile.prod)..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml up -d --build app"
	@echo "📚 Установка PHP зависимостей (composer install) и обновление package manifest..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app sh -lc 'rm -f bootstrap/cache/packages.php bootstrap/cache/services.php && composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader --no-scripts && php artisan package:discover --ansi && php artisan filament:assets && php artisan config:clear && php artisan clear-compiled'"
	@echo "🗃️  Выполнение миграций..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force"
	@echo "🔨 Сборка фронтенда..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && npm run build"
	@echo "✅ Деплой ветки завершён!"

# Быстрый алиас для deploy develop
deploy-develop: BRANCH=develop
deploy-develop: deploy-branch

# Подключение к серверу
ssh:
	ssh -t $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && exec \$$SHELL"

# Логи Docker на сервере
logs:
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml logs -f --tail=100"

# Создать симлинк storage на сервере
storage-link:
	@echo "🔗 Создание симлинка storage..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app php artisan storage:link"
	@echo "✅ Симлинк создан!"

# SSH туннель к базе данных
db-tunnel:
	@echo "🔌 Создание SSH туннеля к MySQL..."
	@echo "📍 Подключайтесь к: localhost:3307"
	@echo "🔐 Credentials: edem / edem / edem"
	@echo "⚠️  Нажмите Ctrl+C для остановки туннеля"
	@echo ""
	ssh -L 3307:localhost:3306 $(REMOTE_USER)@$(REMOTE_HOST) -N
