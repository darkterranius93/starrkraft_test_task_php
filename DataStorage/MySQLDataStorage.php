<?php
namespace DataStorage;

require_once 'Configs/Configs.php';
require_once 'Entities/Entities.php';

use Configs\MySQLConfig;
use Entities\Transaction;

class MySQLDataStorage implements DataStorageInterface {
    private $connection;

    public function __construct()  {
        $this->connection = mysqli_connect(MySQLConfig::HOST, MySQLConfig::USER, MySQLConfig::PASS, MySQLConfig::DATABASE, MySQLConfig::PORT);
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function MySQLDataStorage() {
        self::__construct();
    }

    /**
     * @inheritDoc
     */
    public function fetchRefundTransactions(): array {
        return $this->fetchTransactions("volume > 0");
    }

    /**
     * @inheritDoc
     */
    public function fetchChargeTransactions(?string $cardNumber = NULL, ?string $service = NULL, ?int $addressId = NULL, ?int $fromTimestamp = NULL, ?int $toTimestamp = NULL): array
    {
        $filterParams = array();
        if (!is_null($cardNumber)) {
            $filterParams []= "card_number = '{$cardNumber}'";
        }
        if (!is_null($service)) {
            $filterParams []= "service = '{$service}'";
        }
        if (!is_null($addressId)) {
            $filterParams []= "address_id = {$addressId}";
        }
        if (!is_null($fromTimestamp)) {
            $date = $this->dateString(fromTimestamp: $fromTimestamp);
            $filterParams []= "date >= '{$date}'";
        }
        if (!is_null($toTimestamp)) {
            $date = $this->dateString(fromTimestamp: $toTimestamp);
            $filterParams []= "date <= '{$date}'";
        }

        $filterParams []= "volume < 0";
        $filterString = !empty($filterParams) ? implode(" AND ", $filterParams) : "";

        return $this->fetchTransactions($filterString);
    }

    /**
     * @inheritDoc
     */
    public function performUpdates(array $transactionsToUpdate, array $transactionsToDelete): void {
        try {
            $this->connection->autocommit(false);
            $this->connection->begin_transaction();

            foreach ($transactionsToUpdate as $transaction) {
                $date = $this->dateString(fromTimestamp: $transaction->date);
                $updateRequest = $this->connection->prepare("UPDATE data SET card_number = '{$transaction->cardNumber}', date = '{$date}', volume = {$transaction->volume}, service = '{$transaction->service}', address_id = {$transaction->addressId} WHERE id = {$transaction->id}");
                $result = $updateRequest->execute();
                if ($result == false) {
                    throw new \Exception("Update failed: " . mysqli_error($this->connection));
                }
                $updateRequest->close();
            }

            foreach ($transactionsToDelete as $transaction) {
                $deleteRequest = $this->connection->prepare("DELETE FROM data WHERE id = {$transaction->id}");
                $result = $deleteRequest->execute();
                if ($result == false) {
                    throw new \Exception("Delete failed: " . mysqli_error($this->connection));
                }
                $deleteRequest->close();
            }
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollback();
            echo $exception->getMessage();
        }
    }

    /**
     * @param string|null $filterString SQL строка с фильтрами
     * @return Transaction[]
     */
    private function fetchTransactions(?string $filterString): array {
        $filterString = is_null($filterString) ? "" : "WHERE ".$filterString;
        $fetchRequest = $this->connection->prepare("SELECT id, card_number, date, volume, service, address_id FROM data {$filterString}");
        $fetchRequest->execute();
        // TODO: Названия полей сущности в константы
        $fetchRequest->bind_result($columns['id'], $columns['card_number'], $columns['date'], $columns['volume'], $columns['service'], $columns['address_id']);

        $transactions = array();
        while ($fetchRequest->fetch()) {
            $row = (object) $columns;
            // TODO: Сделать универсальный маппер из DTO в Entity
            $transaction = new Transaction();
            $transaction->id = $row->id;
            $transaction->cardNumber = $row->card_number;
            $transaction->volume = $row->volume;
            $transaction->service = $row->service;
            $transaction->addressId = $row->address_id;
            $transaction->date = \DateTime::createFromFormat(MySQLConfig::DATETIME_FORMAT, $row->date)->getTimestamp();
            $transactions []= $transaction;
        }

        return $transactions;
    }

    /**
     * Преобразовать Timestamp в строку
     * @param int $fromTimestamp
     * @return string
     */
    private function dateString(int $fromTimestamp): string {
        // TODO: TimeZone check
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($fromTimestamp);
        return $dateTime->format(MySQLConfig::DATETIME_FORMAT);
    }
}