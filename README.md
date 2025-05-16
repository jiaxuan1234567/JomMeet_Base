# JomMeet

This project is a simple event gathering web application built using **vanilla PHP** with a **3-layer MVC architecture**. It uses **Bootstrap 5.3.5** for UI and **jQuery 3.6.0** for JavaScript. It is designed to run on **XAMPP** using PHP's built-in server.

---

## Project Structure

```
JomMeet_Base/
│
├── App/
│   ├── Presentation/
│   ├── BusinessLogic/
│   ├── Persistence/
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── asset/
├── jommeet.sql
└── README.md
```

---

## How to Run the Project

1. **Open Terminal / PowerShell / CMD**
2. **Navigate to the `app` folder**:
   ```bash
   cd JomMeet_Base/app
   ```
3. **Start PHP built-in server**:
   ```bash
   php -S localhost:8000 -t public
   ```
4. **Access the application in your browser**:
   ```
   http://localhost:8000
   ```

---

## How to Import Database using phpMyAdmin

1. Start **XAMPP** and ensure **MySQL** service is running.
2. Open your browser and go to:
   ```
   http://localhost/phpmyadmin
   ```
3. Click on **"Import"** tab from the top menu.
4. Click **"Choose File"** and select `jommeet.sql` located in the `JomMeet_Base` folder.
5. Leave all other settings as default and click **"Go"**.
6. You should now see a database named `jommeet` in your phpMyAdmin.

---

## Requirements

- PHP 8.x
- XAMPP (Apache + MySQL)
- Browser (Chrome/Firefox/Edge)
- Internet access for loading Bootstrap CDN

---
