<?php

/**
 * Функция для отладки
 *
 * @param mixed $var
 * @return void
 */
function debug(mixed $var, bool $flag = TRUE): void
{
    if ($flag) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    } else {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * Кодировка пароля SHA1
 *
 * @param string $password
 * @return string|null
 */
function encodePpassword(string $password): ?string
{
    if (is_string($password)) {
        return sha1(trim($password));
    } else {
        return NULL;
    }
}

// $user = ['login' => 'admin', 'password' => '123', 'name' => 'Иван'];

// debug($user);

/**
 * Функия сохранения данных пользователя в файл, на праактике будет использоваться BD
 *
 * @param array $data
 * @return boolean запись прошла успешно
 */
function data_write(array $data): bool
{
    // Для упрощения зададим фиксированный файл 
    $file = __DIR__ . '/users.data';
    // Можно использовать serialize
    $data = json_encode($data) . PHP_EOL;
    // Дописываем в конец файла
    return file_put_contents($file, $data, FILE_APPEND);
}

// debug(data_write($user));

/**
 * Функция чтения данных пользователей из файла
 *
 * @return array|null массив прочитанных данных или NULL если данных нет
 */
function data_read(): ?array
{
    // Для упрощения зададим фиксированный файл
    $file = __DIR__ . '/users.data';
    $data = [];
    if (is_readable($file) && filesize($file)) {
        $handle = fopen($file, 'r');
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle);
                if ($line) {
                    $data[] = json_decode($line, TRUE);
                }
            }
            fclose($handle);
        }
        return $data;
    } else {
        return NULL;
    }
}

// debug(data_read());

/**
 * Возвращает список ['login' => 'password']
 *
 * @return array|null или NULL если данных в файле нет
 */
function getUserList(): ?array
{
    $users = data_read();
    if ($users) {
        $list = array_column(data_read(), 'password', 'login');
        return $list;
    } else {
        return NULL;
    }
}

// debug(getUserList());

/**
 * Проверка существования пользователя с заданным login
 *
 * @param [type] $login
 * @return boolean
 */
function existsUser(string $login): bool
{
    $users = getUserList();
    if ($users) {
        $find = (array_key_exists($login, $users));
        return $find;
    } else {
        return FALSE;
    }
}
// debug(existsUser('sandy'));

/**
 * Проверка существования ползователя с login и проверка его пароля
 *
 * @param string $login
 * @param string $password
 * @return boolean
 */
function checkPassword(string $login, string $password): bool
{
    if (existsUser($login)) {
        $user = getUserList();
        if ($user[$login] === encodePpassword($password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

// debug(checkPassword('admin', '123'));
// debug(checkPassword('admin', '333'));
// debug(checkPassword('admin1', '123'));


function getCurrentUser(): ?string
{
    $name = $_SESSION['name'] ?? NULL;
    return $name;
}