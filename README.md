# Тестовое задание

[Текст задания](docs/TASK.md)

## Решение

Для установки проекта выполните следующие команды:

```bash
git clone --depth 1 https://github.com/anton-pribora/eda1-test-task eda1-test-task
cd eda1-test-task
echo -e "UID=$(id -u)\nGID=$(id -g)" >> .env
sed -i "s/1000/$(id -u)/" src/configs/50-server.local.php
docker-compose up -d
docker exec eda1-test-task php /app/src/bin/update_cdn_libs.php
docker exec eda1-test-task php /app/src/migrations/apply.php
```

Для входа в панель управления перейдите по адресу [http://127.0.0.1:4101/](http://127.0.0.1:4101/),
используя следующие данные аутентификации:

```text
Логин: test
Пароль: test
```

## Общий вид

![docs/example.png](docs/example.png)
