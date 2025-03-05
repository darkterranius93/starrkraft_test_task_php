<?php
namespace Configs;

/**
 * Конфиг подключения к БД
 */
class MySQLConfig {
    /**
     * @const IP
     */
    CONST HOST = '127.0.0.1';

    /**
     * @const Порт
     */
    CONST PORT = 8889;

    /**
     * @const Имя пользователя
     */
    CONST USER = 'root';

    /**
     * @const Пароль пользователя
     */
    CONST PASS = 'root';

    /**
     * @const База данных
     */
    CONST DATABASE = 'starkraft';

    /**
     * @const Формат хранения даты в БД для 'DateTime::createFromFormat'
     */
    CONST DATETIME_FORMAT = 'Y-m-d H:i:s';
}