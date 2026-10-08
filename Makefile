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

.PHONY: help sync sync-dry deploy deploy-branch deploy-develop ssh logs storage-link build db-tunnel env-set

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
	@echo "  make env-set KEY=<имя> VALUE=<значение> - Изменить/добавить переменную в .env на сервере и перезапустить app"
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

# Скрипт, выполняемый на сервере: меняет KEY в .env, а если её нет — добавляет в конец.
# Значение приходит в base64, чтобы не зависеть от кавычек и спецсимволов.
define ENV_SET_SCRIPT
set -e
cd $(REMOTE_PATH)
VAL=$$(printf '%s' "$$ENV_VAL_B64" | base64 -d)
cp .env .env.bak
KEY="$$ENV_KEY" VAL="$$VAL" awk 'BEGIN { k = ENVIRON["KEY"]; v = ENVIRON["VAL"] } index($$0, k "=") == 1 { print k "=" v; found = 1; next } { print } END { if (!found) print k "=" v }' .env.bak > .env.new
cat .env.new > .env
rm .env.new
grep -n "^$$ENV_KEY=" .env
endef
export ENV_SET_SCRIPT

# Изменить/добавить переменную в .env на проде и перезапустить контейнер app
# Пример: make env-set KEY=TELESCOPE_LOG_LEVEL VALUE=info
env-set:
	@if [ -z "$(KEY)" ] || [ -z "$(VALUE)" ]; then echo "Использование: make env-set KEY=<имя> VALUE=<значение>"; exit 1; fi
	@echo "$(KEY)" | grep -Eq '^[A-Za-z_][A-Za-z0-9_]*$$' || { echo "❌ Некорректное имя переменной: $(KEY)"; exit 1; }
	@echo "✏️  Обновление $(KEY) в .env на сервере (бэкап: .env.bak)..."
	@printf '%s\n' "$$ENV_SET_SCRIPT" | ssh $(REMOTE_USER)@$(REMOTE_HOST) "ENV_KEY='$(KEY)' ENV_VAL_B64='$$(printf '%s' "$$VALUE" | base64 | tr -d '\n')' sh -s"
	@echo "🧹 Сброс кеша конфига..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml exec -T app php artisan config:clear"
	@echo "🔄 Перезапуск контейнера app..."
	ssh $(REMOTE_USER)@$(REMOTE_HOST) "cd $(REMOTE_PATH) && docker compose -f docker-compose.prod.yml restart app"
	@echo "✅ Готово!"
