<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/left-nav-style.css">
    <title>GasPromBank Docs</title>
    <script src="https://kit.fontawesome.com/d24ddbde4d.js" crossorigin="anonymous"></script>
    <script src="js/photo.js"></script>
    <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.timers.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script src="face-api.js"></script>
    <script src="js/commons.js"></script>
    <script src="js/bbt.js"></script>
    <script src="js/index.js"></script>
    <script src="js/index2.js"></script>
    <script>
        $(function(){
          //Если куки с именем не пустые, тащим имя и заполняем форму с именем
          if($.cookie("name")!=""){$("#t-box input[class='name']").attr("value", $.cookie("name"));}
          //Переменная отвечает за id последнего пришедшего сообщения
          var mid = 0;
          function escapeSpecialChars(jsonString) 
          {
            return jsonString.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t").replace(/\f/g, "\\f");
          }

          
          //Функция обновления сообщений чата
          
          function get_message_chat(){
            //Генерируем Ajax запрос
            $.ajaxSetup({url: "chat.php",global: true,type: "GET",data: "event=get&id="+mid+"&t="+
                (new Date).getTime()});
            //Отправляем запрос
            $.ajax({
              //Если все удачно
              
              success: function(msg_j){
                //Если есть сообщения в принятых данных
                if(msg_j.length > 2){
                  //Парсим JSON
                  var clearMsg = escapeSpecialChars(msg_j);
                  var obj = JSON.parse(clearMsg);
                  //Проганяем циклом по всем принятым сообщениям
                  for(var i=0; i < obj.length; i ++){
                    //Присваиваем переменной ID сообщения
                    mid = obj[i].id;
                    //Добавляем в чат сообщение
                    $("#msg-box ul").append("<li><div id='msg-box_border'><div class='robo-icon'></div> <b>"+obj[i].name+"</b>: "+obj[i].msg+"</div></li>");
                  }
                  //Прокручиваем чат до самого конца
                  $("#msg-box").scrollTop(2000);
                }
              }
            });
          }
         
          //Первый запрос к серверу. Принимаем сообщения
          get_message_chat();
         
          //Обновляем чат каждые две секунды
          $("#t-box").everyTime(2000, 'refresh', function() {
            get_message_chat();
          });
         
          //Событие отправки формы
          $("#t-box").submit(function() {
            //Запрашиваем имя у юзера.
            if($("#t-box button[class='name']").attr("value") == ""){ alert("Пожалуйста, введите свое имя!")}else{
              //Добавляем в куки имя
              $.cookie("name", $("#t-box button[class='name']").attr("value"));
         
              //Тащим сообщение из формы
              var msg = $("#t-box button[class='msg']").val();
              //Если сообщение не пустое
              if(msg != ""){
                //Чистим форму
                $("#t-box button[class='msg']").attr("value", "");
                //Генерируем Ajax запрос
                $.ajaxSetup({url: "chat.php", type: "GET",data: "event=set&name="+
                    $("#t-box button[class='name']").val()+"&msg="+msg});
                //Отправляем запрос
                $.ajax();
              }
            }
            //Возвращаем false, чтобы форма не отправлялась.
            return false;
          });
        });
    </script>
    <script>
$(document).ready(function(){
	var dropZone = $('#upload-container');

	$('#file-input').focus(function() {
		$('label').addClass('focus');
	})
	.focusout(function() {
		$('label').removeClass('focus');
	});


	dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function(){
		return false;
	});

	dropZone.on('dragover dragenter', function() {
		dropZone.addClass('dragover');
	});

	dropZone.on('dragleave', function(e) {
		let dx = e.pageX - dropZone.offset().left;
		let dy = e.pageY - dropZone.offset().top;
		if ((dx < 0) || (dx > dropZone.width()) || (dy < 0) || (dy > dropZone.height())) {
			dropZone.removeClass('dragover');
		}
	});

	dropZone.on('drop', function(e) {
		dropZone.removeClass('dragover');
		let files = e.originalEvent.dataTransfer.files;
		sendFiles(files);
	});

	$('#file-input').change(function() {
		let files = this.files;
		sendFiles(files);
	});


	function sendFiles(files) {
		let maxFileSize = 5242880;
		let Data = new FormData();
		$(files).each(function(index, file) {
			if ((file.size <= maxFileSize) && ((file.type == 'image/png') || (file.type == 'image/jpeg'))) {
				Data.append('images[]', file);
			}
		});

		$.ajax({
			url: dropZone.attr('action'),
			type: dropZone.attr('method'),
			data: Data,
			contentType: false,
			processData: false,
			success: function(data) {
				alert ('Файлы были успешно загружены!');
			}
		});
	}
})
    </script>
</head>
<body>
<!-- <div class="phone">
  <img src="images/Phone.png">
  <p>Ещё удобнее с мобильным приложением</p>
  <p>Пользуйтесь повсеметно, экономьте с нами время и деньги!</p>
</div> -->
<input type="checkbox" id="nav-toggle" hidden>
<nav class="nav">
  <label for="nav-toggle" class="nav-toggle" onclick></label>
  <h2 class="logo"> 
    <a href="https://www.gazprombank.ru/">Газпром Банк</a> 
  </h2>
  <ul>
    <li><a href="https://online.gpb.ru/login">Личный кабинет</a>
    <li><a href="https://www.gazprombank.ru/personal/take_credit/consumer_credit/">Кредиты</a>
    <li><a href="https://www.gazprombank.ru/personal/increase/deposits/">Вклады</a>
    <li><a href="https://www.gazprombank.ru/personal/brokerage_service/individual_investment_account/">Инвестиции</a>
    <li><a href="https://www.gazprombank.ru/feedback/">Поддержка</a>
    <li><a href="#6"></a>
    <li><a href="#7"></a> 
  </ul>
</nav>
    <div class="container">
      <div class="modal-wrapper">
        <div class="modal">
          <div class="head">
            <span>Идентификация GosUslugi</span>
            <a class="btn-close trigger" href="#">
              <i class="fa fa-times" aria-hidden="true"></i>
            </a>
          </div>
          <ul>
            <details><summary>Исользовать фото с компьютера:</summary> 
            <form id="upload-container" method="POST" action="send.php">          
              <div>
                <input id="file-input" type="file" name="file" multiple>
                <label for="file-input">Выберите файл</label>
                <span>или перетащите его сюда</span>
              </div>
            </form>
            </details>
            <details><summary>Подключиться к Веб-камере:</summary><div>
              <div class = "booth">
		            <video id="video" width="100" height="100" autoplay></video>
		            <button id="snap">Сделать снимок</button>
		            <canvas id="canvas" width="100" height="100"></canvas>
                <img id="photo">
              </div>
	        </div></details>
          </ul>
        </div>
      </div>
      
      <div class="modal-wrapper2">
        <div class="modal">
          <div class="head">
            <span>Цифровая подпись</span>
            <a class="btn-close trigger2" href="#">
              <i class="fa fa-times" aria-hidden="true"></i>
            </a>
          </div>
            <span>Для успешного и безопасного пользования нашим сервисом, вам необходимо указать ключ для цифровой подписи!</span>
            <input type="password">
        </div>
      </div>
        <div class="main-window">
            <div class="main-window_header">             
                <a class="btn" href="#"><i class="fa fa-bars" aria-hidden="true" title="Меню"><input type="checkbox" id="nav-toggle" hidden></i></a>
                <a class="btn trigger" href="#"><i class="fas fa-camera" title="Идентификация GosUslugi"></i></a>
                <a class="btn trigger2" href="#"><i class="fas fa-key" title="Код для цифровой подписи"></i></a>               
                <!-- <button name="clearCanvas">Clear</button> -->
            </div>
            <div id="msg-box">
                <ul>
                </ul>
            </div>
            <!-- <form id="t-box" action="?" style="">
                 Имя: 
                <input type="text" class='name' style="width:100px;" >
                <input type="text" class='msg' style="width:500px;" >
                <input type="submit" value="Отправить" style="margin-top:5px;">
            </form> -->
        </div>
        <!-- <form id="t-box" action="?" style="" class="message-form">
            <input type="text" class='name' style="width:100px;" >
            <input type="text" class="msg"  placeholder="Введите ваше сообщение здесь...">
            <button class="message-form_button"><i class="far fa-envelope"></i><span>Отправить</span></button>
        </form> -->
        <form  id="t-box" action="?" style="" class="message-form">
            <button  name="help" class="message-form_button col-md-3"><i class="far fa-envelope"></i><span>Помощь</span></button>
            <button  name="sendDoc" class="message-form_button col-md-3"><i class="fa fa-paper-plane-o" aria-hidden="true"></i><span>Отправка документа</span></button>
            <button  name="call" class="message-form_button col-md-3"><i class="fa fa-handshake-o" aria-hidden="true"></i><a href="load.html">Связаться с сотрудником</a></button>
        </form>      
    </div>
    <!-- <div class="center-content page-container">
      <div>
        <div class="progress" id="loader">
          <div class="indeterminate">
          </div>
        </div>
        <div class="row side-by-side">
          <div class="center-content">
            <img id="face1" src="" class="margin"/>
            <div id="selectList1"></div>
          </div>
          <div class="center-content">
            <img id="face2" src="" class="margin"/>
            <div id="selectList2"></div>
          </div>
        </div>
        <div class="row">
          <label for="distance">Distance:</label>
          <input disabled value="-" id="distance" type="text" class="bold">
        </div>
      </div>
    </div> -->

  <!-- <script>
    const threshold = 0.6
    let descriptors = { desc1: null, desc2: null }

    function updateResult() {
      const distance = faceapi.utils.round(
        faceapi.euclideanDistance(descriptors.desc1, descriptors.desc2)
      )
      let text = distance
      let bgColor = '#ffffff'
      if (distance > threshold) {
        text += ' (no match)'
        bgColor = '#ce7575'
      }
      $('#distance').val(text)
      $('#distance').css('background-color', bgColor)
    }

    async function onSelectionChanged(which, uri) {
      const input = await faceapi.fetchImage(uri)
      const imgEl = $(`#face${which}`).get(0)
      imgEl.src = input.src
      descriptors[`desc${which}`] = await faceapi.computeFaceDescriptor(input)
    }

    async function run() {
      await  faceapi.loadFaceRecognitionModel()
      $('#loader').hide()
      await onSelectionChanged(1, $('#selectList1 select').val())
      await onSelectionChanged(2, $('#selectList2 select').val())
      updateResult()
    }

    $(document).ready(function() {
      renderFaceImageSelectList(
        '#selectList1',
        async (uri) => {
          await onSelectionChanged(1, uri)
          updateResult()
        },
        { className: 'sheldon', imageIdx: 1 }
      )

      renderFaceImageSelectList(
        '#selectList2',
        async (uri) => {
          await onSelectionChanged(2, uri)
          updateResult()
        },
        { className: 'howard', imageIdx: 1 }
      )
      run()
    })

  </script> -->

</body>
</html>