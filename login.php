<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

if(isset($_POST['loginBtn'])){

    $form_errors = array();


    $required_fields = array('username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    if(empty($form_errors)){


        $user = $_POST['username'];
        $password = $_POST['password'];

        isset($_POST['remember']) ? $remember = $_POST['remember'] : $remember = "";



        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));

        while($row = $statement->fetch()){
            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];

            if(password_verify($password, $hashed_password)){
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                if ($remember === 'yes') {
                    rememberMe($id);
                }
                header("location: index.php");
            }else{
                $result = showMessage("اسم مستخدم أو كلمة مرور خاطئة");
            }
        }

    }else{
        if(count($form_errors) == 1){
            $result = showMessage("هناك خطأ في المدخلات");
        }else{
            $result = showMessage("هناك عدة أخطاء في المدخلات");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>

<body>

<?php
$page_title = "User Authentication - Login Page";
include_once 'parts/header.php';
?>

<div id="bform" class="container">
    <section class="secc ml-auto">
        <h2>تسجيل الدخول</h2><hr>

        <?php if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="usernameField">اسم المستخدم</label>
                <input type="text" class="custom-align-input form-control" name="username" id="usernameField" placeholder="username">
            </div>
            <div class="form-group">
                <label for="passwordField">كلمة السر</label>
                <input type="password" class="custom-align-input form-control" name="password" id="passwordField" placeholder="password">
            </div>
            <div class="checkbox">
                <label>
                    <input name="remember" value="yes" type="checkbox"> تذكرني
                </label>
            </div>
            <hr>
            <div class="row">
                <div class="col col-lg-6">
                <a class="btn btn-outline-info" href="forgot_password.php">هل نسيت كلمة السر؟</a>
                </div>
                <div class="col col-lg-6">
                <button type="submit" name="loginBtn" class="custom-size-button btn btn-info">تسجيل الدخول</button>
                </div>
            </div>
        </form>
    </section>

</div>

<?php include_once 'parts/footer.php'; ?>
</body>
</html>