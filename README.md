# PUP AACUP Accreditation Management System

## 🔗 Repository
[GitHub Repository](https://github.com/Shimofu16/PUP-AACUP.git)

## 📥 Installation
### Prerequisites
- PHP 8+
- Composer
- MySQL
- XAMPP or Laragon
- Node.js & npm

### Steps
1. **Clone the repository:**
   ```bash
   git clone https://github.com/Shimofu16/PUP-AACUP.git
   cd PUP-AACUP
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Set up the database:**
   - Create a database in MySQL.
   - Configure database settings in `.env`.
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
4. **Run migrations:**
   ```bash
   php artisan migrate --seed
   ```
5. **Run development build:**
   ```bash
   npm run dev
   ```
6. **Link storage (if uploaded files are not appearing):**
   ```bash
   php artisan storage:link
   ```
7. **Start the application:**
   - If using **XAMPP**, run:
     ```bash
     php artisan serve
     ```
   - If using **Laragon**, access the application via the configured **Application URL** in the Laragon dashboard (e.g., `http://pup-aacup.test`).

