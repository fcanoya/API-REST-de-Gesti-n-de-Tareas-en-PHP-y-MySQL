# API REST - Proyecto SENA (PHP & MySQL / XAMPP)

API RESTful desarrollada en **PHP Nativo** utilizando **PDO** para la conexión segura a una base de datos MySQL (gestionada mediante XAMPP). Este proyecto implementa un sistema de autenticación de usuarios (Registro e Inicio de Sesión) y un módulo CRUD completo para la gestión de tareas.

---

## 🚀 Tecnologías y Herramientas Utilizadas
* **PHP** (Backend / Lógica de la API)
* **MySQL / MariaDB** (Base de datos a través de XAMPP / phpMyAdmin)
* **PDO (PHP Data Objects)** (Conexión y consultas seguras)
* **Visual Studio Code** (Entorno de desarrollo)
* **Postman / Thunder Client** (Pruebas de endpoints)

---

## 📂 Estructura del Proyecto

```text
api-sena/
├── config/
│   └── database.php       # Conexión a PDO
├── auth/
│   ├── register.php       # Registro de usuario (Password Hashing)
│   └── login.php          # Inicio de sesión (Verificación de Hash)
├── api/
│   └── tareas.php         # CRUD completo (GET, POST, PUT, DELETE)
├── .gitignore             # Archivos ignorados por Git
└── README.md              # Documentación del proyecto
⚙️ Instrucciones de Instalación y Configuración
1. Clonar o Descargar el Proyecto
1.	Coloca la carpeta del proyecto api-sena dentro del directorio raíz de tu servidor local en XAMPP:
o	Windows: C:\xampp\htdocs\api-sena\
o	Mac / Linux: /Applications/XAMPP/xamppfiles/htdocs/api-sena/
2. Configurar la Base de Dios
1.	Inicia los servicios de Apache y MySQL desde el panel de control de XAMPP.
2.	Abre tu navegador y entra a phpMyAdmin.
3.	Crea y ejecuta el siguiente script SQL en la pestaña SQL:
SQL
CREATE DATABASE IF NOT EXISTS api_sena;
USE api_sena;

-- Tabla de usuarios (Autenticación)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabla para el módulo CRUD
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    completado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
3. Verificar la Conexión
El archivo de conexión (config/database.php) ya viene configurado con las credenciales por defecto de XAMPP:
  •	Host: localhost
  •	Usuario: root
  •	Contraseña: "" (vacía)
  •	Base de datos: api_sena

📌 Endpoints de la API
1. Autenticación (/auth/)
  •	Registrar Usuario: POST http://localhost/api-sena/auth/register.php
      o	Body (JSON): {"nombre": "Juan Pérez", "email": "juan@correo.com", "password": "123456"}
  •	Iniciar Sesión: POST http://localhost/api-sena/auth/login.php
      o	Body (JSON): {"email": "juan@correo.com", "password": "123456"}
2. Gestión de Tareas - CRUD (/api/)
  •	Listar Tareas (GET): GET http://localhost/api-sena/api/tareas.php
  •	Crear Tarea (POST): POST http://localhost/api-sena/api/tareas.php
      o	Body (JSON): {"usuario_id": 1, "titulo": "Estudiar PHP y PDO"}
  •	Actualizar Tarea (PUT): PUT http://localhost/api-sena/api/tareas.php
      o	Body (JSON): {"id": 1, "titulo": "Estudiar PHP avanzado", "completado": true}
  •	Eliminar Tarea (DELETE): DELETE http://localhost/api-sena/api/tareas.php
      o	Body (JSON): {"id": 1}
