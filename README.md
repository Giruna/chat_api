# Chat API – Laravel alapú üzenetküldő rendszer

Ez a projekt egy RESTful API, amely lehetővé teszi a felhasználók számára, hogy regisztráljanak, megerősítsék az e-mail címüket, ismerősnek jelöljék egymást, és üzeneteket küldjenek egymásnak.

---

## 🧩 Funkcionális követelmények

### 1. Felhasználói regisztráció
- A felhasználók e-mail címmel és jelszóval regisztrálhatnak.
- A regisztrációhoz e-mailes megerősítés (email-verifikáció) szükséges.

### 2. Ismerősök kezelése
- Csak aktív (e-mail címüket megerősített) felhasználók jelölhetik egymást ismerősnek.
- Az ismerős kapcsolat kölcsönös.
- Egy adott felhasználóval csak egy kapcsolat (barátság vagy függő kérés) létezhet.

### 3. Felhasználók listázása
- Az aktív felhasználók listázhatók, hogy lehessen őket ismerősnek jelölni.
- A lista lapozható (`pagination`) és szűrhető (pl. név alapján).

### 4. Üzenetküldés
- Csak barátok tudnak egymásnak üzenetet küldeni.
- A rendszer tárolja az üzeneteket.
- Az üzenetek lekérdezhetők paginált formában.

---

## ⚙️ Technikai követelmények

|  | Verzió / Követelmény |
|------|----------------------|
| PHP verzió | 8.3 vagy újabb |
| Keretrendszer | Laravel 12 |
| Adatbázis | MySQL vagy MariaDB |
| Autentikáció | Laravel Sanctum + e-mail verifikáció |
| API struktúra | RESTful elvek szerint |

---

## 🚀 Telepítés

### 1️⃣ Klónozd a projektet
```bash
git clone https://github.com/Giruna/chat_api.git
cd chat-api
```

### 2️⃣ Telepítsd a függőségeket
```bash
composer install
```

### 3️⃣ Szerkeszd az `.env` fájlt
Az adatbázis beállításokat, igény szerint:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generáld az alkalmazás kulcsát
```bash
php artisan migrate
```
---

## 🔐 API végpontok

### 🔸 Regisztráció
**POST** `/api/register`
```json
{
  "name": "Nándor",
  "email": "nandor@gmail.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

---

### 🔸 E-mail megerősítés
**GET** `/api/email/verify/{id}/{hash}`

---

### 🔸 Bejelentkezés
**POST** `/api/login`
```json
{
  "email": "nandor@gmail.com",
  "password": "12345678"
}
```

---

### 🔸 Ismerősnek jelölés
**POST** `/api/friend-request/{receiverId}`  
*Csak bejelentkezett és hitelesített felhasználók számára.*

---

### 🔸 Ismerős kérés elfogadása
**POST** `/api/friend-request/{senderId}/accept`

---

### 🔸 Felhasználók listázása
**GET** `/api/users?page=1&per_page=5&search=Gábor`

---

### 🔸 Üzenet küldése
**POST** `/api/messages/{receiverId}`
```json
{
  "message": "Szia, hogy vagy?"
}
```

---

### 🔸 Közös beszélgetés lekérdezése
**GET** `/api/messages/{friendId}?page=1&per_page=10`

---


| Method | Endpoint | Description | Auth Required |
|:-------|:----------|:-------------|:---------------|
| POST | `/register` | Register new user | No |
| GET | `/email/verify/{id}/{hash}` | Verify email | No |
| POST | `/login` | User login, returns token | No |
| GET | `/users` | Paginated user list | ✅ |
| POST | `/friend-request/{receiverId}` | Send friend request | ✅ |
| POST | `/friend-request/{senderId}/accept` | Accept friend request | ✅ |
| POST | `/messages/{receiverId}` | Send message | ✅ |
| GET | `/messages/{friendId}` | Get conversation | ✅ |

---

## 🧑‍💻 Készítette
**Ungvári Imre**  
📧 imreungvari75@gmail.com  
💻 Laravel 12 / PHP 8.3 / MySQL  
📅 2025
