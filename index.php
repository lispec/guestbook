<?php
session_start();

//var_dump($_SESSION);

if ($_SESSION['auth'] == false) {
    header("Location: login.php");
} else {


// -------------- ФУНКЦИЯ ДЛЯ ВЫВОДА ПОСТОВ ------------------

    function display()
    {
        // ** считываем номер страницы из $_GET
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // ** вводим счетчик строки
        $count = 0;

        $data = fopen("guestbook.csv", "r");     // открываем файл для чтения
        while (!feof($data)) {              // читаем его пока не окажемся в конце файла
            $line = fgets($data);         // * получаем строку из guestbook.csv в json

            $count++;                   // **

            $note = json_decode($line, true);

            if ((empty($note)) || ($count == ($page * 2 + 1))) {             // ** завершаем цикл если считаная строка оказываеться пустой
                break;
            }
            ?>

            <?php
            // ** - выводим только нужные 2 строки
            if (($count == $page * 2) || ($count == $page * 2 - 1)) {
                ?>

                <div class="post-block">
                    <b><?php echo $note['email']; ?></b> <i><?php echo $note['name']; ?></i> <i
                        style="text-decoration: underline;"><?php echo $note['date']; ?></i>

                    <p>
                        <?php echo $note['text']; ?>
                    </p>
                </div>

            <?php } ?>

            <?php
        }
        fclose($data);                      // закрываем файл
    }

// -------------- ПАГИНАЦИЯ ------------------

// * - создаем функцию которая считает колличсетво наших постов (через счетчик считаем колличество строк в файле)
    function PostCount()
    {
        $count = 0;
        $data = fopen('guestbook.csv', 'r');

        while (!feof($data)) {
            fgets($data);
            $count++;

        }
        return $count;
    }

// * - создаем функцию для пагинации
    function pagination()
    {
        $perPage = 2;
        $pageCount = ceil(PostCount() / $perPage);

        for ($i = 1; $i <= $pageCount; $i++) {
            echo "<li><a href=\"index.php?page=$i\">$i</a></li>";
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
            Гостевая книга
        </div>
        <div class="panel-body">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="data[email]" class="form-control" <?php
                    //                    if (isset($_COOKIE['email'])) {
                    //                        echo "value=" . $_COOKIE['email'];
                    //                    }
                    ?> />
                    <!-- новый вид задания параметра name -->
                </div>
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="data[name]" class="form-control"/>
                    <!-- новый вид задания параметра name -->
                </div>
                <div class="form-group">
                    <label>Текст</label>
                    <textarea name="data[text]" class="form-control" required></textarea>
                    <!-- новый вид задания параметра name, задали required - как поле для обязательного воода -->
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="form"/>
                    <!-- задали name для submit  - что позволяет узнать какая форма засабмитилась -->
                </div>
            </form>

            <hr/>

            <!-- вставляем наш php -->
            <?php display(); ?>

            <!-- * - навигационное меню и вставляем наш php -->
            <nav>
                <ul class="pagination">

                    <?php
                    pagination();
                    ?>

                </ul>
            </nav>


        </div>
    </div>
</div>

</body>
</html>
