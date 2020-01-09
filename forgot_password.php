<?php

include_once 'resource/Database.php';
include_once 'resource/utilities.php';

//التحقق من الإدخالات في حال تم الضغط على زر التغيير
if(isset($_POST['passwordResetBtn'])){
    //مصفوفة لحفظ الأخطاء الممكنة
    $form_errors = array();

    //الحقول التي ينبغي التحقق منها
    $required_fields = array('email', 'new_password', 'confirm_password');

    //استدعاء التابع check_empty_fields من أجل التحقق من وجود
    //حقول فارغة و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    //الحقول التي ينبغي التحقق من طولها
    $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

    //استدعاء التابع check_min_length من أجل التحقق من طول الحقول
    //و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //استدعاء التابع check_email من أجل التحقق من البريد الإلكتروني
    //    //و إضافة الأخطاء إلى المصفوفة
    $form_errors = array_merge($form_errors, check_email($_POST));

    //التحقق من أن مصفوفة الأخطاء فارغة و البدء بإدخال المعطيات
    if(empty($form_errors)){
        //جمع المعطيات و تخزينها في متغيرات
        $email = $_POST['email'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        //التحقق من أن الكلمتين المدخلتين متطابقتان
        if($password1 != $password2){
            $result = showMessage("لا يوجد تطابق بين الكلمتين");
        }else{
            try{
                //إنشاء تعليمة sql من أجل البحث عن البريد الإلكتروني في قاعدة البيانات
                $sqlQuery = "SELECT email FROM users WHERE email =:email";

                //استخدام PDO prepare من أجل تحضير معطيات التعليمة
                $statement = $db->prepare($sqlQuery);

                //تنفيذ التعليمة
                $statement->execute(array(':email' => $email));

                //إذا كان البريد موجوداً
                if($statement->rowCount() == 1){
                    //تشفير كلمة المرور
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                    //تعليمة sql من أجل تغيير كلمة المرور
                    $sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

                    //استخدام PDO prepare من أجل تحضير معطيات التعليمة
                    $statement = $db->prepare($sqlUpdate);

                    //تنفيذ التعليمة
                    $statement->execute(array(':password' => $hashed_password, ':email' => $email));
                    //إظهار النتيجة
                    $result = showMessage("تمت إعادة تعيين كلمة المرور بنجاح", "Pass");
                }
                else{
                    $result = showMessage("البريد الإلكتروني غير موجود");
                }
            }catch (PDOException $ex){
                $result = showMessage("حصل خطأ: " .$ex->getMessage());
            }
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
$page_title = "User Authentication - Password reset Page";
include_once 'parts/header.php';
?>

<div id="bform" class="container">
    <section class="secc ml-auto">
        <h2>تغيير كلمة السر</h2><hr>

        <?php if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="emailField">البريد الإلكتروني</label>
                <input type="email" class="custom-align-input form-control" name="email" id="emailField" placeholder="email">
            </div>

            <div class="form-group">
                <label for="passwordField">كلمة السر الجديدة</label>
                <input type="password" class="custom-align-input form-control" name="new_password" id="passwordField" placeholder="new password">
            </div>
            <div class="form-group">
                <label for="passwordField">تأكيد كلمة السر</label>
                <input type="password" class="custom-align-input form-control" name="confirm_password" id="passwordField" placeholder="confirm password">
            </div>

            <button type="submit" name="passwordResetBtn" class="custom-size-button btn btn-info">تغيير</button>
        </form>
    </section>
<!--    <p><a href="index.php">Back</a> </p>-->
</div>

<?php include_once 'parts/footer.php'; ?>
</body>
</html>
