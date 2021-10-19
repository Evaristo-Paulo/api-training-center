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
POST - http://localhost::8000/api/auth/login
{
    "email": "example@gmail.com",
    "password": "example"
}

GET - http://localhost::8000/api/auth/logout

GET - http://localhost::8000/api/auth/profile
