# TASK MANAGEMENT

This is a simple API application that implement authentication, API calls with proper methods and validation.

### SYSTEM REQUIREMENT

-   PHP 8 or higher
-   PHP Modules
    > -   bcmath
    > -   mysqli
    > -   pdo
    > -   pdo_mysql

### Running Local

-   First make sure you have running Mysql Database.
-   Make a copy of `.env.example` and name it `.env`
-   In your terminal, go to the base directory of the application and run below command

```
php artisan key:generate
```

-   Open `.env` file and locate below variable and set database connection

```
DB_HOST=taskmanagement_db
DB_PORT=3306
DB_DATABASE=taskmanagement_db
DB_USERNAME=root
DB_PASSWORD=
```

-   Now you can run your application using below command

```
php artisan serve
```

## Postman Reference

Located in

```
/postman
```

## Routes

<details>
<summary>User Registration</summary>

```
POST /api/users/register
```

Parameters

```
{
    "name": "",
    "email": "",
    "password": "",
    "password_confirmation": ""
}
```

</details>

<details>
<summary>User Login</summary>

```
POST /api/users/login
```

Parameters

```
{
    "email": "",
    "password": ""
}
```

</details>

<details>
<summary>Task Create</summary>

```
POST /api/tasks
```

Parameters

```
{
    "title": "",
    "description": "",
    "status": "",
    "due_date": ""
}
```

</details>

<details>
<summary>Task Update</summary>

```
PUT /api/tasks/{Task ID}
```

Parameters

```
{
    "title": "",
    "description": "",
    "status": "",
    "due_date": ""
}
```

</details>

<details>
<summary>Task Delete</summary>

```
DELETE /api/tasks/{Task ID}
```

</details>

<details>
<summary>Task Listing</summary>

```
GET /api/tasks
```

</details>
