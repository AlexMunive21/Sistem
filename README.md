# Sistem

Este proyecto es un **sistema básico** con gestión de usuarios, autenticación, control de roles y un panel de administración con notificaciones y gráficos interactivos.

## 🚀 Características principales

- 🔐 **Inicio de sesión seguro** con control de sesión.
- 🧑‍💼 **Roles de usuario** con acceso restringido:
  - `1 => Admin`
  - `2 => Dirección`
  - `3 => Subdirección`
  - `4 => Gerencias`
  - `5 => Coordinadores`
  - `6 => Generalistas`
  - `7 => Analista`
  - `8 => Logística`
  - `9 => Becarios`
- 📋 CRUD de usuarios (solo para administradores).
- ✅ Clasificación de usuarios activos e inactivos.
- 📈 Gráfica de estado de usuarios con **Chart.js**.
- 🔔 Notificaciones en tiempo real (mensajes no leídos).
- 🌙 Modo claro/oscuro con preferencia guardada.
- 💬 Chat básico entre usuarios (en desarrollo).

## 🛠️ Tecnologías usadas

- PHP 8+
- MySQL (o MariaDB)
- Bootstrap 5
- Chart.js
- JavaScript nativo
- HTML5 + CSS3

## 📁 Estructura del proyecto

```bash
📁 /views
│   ├── dashboard.php
│   ├── login.php
│   ├── profile.php
│   ├── users.php
│   ├── users_inactivos.php
│   ├── ...
📁 /controllers
│   ├── login_process.php
│   ├── create_user.php
│   ├── update_user.php
│   ├── delete_user.php
│   ├── restore_user.php
│   ├── check_new_messages.php
│   ├── ...
📁 /config
│   └── db.php
📁 /assets
│   └── (JS/CSS personalizados si se usan)
