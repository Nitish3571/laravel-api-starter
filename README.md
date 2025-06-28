# 🚀 Laravel API Starter Kit

A scalable, modular, and well-structured **Laravel API Starter Kit** built with best industry practices including **Authentication**, **Authorization**, **Role & Permission Management**, **CRUD APIs**, and **Swagger API Documentation**.

---

## ✅ Features

- 🔐 **Authentication** (Login & Register)
- 👥 **Authorization** (Role & Permission based access)
- 🧑‍💼 **User Management** (CRUD)
- 🧩 **Module Management** (CRUD)
- 🔄 **Role & Permission Management**
- 🧾 **Swagger API Documentation** for easy API testing
- 🧬 Built using **Repository-Service Pattern**
- 🌱 **Seeders** for Users, Roles, and Permissions

---

## 📦 Packages Used

| Feature              | Package                                |
|----------------------|----------------------------------------|
| Role & Permissions   | [spatie/laravel-permission](https://github.com/spatie/laravel-permission) |
| Swagger API Docs     | [l5-swagger](https://github.com/DarkaOnLine/L5-Swagger) |
| Media Uploads        | [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary) |

---

## 🏗️ Project Architecture

- **Repository Pattern**
- **Service Layer**
- **Swagger Integration**
- **Modular & Scalable Codebase**

---

## 🛠️ Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Laravel >= 12.x
- MySQL or compatible DB

### 🧬 Clone & Setup

```bash
# Create a new Laravel project using this starter
composer create-project nitish/laravel-api-starter

# Navigate into the project
cd laravel-api-starter

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your `.env` file with database and mail details

# Run migrations and seeders
php artisan migrate --seed

# Install Swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate

---

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

Developed by **Nitish Kumar**  
Feel free to contribute or fork this repository.

---

## 🙌 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## 💡 Support

If you find this project helpful, give it a ⭐ on GitHub and share it with your dev friends!
