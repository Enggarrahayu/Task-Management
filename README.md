# Task-Management
A Task Management system implementing Role-Based Access Control (RBAC) with Filament and Laravel.

## Installation steps and how to run the project in local 
- <b>Clone the repo</b>: [git clone https://github.com/Enggarrahayu/Task-Management.git]
<br>cd your-project
- <b>Install PHP Dependency</b>: composer install
- <b> Copy and configure the .env file</b>: cp .env.example .env <br>
Update this line in .env
DB_DATABASE=your_database_name <br>
DB_USERNAME=root <br>
DB_PASSWORD= <br>
- <b> Generate App Key </b>php artisan key:generate
- <b> Migrate the database </b>: php artisan migrate
- <b> Run database seeder</b> (to generate dummy data and roles permission): php artisan db:seed

- <b>Run the server </b>: php artisan serve
- <b> Access project in this URL</b>: http://localhost:8000/admin
### Notes
Dummy users for login are generated in the `UserSeeder` class. You can check the password there.





