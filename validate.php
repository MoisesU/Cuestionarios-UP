<?php
    if(!isset($_POST['inputEmail'])){
        echo "Error 505: Access denied.";
        exit;
    }
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
    $user = $_POST['inputEmail']; 
    $pass = $_POST['inputPassword'];
    define('DB_SERVER','localhost');
    define('DB_NAME','CUESTIONARIOSBD');
    define('DB_USER',$user);
    define('DB_PASS',$pass);
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
    mysqli_select_db($con, DB_NAME);
    
    if (!$con)
    { 
        echo '<script>alert("CONTRASEÑA INCORRECTA")</script> ';
        echo "<script>location.href='index.php'</script>";
    }else{
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        mysqli_close($con);
        echo "<script>location.href='showCuestionarios.php'</script>";
    }
?>