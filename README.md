## Overview

Training center is an API that users can access to consume and provide services such as: 

- Courses
- Trainers.
- Trainees.
- Managers (secretaries) - Authorized people who can have access to manage all critical information.

## Authentication

<p>Authough you can have access of some services without authentication, most of the services you need to be autheticated firstly.</p>

- Endpoint: http://localhost::8000/api/auth/login
- Method: POST
- Request

{
    "email": "example@gmail.com",
    "password": "example"
}
- Response

{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGktdHJhaW5pbmctY2VudGVyLnRlc3RcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MzQ2MzU2MTEsImV4cCI6MTYzNDcyMjAxMSwibmJmIjoxNjM0NjM1NjExLCJqdGkiOiJOZjN6VlR3emR6cWxWNFhRIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.9ay0xZkLIowfb1uW3pio8q2GOch1Bn82g74_JkMr7Yg",
    "token_type": "bearer",
    "token_validity": 86400
}

- token: Token you need to pass in all request that needs authetication
- token_type: type of the token
- token_validity: How long this token is valid. Default value is 1 day


## Resourses

All endpoints you need to know to start using training center API

- Endpoint: http://localhost::8000/
- Method: POST
- Get info about API


- Endpoint: http://localhost::8000/api/auth/login
- Method: POST
- Request {
    "email": "example@gmail.com",
    "password": "example"
}
- Login

GET - http://localhost::8000/api/auth/profile

POST - http://localhost::8000/api/auth/refresh

GET - http://localhost::8000/api/auth/logout

GET - http://localhost::8000/api/courses

POST - http://localhost::8000/api/courses/store 
{
    "name": "JAVA",
    "date_begin": "2021/10/19",
    "date_end": "2021/10/30",
    "price": 12000,
    "description": "Curso de JAVA"
}

GET - http://localhost::8000/api/courses/1/show

PUT - http://localhost::8000/api/courses/1/update
{
    "name": "JAVA",
    "date_begin": "2021/10/19",
    "date_end": "2021/10/30",
    "price": 12000,
    "description": "Curso de JAVA"
}

DELETE - http://localhost::8000/api/courses/remove
{
    "id": 1,
}

POST - http://localhost::8000/api/courses/completed
{
    "id": 1,
}

POST - http://localhost::8000/api/courses/incompleted
{
    "id": 1,
}

POST - http://localhost::8000/api/courses/search-by-name
{
    "query": "Java",
}

GET - http://localhost::8000/api/trainers

POST - http://localhost::8000/api/trainers/store 
{
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}

GET - http://localhost::8000/api/trainers/1/show

PUT - http://localhost::8000/api/trainers/1/update
{
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}

DELETE - http://localhost::8000/api/trainers/remove
{
    "id": 1,
}

GET - http://localhost::8000/api/trainees

POST - http://localhost::8000/api/trainees/store 
{
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}

GET - http://localhost::8000/api/trainees/1/show

PUT - http://localhost::8000/api/trainees/1/update
{
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}

DELETE - http://localhost::8000/api/trainees/remove
{
    "id": 1,
}
