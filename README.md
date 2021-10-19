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
- Method: GET
- Get info about API
<p></p>

- Endpoint: http://localhost::8000/api/auth/login
- Method: POST
- Request {
    "email": "example@gmail.com",
    "password": "example"
}
<p></p>

- Endpoint: http://localhost::8000/api/auth/profile
- Method: GET
- Get info from authenticated user
<p></p>

- Endpoint: http://localhost::8000/api/auth/refresh
- Method: POST
- Refresh token
<p></p>

- Endpoint: http://localhost::8000/api/auth/logout
- Method: GET
- User logout
<p></p>

- Endpoint: http://localhost::8000/api/courses
- Method: GET
- Get list of courses
<p></p>

- Endpoint: http://localhost::8000/api/courses/store
- Method: POST
- {
    "name": "JAVA",
    "date_begin": "2021/10/19",
    "date_end": "2021/10/30",
    "price": 12000,
    "description": "Curso de JAVA"
}
- Store new course
<p></p>

- Endpoint: http://localhost::8000/api/courses/1/show
- Method: GET
- Get data from course with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/courses/1/update
- Method: PUT
- {
    "name": "JAVA WEB",
    "date_begin": "2021/10/19",
    "date_end": "2021/10/30",
    "price": 12000,
    "description": "Curso de JAVA WEB"
}
- Update data from course with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/courses/remove
- Method: DELETE
- {
    "id": 1,
}
- delete course with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/courses/completed
- Method: POST
- {
    "id": 1,
}
- Course with id = 1 is not available for new trainne anymore
<p></p>

- Endpoint: http://localhost::8000/api/courses/incompleted
- Method: POST
- {
    "id": 1,
}
- Course with id = 1 is available for new trainne again
<p></p>

- Endpoint: http://localhost::8000/api/courses/search-by-name
- Method: POST
- {
    "query": "java",
}
- Get all courses where we can find "java" word on it.
<p></p>

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
