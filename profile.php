<?php
$page_title = "User Authentication - Prfile";
include_once 'parts/header.php';
?>


<div role="main" class="container">

    <div class="base">
        <?php if(!isset($_SESSION['username'])): ?>
            <h1>نظام تسجيل دخول</h1>
        <?php else: ?>
            <h1>الصفحة الشخصية</h1>
        <?php endif ?>
        <hr>
        
        <?php if(!isset($_SESSION['username'])): ?>
            <P class="lead">أنت حالياً لم تسجل دخول إلى الموقع <a class="btn btn-info custom-signin-btn" href="login.php">تسجيل الدخول</a> لست عضواً بعد؟ <a class="btn btn-outline-info custom-signin-btn" href="signup.php">إنشاء حساب</a> في الموقع</P>
        <?php else: ?>
            <p class="lead">أهلا وسهلا بك <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?><hr> <a class="btn btn-outline-info" onclick="return confirm('هل أنت متأكد؟')" href="logout.php" >تسجيل الخروج</a> </p>
        <?php endif ?>

    </div>
</div>

<?php include_once 'parts/footer.php'; ?>
</body>
</html>