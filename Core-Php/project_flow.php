<?php
$pageTitle = "Core PHP Blog Project Flow";
$steps = [
    "Project setup with Docker, PHP, MySQL, phpMyAdmin",
    "Create database tables with primary keys and foreign keys",
    "Register user",
    "Send email verification code",
    "Verify email before login",
    "Login user",
    "Create blog post with title, short description, images, and optional file",
    "Show blogs on home page with Read More",
    "Like blog only once per user",
    "Comment on blog",
    "Blog owner can reply to comments",
    "Show comment and reply count",
    "Use JOIN queries to get related data"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Mermaid Flowchart Library -->
    <script type="module">
        import mermaid from "https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs";
        mermaid.initialize({
            startOnLoad: true,
            theme: "default"
        });
    </script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        header {
            background: #222;
            color: #fff;
            padding: 25px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 30px;
        }

        header p {
            margin-top: 8px;
            color: #ddd;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        ul {
            line-height: 1.8;
        }

        .step-list li {
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            background: #222;
            color: #fff;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 13px;
            margin-right: 8px;
        }

        .mermaid {
            background: #fafafa;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        table th {
            background: #222;
            color: #fff;
        }

        .footer {
            text-align: center;
            color: #777;
            padding: 20px;
        }

        .note {
            background: #fff8d6;
            padding: 15px;
            border-left: 5px solid #f2c94c;
            border-radius: 5px;
        }

        code {
            background: #eee;
            padding: 3px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header>
    <h1><?= htmlspecialchars($pageTitle) ?></h1>
    <p>Login, Register, Email Verification, Blog Upload, Likes, Comments, and Replies</p>
</header>

<div class="container">

    <div class="card">
        <h2>Project Overview</h2>
        <p>
            This project is a Core PHP blog system where users can register, verify their email,
            login, create blog posts, upload images, upload a full blog file, like posts,
            comment on posts, and reply to comments.
        </p>

        <div class="note">
            <strong>Important:</strong>
            User cannot login until email verification is completed.
        </div>
    </div>

    <div class="card">
        <h2>Main Application Flow</h2>

        <div class="mermaid">
flowchart TD
    A[User Opens Website] --> B{Is User Logged In?}

    B -- No --> C[View Blogs]
    C --> D[Register]
    D --> E[Save User]
    E --> F[Send Verification Code]
    F --> G[Verify Email]
    G --> H{Code Correct?}
    H -- No --> G
    H -- Yes --> I[Allow Login]

    I --> J[Login]
    J --> K{Login Valid?}
    K -- No --> J
    K -- Yes --> L[Dashboard / Home]

    B -- Yes --> L

    L --> M[Create Blog Post]
    M --> N[Upload Title and Short Description]
    N --> O[Upload Images]
    O --> P{Large Blog?}
    P -- Yes --> Q[Upload Blog File]
    P -- No --> R[Save Blog]
    Q --> R

    L --> S[Open Blog Detail]
    S --> T[Like Blog]
    S --> U[Comment on Blog]
    U --> V[Blog Owner Reply]
    V --> W[Show Comment Hierarchy]
        </div>
    </div>

    <div class="card">
        <h2>Email Verification Flow</h2>

        <div class="mermaid">
flowchart TD
    A[User Register Form] --> B[Validate Name Email Password]
    B --> C{Email Already Exists?}
    C -- Yes --> D[Show Error]
    C -- No --> E[Hash Password]
    E --> F[Insert User with is_verified = 0]
    F --> G[Generate 6 Digit Code]
    G --> H[Save Code in Database]
    H --> I[Send Code to Email / Log File]
    I --> J[User Enters Code]
    J --> K{Code Valid?}
    K -- No --> L[Show Invalid Code]
    K -- Yes --> M[Update User is_verified = 1]
    M --> N[User Can Login]
        </div>
    </div>

    <div class="card">
        <h2>Blog Post Flow</h2>

        <div class="mermaid">
flowchart TD
    A[Logged In User] --> B[Create Post Page]
    B --> C[Enter Title]
    C --> D[Enter Short Description]
    D --> E{Description 3 to 5 Lines?}
    E -- No --> F[Show Validation Error]
    E -- Yes --> G[Upload 1 to 5 Images]
    G --> H{Full Blog File Uploaded?}
    H -- Yes --> I[Validate File Type]
    H -- No --> J[Skip File]
    I --> K[Save Blog File]
    J --> L[Save Post]
    K --> L
    L --> M[Save Images]
    M --> N[Redirect to Blog Detail]
        </div>
    </div>

    <div class="card">
        <h2>Like, Comment, and Reply Flow</h2>

        <div class="mermaid">
flowchart TD
    A[User Opens Blog Detail] --> B[Show Blog]
    B --> C[Like Button]
    C --> D{Already Liked?}
    D -- Yes --> E[Unlike Blog]
    D -- No --> F[Insert Like]

    B --> G[Comment Box]
    G --> H[User Adds Comment]
    H --> I[Save Comment parent_id NULL]

    I --> J{Logged In User is Blog Owner?}
    J -- No --> K[Only View Comments]
    J -- Yes --> L[Show Reply Button]
    L --> M[Owner Replies]
    M --> N[Save Reply with parent_id]
    N --> O[Show Reply Below Comment]
        </div>
    </div>

    <div class="card">
        <h2>Database Relationship</h2>

        <div class="mermaid">
erDiagram
    users ||--o{ posts : creates
    users ||--o{ comments : writes
    users ||--o{ post_likes : likes
    users ||--o{ email_verifications : verifies

    posts ||--o{ post_images : has
    posts ||--o{ comments : receives
    posts ||--o{ post_likes : receives

    comments ||--o{ comments : replies
        </div>
    </div>

    <div class="card">
        <h2>Tables Required</h2>

        <table>
            <thead>
                <tr>
                    <th>Table</th>
                    <th>Purpose</th>
                    <th>Main Relations</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>users</td>
                    <td>Stores registered users</td>
                    <td>Primary table</td>
                </tr>
                <tr>
                    <td>email_verifications</td>
                    <td>Stores verification codes</td>
                    <td>user_id → users.id</td>
                </tr>
                <tr>
                    <td>posts</td>
                    <td>Stores blog posts</td>
                    <td>user_id → users.id</td>
                </tr>
                <tr>
                    <td>post_images</td>
                    <td>Stores multiple blog images</td>
                    <td>post_id → posts.id</td>
                </tr>
                <tr>
                    <td>post_likes</td>
                    <td>Stores blog likes</td>
                    <td>post_id → posts.id, user_id → users.id</td>
                </tr>
                <tr>
                    <td>comments</td>
                    <td>Stores comments and replies</td>
                    <td>post_id → posts.id, user_id → users.id, parent_id → comments.id</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Development Steps</h2>

        <ul class="step-list">
            <?php foreach ($steps as $index => $step): ?>
                <li>
                    <span class="badge">Step <?= $index + 1 ?></span>
                    <?= htmlspecialchars($step) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card">
        <h2>Project Pages</h2>

        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Use</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>index.php</code></td>
                    <td>Home page, show all blogs</td>
                </tr>
                <tr>
                    <td><code>register.php</code></td>
                    <td>User registration</td>
                </tr>
                <tr>
                    <td><code>verify.php</code></td>
                    <td>Email verification code page</td>
                </tr>
                <tr>
                    <td><code>login.php</code></td>
                    <td>User login</td>
                </tr>
                <tr>
                    <td><code>logout.php</code></td>
                    <td>User logout</td>
                </tr>
                <tr>
                    <td><code>create_post.php</code></td>
                    <td>Create blog post</td>
                </tr>
                <tr>
                    <td><code>post.php</code></td>
                    <td>Single blog detail page</td>
                </tr>
                <tr>
                    <td><code>actions/like.php</code></td>
                    <td>Like/unlike blog</td>
                </tr>
                <tr>
                    <td><code>actions/comment.php</code></td>
                    <td>Add comment or reply</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="footer">
    Core PHP Blog Project Flow Document
</div>

</body>
</html>