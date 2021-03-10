# Leave Management

Leave management that can use database or json file as storage

- Switch storage by updating config

## Tech

This project uses a number of projects/library to work properly:

- [PHP - Codeigniter] - For the backend
- [HTML, CSS, Javascript] - For the frontend
- [Mysql] - Database
- [Docker] - Container engine

## Installation
Install the dependencies and devDependencies and start the server.

**Need to install/create**
- PHP 7.3
- Docker

**Install dependencies**

Install codeigniter dependencies
```sh
cd codeigniter
composer install
```

**Build environment**
```sh
docker-compose build
```

**Start the environment**
```sh
docker-compose up
```

**Setup Storage**
##### Database
Can run the script `database/dump.sql` to setup the database with seeded data

##### JSON
JSON files are stored in `codeigniter/storage`

*Employees*
| Username  | Password  | Role   | Leave Credits |
| :-------: | :-------: | :----: | :-----------: |
| username1 | password1 | user   | 10            |
| username2 | password2 | user   | 10            |
| username3 | password3 | user   | 10            |
| admin     | admin123  | admin  | 10            |


### Config
- Update `codeigniter/application/config/config.php` to set data_storage value
- **data_storage** - config item checked to know which storage the application will use
-- **database** - will use Repository and save data to database
-- **json** - will use JSONRepository and save data to json file


Once started, can now launch test page by accessing `http://localhost/`