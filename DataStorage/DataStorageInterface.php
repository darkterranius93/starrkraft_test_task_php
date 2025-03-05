<?php
namespace DataStorage;
use Entities;

interface DataStorageInterface {
    /**
     * Получить все транзакции с возвратом средств
     * @return Entities\Transaction[]
     */
    public function fetchRefundTransactions(): array;

    /**
     * Получить транзакции с списаниями по параметрам:
     * @param string|null $cardNumber Номер карты
     * @param string|null $service Тип топлива
     * @param int|null $addressId ID заправки
     * @param int|null $fromTimestamp От даты
     * @param int|null $toTimestamp До даты
     * @return Entities\Transaction[]
     */
    public function fetchChargeTransactions(?string $cardNumber = NULL, ?string $service = NULL, ?int $addressId = NULL, ?int $fromTimestamp = NULL, ?int $toTimestamp = NULL): array;

    /**
     * Выполнение изменений одной транзакцией
     * @param Entities\Transaction[] $transactionsToUpdate
     * @param Entities\Transaction[] $transactionsToDelete
     * @return void
     */
    public function performUpdates(array $transactionsToUpdate, array $transactionsToDelete): void;
}