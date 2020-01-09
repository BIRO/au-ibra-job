<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="css/custom.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Almarai&display=swap" rel="stylesheet">
    <title><?php if(isset($page_title)) echo $page_title; ?></title>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-info fixed-top">
    <a class="navbar-brand ml-auto" href="index.php">موقع تسجيل دخول</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">

            <?php if(isset($_SESSION['username']) || isCookieValid($db)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" onclick="return confirm('هل أنت متأكد؟')">تسجيل الخروج</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">الصفحة الشخصية</a>
                </li>

            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">إنشاء حساب</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">تسجيل الدخول</a>
                </li>

            <?php endif ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php">الرئيسية <span class="sr-only">(current)</span></a>
            </li>
        </ul>

    </div>
</nav>

</body>
</html>