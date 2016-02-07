<?php
session_start();



// ФУНКЦИЯ РЕАЛИЗУЮЩАЯ РЕГИСТРАЦИЮ В ГОСТЕВОЙ КНИГЕ (на странице http://register.php). Это позволит потом залогиниться в гостевой книге (на странице http://login.php)
function register()
{
    if (!empty($_POST)) {
        if (isset($_POST['dataRegister']) && trim($_POST['dataRegister']['email']) && trim($_POST['dataRegister']['password'])) {

            $arrR = $_POST['dataRegister'];
            $emailR = $arrR['email'];
            $passwordR = $arrR['password'];

            $str = file_get_contents("users.csv");
            $arrJ = explode("\n", $str);

            $checkUsers = false;

            foreach ($arrJ as $value) {
                $value = json_decode($value, true);

                if ($value != 0) {
                    foreach ($value as $email => $password) {
                        if (($email == $emailR) && ($password == $passwordR)) {
                            $checkUsers = true;
                            echo "<hr/>Такой пользователь уже существует. Перейдите на страницу входа.";
                            echo "<a href='login.php'>Страница входа</a>";

                            break 2;
                        }
                    }
                }
            }

            if (!$checkUsers) {
                $arrInfoR = [$emailR => $passwordR];
                file_put_contents("users.csv", json_encode($arrInfoR) . "\n", FILE_APPEND);
            }
        } else {echo "Заполните форму регистрации!!!";}
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
            Register
        </div>
        <div class="panel-body">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="dataRegister[email]"/>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="dataRegister[password]"/>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="formRegister"/>
                </div>
            </form>

            <?php
            // вставляем нашу функцию
            register();
            ?>

        </div>


    </div>
</div>

</body>
</html>