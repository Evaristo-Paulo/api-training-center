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
- Request {
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
- Request {
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
- Request {
    "id": 1,
}
- delete course with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/courses/completed
- Method: POST
- Request {
    "id": 1,
}
- Course with id = 1 is not available for new trainne anymore
<p></p>

- Endpoint: http://localhost::8000/api/courses/incompleted
- Method: POST
- Request {
    "id": 1,
}
- Course with id = 1 is available for new trainne again
<p></p>

- Endpoint: http://localhost::8000/api/courses/search-by-name
- Method: POST
- Request {
    "query": "java",
}
- Get all courses where we can find "java" word on it.
<p></p>

- Endpoint: http://localhost::8000/api/trainers
- Method: GET
- Get list of trainers
<p></p>

- Endpoint: http://localhost::8000/api/trainers/store
- Method: POST
- Request {
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}
- Store new trainers
<p></p>

- Endpoint: http://localhost::8000/api/trainers/1/show
- Method: GET
- Get data from trainers with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/trainers/1/update
- Method: PUT
- Request {
    "name": "Daniel Canhamena",
    "email": "danielcanhamena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}
- Update data from trainers with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/trainers/remove
- Method: DELETE
- Request {
    "id": 1,
}
- delete trainers with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/trainees
- Method: GET
- Get list of trainees
<p></p>

- Endpoint: http://localhost::8000/api/trainees/store
- Method: POST
- Request {
    "name": "Maria Madalena",
    "email": "mariamadalena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}
- Store new trainees
<p></p>

- Endpoint: http://localhost::8000/api/trainees/1/show
- Method: GET
- Get data from trainees with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/trainees/1/update
- Method: PUT
- Request {
    "name": "Maria Madalena João",
    "email": "mariamadalena@gmail.com",
    "phone": "999123456",
    "bi": "12345678901234",
    "address": "Cacuaco, Luanda",
    "gender": 1,
    "courses": [2]
}
- Update data from trainees with id = 1
<p></p>

- Endpoint: http://localhost::8000/api/trainees/remove
- Method: DELETE
- Request {
    "id": 1,
}
- delete trainees with id = 1
<p></p>

## DATA

Here you can find some info about data and its value

- Course 
- name: Course name (required)
- date_begin: date to begin this course (required) (YYYY/MM/DD)
- date_end: date to end this course (required) (YYYY/MM/DD)
- price: cost (required)
- Description: Some info about this course (optional)
<p></p>

- Trainer 
- name: Trainer full name (required)
- email: email (required)
- phone: phone (required)
- bi: Identity Document (required)
- address: address (required)
- gender: gender id (required)
- courses: course id (it can be array of courses id) (required)
<p></p>

- Trainee
- name: Trainee full name (required)
- email: email (required)
- phone: phone (required)
- bi: Identity Document (required)
- address: address (required)
- gender: gender id (required)
- courses: course id (it can be array of courses id) (required)