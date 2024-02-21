<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Просмотр данных регистраций</title>
  <link rel="stylesheet" href="/bootstrap/bootstrap.min.css"/>
  <link rel="stylesheet" href="/bootstrap/bootstrap-responsive.min.css"/>
</head>
<body>
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand">Учебный проект</a>
                <ul class="nav">
                    <li><a href="/">Регистрация</a></li>
                    <li class="active"><a href="#">Просмотр данных регистраций</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Данные в файле</h1>
        </div>
        <ul class="thumbnails" id="regThumbnails"></ul>
    </div>

    <script src="/jquery.js"></script>
    <script src="/showReg/scriptShowReg.js"></script>
</body>