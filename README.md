# Aktin API – REST API in PHP 8.2 (Nette Framework)

Testovací úkol pro firmu **Aktin**. Projekt demonstruje schopnosti seniorního vývojáře – SOLID architektura, Dependency Injection, návrhové vzory a čistý kód.

---

## 🚀Tech stack

- PHP 8.2 + Nette Framework
- Docker (Nginx + PHP-FPM)
- SQLite (s migrací)
- JWT autentizace (`firebase/php-jwt`)
- Composer
- PSR-12 + SOLID principles
- Unit testy

---

## 🔧Jak spustit projekt

### 1. Klonuj repozitář a spusť Docker

```bash
git clone https://github.com/tvoje-username/aktin-api.git
cd aktin-api
docker-compose up -d --build
