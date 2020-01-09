<?php

function check_empty_fields($required_fields_array){

    $form_errors = array();

    //انتقل عبر العناصر المطلوبة و أضف خطأ عند الحقل الفارغ
    foreach($required_fields_array as $name_of_field){
        if(!isset($_POST[$name_of_field]) || $_POST[$name_of_field] == NULL){
            $form_errors[] = " حقل مطلوب " ;
        }
    }

    return $form_errors;
}


function check_min_length($fields_to_check_length){

    $form_errors = array();

    foreach($fields_to_check_length as $name_of_field => $minimum_length_required){
        if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required && $_POST[$name_of_field] != NULL){

            $form_errors[] = " الحقل قصير جداً، يجب أن يحتوي على أكثر من $minimum_length_required حروف ";
        }
    }
    return $form_errors;
}


function check_email($data){

    $form_errors = array();
    $key = 'email';
    //التحقق من أن البريد موجود
    if(array_key_exists($key, $data)){

        //تحقق من أن حقل البريد الإلكتروني فيه قيمة
        if($_POST[$key] != null){

            // إزالة كل المحارف الغير مناسبة من البريد الإلكتروني
            $key = filter_var($key, FILTER_SANITIZE_EMAIL);

            //التحقق من أن البريد الإلكتروني صالح
            if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                $form_errors[] = " ليس بريداً حقيقياً".$key;
            }
        }
    }
    return $form_errors;
}


function show_errors($form_errors_array){
    $errors = "<p><ul class='alert alert-danger'>";

    //التنقل عبر عناصر مصفوفة الأخطاء و إظهارها في سلسلة
    foreach($form_errors_array as $the_error){
        $errors .= "<li> {$the_error} </li>";
    }
    $errors .= "</ul></p>";
    return $errors;
}

//تابع لإظهار الرسائل بطريقة جيدة
function showMessage($message, $passFail = "Fail") {
    if($passFail === "Pass") {
        $data = "<p class='alert alert-success' '> {$message}</p>";
    } else {
        $data = "<p class='alert alert-danger'> {$message}</p>";
    }
    return $data;
}

//التحقق من وجود قيمة مكررة في قاعدة البيانات مع إمكانية تحديد الجدول و العمود و القيمة
function checkDuplicateEntry($table, $column_name, $value, $db) {
    try {
        $sqlQuery = "SELECT * FROM " .$table. " WHERE " .$column_name. "=:$column_name";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(":$column_name" => $value));

        if($row = $statement->fetch()) {
            return true;
        }
        return false;
    } catch (PDOException $ex) {

    }

}

function rememberMe ($user_id) {
    //تشفير $user_id في cookie
    $encryptData = base64_encode("aghgTHaiaRTgUlbdhagG{$user_id}");
    //تعيين cookie لها صلاحية لمدة 100 يوم
    setcookie("rememberUserCookie", $encryptData, time()+60*60*24*100, "/");
}

function isCookieValid($db) {
    $isValid = false;
    //التحقق من أن ال cookie لها قيمة و بالتالي فإن المستخدم قام بتحديد "تذكرني"
    if(isset($_COOKIE['rememberUserCookie'])) {
        //فك تشفير ال userid
        $decryptData = base64_decode($_COOKIE['rememberUserCookie']);
        //تحويل المعطيات المشفرة إلى مصفوفة
        $user_id = explode("aghgTHaiaRTgUlbdhagG", $decryptData);
        //الحصول على userid من العنصر الثاني للمصفوفة
        $userID = $user_id[1];

        //التحقق من أن ال userid موجودة بالفعل في قاعدة البيانات
        $sqlQuery = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':id' => $userID));

        //في حال كانت الهوية موجودة خزن id و usernam
        if ($row = $statement->fetch()) {
            $id = $row['id'];
            $username = $row['username'];

            //ابدأ جلسة بواسطة الهوية الجديدة
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $isValid = true;

        } else {
            // إن لم تكن الهوية موجودة قم بتسجيل الخروج
            $isValid = false;
            $this->signout();
        }
    }
    return $isValid;
}

function signout() {
    //إلغاء الجلسة
    unset($_SESSION['username']);
    unset($_SESSION['id']);

    //حذف ال cookie
    if(isset($_COOKIE['rememberUserCookie'])) {
        unset($_COOKIE['rememberUserCookie']);
        setcookie('rememberUserCookie', null, -1, '/');
    }
    session_destroy();
    session_regenerate_id(true);
    header("location: index.php");

}