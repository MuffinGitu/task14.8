<?php
if (!$_REQUEST) {
    header('Location: index.php');
}

require_once('functions.php');

session_start();
$user = $_GET ?? [];
$error = $_SESSION['error'] ?? NULL;

if (key_exists('user', $user)) {
    if ($user['user'] === 'logout') {
        session_destroy();
        header('Location: index.php');
    } elseif ($user['user'] === 'new' || $user['user'] === 'reg') {
?>

        <!DOCTYPE html>
        <html lang="ru">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Описание страницы" />
            <meta name="keywords" content="Ключевые слова" />
            <title>SPA - салон "Оздоровяшка"</title>
            <link rel="stylesheet" href="style/style.css" type="text/css">
        </head>

        <body>
            <header>
                <h1>Форма регистрации</h1>
            </header>
            <section>
                <form class="registration" name="reg" method="POST" action="login.php" enctype="application/x-www-form-urlencoded">
                    <fieldset>
                        <legend>Основные данные</legend>
                        <div>
                            <label for="name">Имя:</label>
                            <input id="name" type="text" name="reg[name]" placeholder="Имя" />
                        </div>
                        <div>
                            <label for="login">Логин:</label>
                            <input id="login" type="text" name="reg[login]" placeholder="login" />
                        </div>
                        <div>
                            <label for="password">Пароль:</label>
                            <input id="password" type="password" name="reg[password]" placeholder="password" />
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Дополнительная информация</legend>
                        <div>
                            <label for="date">День рождения:</label>
                            <input id="date" type="date" name="reg[date]" />
                        </div>
                    </fieldset>

                    <?php
                    if ($user['user'] === 'reg' && $error) {
                    ?>

                        <fieldset>
                            <legend>Ошибка</legend>
                            <div><span><?= $error ?></span></div>
                        </fieldset>

                    <?php
                    }
                    ?>

                    <div>
                        <input type="submit" name="reg[submit]" value="Регистрация" />
                        <input type="reset" name="" value="Сбросить" />
                    </div>
                </form>

            </section>
            <footer>

            </footer>

            <!-- <script src="script.js" type="text/javascript"></script> -->
        </body>

        </html>
<?php
    }
} else {

    $user = $_POST ?? [];
    if (key_exists('reg', $user)) {
        $data['name'] = $user['reg']['name'];
        $data['login'] = $user['reg']['login'];
        $data['password'] = encodePpassword($user['reg']['password']);
        $data['date'] = strtotime($user['reg']['date']);
        if (count(array_filter($data)) < 4) {
            $_SESSION['error'] = 'Не все поля формы заполнены';
            header('Location: login.php?user=reg');
        } elseif (existsUser($data['login'])) {
            $_SESSION['error'] = 'Логин уже занят';
            header('Location: login.php?user=reg');
        } else {
            $login = $data['login'];
            $data['time'] = time();
            if (!existsUser($login)) {
                data_write($data);
            }
            $_SESSION['error'] = '';
            $_SESSION['authorization'] = TRUE;
            $_SESSION['name'] = $data['name'];
            $_SESSION['login'] = $data['login'];
            $_SESSION['date'] = $data['date'];
            $_SESSION['time'] = $data['time'];
            header('Location: index.php');
        }
    } elseif (key_exists('auto', $user)) {
        $login = $user['auto']['login'];
        $password = $user['auto']['password'];
        if (!existsUser($login)) {
            $_SESSION['error'] = 'Пользователя с таким логином не существует, пожалуйста зарегистрируйтесь';
            header('Location: index.php');
        } else {
            if (!checkPassword($login, $password)) {
                $_SESSION['error'] = 'Не верный пароль';
                header('Location: index.php');
            } else {
                $data = data_read();
                $index = array_search($login, array_column($data, 'login'));
                $data = $data[$index];

                $_SESSION['error'] = '';
                $_SESSION['authorization'] = TRUE;
                $_SESSION['name'] = $data['name'];
                $_SESSION['login'] = $data['login'];
                $_SESSION['date'] = $data['date'];
                if (time() < ($data['time'] + (60 * 60 * 24))) {
                    $_SESSION['time'] = $data['time'];
                } else {
                    $_SESSION['time'] = NULL;
                }
                header('Location: index.php');
            }
        }
    }
}
?>