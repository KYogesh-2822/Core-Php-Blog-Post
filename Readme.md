# Core PHP Blog Post Project

A Core PHP blog application where users can register, verify their email, login, create blog posts, upload images, upload a blog file, like posts, comment on blogs, and reply to comments.

This project is built using **Core PHP**, **MySQL**, **Apache**, and **Docker**.

---

## Features

### User Authentication

- User registration
- Email verification using verification code
- Login only after email verification
- Secure password hashing
- Logout functionality

### Blog Post System

- Create blog posts
- Add blog title
- Add short description of 3 to 5 lines
- Upload single or multiple blog images
- Upload optional full blog file for long content
- Show blog preview with **Read More**
- Show uploaded blog file on blog detail page

### Like System

- Logged-in users can like blog posts
- One user can like a blog only once
- Like count is shown on blog listing and detail page

### Comment and Reply System

- Logged-in users can comment on blog posts
- Blog owner can reply to comments on their own blog
- Replies are shown below comments in hierarchy format
- Comment count is shown only when comments exist
- Reply count is shown only when replies exist

### Database Relations

The project uses relational database tables with:

- Primary keys
- Foreign keys
- JOIN queries
- One-to-many relationships
- Self-referencing comment replies

---

## Technology Used

- Core PHP
- MySQL
- Apache
- Docker
- Docker Compose
- phpMyAdmin
- HTML
- CSS
- Git and GitHub

---

## Project Folder Structure

```text
core-php-project/
├── Core-Php/
│   ├── index.php
│   ├── project_flow.php
│   └── uploads/
├── Dockerfile
├── compose.yaml
├── .dockerignore
├── README.Docker.md
└── Readme.md