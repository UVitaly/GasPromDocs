<?
//Функции для работы с БД
function getQuery($query){
  $res = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_row($res);
  $var = $row[0];
  return $var;
}
 
function setQuery($query){
  $res = mysql_query($query) or die(mysql_error());
  return $res;
}
 
//Соединяемся с базой
@mysql_connect('localhost', 'root', '') or die("Не могу соединиться с MySQL.");
@mysql_select_db('gasprom') or die("Не могу подключиться к базе.");
@mysql_query('SET NAMES utf8;');
 



switch($_GET["event"]){
 
  case "get":
    $max_message = 60;
    //Всего сообщений в чате
    $count = getQuery("SELECT COUNT(`id`) FROM `chat`;");
    //Максимальный ID сообщения
    $m = getQuery("SELECT MAX(id) FROM `chat` WHERE 1");
    //Удаление лишних сообщений.
    if($count > $max_message){
      setQuery("DELETE FROM `chat` WHERE `id`<".($m-($max_message-1)).";");
    }
    //Принимаем от клиента ID последнего сообщения
    $mg = $_GET['id'];
    //Генерируем сколько сообщений нехватает клиенту
    if($mg == 0){$mg = $m-$max_message;}
    if($mg < 0){$mg = 0;}
    $msg = array();
 
    //Если у клиента не все сообщения, отсылаем ему недостоющие
    if($mg<$m){
      //Берем из базы недостобщие сообщения
      $query = "SELECT * FROM `chat` WHERE `id`>".$mg." AND `id`<=".$m." ORDER BY `id` ";
      $res = mysql_query($query) or die(mysql_error());
      while($row = mysql_fetch_array($res)){
        //Заносим сообщения в массив
        $msg[] = array("id"=>$row['id'], "name"=>$row['name'], "msg"=>$row['text']);
      }
    }
    //Отсылаем клиенту JSON с данными.
    echo json_encode($msg);
  break;
 
  case "set":
  $name = "Онлайн-помошник ГазПромБанк"; //Имя Бота
    if($start==0) //Затычка.  Нужна проверка потверждения
    {
      $msg = "Для доступа к сервису идентифицируйте себя!";
        //Сохраняем сообщение
        setQuery("INSERT INTO `chat` (`id` ,`name` ,`text` )VALUES (NULL , '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($msg)."');");
  
    }   
   
if (isset($_POST['help'])||1<2) //Закрто
{
  $start = 1;
  //Принимаем текст сообщения
    $msg = "Сервис предназначен для пользователей банка, которые заинтересованы в его услугах, но не имеют возможности лично оформить услугу. 
    Вам необходимо загрузить свое фото и фото паспорта или же воспользоватся Веб-камерой, а также отправить специальный ключ для создания цифровой подписи на передаваемые документы!
    Если возникнут проблемы, можно вызвать работника банка за уточнением дальнейших указаний";
  //Сохраняем сообщение
    setQuery("INSERT INTO `chat` (`id` ,`name` ,`text` )VALUES (NULL , '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($msg)."');");
}
else if (isset($_POST['sendDoc']))
{     
//Принимаем текст сообщения
  $msg = "Должно появится модальное окно с возвожностью выбора файла и его категории!";
//Сохраняем сообщение
  setQuery("INSERT INTO `chat` (`id` ,`name` ,`text` )VALUES (NULL , '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($msg)."');");
} else 
{
  //Принимаем текст сообщения
    $msg = "Сотрудник свяжется с вами в ближайшее время";
  //Сохраняем сообщение
    setQuery("INSERT INTO `chat` (`id` ,`name` ,`text` )VALUES (NULL , '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($msg)."');");
}

  break;
  
}
