# Multi-level-menu
## Multi-level menu in php + MySQL with a menu editor window + showing and hiding submenus on click
## Многоуровневое меню в php + MySQL с окном редактором самого меню + показ и скрытие подменю при клике
## Задание: 
### 1) С помощью PHP и Mysql напишите скрипт построения каталога неограниченной вложенности.
### 2) С помощью CSS выделите вложенные категории серым цветом
### 3) С помощью Javascript сделайте возможность сворачивания какой либо категории.
### Сделал всё в одном файле.
## Как использовать:
### Создать файл с таким же названием (tttr.php) и скопировать в него весь код, затем запустить на локальном вебсервере.
### Откроется окно с редактором меню. Самого меню не будет. Меню можно самому создать при помощи редактора или импортируйте его из файла menu.sql
### При запуске файла (tttr.php) создастся база данных 'tree_work' и таблица 'menu', для импорта таблицы сначала удалите созданную в базе данных таблицу 'menu', (она будет пуста).
### Для создания своего меню в редакторе введите название пункта меню (цифра 1 на скриншоте).
![Screenshot_1](https://user-images.githubusercontent.com/99415686/153711775-acaf84e6-1c45-48cb-89ab-440f1836c853.png)
### Далее нужно ввести номер меню (цифра 2 на скриншоте) в которое добавляется подпункт, для основных, которые будут отображаться сразу, вводим 0.
### Цифры 3,4 на скриншоте показывают или скрывают номера пунктов меню которые нужно вводить в поле 2.
### Цифра 5 применить введённые данные.
### Удалить тоже самое, вводим номер меню и применить.
### Очистить полностью - удалить всё меню.
### После создания меню получится это.
![Screenshot_2](https://user-images.githubusercontent.com/99415686/153716034-dcfad671-24c3-4403-8ceb-8071d68028d4.png)
### Основное меню зеленоватого цвета, подменю в которых есть подменю - будут серыми, а там где нет, при наведении мыши станут ссылками голубого цвета.
