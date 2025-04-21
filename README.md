# How to run this project

```bash
git clone https://github.com/yourname/clean-api.git
cd clean-api
cp .env.local .env
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php php doctrine-migrations.php migrate
```

Application will run at:  
[http://localhost:8080](http://localhost:8080)

---

# Unit tests

```bash
docker-compose exec php vendor/bin/phpunit tests
```

---

# Examples of API callings

API has one default user:

```
email: admin@example.com  
password: admin123
```

---

## Register new user

```bash
curl -i -X POST http://localhost:8080/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "reader10000@example.com",
    "password": "admin123",
    "name": "test",
    "role": "reader"
  }'
```

**Response:**
```json
{
  "code": 201,
  "message": "User registered successfully"
}
```

---

## Login

```bash
curl -X POST http://localhost:8080/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "admin123"
  }'
```

**Response:**
```json
{
  "token": "your-jwt-token"
}
```

Use token in authorization header:
```
Authorization: Bearer your-jwt-token
```

---

## Create article

```bash
curl -X POST http://localhost:8080/articles \
  -H "Authorization: Bearer your-jwt-token" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My Article",
    "content": "This is the article content."
  }'
```

---

## Get all articles

```bash
curl http://localhost:8080/articles
```

---

# Assignment description

```
Úkol (zjednodušený)
Vytvořte malé REST API v PHP 8.2 pro práci se dvěma entitami: User a Article. Požadujeme základní operace (CRUD) a jednoduchou logiku rolí.

1. Entity
   User:
   Pole: id, email, password_hash, name, role.
   Role:
   admin (může vše),
   author (může vytvářet/upravovat své články, nikoli cizí),
   reader (může články pouze číst).
   Article:
   Pole: id, title, content, author_id, created_at, updated_at.
   Článek vytváří buď author nebo admin; upravovat/mazat ho smí pouze jeho vlastník nebo admin.
   reader může pouze prohlížet seznam článků a detailní informace.

2. Funkce API
   Registrace a přihlášení:
     POST /auth/register — registrace nového uživatele (role admin, author nebo reader).
     POST /auth/login — přihlášení, vrací token (JWT či jiný způsob autorizace dle vlastního výběru).
   Správa uživatelů (pouze pro admin):
     GET /users — seznam všech uživatelů.
     GET /users/{id} — získání dat o konkrétním uživateli.
     POST /users — vytvoření uživatele (včetně určení role).
     PUT /users/{id} — úprava uživatele (email, name, role).
     DELETE /users/{id} — smazání uživatele.
   Správa článků:
     GET /articles — seznam všech článků.
     GET /articles/{id} — získání článku podle id.
     POST /articles — vytvoření článku (pokud je role author nebo admin).
     PUT /articles/{id} — úprava článku (pokud je uživatel jeho vlastníkem nebo admin).
     DELETE /articles/{id} — smazání článku (pokud je uživatel jeho vlastníkem nebo admin).

3. Technické požadavky
   Jazyk: PHP 8.2.
   Framework: je nutné použít Nette nebo Symfony.
   Docker:
     V projektu musí být funkční Dockerfile a/nebo docker-compose.yml, aby bylo možné aplikaci snadno spustit v Dockeru.
     Krátký popis nastavení a spuštění v Dockeru uveďte v README.
   Ukládání dat:
     Můžete použít SQLite pro zjednodušení. Lze využít migrace nebo jednoduchý SQL skript pro vytvoření tabulek.
   Autorizace:
     Jakýkoliv jednoduchý mechanismus tokenů — JWT, Bearer token atd.
   Testy:
     Alespoň 2–3 testy (unit nebo feature) ověřující klíčové role a omezení (např. že reader nemůže vytvářet článek).
   README:
     Krátký návod ke spuštění,
     Příklad volání API (registrace, přihlášení, vytvoření článku),
     Případné upřesnění, jak testovat (pokud je to potřeba).
   Git repozitář:
     Zdrojový kód zveřejněte v veřejném repozitáři (GitHub, GitLab, Bitbucket apod.),
     Zašlete odkaz na repozitář společně s vypracovaným zadáním.

4. Hodnocení
   Architektura a kvalita kódu:
     Dodržení základních PSR standardů (PSR-12, PSR-4).
     Rozdělení logiky do vrstev (kontrolery, modely, servisy, router).
     Využití moderních možností PHP 8.2 (typování, readonly, enum dle potřeby).
   Testy:
     Existence a funkčnost alespoň několika testů, které prověřují role (zda reader nemá přístup k POST/PUT/DELETE, zda admin má přístup ke všem operacím atd.).
   Dokumentace:
     Srozumitelný popis spuštění (zvlášť pokud nastavujete Nette/Symfony projekty s využitím CLI či Dockeru).
     Příklad volání API.
```

