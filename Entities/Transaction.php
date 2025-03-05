<?php

namespace Entities {
    class Transaction
    {
        /**
         * ID
         * @var int
         */
        public int $id;
        /**
         * Номер карты
         * @var string|null
         */
        public ?string $cardNumber;
        /**
         * Дата проведения
         * @var int
         */
        public int $date;
        /**
         * Сумма
         * @var float
         */
        public float $volume;
        /**
         * Тип бензина
         * @var string
         */
        public string $service;
        /**
         * ID заправки
         * @var int|null
         */
        public ?int $addressId;
    }
}
