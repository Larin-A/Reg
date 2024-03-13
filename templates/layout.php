<?php
/**
 * @var $title string
 * @var $title string
 * @var $title string
 */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>
        <?= $title; ?>
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="/bootstrap/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href= <?= $style; ?> />
</head>
<body>
    <script src="/jquery.js"></script>
    
    <header>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand">Учебный проект</a>
                    <ul class="nav">
                        <li id="nav-reg"><a href="/">Регистрация</a></li>
                        <li id="nav-showReg"><a href="/showReg/showReg.php">Просмотр данных регистраций</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="main-content">
        <main class="content">
            <?= $content; ?>
        </main>
    </div>
</body>

</html>