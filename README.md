# Project Managerment App

Project Manager built on Laravel 10 and Filament 3.

## Features

There are 3 user groups:
-   Employee
-   Project manager
-   Administrator

The administrator and project manager have access to the admin panel. Other users have access to the employee panel.

##### Employee:

-   View their projects and teams
-   Create and modify their tasks
-   Update your profile
-   View dashboard to summarize information

##### Project manager:

-   Create, read and update their projects
-   CRUD resource on all teams and tasks from their projects, assign tasks to employees
-   View all users
-   Update your profile
-   View dashboard to summarize information

##### Administrator:

-   CRUD resource on all projects, tasks, teams and users
-   View dashboard to summarize information


## Installation:

1. Clone repository:

    ```
    git clone https://github.com/ArtemTitariev/Laravel-Project-Management-App.git
    ```

2. Install dependencies, setup enviroment:

    ```
    composer install
    npm install
    cp .env.example .env
    ```

3. Then create the necessary database (if TTY mode is not supported on your machine, create the database manually):

    ```
    php artisan db
    create database project_management
    ```

4. Run the initial migrations and seeders:

   ```
   php artisan migrate --seed
   ```

5. Finaly, link the file storage:

   ```
   php artisan storage:link
   ```

## Addtitonal information: 
Password `1234` is set for all users. 
Of course, it can be updated, but you need to come up with a more complex one, because a password like this one will not pass validation 😊.
