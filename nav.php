<?php
    require 'session.php';
    $id = $_SESSION['user'] ?? null;




?>

<style>
    a {
        text-decoration: none;
    }
    body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Remove padding from container-fluid */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

    /* Glass Morphism Overlay Styles */
    .glass-overlay {
        background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
        backdrop-filter: blur(10px); /* Blur effect */
        border: 1px solid rgba(255, 255, 255, 0.2); 
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    .glass-overlay h3,
    .glass-overlay p {
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5); /* Add shadow to text for better contrast */
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>The Muse</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .blog-card{
            min-height: 35dvh;
        }
    </style>
</head>
<body>
    <header class=" bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">The Muse</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/Blog/index.php">Home</a>
                        </li>
                        <?php if($id){ ?>
                            <li class="nav-item">
                            <a class="nav-link" href="/Blog/createBlog.php">Create Blog</a>
                            </li>
                            <div class="d-flex">
                                <div class="dropdown">
                                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-2"></i>My Account
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                        <li><a class="dropdown-item active" href="/Blog/profile.php/?id=<?php echo $id?>"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                                        
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/Blog/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php }else{ ?>
                        <li class="">
                            <a class="nav-link" href="/Blog/login.php">Login</a>
                            </li>
                        <?php } ?>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </header>
