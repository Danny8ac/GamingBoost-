# 🎮 GamingBoost

GamingBoost es una plataforma fullstack para la compra de servicios de boosting y coaching gamer.

Incluye:
- 📱 App Android (Kotlin + Jetpack Compose)
- 🌐 Web (HTML, CSS, JavaScript)
- 🧠 Backend API (Laravel + Sanctum)
- 💳 Pedidos + checkout simulado

---

## 🏗️ Tecnologías

### Backend
- PHP 8+
- Laravel
- Laravel Sanctum (token auth)
- MySQL

### Android
- Kotlin
- Jetpack Compose
- Retrofit
- Coroutines

### Web
- HTML5
- CSS3 (glass / gradients estilo gamer)
- JavaScript (Fetch API)

---

## 📱 Repositorio Android
- https://github.com/Danny8ac/GamingBoost-Android

## 🚀 Instalación (Backend Laravel)

### 1) Clonar repositorio

```bash
git clone https://github.com/Danny8ac/GamingBoost-.git
cd GamingBoost-
```

### 2) Instalar dependencias

```bash
composer install
```

### 3) Configurar entorno

Copiar archivo de ejemplo:

```bash
cp .env.example .env
```

Editar `.env` y configurar base de datos:

```env
DB_DATABASE=gamingboost
DB_USERNAME=root
DB_PASSWORD=
```

### 4) Generar clave

```bash
php artisan key:generate
```

### 5) Ejecutar migraciones

```bash
php artisan migrate
```

### 6) Ejecutar servidor

```bash
php artisan serve
```

Servidor disponible en:

http://127.0.0.1:8000

---

## 🌐 Web Demo

Archivos ubicados en:

`public/site/`

Abrir en navegador:

http://127.0.0.1:8000/site/index.html

---

## 📱 App Android

Base URL en emulador:

http://10.0.2.2:8000/api

Incluye:
- Login
- Catálogo de boosts
- Creación de pedidos
- Checkout simulado
- Historial de órdenes

---

## 🔐 Seguridad

Por buenas prácticas no se incluyen en el repositorio:
- `.env`
- `vendor/`

---

## 👨‍💻 Autor

Daniel Ochoa  
Proyecto académico – Desarrollo Fullstack  
2026