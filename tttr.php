<?php
$db = [
    'host' => 'localhost',
    'dbname' => 'tree_work',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8'
];

class SQL
{
    //PDO объект
    private $pdo;
    //Соединение с базой данных
    private $isConnected;
    //PDO подготовленный объект
    //private $statement;
    //Настройки базы данных
    private $settings = [];
    //Параметры SQL подключения
    private $parameters = [];

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->parameters = $parameters = [];
        $this->connect();
        return $this->settings;
    }

    private function connect()
    {
        $base = $this->settings['dbname'];
        $con = 'mysql:host=' . $this->settings['host'] . ';dbname=' . $this->settings['dbname'];
        'charset=' . $this->settings['charset'];

        try {
            $this->pdo = new \PDO($con, $this->settings['user'], $this->settings['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->isConnected = true;

        } catch (\PDOException $e) {
            if(!$this->pdo){
                $con = 'mysql:host=' . $this->settings['host'];

                try {
                    $this->pdo = new \PDO($con, $this->settings['user'], $this->settings['password']);
                    $query = "CREATE DATABASE $base DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
                    $q = $this->pdo->prepare($query);
                    $q->execute();
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                    $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                    $this->isConnected = true;
                } catch (\PDOException $e) {
                    exit($e->getMessage());
                }
            }
            exit($e->getMessage());
        }

    }

    public function CheckConnect()
    {
        if (!empty($this->pdo)) {
            $this->isConnected = true;
            print_r('Хорошоооо!!! Мы пока пристыкованы!!!(Пока!!!)');
        } else {
            $this->isConnected = false;
            print_r('Чё никак? Ищи ключик!!!');
        }
    }

    public function SelectAll($table){
        $query1 = "CREATE TABLE IF NOT EXISTS `$table` (`id` int(11) NOT NULL AUTO_INCREMENT, `titlle` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `parent_id` int(11) NOT NULL, PRIMARY KEY (  `id` ))";
        $q = $this->pdo->prepare($query1);
        $q->execute();
        $query = "SELECT * FROM $table";
        $q = $this->pdo->prepare($query);
        $q->execute();



        if($q->errorCode() != \PDO::ERR_NONE){

            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function Select_one($table, $object, $parameters = []){

        $query = "SELECT * FROM $table WHERE $object";

        $q = $this->pdo->prepare($query);

        $q->execute($parameters);
        return $q->fetchAll(\PDO::FETCH_ASSOC);

        if($q->errorCode() != \PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
        return $q->fetchALL();
    }

    public function Select_one_order_by($object, $table, $find, $parameters = []){

        $query = "SELECT $object FROM $table WHERE $find";
        $q = $this->pdo->prepare($query);

        $q->execute($parameters);
        return $q->fetchAll(\PDO::FETCH_ASSOC);

        if($q->errorCode() != \PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
        return $q->fetchALL();
    }

    public function Insert($table , $object){
        $columns = array();

        foreach($object as $key => $value){
            $columns[] = $key;
            $masks[] = ":$key";

            if($value === null){
                $object[$key] = 'NULL';
            }
        }
        $columns_ser = implode(',', $columns);
        $masks_ser = implode(',', $masks);

        $query = "INSERT INTO $table ($columns_ser) VALUE ($masks_ser)";

        $q = $this->pdo->prepare($query);
        $q->execute($object);

        if($q->errorCode() != \PDO::ERR_NONE){
            $info = $q->errorInfo();
            echo($info[2]);
        }
        return $this->pdo->lastInsertId();
    }

    public function Disconnect(){
        $this->pdo = NULL;
        $this->isConnected = False;
    }

    public function Delete($table, $where, $parameters = []){

        $query = "DELETE FROM $table WHERE $where";
        $q = $this->pdo->prepare($query);
        $q->execute($parameters);

        if($q->errorCode() != \PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();

    }

    public function Clear_All($table){
        $query = "TRUNCATE TABLE $table";
        $q = $this->pdo->prepare($query);
        $q->execute();

        if($q->errorCode() != \PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();

    }

}

class Tree
{

    private $sql;
    private $_category_arr = array();
    private $data;

    public function __construct($sql, $data, $table)
    {
        $this->sql = $sql;
        //В переменную $_category_arr записываем все категории
        $this->_category_arr = $this->_getCategory($table);
        $this->data = $data;
    }

    private function _getCategory($table)
    {
        $result = $this->sql->SelectAll($table);
        //Перелапачиваем массив (делаем из одномерного массива - двумерный, в котором
        //первый ключ - parent_id)
        $return = array();
        foreach ($result as $value) { //Обходим массив
            $return[$value['parent_id']][] = $value;
        }
        return $return;
    }

    public function outTree($parent_id, $level) {
        if (isset($this->_category_arr[$parent_id])) { //Если категория с таким parent_id существует

            echo '<ul class="menu" id="tree_ul">';
            foreach ($this->_category_arr[$parent_id] as $value) { //Обходим ее
                /**
                 * Выводим категорию
                 *  $level * 25 - отступ, $level - хранит текущий уровень вложености (0,1,2..)
                 */

                //рекурсия - проверяем нет ли дочерних категорий

                echo'<li>';
                echo'<div id="whithout_number" data-modal-window="" class="whithout_number">' . $value['id'] . ":&nbsp";
                $page = $value['id'].'.php';
                echo '<p class="sub_list">' . $value["titlle"] . '</p>';
                echo'<a href='.$page.'>'.$value["titlle"].'</a>';
                echo '</div>';
                echo '</li>';



                $level++; //Увеличиваем уровень вложености
                //Рекурсивно вызываем этот же метод, но с новым $parent_id и $level
                $this->outTree($value['id'], $level);
                $level--; //Уменьшаем уровень вложености
            }
            echo '</ul>';
        }
    }

    public function outTree_bez($parent_id, $level) {
        if (isset($this->_category_arr[$parent_id])) {

            echo '<ul class="menu" id="tree_ul">';
            foreach ($this->_category_arr[$parent_id] as $value) {

                echo'<li>';

                $page = $value['id'].'.php';
                echo '<p class="sub_list">' . $value["titlle"] . '</p>';
                echo '<a href=' . $page . '>' . $value["titlle"] . '</a>';
                echo '</li>';
                $level++; //Увеличиваем уровень вложености
                //Рекурсивно вызываем этот же метод, но с новым $parent_id и $level
                $this->outTree_bez($value['id'], $level);
                $level--; //Уменьшаем уровень вложености
            }
            echo '</ul>';
        }
    }

    public function InsertToDatabase(){

        $string_bd = 'menu';
        $tobd = array();
        $tobd['titlle'] = htmlspecialchars($this->data['titlle']);
        $tobd['parent_id'] = htmlspecialchars($this->data['parent_id']);

        $this->sql->Insert($string_bd, $tobd);
        header('Location: tttr.php');
        exit();
    }

    public function delete_point($id){

        $table = 'menu';
        $id_field = "`id` = :id";
        $parameters = [
            ':id'=>$id
        ];
        $this->sql->Delete($table, $id_field, $parameters);
    }


}
$data = $_POST;
$table = 'menu';
$SQL = new SQL($db);
$trees = new Tree($SQL, $data, $table);




if (isset($data['insert'])){
    if (!empty($data['titlle']) && isset($data['parent_id'])) {
        $trees->InsertToDatabase();
    } else {
        header('Location: tttr.php');
        exit();
    }
}

if (isset($data['delete_menu'])){
    if (!empty($data['id_del'])) {
        $trees->delete_point($data['id_del']);
    } else {
        header('Location: tttr.php');
        exit();
    }
}

if (isset($data['clear_menu'])){
    $SQL->Clear_All($table);
}

$SQL->Disconnect();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"/>
</head>
<body>

<script src="jquery-3.5.1.min.js"></script>
<style type="text/css">

    .menu li a{
        color: red;
        border: 0 solid red;
        padding: 4% 4% 4% 4%;
    }

    li{
        margin: 1% 1% 1% 1%;
        border: 0 solid black;
        width: 20%;
    }

    .menu ul li span{
        background-color: grey;
    }

    .menu {
        list-style-type: none;
        color: blue;
        margin: 1% 1% 1% 1%;
        border: 0 solid black;
    }

    .menu a:hover{
        color: darkmagenta;
    }

    .menu span {
        border: 1px solid black;
        width: 100%;
        height: 100%;
        border-radius: 2%;
        box-shadow: 0 0 5px 4px #1B1818;
        background-color: #21D0AA;

    }

    .form-reg{
        text-align: center;
        position: absolute;
        right: 5%;
        top: 5%;
        margin-top: 3%;
        border: 0 solid blue;
        width: 40%;
        height: max-content;
    }

    .form-reg1{
        padding: 4%;
        border-radius: 2%;
        box-shadow: 0 0 10px 8px #1B1818;
        border: 1px solid #0C3200;
        background-color: rgba(255,255,255, 0.8);
        margin-bottom: 3%;
        color: black;
    }

    .form-reg1 a{
        text-decoration: none;
        color: black;
    }

    .form-reg1 a:hover{
        text-decoration: none;
        color: #00FF1A;
    }

    .form-login{
        margin: 1% 0 4% 0;
        height: max-content;
        width: 100%;
        font-size: 14px;
        border: unset;
        outline: none;
        border-bottom: 1px solid black;
        background-color: rgba(255,255,255, 0);
    }

    label {
        font-size: 18px;
    }

    textarea{
        overflow: hidden;
    }

    #but{
        margin: 2% 0 0 0;
    }

    .whithout_number{
        position: relative;
        border: 0 solid blue;
        width: 100%;
        text-align: left;
        display: flex;
    }

    .menu p{
        position: relative;
        border: 0 solid yellow;
        text-align: center;
        width: 100%;
        margin: 4% 4% 4% 4%;
        right: 4%;
    }

    .menu a{
        position: relative;
        color: black;
        border: 1px solid black;
        width: 92%;
        text-align: center;
        border-radius: 2%;
        box-shadow: 0 0 5px 4px #1B1818;
        background-color: #66C0D0;
        text-decoration: none;
    }

    .show_number{
        height: max-content;
        width: 50%;
        box-shadow: 0 0 10px 8px #1B1818;
        margin: 2% 0 2% 0;
    }

    #clear_menu{
        margin-top: 3%;
    }

    .open{
        display: block;
    }

    .close{
        display: none;
    }

</style>
<div class="number">
    <?
    if (isset($data['show_number1'])){
        $trees->outTree(0, 0);
    } elseif (isset($data['show_number2'])){
        $trees->outTree_bez(0, 0);
    } else {
        $trees->outTree_bez(0, 0);
    }

    ?>
</div>
<form class="form-reg" action="#" method="post">
    <div class="form-reg1">
        <p>Правка меню</p>
        <div class="i">
            <label for="login">Добавить пункт меню</label>
            <textarea type="textarea" class="form-login" name="titlle"
                      id="" value="" placeholder="Название пункта меню"></textarea>
        </div>
        <div class="i">
            <label for="pass">Номер</label>
            <textarea type="textarea" class="form-login" name="parent_id"
                      id="" value="" placeholder="Введите номер меню в которое добавляете пункт меню"></textarea>
        </div>
        <div class="itt">
            <button id="showContent" type="submit" name="show_number1" class="show_number">Показать id каталогов</button>
        </div>
        <div class="itt">
            <button id="closeContent" type="submit" name="show_number2" class="show_number">Скрыть id каталогов</button>
        </div>
        <div class="i">
            <button id="but" type="submit" name="insert" class="submit">Применить</button>
        </div>
        <p>Правка меню</p>
        <div class="i">
            <label for="pass">Удалить пункт меню</label>
            <textarea type="textarea" class="form-login" name="id_del"
                      id="" value="" placeholder="Введите номер удаляемого меню"></textarea>
        </div>
        <div class="i">
            <button id="" type="submit" name="delete_menu" class="submit">Применить</button>
        </div>
        <div class="i">
            <button id="clear_menu" type="submit" name="clear_menu" class="submit">Очистить полностью</button>
        </div>
</form>
<script>

    const divsList = document.querySelectorAll(".whithout_number");
    const show = document.getElementById("showContent");
    const closeContent = document.getElementById("closeContent");

    show.addEventListener('click', function (){
        for (let i = 0; i < divsList.length; i++) {
            divsList[i].classList.remove("close");
            divsList[i].classList.add("whithout_number");

        }
    })
    closeContent.addEventListener('click', function (){
        for (let i = 0; i < divsList.length; i++) {
            divsList[i].classList.remove("close");
            divsList[i].classList.add("whithout_number");

        }
    })

    let btn = document.querySelector(".menu");
    let content = btn.children;
    let uuu = document.querySelectorAll("li");


    for (let elem of uuu) {

        let span = document.createElement('span');
        span.classList.add('open');
        elem.prepend(span);
        span.append(span.nextSibling);
    }

    for (let elemz of content) {

        let element = elemz.querySelectorAll("li");

        element.forEach((elemt) => {
            elemt.classList.add("close");

        })
    }

    for (let elem of uuu) {

        let p = elem.querySelector("p");
        p.classList.remove("close");
        p.classList.add("open");
        let a = elem.querySelector("a");
        a.classList.remove("open");
        a.classList.add("close");

        let qspan = elem.querySelector("span");

        qspan.onmouseover = function (event) {

            let next = elem.nextSibling;

            if (next != null) {
                let h = next.firstElementChild.tagName;

                if (h !== "LI") {

                    let p1 = elem.querySelector("p");
                    p1.classList.remove("open");
                    p1.classList.add("close");
                    let a1 = elem.querySelector("a");
                    a1.classList.remove("close");
                    a1.classList.add("open");
                }

            } else {

                let p1 = elem.querySelector("p");
                p1.classList.remove("open");
                p1.classList.add("close");
                let a1 = elem.querySelector("a");
                a1.classList.remove("close");
                a1.classList.add("open");
            }
        }

        qspan.onclick = function (event) {

            qspan.addEventListener('click', e => {
                console.log('target', e.target);
                console.log('currentTarget', e.currentTarget);
                console.log('evt', e);
            });

            let tre1 = elem.nextSibling;

            if (tre1.classList.contains("close")) {
                tre1.classList.remove("close");
            }


            if (tre1 != null) {
                let child2 = tre1.children;

                if (child2 != null) {
                    for (let g of child2) {

                        let a = g.querySelector("a");
                        if (a.classList.contains("open")) {
                            a.classList.toggle("close");
                        }

                        if (g.classList.contains("close")) {
                            g.classList.toggle("close");

                        } else {
                            g.classList.toggle("close");

                        }
                    }
                }
            }
        }
    }

</script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>
