<?php
require_once('functions.php');
session_start();
$error = $_SESSION['error'] ?? NULL;
$authorization = $_SESSION['authorization'] ?? NULL;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Описание страницы" />
    <meta name="keywords" content="Ключевые слова" />
    <title>Spa - Салон "Оздоровяшка"</title>
    <link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#000" background="images/4.jpg">
    <header>
        <h1>14.8 Практика</h1>
        <section class="header">
            <div class="about">
                <h2>SPA - салон "Оздоровяшка"</h2>
            </div>
            <?php
            if (!$authorization) {
            ?>
                <div class="form">
                    <form class="authorization" name="auto" method="POST" action="login.php" enctype="application/x-www-form-urlencoded">
                        <fieldset>
                            <legend>Авторизация</legend>
                            <div>
                                <input id="login" type="text" name="auto[login]" placeholder="login" />
                            </div>
                            <div>
                                <input id="password" type="password" name="auto[password]" placeholder="password" />
                            </div>
                            <div>
                                <input type="submit" name="auto[submit]" value="Войти" />
                            </div>
                            <div>
                                <p><a href="login.php?user=new">Регистрация</a></p>

                            </div>
                        </fieldset>
                    <?php
                }
                if ($error) {
                    ?>

                        <fieldset>
                            <legend>Ошибка</legend>
                            <div><span><?= $error ?></span></div>
                        </fieldset>
                    <?php
                }
                if ($authorization) {
                    ?>
                        <div class="welcome">
                            <p>Добро пожаловать! <span><?= getCurrentUser() ?></span></p>
                            <?php
                            if (key_exists('date', $_SESSION)) {
                                $birthday = getdate($_SESSION['date']);
                                $current_birthday = mktime(0, 0, 0, $birthday['mon'], $birthday['mday'], date('Y'));
                                $now = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                                switch ($current_birthday <=> $now) {
                                    case 1:
                                        $days = 'До вашего дня рождения осталось: ' . (($current_birthday - $now) / 86400) . ' дн.';
                                        break;
                                    case 0:
                                        $days = 'Позравляем с днем рождения';
                                        $discount = 0.85;
                                        break;
                                    case -1:
                                        $days = 'До вашего дня рождения осталось: ' . ((mktime(0, 0, 0, $birthday['mon'], $birthday['mday'], date('Y') + 1) - $now) / 86400) . ' дн.';
                                        break;
                                }
                            ?>
                                <p><span><?= $days ?></span></p>
                                <?php
                                $discount = $discount ?? NULL;
                                if ($discount) {
                                ?>
                                    <p><span> Ваша скидка на все услуги <?= (1 - $discount) * 100 . '%' ?></span></p>
                            <?php
                                }
                            }
                            ?>
                            <p><a href="login.php?user=logout">Выход</a></p>
                        </div>
                    <?php
                }
                    ?>
                    </form>
                </div>
        </section>
    </header>

    <section class="services">
        <h3>Наши услуги</h3>

        <div class="services">
            <?php
            if ($authorization && $_SESSION['time']) {
                $time_discount = $_SESSION['time'] + (60 * 60 * 24) - time();
                if ($time_discount > 0) {
                    $h = intdiv($time_discount, 3600);
                    $m = intdiv($time_discount, 60) - $h * 60;
                    $s = $time_discount - $h * 3600 - $m * 60;
            ?>
                    <div class="service">
                        <h4>Массаж для ценителей</h4>
                        <p class="discount">Индивидуальное предложение, действует: <span><?= "{$h}ч {$m}м {$s}с" ?> </span></p>
                        <img src='images/2.jpg' width="650px">
                        <p>Цена: <span>2500 руб.</span></p>
                        <?php
                        $discount = $discount ?? NULL;
                        if ($discount) {
                        ?>
                            <p style="color: red">Цена со скидкой: <span><?= 2500 * $discount ?> руб.</span></p>
                        <?php
                        }
                        ?>
                    </div>
            <?php
                }
            }
            ?>
            <div class="service">
                <h4>Массаж "Standart"</h4>
                <img src='images/1.jpg' width="650px">
                <p>Цена: <span>3000 руб.</span></p>
                <?php
                $discount = $discount ?? NULL;
                if ($discount) {
                ?>
                    <p style="color: red">Цена со скидкой: <span><?= 3000 * $discount ?> руб.</span></p>
                <?php
                }
                ?>
            </div>
            <div class="service">
                <h4>Массаж "V.I.P"</h4>
                <img src='images/3.jpg' width="650px">
                <p>Цена: <span>5000 руб.</span></p>
                <?php
                $discount = $discount ?? NULL;
                if ($discount) {
                ?>
                    <p style="color: red">Цена со скидкой: <span><?= 3000 * $discount ?> руб.</span></p>
                <?php
                }
                ?>
            </div>

        </div>

    </section>
    <footer>

    </footer>

    <!-- <script src="script.js" type="text/javascript"></script> -->
</body>

</html>