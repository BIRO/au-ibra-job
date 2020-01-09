<?php

include_once 'resource/Database.php';
include_once 'resource/utilities.php';


if(isset($_POST['signupBtn'])){

    $form_errors = array();

    //الحقول التي ينبغي التحقق منها
    $required_fields = array('email', 'username', 'password');

    //استدعاء التابع check_empty_fields من أجل التحقق من وجود
    //حقول فارغة و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    //الحقول التي ينبغي التحقق من طولها
    $fields_to_check_length = array('username' => 4, 'password' => 6);

    //استدعاء التابع check_min_length من أجل التحقق من طول الحقول
    //و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //استدعاء التابع check_email من أجل التحقق من البريد الإلكتروني
    //و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_email($_POST));

    //جمع المعطيات و تخزينها في متغيرات
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(checkDuplicateEntry("users", "email", $email, $db)) {
        $result = showMessage("البريد الإلكتروني موجود مسبقاً");
    }
    else if(checkDuplicateEntry("users", "username", $username, $db)) {
        $result = showMessage("اسم المستخدم موجود مسبقاً");
    }

    //التحقق من أن مصفوفة الأخطاء فارغة و البدء بإدخال المعطيات
    else if(empty($form_errors)){
        //تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try{
            //إنشاء تعليمة sql من أجل الإدخال إلى قاعدة البيانات
            $sqlInsert = "INSERT INTO users (username, email, password, join_date)
              VALUES (:username, :email, :password, now())";

            //استخدام PDO prepare من أجل تحضير معطيات التعليمة
            $statement = $db->prepare($sqlInsert);

            ////تنفيذ التعليمة
            $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));

            //التحقق من أن الصف تم إنشاؤه
            if($statement->rowCount() == 1){
                $result = showMessage("تم التسجيل بنجاح", "Pass");
            }
        }catch (PDOException $ex){
            $result = showMessage("حصل خطأ: " .$ex->getMessage());
        }
    }
    else{
        if(count($form_errors) == 1){
            $result = showMessage("هناك خطأ في المدخلات");
        }else{
            $result = showMessage("هناك عدة أخطاء في المدخلات");
        }
    }

}

?>
<?php
$page_title = "User Authentication - Register Page";
include_once 'parts/header.php';
?>

<div id="bform" class="container">
    <section class="secc ml-auto">
        <h2>إنشاء حساب</h2><hr>

        <?php if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="emailField">البريد الإلكتروني</label>
                <input type="email" class="custom-align-input form-control" name="email" id="emailField" placeholder="email">
            </div>
            <div class="form-group">
                <label for="usernameField">اسم المستخدم</label>
                <input type="text" class="custom-align-input form-control" name="username" id="usernameField" placeholder="username">
            </div>
            <div class="form-group">
                <label for="passwordField">كلمة السر</label>
                <input type="password" class="custom-align-input form-control" name="password" id="passwordField" placeholder="password">
            </div>
            <div class="row">
                <div class="col col-lg-6">
                    <a class="custom-size-button btn btn-outline-info" href="index.php">عودة</a>
                </div>
                <div class="col col-lg-6">
                    <button type="submit" name="signupBtn" class="custom-size-button btn btn-info">تسجيل</button>
                </div>
            </div>

        </form>
    </section>
<!--    <p><a href="index.php">Back</a> </p>-->
</div>

<?php include_once 'parts/footer.php'; ?>
</body>
</html>