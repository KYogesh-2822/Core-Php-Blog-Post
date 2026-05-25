docker init

Welcome to the Docker Init CLI!

This utility will walk you through creating the following files with sensible defaults for your project:
  - .dockerignore
  - Dockerfile
  - compose.yaml
  - README.Docker.md

Let's get started!

? What application platform does your project use? PHP with Apache                                                                                                              
? What version of PHP do you want to use? (8.2.12)                                                                                                                              
                                                                                                                                                                                
? What version of PHP do you want to use? 8.2.12                                                                                                                                
? Please enter the relative directory (with a leading .) for your app: (./ (current directory))                                                                                 
                                                                                                                                                                                
? Please enter the relative directory (with a leading .) for your app: ./ (current directory)                                                                                   
? What port do you want to use to access your app? (9000) 9001                                                                                                                  
                                                                                                                                                                                
? What port do you want to use to access your app? 9001                                                                                                                         
                                                                                                                                                                                
✔ Created → .dockerignore                                                                                                                                                       
✔ Created → Dockerfile
✔ Created → compose.yaml
✔ Created → README.Docker.md

→ Your Docker files are ready!
  Review your Docker files and tailor them to your application.
  Consult README.Docker.md for information about using the generated files.

What's next?
  Start your application by running → docker compose up --build
  Your application will be available at http://localhost:9001





 PHP doesn't have the MySQL/PDO extension installed in your Docker container.
 ✅ Fix: Force Rebuild from Scratch
Step 1 — Stop and remove everything

docker compose down --rmi all --volumes

Step 2 — Make sure your php/Dockerfile is exactly this
FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd

WORKDIR /var/www/html

Step 3 — Rebuild with no cache
bashdocker compose build --no-cache
docker compose up -d


done


after this update the dockerfile in the root directory add this ->
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd

and dont remove any thing in this file then run 

docker compose build --no-cache
docker compose up -d

it download the pdo driver and sql now your connect with pdo in core php is done 