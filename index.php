<?php
require_once 'DataStorage/DataStorage.php';

$dataStorage = new DataStorage\MySQLDataStorage();

$refundTransactions = $dataStorage->fetchRefundTransactions();

foreach ($refundTransactions as $refundTransaction) {
    // Поиск транзакций с списаниями с такими же:
    // номером карты, типом топлива и заправкой за эти же сутки
    // TODO: TimeZone check
    $beginOfDay = strtotime("today", $refundTransaction->date);
    $endOfDay   = strtotime("tomorrow", $refundTransaction->date) - 1;

    $chargeTransactions = $dataStorage->fetchChargeTransactions(
        cardNumber: $refundTransaction->cardNumber,
        service: $refundTransaction->service,
        addressId: $refundTransaction->addressId,
        fromTimestamp: $beginOfDay,
        toTimestamp: $endOfDay
    );

    if (count($chargeTransactions) > 0) {
        $chargeTransaction = $chargeTransactions[0];
        $chargeTransaction->volume = $chargeTransaction->volume + $refundTransaction->volume;
        $dataStorage->performUpdates(transactionsToUpdate: [$chargeTransaction], transactionsToDelete: [$refundTransaction]);
    }
}