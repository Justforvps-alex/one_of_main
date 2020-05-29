<?php
function check_signal(){
    $signal=htmlentities(file_get_contents("signal.txt"));
    if($signal=="stop")
    {
        $php_status = fopen('phpstatus.txt', 'w+');
        fwrite($php_status, "done");
        fclose($php_status);
        exit;
    }
}
function Curl_avito2($url_2,$time_sleep,$mistakes)
{
$useragent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/'.rand(60,72).'.0.'.rand(1000,9999).'.121 Safari/537.36';
$ch = curl_init($url_2);
curl_setopt($ch, CURLOPT_URL, $url_2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_PROXY, '34.96.248.174:443');
curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'vps:12345');
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
$page = curl_exec($ch);
$html=str_get_html($page);
curl_close($ch);
sleep($time_sleep);
//fwrite($mistakes, $html);
return $html;
}
function Curl_avito1($url,$time_sleep,$mistakes)
{
$useragent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/'.rand(60,72).'.0.'.rand(1000,9999).'.121 Safari/537.36';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_PROXY, '35.243.124.130:443');
curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'vps:12345');
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
$page = curl_exec($ch);
$html=str_get_html($page);
curl_close($ch);
sleep($time_sleep);
//fwrite($mistakes, $html);
return $html;
}
function find_phone_url($id, $phone_item_only0, $phone_item_only1, $phone_item_only2)
{
	$id_only=$id;
	$array_keys0 = str_split($phone_item_only0); 
	$array_keys1 = str_split($phone_item_only1);
	$array_keys2 = str_split($phone_item_only2);
	//function check_code($array0,$array1,$array2)
	$k=0; //Номер кода
	$code_key='';//Буква разделитель
	$letters_in_key=count($array_keys0);
	for($a=0;$a<$letters_in_key;$a=$a+3)
	{
		//Если все 3 совпадают
		if($array_keys0[$a]==$array_keys1[$a] and $array_keys0[$a]==$array_keys2[$a])
		{ continue; }
		//Проверка когда 2 отличаются
		elseif($array_keys0[$a]!=$array_keys1[$a] and $array_keys0[$a]!=$array_keys2[$a] and $array_keys1[$a]!=$array_keys2[$a])
		{
			if($array_keys0[$a+1]==$array_keys1[$a+1]) { $k=0; $code_key=$array_keys0[$a]; break;}
			elseif($array_keys0[$a+1]==$array_keys2[$a+1]) { $k=0; $code_key=$array_keys0[$a]; break;} 
			elseif($array_keys1[$a+1]==$array_keys2[$a+1]) { $k=1; $code_key=$array_keys1[$a]; break;}	
		}
		//Проверка когда 1 отличается
		elseif($array_keys0[$a]==$array_keys1[$a] and $array_keys0[$a]!=$array_keys2[$a])
		{
			$code_key=$array_keys2[$a];
			if($array_keys0[$a]==$array_keys2[$a+1]) { $k=2; break;}	
		}
		elseif($array_keys0[$a]==$array_keys2[$a] and $array_keys0[$a]!=$array_keys1[$a])
		{
			$code_key=$array_keys1[$a];
			if($array_keys0[$a]==$array_keys1[$a+1]) { $k=1; break;}	
		}
		elseif($array_keys1[$a]==$array_keys2[$a] and $array_keys1[$a]!=$array_keys0[$a])
		{
			$code_key=$array_keys0[$a];
			if($array_keys1[$a]==$array_keys0[$a+1]) { $k=0; break;}	
		}
	}
	//Находим количество букв и вгоняем линию
	if($k==0) { $crypted_line_array=$array_keys0; }
	elseif($k==1) { $crypted_line_array=$array_keys1; }
	elseif($k==2) { $crypted_line_array=$array_keys2; }
	$numer=count($crypted_line_array);
	$pkey=''; //Код
	//$i=0;
	for($i=0;$i<$numer;$i=$i+3)
	{
		if($crypted_line_array[$i]==$code_key) {$i++;}
		$pkey.=$crypted_line_array[$i];
	}
	$phoneUrl="https://www.avito.ru/items/phone/".$id_only."?pkey=".$pkey."&vsrc=r";
	$findphone = fopen('phoneurls.txt', 'a+');
    fwrite($findphone, $phoneUrl." разделитель) ".$code_key.PHP_EOL);
    fclose($findphone);
    $findphone = fopen('codekeys.txt', 'a+');
    fwrite($findphone, $code_key.",");
    fclose($findphone);
	//echo "<br>Вывод из поиска".$phoneUrl."<br>";
	return $phoneUrl;
}
function find_phone_url_2($id, $phone_item_only0, $phone_item_only1, $phone_item_only2)
{
$id_only=$id;
$array_keys0 = str_split($phone_item_only0); 
$array_keys1 = str_split($phone_item_only1);
$array_keys2 = str_split($phone_item_only2);
//////////Вводим pkey
$pkey_0='';
$pkey_1='';
$pkey_2='';
$all_keys= array(
'k',
'p',
'l',
'q',
'y',
's',
'g',
't',
'j',
'n',
'o',
'x',
'm',
'u',
'v',
'i',
'w',
'h',
'z',
'r',
);
////////////Поиск ключей для строк
$i=0;
while($array_keys0[$i]!='')
{
	if (in_array($array_keys0[$i], $all_keys)) {
		$key_letter_0=$array_keys0[$i];
		//echo $key_letter_0;
		break;
	}
	$i++;
}
$i=0;
while($array_keys1[$i]!='')
{
	if (in_array($array_keys1[$i], $all_keys)) {
		$key_letter_1=$array_keys1[$i];
		//echo $key_letter_1;
		break;
	}
	$i++;
}
$i=0;
while($array_keys2[$i]!='')
{
	if (in_array($array_keys2[$i], $all_keys)) {
		$key_letter_2=$array_keys2[$i];
		//echo $key_letter_2;
		break;
	}
	$i++;
}
/////////////////////////Разбиваем на строки по ключам
$array_parts_0=explode($key_letter_0,$phone_item_only0);
$max_parts_0=count($array_parts_0);
$array_parts_1=explode($key_letter_1,$phone_item_only1);
$max_parts_1=count($array_parts_1);
$array_parts_2=explode($key_letter_2,$phone_item_only2);
$max_parts_2=count($array_parts_2);
//////////////////////////////Находим pley в каждой
////////////////////////////////В первой строке 
$i=$max_parts_0-1;
while($i>=0)
{
	$array_in_one_part = str_split($array_parts_0[$i]); 
	$max_in_one_part=count($array_in_one_part);
	for($j=0; $j<$max_in_one_part; $j=$j+3)
	{
		$pkey_0.=$array_in_one_part[$j];
	}
	$i=$i-1;
}

////////////////////////////////В второй строке 
$i=$max_parts_1-1;
while($i>=0)
{
	$array_in_one_part = str_split($array_parts_1[$i]); 
	$max_in_one_part=count($array_in_one_part);
	for($j=0; $j<$max_in_one_part; $j=$j+3)
	{
		$pkey_1.=$array_in_one_part[$j];
	}
	$i=$i-1;
}

////////////////////////////////В третьей строке 
$i=$max_parts_2-1;
while($i>=0)
{
	$array_in_one_part = str_split($array_parts_2[$i]); 
	$max_in_one_part=count($array_in_one_part);
	for($j=0; $j<$max_in_one_part; $j=$j+3)
	{
		$pkey_2.=$array_in_one_part[$j];
	}
	$i=$i-1;
}
/////////////////Находим pkey
if($pkey_0==$pkey_1 and $pkey_0==$pkey_2) 
{ $pkey=$pkey_0; }
else if($pkey_0==$pkey_1) 
{ $pkey=$pkey_0; }
else if($pkey_0==$pkey_2) 
{ $pkey=$pkey_0; }
else if($pkey_1==$pkey_2) 
{ $pkey=$pkey_1; }
else 
{ $pkey=$pkey_0; }
$phoneUrl="https://www.avito.ru/items/phone/".$id_only."?pkey=".$pkey."&vsrc=r";
$findphone = fopen('phoneurls.txt', 'a+');
fwrite($findphone, $phoneUrl.PHP_EOL);
fclose($findphone);
return $phoneUrl;
}
function download_proxy($url)
{
	$fp = fopen('socks5_proxies.txt', 'wb'); // создаём и открываем файл для записи
	$ch = curl_init($url); // $url содержит прямую ссылку на видео
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILE, $fp); // записать вывод в файл
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}
?>