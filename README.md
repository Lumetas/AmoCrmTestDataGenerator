# AmoCrmTestDataGenerator
Генератор тестовых данных для amoCRM
## Использование:
```
php main.php <type> <count> <filename.csv> --data=<iterable|random|humanized> --fill=<full|base|minimum>
```

## Параметры:
- **type** : сущность
    - companies
    - contacts
    - leads
    - customers
- **count** : количество записей 
- **filename.csv** : имя выходного файла
- **--data** : тип данных:
    - iterable: последовательные данные (Иван 1, Иван 2...)
    - random: полностью случайные данные
    - humanized: правдоподобные человеческие данные
- **--fill** : уровень заполнения полей:
    - full: все возможные поля
    - base: основные поля (рекомендуется)
    - minimum: минимальный набор полей

## Примечания:
- При создании сделок с высоким уровнем заполнения так же будут сгенерированы базовые карточки для Контактов и компаний
