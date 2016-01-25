<?php 
	session_start();
	$restrictGoTo=  "index.php";	
	if ( !isset($_SESSION['Username']) )	header("Location: ". $restrictGoTo);
	else {
		require_once('Connections/trainer.php');
		$query = "SELECT id_user FROM user WHERE name='". $_SESSION['Username']."'";
		$id_u = mysql_query($query, $trainer) or die(mysql_error());
		$id_u_row = mysql_fetch_assoc($id_u);
	}

	$act = $_SERVER[ 'PHP_SELF' ];

	if(  isset($_POST['start'])) {
		$_SESSION['sch'] =  0;
		// Тело оператора выполняется единственный раз при нажатии кнопки начала теста	
		$_SESSION['thm'] = get_magic_quotes_gpc() ? $_POST['id_theme'] : addslashes($_POST['id_theme']);
	
		$query = "INSERT INTO stat_theme (id_user, id_theme, date, allques, topmark) VALUES (".	$id_u_row['id_user'].", ".$_SESSION['thm'].", '".date("Y-m-d H:i:s")."')";
		if( mysql_query($query) ) 	{
			$id_stat_th = mysql_insert_id();
			$_SESSION['stat_th'] = $id_stat_th;
		}
		
		// 1. Переменная, соответствующая текущему выполняемому вопросу теста
		// 2. Отвечает смещению в запросе	
		$count = 0; 
		
		$query = "SELECT * FROM question WHERE id_theme=".$_SESSION['thm']." AND showhide='show'";
		$question = mysql_query($query,  $trainer) or die(mysql_error());
		$num_qs= mysql_num_rows($question);

		$query = "SELECT mark FROM question WHERE id_theme=".$_SESSION['thm']." AND showhide='show'";
		$mark = mysql_query($query,  $trainer) or die(mysql_error());
		$topmark = 0;
		while($row_mark = mysql_fetch_assoc($mark)) {
			$topmark += $row_mark['mark'];
		}
		
		$query = "INSERT INTO stat_theme (id_user, id_theme, date, allques, topmark) VALUES (".$id_u_row['id_user'].", ".$_SESSION['thm'].", '".date("Y-m-d H:i:s")."', ".$num_qs.", ".$topmark.")";
		if( mysql_query($query) ) 	{
			$id_stat_th = mysql_insert_id();
			$_SESSION['stat_th'] = $id_stat_th;
		}
		
		// Создаётся массив значений от 0 до (количество_вопросов - 1)
		// для генерации набора вопросов в случайном порядке
		$numbers = range(0, $num_qs-1);
		shuffle($numbers); // перемещивание массива
		$_SESSION['num'] = $numbers;  // занесение массива в сессию
		$_SESSION['marks'] = array_fill(0, $num_qs, 0); // количество набранных балов
		$_SESSION['passed'] = 1; // количество пройденных вопросов теста
		$_SESSION['single'] = true; // отвечает за однократное перемешивание ответов в течение теста
		
		if(isset($_SESSION['anstxt']))	unset($_SESSION['anstxt']);
		mysql_data_seek($question, $_SESSION['num'][$count]);
		if(!($row_ques =  mysql_fetch_assoc($question)) )	header("Location: testover.php");
	}
	else {
		$id_stat_th = $_SESSION['stat_th'];
		$count_old =  $_POST['count'];
		
		if(isset($_POST['page']))	
		// изменение $count при перемещении среди пройденных вопросов
			$count = $_POST['page'] - 1;
		else	{
		// увеличение $count при переходе к следующему вопросу
			$count = $_POST['count'] +1;
			if(($count +1) > $_SESSION['passed']) {
		// увеличенния количества пройденных вопросов	
				++$_SESSION['passed'];
		// регестрируем увеличение переменной $_SESSION['passed']
				$change_sess = true;
			}
		}
		
		$theme = $_SESSION['thm'];
		
		$query = "SELECT qtype FROM question WHERE id_ques=".$_POST['id_ques'];
		$question = mysql_query($query,  $trainer) or die(mysql_error());
		$row_ques2 =  mysql_fetch_assoc($question);
		
		// Создается переменная $a_ver, проверяющая дан ли ответ на вопрос теста
		if(isset($_POST['answ']) and is_array($_POST['answ']))
			foreach($_POST['answ'] as $v) {
				if(empty($v)) { 
					$a_ver = false;	break;
				}
				$a_ver = true;
			}
		else {
			if(empty($_POST['answ'])) $a_ver = false;
			else $a_ver = true;
		}
		
	//	if(isset($_POST['next']) ) 
		if( isset($_POST['answ']) and $a_ver and $_POST['count'] != ($_SESSION['passed'] - 1)  and !isset($_POST['finish']))
  				switch($row_ques2['qtype']) {
		// Обработка ответа на вопрос 1-го типа (единств. ответ) 
					case 'single': 
						$query = "SELECT * FROM answer WHERE id_ans =".$_POST['answ']. " AND id_ques=".$_POST['id_ques']. " AND correct='yes'";
						$ans = mysql_query($query, $trainer)  or die(mysql_error());		
						
						//Удаление старой(ых) записи(ей) в таблице статистики (если таковые имеются)			
						$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$id_stat_th." AND id_ques=".$_POST['id_ques'];
						mysql_query($query, $trainer);
						
						if( !mysql_num_rows($ans) ) {
							$query = "INSERT INTO stat_wrans (id_ques, id_ans, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$_POST['answ'].", ".$id_stat_th.", ".(1+$_POST['count']).")";
							mysql_query($query)  or die(mysql_error());
							$wr = 1;
							$_SESSION['marks'][$_POST['count']] = 0;
						}
						else {
							$query = "SELECT mark FROM question WHERE id_ques=".$_POST['id_ques'];
							$mark = mysql_query($query);
							$row_mark = mysql_fetch_assoc($mark);
							$_SESSION['marks'][$_POST['count']] = $row_mark['mark'];
						}
						break;
		//////////////////////////////////////////////////////////////
		// Обработка ответа на вопрос 2-го типа (неединств. ответ) 
					case  'many': 		
						$query = "SELECT * FROM answer WHERE id_ques=".$_POST['id_ques']. " AND correct='yes'";
						$ans = mysql_query($query, $trainer)  or die(mysql_error());		
						
						//Удаление старой(ых) записи(ей) в таблице статистики (если таковые имеются)
						$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$id_stat_th." AND id_ques=".$_POST['id_ques'];
						mysql_query($query, $trainer);
			
						if( mysql_num_rows($ans) && mysql_num_rows($ans) == sizeof($_POST['answ']) ) 
							foreach($_POST['answ'] as $v) {
								$ans_row = mysql_fetch_assoc($ans);
								if(!in_array($ans_row['id_ans'],  $_POST['answ'])) {
									foreach($_POST['answ'] as $vv) {
										$query = "INSERT INTO stat_wrans (id_ques, id_ans, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$vv.", ".$id_stat_th.", ".(1+$_POST['count']).")";
										mysql_query($query)  or die(mysql_error());
									}
									$wr = 1;
									break;
								}
							}
						else {
							foreach($_POST['answ'] as $vv) {
								$query = "INSERT INTO stat_wrans (id_ques, id_ans, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$vv.", ".$id_stat_th.", ".(1+$_POST['count']).")";
								mysql_query($query)  or die(mysql_error());
							}
							$wr = 1;
						}
						if( !isset($wr) )  {
							$query = "SELECT mark FROM question WHERE id_ques=".$_POST['id_ques'];
							$mark = mysql_query($query);
							$row_mark = mysql_fetch_assoc($mark);
							$_SESSION['marks'][$_POST['count']] = $row_mark['mark'];
						}
						else  	$_SESSION['marks'][$_POST['count']] = 0;
						break;
		//////////////////////////////////////////////////////////////
		// Обработка ответа на вопрос 3-го типа (задание порядка ответов) 
					case 'order': 
						//Удаление старой(ых) записи(ей) в таблице статистики (если таковые имеются)
						$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$id_stat_th." AND id_ques=".$_POST['id_ques'];
						mysql_query($query, $trainer);											
						foreach($_POST['answ'] as $id_ans => $ord)	{
							$query = "SELECT * FROM answer WHERE id_ans=".$id_ans." AND qorder=".$ord;
							$ans =  mysql_query($query) or die(mysql_error());

							if( !mysql_num_rows($ans) ) {
								foreach($_POST['answ'] as $id_ans => $ord) {
									$query = "INSERT INTO stat_wrans (id_ques, id_ans, qorder, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$id_ans.", ".$ord.", ".$id_stat_th.", ".(1+$_POST['count']).")";				
									mysql_query($query);
								}

								$wr = 1;
								break;
							}
							if(isset($wr) ) break;
						}

						if( !isset($wr) )  {
							$query = "SELECT mark FROM question WHERE id_ques=".$_POST['id_ques'];
							$mark = mysql_query($query);
							$row_mark = mysql_fetch_assoc($mark);
							$_SESSION['marks'][$_POST['count']] = $row_mark['mark'];
						}
						else  	$_SESSION['marks'][$_POST['count']] = 0;
						break;
		//////////////////////////////////////////////////////////////
		// Обработка ответа на вопрос 4-го типа (соответствие ответов) 
					case  'correspond': 
						//Удаление старой(ых) записи(ей) в таблице статистики (если таковые имеются)
						$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$id_stat_th." AND id_ques=".$_POST['id_ques'];
						mysql_query($query, $trainer);
						
						$query = "SELECT * FROM correspond WHERE id_ques=".$_POST['id_ques'];
						$corr = mysql_query($query) or die(mysql_error());
						while($corr_row = mysql_fetch_assoc($corr)) {
							$ans = $corr_row['id_ans'];
							$ans_corr = $corr_row['id_ans_corr'];
						//	echo $ans;
						//	print_r($_POST['answ']);
							if( !isset($_POST['answ'][$ans]) or  !in_array($ans_corr, $_POST['answ'][$ans]) ) {
								foreach ($_POST['answ'] as  $id_ans => $ans_corr_ar)	 {
									foreach($ans_corr_ar as $ans_corr) {
										$query = "INSERT INTO stat_wrans (id_ques, id_ans, id_ans_corr, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$id_ans.", ".$ans_corr.", ".$id_stat_th.", ".(1+$_POST['count']).")";
										mysql_query($query)  or die(mysql_error());
									}	
								}
								$wr = 1;
								break;
							}
							if(isset($wr)) break;
						}

						if(!isset($wr))
						foreach ($_POST['answ'] as  $id_ans => $ans_corr_ar)	 {
							foreach($ans_corr_ar as $ans_corr) {
								$query = "SELECT * FROM correspond WHERE id_ques=".$_POST['id_ques']. " AND id_ans=".$id_ans." AND id_ans_corr=".$ans_corr;
								$corr = mysql_query($query);
								if( !mysql_num_rows($corr) )  {
									foreach ($_POST['answ'] as  $id_ans => $ans_corr_ar)	 {
										foreach($ans_corr_ar as $ans_corr) {
											$query = "INSERT INTO stat_wrans (id_ques, id_ans, id_ans_corr, id_stat_th, num) VALUES(".$_POST['id_ques'].", ".$id_ans.", ".$ans_corr.", ".$id_stat_th.", ".(1+$_POST['count']).")";
											mysql_query($query)  or die(mysql_error());
										}
									}
									$wr = 1;
									break;
								}
								if(isset($wr) ) break;
							}
							if(isset($wr) ) break;
						}
						
						if( !isset($wr) ) 	{
							$query = "SELECT mark FROM question WHERE id_ques=".$_POST['id_ques'];
							$mark = mysql_query($query);
							$row_mark = mysql_fetch_assoc($mark);
							$_SESSION['marks'][$_POST['count']] = $row_mark['mark'];
						}
						else  	$_SESSION['marks'][$_POST['count']] = 0;
						break;
		//////////////////////////////////////////////////////////////
		// Обработка ответа на вопрос 5-го типа (ввод ответа с клавиатуры) 
					case 'enter': 
						//Удаление старой(ых) записи(ей) в таблице статистики (если таковые имеются)
						$query = "DELETE FROM stat_wrans WHERE id_stat_th=".$id_stat_th." AND id_ques=".$_POST['id_ques'];
						mysql_query($query, $trainer);
						//print_r($_POST['answ']);

						$query = "SELECT * FROM answer WHERE ans_text='".trim($_POST['answ'][$count_old])."'";
						$ans = mysql_query($query, $trainer) or die(mysql_error());
						if(!mysql_num_rows($ans)) {
							$query = "INSERT INTO stat_wrans (id_ques, wr_ans_text, id_stat_th, num) VALUES(".$_POST['id_ques'].", '".trim($_POST['answ'][$count_old])."', ".$id_stat_th.",  ".(1+$_POST['count']).")";
							mysql_query($query)  or die(mysql_error());
							$wr = 1;
						}
						
						$_SESSION['anstxt'][$count_old] = trim($_POST['answ'][$count_old]);
						
						if( !isset($wr) )	{ 
							$query = "SELECT mark FROM question WHERE id_ques=".$_POST['id_ques'];
							$mark = mysql_query($query);
							$row_mark = mysql_fetch_assoc($mark);
							$_SESSION['marks'][$_POST['count']] = $row_mark['mark'];
						}
						else  	$_SESSION['marks'][$_POST['count']] = 0;
						break;
		//////////////////////////////////////////////////////////////							
				}
		else  {
				if(!isset($_POST['page']) )  {
					$wr = 1;
					$notfilled = "Ответ не указан!";
			
					if( isset($change_sess) )	--$_SESSION['passed'];
					--$count;
					$query = "SELECT * FROM question WHERE id_theme=".$theme. " AND showhide='show'";
					$question = mysql_query($query,  $trainer) or die(mysql_error());
					mysql_data_seek($question, $_SESSION['num'][$count]);
					$row_ques =  mysql_fetch_assoc($question);
				}
		}
		
		if( isset($_POST['finish']) ) {
			//if( isset($change_sess) )	--$_SESSION['passed'];
			header("Location: testover.php");
			exit();
		}	
		
		if( isset($change_sess) ) 
			$query = "UPDATE stat_theme SET giv_qu=".($_SESSION['passed']-1).", mark=".array_sum($_SESSION['marks'])." WHERE id_stat_th=".$_SESSION['stat_th'];

		mysql_query($query);
		
		$query = "SELECT * FROM question WHERE id_theme=".$theme. " AND showhide='show'";
		$question = mysql_query($query,  $trainer) or die(mysql_error());
		if($count < sizeof($_SESSION['num']))	{
			mysql_data_seek($question, $_SESSION['num'][$count]);
			$row_ques =  mysql_fetch_assoc($question);
		}
		else 	
			header("Location: testover.php");		
	}
	//print_r($_SESSION);
?>
<html>
<head>
<title>Тренажер</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!--
// Расспахивающееся меню
function change(name){
  if (document.getElementById(name).style.display=='none') {
    document.getElementById(name).style.display=''; 
  } else 
  { document.getElementById(name).style.display='none';}
}
<!-- Изменение цвета шрифта на кнопке
function flashMe(eSrc, sColor) {
  eSrc.style.color=sColor
}
//-->

function MM_controlSound(x, _sndObj, sndFile) { //v3.0
  var i, method = "", sndObj = eval(_sndObj);
  if (sndObj != null) {
    if (navigator.appName == 'Netscape') method = "play";
    else {
      if (window.MM_WMP == null) {
        window.MM_WMP = false;
        for(i in sndObj) if (i == "ActiveMovie") {
          window.MM_WMP = true; break;
      } }
      if (window.MM_WMP) method = "play";
      else if (sndObj.FileName) method = "run";
  } }
  if (method) eval(_sndObj+"."+method+"()");
  else window.location = sndFile;
}
//-->
</script>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr> 
    <td width="760" height="153" colspan="2" valign="top"> 
      <?php include "utils/top.php";?>
    </td>
  </tr>
  <form action="<?php echo $act?>" method="post" name="ques_form" id="ques_form">
    <tr> 
      <td colspan="2" valign="top"> 
        <?php 
		echo "<p>";
  		for($i=1; $i <= sizeof($_SESSION['num']); $i++) {
			if($i <= $_SESSION['passed']) {
				if($i == ($count+1))	$cl = 'currbutton';
				else 							$cl = 'button';
				echo "<input name=page type=submit class=".$cl." id=page value=$i onMouseOver=\"flashMe(this,'orange')\" onMouseOut=\"flashMe(this,'black')\">\n";
			}
			else
				echo "<span class=notpassed>$i</span>\n";
		}
		echo "</p>";
  ?>
        <?php
/*
if($count  and !isset($notfilled)) 
	if(isset($_POST['next']) )
		echo !isset($wr) ? "Правильно!" : "Неправильно!";
	*/	
echo isset($notfilled) ? "<p><font color=#FF0000 face='Verdana, Arial, Helvetica, sans-serif' size=2>".$notfilled . "</font></p>": "<p>&nbsp;</p>";
?>
        <p><span class="body"><strong>Вопрос №:<?php echo $count+1;?></strong></span><br>
          <?php echo "<span class=body><em>".$row_ques['que_text']."</em></span>";
		  //print_r($_SESSION['marks']);?> 
        </p>
        <?php 
	if( $row_ques['picture'] )  { 
		echo "<p><img src='".$row_ques['picture']."'></p>";
	}
	if( $row_ques['sound']) {
		echo "<input name=play class=play type=button value='play   ' onClick=\"MM_controlSound('play','document.CS1147854152515','".$row_ques['sound']."')\">";
		echo "<EMBED NAME='CS1147854152515' SRC='".$row_ques['sound']."' LOOP=false AUTOSTART=false MASTERSOUND HIDDEN=true WIDTH=0 HEIGHT=0></EMBED>";
	}
?>
        <p> 
          <input name="count" type="hidden" id="count" value="<?php echo $count;?>">
          <input name="id_ques" type="hidden" id="id_ques" value="<?php echo $row_ques['id_ques'];?>">
          <?php 
  		$query = "SELECT * FROM answer WHERE id_ques=".$row_ques['id_ques'];
		$answer = mysql_query($query,  $trainer) or die(mysql_error());
  		switch($row_ques['qtype']) {
		// Формирование вариантов ответа на вопрос 1-го типа (единств. ответ) 
			case 'single': 
				if( $_SESSION['passed'] > $count+1 ) {
					$query = "SELECT * FROM stat_wrans WHERE id_ques=".$row_ques['id_ques']." AND id_stat_th=".	$id_stat_th;
					$wr_ans = mysql_query($query, $trainer);
					if(mysql_num_rows($wr_ans)) {
						$giv_ans = mysql_fetch_assoc($wr_ans);
					}
					else {
						$query = "SELECT * FROM answer WHERE id_ques=".$row_ques['id_ques']." AND correct='yes'";
						$r_ans = mysql_query($query, $trainer);
						$giv_ans = mysql_fetch_assoc($r_ans);
					}
				}
				echo "<span class=instr>Выберите правильный вариант<br></span>"; 	
				while( $row_ans =  mysql_fetch_assoc($answer) )  {
					if(isset($giv_ans) and $giv_ans['id_ans'] == $row_ans['id_ans']) $ch = "checked";
					else $ch = "";
					echo "<input name=answ ".$ch." type=radio value=".$row_ans['id_ans']."><span class=answer>".$row_ans['ans_text']."</span><br>";
				}
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 2-го типа (неединств. ответ) 			
			case  'many': 
				if( $_SESSION['passed'] > $count+1 ) {
					$query = "SELECT * FROM stat_wrans WHERE id_ques=".$row_ques['id_ques']." AND id_stat_th=".	$id_stat_th;
					$wr_ans = mysql_query($query, $trainer);
					$i = 1;
					if(mysql_num_rows($wr_ans)) {
						while( $giv_ans = mysql_fetch_assoc($wr_ans) )
							$giv_ans_ar[$i++] = $giv_ans['id_ans'];
					}
					else {
						$query = "SELECT * FROM answer WHERE id_ques=".$row_ques['id_ques']." AND correct='yes'";
						$r_ans = mysql_query($query, $trainer);
						while( $giv_ans = mysql_fetch_assoc($r_ans) ) 
							$giv_ans_ar[$i++] = $giv_ans['id_ans'];
					}
				}
				echo "<span class=instr>Выберите правильный(ые) вариант(ы)</span><br>"; 
				for($i = 1; $row_ans =  mysql_fetch_assoc($answer); $i++)  {
					if(isset($giv_ans_ar) and  in_array($row_ans['id_ans'], $giv_ans_ar) and !isset($notfilled) )	
						$ch = "checked";
					else 	$ch = "";								
					echo "<input name=answ[$i] ".$ch." type=checkbox value=".$row_ans['id_ans']."><span class=answer>".$row_ans['ans_text']."</span><br>";	
				}
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 3-го типа (задание порядка ответов) 			
			case 'order': 
				echo "<span class=instr>Задайте порядок</span><br>"; 
				
				$numrows =  mysql_num_rows($answer);				
				if($_SESSION['single'])	{ // однократное перемешивание
					$numbers2 =  range(0, $numrows-1);
					shuffle($numbers2);
					$_SESSION['num2'] = $numbers2;
					$_SESSION['single'] = false;
				}
				$numbers2 =  $_SESSION['num2'];
							
				if( $_SESSION['passed'] > $count+1 ) {
					$query = "SELECT * FROM stat_wrans WHERE id_ques=".$row_ques['id_ques']." AND id_stat_th=".$id_stat_th;
					$wr_ans = mysql_query($query, $trainer);

					if(mysql_num_rows($wr_ans)) 
						foreach($numbers2 as $pos)  {
							mysql_data_seek($wr_ans, $pos);
							$giv_ans=  mysql_fetch_assoc($wr_ans);
							$giv_ans_ar[$giv_ans['id_ans']] = $giv_ans['qorder'];
						}
					else {
						$query = "SELECT * FROM answer WHERE id_ques=".$row_ques['id_ques'];
						$r_ans = mysql_query($query, $trainer);
						foreach($numbers2 as $pos)  {
							mysql_data_seek($r_ans, $pos);
							$giv_ans=  mysql_fetch_assoc($r_ans);
							$giv_ans_ar[$giv_ans['id_ans']] = $giv_ans['qorder'];
						}
					}
				}								
				foreach($numbers2 as $pos)  {
					mysql_data_seek($answer, $pos);
					$row_ans =  mysql_fetch_assoc($answer);
					echo "<p><select class=instr name=answ[".$row_ans['id_ans']."] size=1>\n";
					echo "<option selected></option>";
					for ($j = 1; $j <= $numrows; $j++) {
						if(isset($giv_ans_ar) and $giv_ans_ar[$row_ans['id_ans']] == $j  and !isset($notfilled) )	
							$ch = "selected";
						else 	$ch = "";
						echo "<option value=$j ".$ch.">$j</option>";
					}
					echo  "</select>";
					echo "&nbsp;<span class=answer>".$row_ans['ans_text']."</span></p>";
				}	
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 4-го типа (соответствие ответов) 			
			case  'correspond': 
				echo "<span class=instr>Поставьте в соответствие</span><br>"; 
  				$query = "SELECT * FROM answer_corr WHERE id_ques=".$row_ques['id_ques'];
				$ans_corr = mysql_query($query,  $trainer) or die(mysql_error());
				
				if( $_SESSION['passed'] > $count+1 ) {
					$query = "SELECT * FROM stat_wrans WHERE id_ques=".$row_ques['id_ques']." AND id_stat_th=".$id_stat_th;
					$wr_ans = mysql_query($query, $trainer);

					if(mysql_num_rows($wr_ans)) {
						while( $giv_ans = mysql_fetch_assoc($wr_ans) )
							$giv_ans_ar[$giv_ans['id_ans']] [$giv_ans['id_ans_corr']] = $giv_ans['id_ans_corr'];
					}
					else {
						$query = "SELECT * FROM correspond WHERE id_ques=".$row_ques['id_ques'];
						$r_ans = mysql_query($query, $trainer);
						while( $giv_ans = mysql_fetch_assoc($r_ans) ) 
							$giv_ans_ar[$giv_ans['id_ans']] [$giv_ans['id_ans_corr']] = $giv_ans['id_ans_corr'];
					}
				}
				
				for ($j = 1; $row_ans_corr =  mysql_fetch_assoc($ans_corr); $j++)
					$arr_ans_corr[$j] =  $row_ans_corr;
				echo "<table>";				
				for($i = 1; $row_ans =  mysql_fetch_assoc($answer); $i++)  {
					echo "<tr>";
					echo "<td width=17 height=33><a href=\"javascript:change('name$i')\"><img src='img/down.gif' border=0 alt='Cоответствие'></a></td>
								<td width=700><a href=\"javascript:change('name$i')\" class=answer>".$row_ans['ans_text']."</a></td>";
					echo "</tr>";
					echo "<tr><td colspan=2>";
					echo "<DIV id='name$i' style='DISPLAY: none;'>";
					for ($j = 1; $j <= count($arr_ans_corr); $j++) {
						$ans =  $row_ans['id_ans'];
						$ans_cr = $arr_ans_corr[$j]['id_ans_corr'];
						if( isset($giv_ans_ar) and isset($giv_ans_ar[$ans][$ans_cr]) and !isset($notfilled) )
							$ch = "checked";
						else 	$ch = "";
						echo "&nbsp;&nbsp;&nbsp;<input name=answ[".$row_ans['id_ans']."][$j] type=checkbox ".$ch." value=".$arr_ans_corr[$j]['id_ans_corr']."><span class=corr>".$arr_ans_corr[$j]['ans_corr_text']."</span><br>";
					}
					echo "</DIV>";
					echo "<td></tr>";
				}
				echo "</table>";
				break;
		////////////////////////////////////////////////////////////////////////////////////
		// Формирование вариантов ответа на вопрос 4-го типа (ввод ответа с клавиатуры) 			
			case 'enter': 
				$row_ans =  mysql_fetch_assoc($answer);

				if( isset($_SESSION['anstxt'][$count]) and !isset($notfilled) ) {
					$v = "value='".$_SESSION['anstxt'][$count]."' ";
					//unset($_SESSION['anstxt']);
				}
				else $v = "";
				echo "<span class=instr>Введите с клавиатуры</span><br>";
				echo "<input class=editbt2 ".$v."name=answ[".$count."] type=text><br>" ;
			break;
		}
  ?>
          <br>
          <br>
        </p></td>
    </tr>
    <tr> 
      <td height="74" valign="top"> <input name="next" type="submit" class="button2" id="next" value="Следующий&gt;&gt;&gt;"></td>
      <td align="right" valign="top"> <input name="finish" type="submit" class="button2" id="finish2" value="Я сдаюсь! :("> 
        &nbsp;&nbsp;&nbsp;</td>
    </tr>
  </form>
  <tr valign="bottom"> 
    <td colspan="2"> 
      <?php include "utils/bottom.php";?>
    </td>
  </tr>
  <tr> 
    <td height="1"><img src="img/spacer.gif" alt="" width="394" height="1"></td>
    <td><img src="img/spacer.gif" alt="" width="161" height="1"></td>
  </tr>
</table>
</body>
</html>
