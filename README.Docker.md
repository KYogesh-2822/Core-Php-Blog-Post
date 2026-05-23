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
