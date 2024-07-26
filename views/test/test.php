<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require_once 'config/load_vendors.php'; ?>
    <style>
        body {
            display: flex;
        }
        #sidebar {
            min-width: 200px;
            max-width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
        }
        #sidebar .nav-link {
            color: white;
        }
        #sidebar .nav-link.active {
            background-color: #495057;
        }
        #content {
            flex-grow: 1;
            padding: 20px;
        }
        .navbar {
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="sidebar" class="d-flex flex-column p-3">
        <a href="#" class="nav-link active">Dashboard</a>
        <a href="#" class="nav-link">Posts</a>
        <a href="#" class="nav-link">Pages</a>
        <a href="#" class="nav-link">Comments</a>
        <a href="#" class="nav-link">Settings</a>
    </div>

    <div id="content">
        <h1>Welcome to the Admin Panel</h1>
        <p>This is the main content area.</p>
    </div>

</body>
</html>