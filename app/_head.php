<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>

<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="/" class="nav-link">Index</a>
                    </li>
                    <li class="nav-item">
                        <a href="/" class="nav-link">Index</a>
                    </li>
                    <li class="nav-item">
                        <a href="/" class="nav-link">Index</a>
                    </li>
                    <li class="nav-item">
                        <a href="/" class="nav-link">Index</a>
                    </li>
                    <li class="nav-item">
                        <a href="/" class="nav-link">Index</a>
                    </li>
                </ul>
                    
            </div>
        </div>



    </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>