<?php
// ФУНКЦИЯ РЕАЛИЗУЮЩАЯ ВХОД ЧЕРЕЗ ВКОНТАКТЕ

// 1. Открытие диалога авторизации OAuth (Первый запрос который реализуеться после нажатия ссылки на регистрацию через ВК)
//  https://oauth.vk.com/authorize?client_id=5063180&display=page&redirect_uri=http://localhost/HW13/testVK.php&scope=friends&response_type=code&v=5.44
// 2. Разрешение прав доступа
// 3. Получение code
// 4. Получение access_token

//        echo "<b>3. Выводим содержание массива \$_GET (нас интересует \$_GET['code']):</b>";
//        var_dump($_GET);

if (!empty($_GET)) {
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $url = "https://oauth.vk.com/access_token?client_id=5063180&client_secret=rVVUcpulg7sOi3V6wsy9&redirect_uri=http://localhost/HW13/loginViaVK.php&code=$code";

//          echo "<b>4 Выводим содержание ссылки \$url для получения access_token:</b>";
//          формируем первый запрос и его обработку
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $str = curl_exec($ch);

//          echo "<br/><b>4.1 Выводим access_token:</b>";
//          echo $str;
        $data = json_decode($str, true);

//          echo "<br/><b>4.2 Выводим data = json_decode c access_token:</b><br/>";
//          var_dump($data);
        $userId = $data['user_id'];
        $token = $data['access_token'];

//  формируем второй запрос и его обработку
        $url = "https://api.vk.com/method/users.get?user_id=$userId&v=5.44&access_token=$token";
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);

        echo "<br/><b>4.3 Выводим информацию о пользователе:</b><br/>";
        $userInfo = json_decode($data, true);
        var_dump($userInfo);

// если почта получена, то перебрасываем пользователя в гостевую, и сохраняем его почту и пароль 123 в файл
        $userVkID = $userInfo['response'][0]['id'];
        $userVkFname = $userInfo['response'][0]['first_name'];
        $userVkLname = $userInfo['response'][0]['last_name'];

        // сами формируем почту
        $userVkEmail = $userVkID . '@mail.com';
        $userVkPassword = $userVkID;

        // перебрасываем на страницу логирования
        header("Location: login.php?email=$userVkEmail&password=$userVkPassword");
    }
}
