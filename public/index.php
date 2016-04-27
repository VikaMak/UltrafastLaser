<?php
/**
 * Отключение вывода ошибок на экран
 */
ini_set('display_errors', 'Off');
ini_set('session.cookie_httponly',1);

/**
 * Начало сессии
 */
session_start();

/**
 * Подключение файла, содержащего классы с различными методами, которые используются в 
 * данном приложении
 */
require_once '../app/init.php';

/**
 * Определение константы, необходимой для распознавания пользователя с правами администратора
 * @var boolean
 */
define("ADMIN", isset($_SESSION['ADMIN']));

/**
 * Создание экземпляра класса App
 * @var object
 */
$app = App::getInstance();