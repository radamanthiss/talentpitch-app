<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Initial config
To run the project in local is necessary apply some configurations that list in next comments

- install postgresql from https://www.postgresql.org/download/
- for setup the environment with composer and php to run the project. you can install laravel Herd that works to install latest version from composer and php https://herd.laravel.com/windows 
- in the path of this code run composer install
- change the environment variables in the env to put the values in database configuration
- install api to create the api routes from routes/api.php usign php artisan install:api if doesn't have configured
- create into postgresql a database named laravel, you can use pgAdmin or run the next command CREATE DATABASE laravel
- run the migrations wiht php artisan migrate to create the tables into laravel database
- we need get the key from open api so if you have an account you can create your api key or use the api key that is in env file



## Migrations
execute the next command to apply the migration of tables into the database
- php artisan migrate

## Project execution
For run this project please use this command
- php artisan serve

## First step to use the endpoints
NOTE: for all the endpoint in headers configure in postman the variable
```
{
  'Accept': 'application/json'
}
```
this is for return the json response unauthenticated when doesn't pass the Bearer token for every endpoint

## API GPT
First you have to use the endpoint to generate the initial data for all the tables. In this case you can run this endpoint
- http://127.0.0.1:8000/api/process-gpt
In the body you have to pass this configuration

- users
```json
{
  "type": "users",
  "prompt":"Im looking for a JSON structured exactly like this: {'users': [{'name': 'Example Name', 'email': 'example@email.com', 'image_path': '/path/to/image.jpg', 'password': 'examplePassword'}]}. Please generate a similar JSON object with 10 unique users and keep present that password field must be at least 8 characters like password1 the password must be unique for every user"
}
```

- companies
```json 
{
  "type": "companies",
  "prompt": "Im looking for a JSON structured exactly like this: {'companies': [{'name': 'Tech Innovations', 'image_path': '/path/to/image.jpg', 'location': 'Silicon Valley', 'industry': 'Technology', 'user_id': 1}]} with 10 unique companies. Each company should have a unique name, a representative image path, a specific location that varies across entries, industries such as Technology, Healthcare, Finance, and Education, and user_id should be integers indicating different users from 1 to 10 that is the user_id in table users"
}

```

- challenges
```json
{
  "type": "challenges",
  "prompt": "Im looking for a JSON structured exactly like this: {'challenges': [{'title': 'Solve the maze', 'description': 'Create an algorithm to solve a given maze', 'difficulty': 'hard', 'user_id': 1}]} with 10 unique challenges. The titles should be creative and varied, the descriptions detailed, explaining the challenge, the difficulty can range from easy, medium, to hard, and user_id should be integers representing different users from 1 to 10 that is the user_id in table users"
}
```

- programs
```json 
{
  "type": "programs",
  "prompt": "I am looking for a JSON structured exactly like this: {'programs': [{'title': 'Introduction to Python', 'description': 'A comprehensive guide to mastering Python programming', 'start_date': '2023-01-01', 'end_date': '2023-06-01', 'user_id': 1}]} with 10 unique programs. Titles should reflect a variety of educational or training themes, descriptions should provide a brief overview of the program content, start and end dates should span plausible durations, and user_id should be integers representing different user from 1 to 10 that is the user_id in table users"
}
```

## CRUD operations
After run the gpt api and insert data you can use the crud for the other endpoint, this is the configuration for every endpoint in the project
## IMPORTANT
You have to generate a bearer token to use the different endpoints
- POST http://127.0.0.1:8000/api/token
```json
{
  "email":"user1@email.com",
  "password":"password1"
}
```
with this the response return a object like this
```json
{
  "token": "1|Gqal9RTKzxHiab9EGAr7wbBPM9JWeHSLuk0qoBgIe3b7d2f7"
}
```
And this is the token that you have to put into postman Authorization sheet and select the bearer type

## Users
- GET http://127.0.0.1:8000/api/users?page=1&items=10
the page and items are optionally if not pass return also 10 items

- GET http://127.0.0.1:8000/api/users/{id}
id: id of the user return info filter by id

- POST http://127.0.0.1:8000/api/users
```json
{
  "name": "user 1",
  "email":"test@email.com",
  "image_path":"path/to/image.jpg",
  "password":"password1234"
}
```

- PUT http://127.0.0.1:8000/api/users/{id}
id: the id that i want to update
```json
{
  "name": "user 1",
  "email":"test@gmail.com",
  "image_path":"path/to/image.jpg",
  "password":"password1234"
}
```

- DELETE http://127.0.0.1:8000/api/users/{id}
id: The id that i want to delete

## Companies

- GET http://127.0.0.1:8000/api/companies?page=1&items=10
the page and items are optionally if not pass return also 10 items

- GET http://127.0.0.1:8000/api/companies/{id}
id: id of the company return info filter by id

- POST http://127.0.0.1:8000/api/companies
```json
{
  "name": "Tesla",
  "image_path":"path/to/image.jpg",
  "location":"California",
  "industry":"AI",
  "user_id":1
}
```

- PUT http://127.0.0.1:8000/api/companies/{id}
id: the id that i want to update
```json
{
  "name": "Tesla",
  "image_path":"path/to/image.jpg",
  "location":"California",
  "industry":"AI",
  "user_id":1
}
```

- DELETE http://127.0.0.1:8000/api/companies/{id}
id: the id that i want to delete


## Challenges

- GET http://127.0.0.1:8000/api/challenges?page=1&items=10
the page and items are optionally if not pass return also 10 items

- GET http://127.0.0.1:8000/api/challenges/{id}
id: id of the challenge return info filter by id

- POST http://127.0.0.1:8000/api/challenges
```json
{
  "title": "Solve the puzzle",
  "description":"Create app that solve puzzle",
  "difficulty":"hard",
  "user_id":1
}
```

- PUT http://127.0.0.1:8000/api/challenges/{id}
id: the id that i want to update
```json
{
  "title": "Solve the puzzle",
  "description":"Create app that solve puzzle",
  "difficulty":"hard",
  "user_id":1
}
```

- DELETE http://127.0.0.1:8000/api/challenges/{id}
id: the id that i want to delete


## programs
- GET http://127.0.0.1:8000/api/programs?page=1&items=10
the page and items are optionally if not pass return also 10 items

- GET http://127.0.0.1:8000/api/programs/{id}
id: id of the program return info filter by id

- POST http://127.0.0.1:8000/api/challenges
```json
{
  "title": "Introduction to Python",
  "description":"Python programming",
  "start_date":"2024-03-01",
  "end_date":"2024-06-01",
  "user_id":1
}
```

- PUT http://127.0.0.1:8000/api/challenges/{id}
id: the id that i want to update
```json
{
  "title": "Introduction to Python",
  "description":"Python programming",
  "start_date":"2024-03-01",
  "end_date":"2024-06-01",
  "user_id":1
}
```

- DELETE http://127.0.0.1:8000/api/challenges/{id}
id: the id that i want to delete

## programs_participant

- GET http://127.0.0.1:8000/api/programs-participant?page=1&items=10
the page and items are optionally if not pass return also 10 items

- GET http://127.0.0.1:8000/api/programs-participant/{id}
id: the id to filter data in program participant

- POST http://127.0.0.1:8000/api/programs-participant

- PUT http://127.0.0.1:8000/api/programs-participant/{id}

- DELETE http://127.0.0.1:8000/api/programs-participant/{id}
id: the id that i want to delete


## PRODUCTION DEPLOYMENT
For this project i follow the steps for laravel site about prod deployment and use laravel forge with vultr for deploy the git repository
this is the url for test the status of deployment

- http://45.77.112.84/api/up

For test the different endpoint only you have to change the domain localhost in postman by http://45.77.112.84/api/{name_of_endpoint}
now for testing the connection of production database we can use TablePlus to connecting using ssh

- https://tableplus.com/download

Once install use the connection url that you can see in the email with the password then you can see if the information using the endpoint from GPT is inserting into the different tables. Follow the same step
- 1 inserting data to different tables using GPT api
- 2 generate a token using the endpoint api/token
- 3 test the different endpoints for users, programs, challenges etc. Using the token generated for authenticated

For a best testing the current users table already have information generated from API gpt you can test the other table inserting like this
```json 
{
  "type": "companies",
  "prompt": "Im looking for a JSON structured exactly like this: {'companies': [{'name': 'Tech Innovations', 'image_path': '/path/to/image.jpg', 'location': 'Silicon Valley', 'industry': 'Technology', 'user_id': 1}]} with 10 unique companies. Each company should have a unique name, a representative image path, a specific location that varies across entries, industries such as Technology, Healthcare, Finance, and Education, and user_id should be integers indicating different users from 1 to 10 that is the user_id in table users"
}
```

- example users insert

![Captura de pantalla 2024-04-05 a la(s) 9 13 00 a  m  (2)](https://github.com/radamanthiss/talentpitch-app/assets/22681704/af27bfdc-3c42-4430-9bed-ed21c4f77257)

- example user table information
![Captura de pantalla 2024-04-05 a la(s) 9 14 46 a  m](https://github.com/radamanthiss/talentpitch-app/assets/22681704/2dac1073-e107-415b-a775-cc8a084948d9)

## Recommendations
Please follow the steps for a successful testing

## Unit Test

run the command
- php artisan test 
for execute the different test of the app


## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
