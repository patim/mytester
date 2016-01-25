<?php 
/*
if ($_POST['Submit']) { 
   //  bla-bla... 
   // здесь необходимые действия (внесение в базу, etc) 
   // 
  // echo "<SCRIPT language=\"JavaScript\">window.location.replace('back.php?bck=".$_SERVER['HTTP_REFERER']."')</SCRIPT>"; 
}
  */ 
?> 
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<body>
<p><?php /*
echo "<br> from checkbox"; print_r($_POST['otvet']);
echo "<br> size = ".sizeof($_POST['otvet']);
echo in_array(2, $_POST['otvet']) ? "<br> 2 there is" : "<br> there is no 2";*/
/*foreach ($_POST['corr'] as  $id_ans => $ans_corr_ar)	 {
	foreach($ans_corr_ar as $ans_corr)
		echo   "ans=".$id_ans."\t ans_corr=".$ans_corr;
	echo "<br>";
}
for($i = 0; $i < 10; $i++) {
	for($j = 0; $j < 10; $j++) {
		$k = $i + $j;
		echo "\$k=".$k. "  ";
		if ($i == 5 && $j == 1) {
			$exit = true;
			break;
		}
	}
	if(isset($exit)) break;
	echo "<br>";
}*/
echo "Текст".$_GET['f'];
echo "<br>".$_GET['Submit'];
echo  "<br> Cумма с един.".($_GET['Submit']+1);
?>
</p>
<p><a href="javascript: location.replace('test3.php')">Ссылка на тест 3</a> <br></p>
</body>
</html>
