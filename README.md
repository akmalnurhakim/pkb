# PHP Docker Web Application

## Project Overview

This project is a web application built using **PHP**, **MySQL**, and **Bootstrap**, and runs inside a Docker environment.

The application is containerized using Docker to ensure consistent development and deployment environments.

## Project Features

* Leaderboard view (By team or by Player)
* Match Schedule (Ongoing, upcoming and history)
* Admin Dashboard (include login/logout features)
* Team Management
* Match Scheduling

## Tech Stack

* PHP (PHP-FPM)
* MySQL
* Nginx
* Bootstrap
* Docker & Docker Compose
* phpMyAdmin (for database management)

## Project Structure

```
project-root
│
├── docker-compose.yml
├── README.md
│
├── nginx
│   └── default.conf
│
├── php
│   └── Dockerfile
│
├── mysql
│   └── init.sql
│
└── src
    └── PHP application files
```

## Prerequisites

Make sure the following are installed:

* Docker
* Docker Compose
* Git

## How to Run the Project

1. Clone the repository

```
git clone https://github.com/akmalnurhakim/pkb.git
cd pkb
```

2. Start Docker containers

```
docker compose up -d --build
```

3. Open the application

```
http://localhost:8080
```

## phpMyAdmin Access

URL:

```
http://localhost:8081
```

Login credentials:

```
Server: mysql
Username: user
Password: password
```

## Database

The database is automatically created when the container starts using the SQL file located in:

```
mysql/init.sql
```

## Stopping the Containers

```
docker compose down
```


