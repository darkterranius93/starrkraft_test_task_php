# Тестовое задание для компании "Старркрафт"
**Описание**

В компании работают водители, которые совершают поездки на корпоративных автомобилях. В течение рабочего времени, они совершают расходы, приобретая услуги (заправка топливом, мойка, шиномонтаж) на различных автозаправках, которые оплачивают топливными картами компании.

В некоторых случаях происходят возвраты средств по приобретенным услугам. Например, водитель попросил оператора заправочной станции заправить автомобиль 50 литрами топлива. При этом оператор совершает списание по топливной карте ДО начала заправки. По окончании заправки выясняется, что в топливный бак вместилось только 48 литров. В этом случае, оператор оформляет возврат средств (2 литра, которые не вместились) на топливную карту.

Данные о приобретенных услугах по топливным картам передаются (в реальном времени) и хранятся в БД.

Таким образом таблица с данным о расходах по топливным картам содержит данные по списанию и возвратам.

**Задание.**

***Требование: должно быть реализовано с использованием PostgreSQL либо MySQL, при желании можно задействовать PHP***

Преобразовать данные таблицы таким образом, чтобы в ней содержались ТОЛЬКО транзакции-расходы. То есть, все транзакции-возвраты должны быть учтены в предшествующих им транзакциях-расходах.

Пример. Дамп данных содержит две следующие транзакции.

| ID  | Номер карты | Дата/время | Объем | Услуга | ID заправочной станции |
| --- | --- | --- | --- | --- | --- |
| 31769 | 257473011 | 2015-10-18 11:10:36 | \-68 | 35.3 | 41  |
| 31768 | 257473011 | 2015-10-18 11:21:15 | 2,44 | ДТ  | 41  |

Где транзакция ID 31769 – расход, а ID 31768 возврат по этой транзакции.

В результате преобразования, транзакция ID 31769 должна иметь объем 65,56, а транзакция ID 31768 должна быть удалена.

К задаче прилагается файл с дампом транзакций.

Задача может быть решена в несколько этапов (не обязательно одной транзакцией). Ключевое требование – скорость исполнения кода.

**Цель выполнения задания**

Исполнитель должен продемонстрировать высокий уровень знаний языка SQL и понимание того, как эффективнее всего может быть решена задача.
