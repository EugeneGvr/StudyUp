# StudyUp

An application made for graduates for preparing to External Independent Evaluation. 

## Installation

Clone the repo locally:

```sh
git clone https://github.com/EugeneGvr/StudyUp.git
cd StudyUp
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm install
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

## Create MySQL database:

Enter the local mysql

```sh
mysql -u root -p
```

Input your password and create database

```sh
create database study_up_db character set utf8 collate utf8_general_ci;
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

## Running tests

To run the Ping CRM tests, run:

```
phpunit
```
