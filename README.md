Installation:

1. Clone repository:
`git clone Laravel-Project-Management-App`

2. Install dependencies, setup enviroment:
composer install
cp .env.example .env

3. Create the necessary database:
php artisan db
create database project_management

4. Run the initial migrations and seeders:  
php artisan migrate --seed