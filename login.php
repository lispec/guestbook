<?php
session_start();

//require_once "loginViaVK.php";

// РЕГИСТРАЦИЯ ЧЕРЕЗ vkontakte (когда вся информация уже есть)

if (!empty($_GET)) {
    if (isset($_GET['email']) && isset($_GET['password'])) {

        // вносим данные о нашем пользователе vk.com в наш файл и перебрасываем его в гостевую

        $emailR = $_GET['email'];
        $passwordR = $_GET['password'];

        $str = file_get_contents("users.csv");
        $arrJ = explode("\n", $str);

        $checkUsers = false;

        foreach ($arrJ as $value) {
            $value = json_decode($value, true);

            if ($value != 0) {
                foreach ($value as $email => $password) {
                    if (($email == $emailR) && ($password == $passwordR)) {
                        $checkUsers = true;
                        break 2;
                    }
                }
            }
        }

        if ($checkUsers){
            $_SESSION['auth'] = true;
            $_SESSION['email'] = $GET['email'];
            header("Location: index.php");
        } elseif (!$checkUsers) {
            $arrInfoR = [$emailR => $passwordR];
            file_put_contents("users.csv", json_encode($arrInfoR) . "\n", FILE_APPEND);

            $_SESSION['auth'] = true;
            $_SESSION['email'] = $GET['email'];
            header("Location: index.php");
        }


       // конец

    }
}

//var_dump($_GET);
//var_dump($_POST);

// ФУНКЦИЯ РЕАЛИЗУЮЩАЯ ВХОД (на странице http://login.php) В ГОСТЕВУЮ КНИГУ(на странице http://index.php)
function EnterInGuestBook()
{
    if (!empty($_POST)) {
        if (isset($_POST['data']) && trim($_POST['data']['email']) && trim($_POST['data']['password'])) {
            $str = file_get_contents("users.csv");
            $arrJ = explode("\n", $str);

            $checkUsers = false;

            foreach ($arrJ as $value) {
                $value = json_decode($value, true);

                if ($value != 0) {
                    foreach ($value as $email => $password) {
                        if (($email == $_POST['data']['email']) && ($password == $_POST['data']['password'])) {
                            $_SESSION['auth'] = true;
                            $_SESSION['email'] = $_POST['data']['email'];

                            header("Location: index.php");

                            break 2;
                        }
                    }
                }
            }

            if (!$checkUsers) {
                echo "<hr/>Такого пользователя не существует. Перейдите на страницу регистрации. ";
                echo "<a href='register.php'>Страница регистрации</a>";
            }

        }
    }
}

?>


<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Guestbook</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
</head>
<body>

<div class="container">

    <?php require_once 'navbar.php' ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Login
        </div>
        <div class="panel-body">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="data[email]"/>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="data[password]"/>
                </div>

                <div class="form-group, text-center">
                    <label>Войти как пользователь соц сетей</label><br/>
                    <a href="https://oauth.vk.com/authorize?client_id=5063180&display=page&redirect_uri=http://localhost/HW13/loginViaVK.php&scope=friends&response_type=code&v=5.44"><img
                            src="vk.jpg" width="170px"></a>
<!--                    Эта ссылка перебрасывае пользователя на файл loginViaVK.php , а он в свою очередь вернет нужную информацию на эту страницу через $_GET   -->
                </div>


                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="form"/>
                </div>
            </form>

            <?php EnterInGuestBook(); ?>

        </div>
    </div>
</div>

</body>
</html>

