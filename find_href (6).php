<?php
ignore_user_abort(true);
ini_set('max_execution_time', 86400);
set_time_limit(86400);
error_reporting(0);
$main_url=$_GET['url'];
$number_of_phones=$_GET['n'];
$page_number=0;
$phone_number=0;
print('<form action="status.php" class="justify-content-center "><input style="margin-top:20px; width:100%; font-size:20px;" type="submit" name="status" value="Отслеживать состояние загрузки">
</form>');
ob_flush();
flush();
$connect = new mysqli("localhost", "root", "12345", "phones");
$delete_all = $connect->query("DELETE FROM phones_url");
$delete_all = $connect->query("DELETE FROM phones");
//Создание статусазапущено
$php_status = fopen('phpstatus.txt', 'w+');
fwrite($php_status, "run");
fclose($php_status);
$main_url=str_replace ('nedvizhimost','kvartiry/prodam-ASgBAgICAUSSA8YQ',$main_url);
$main_url=str_replace ('transport','avtomobili',$main_url);
$main_url=str_replace ('uslugi','predlozheniya_uslug/bytovye_uslugi-ASgBAgICAUSYC7CfAQ',$main_url);
require_once 'simple_html_dom.php';
require_once 'functions.php';
//$url='https://api.proxyscrape.com/?request=getproxies&proxytype=socks4&timeout=10000&country=all';
//download_proxy($url);
$max_pages=100;
//$number_of_phones=1500; //vvodim post
$url='';
$write_number_for_status = fopen('need_phones.txt', 'w+');
fwrite($write_number_for_status, $number_of_phones);
fclose($write_number_for_status);
$mistakes = fopen('mistakes.txt', 'w+');
fwrite($mistakes, date('l jS \of F Y h:i:s A'));
$time = fopen('time.txt', 'w+');
fwrite($time, "Начало");
fwrite($time, date('l jS \of F Y h:i:s A'));
while($phone_number<=$number_of_phones)
{
fwrite($mistakes, "Глобальный цикл");
if($page_number>98)
{
    $page_number=0;
}
    //Проверка сигналов
    check_signal();
	
	//Первый поток 1 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$page_number++;
	$url=$main_url.'?p='.$page_number;
	$time_sleep=rand(2,3);
	$html=Curl_avito1($url,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	fwrite($mistakes, 'nulevoy cicl');
	$write_id_file_1 = fopen('id_array_1.txt', 'w+');
	foreach($html->find('div.snippet-horizontal') as $href_div)
	{
		$id=$href_div->attr['data-item-id'];
			$array0[$id]=$href_div->attr['data-pkey'];
			if($array0[$id]!='')
			{
			    $contact_number=0;
		    	foreach($href_div->find('a.snippet-link') as $href_to_check)
	    	    {$contact_number=$contact_number+1;  }	
		         if($contact_number==1)
		        {
                   fwrite($write_id_file_1, $id.",");
		    	    fwrite($mistakes, $id);
		        }
			}
	}
	fclose($write_id_file_1);

	//Второй поток 1 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$page_number++;
	$url_2=$main_url.'?p='.$page_number;
	$time_sleep=rand(2,3);
	$html=Curl_avito2($url_2, $time_sleep, $mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	fwrite($mistakes, 'nulevoy cicl votorogo potoka');
	$write_id_file_2 = fopen('id_array_2.txt', 'w+');
	foreach($html->find('div.snippet-horizontal') as $href_div)
	{
		$id=$href_div->attr['data-item-id'];
			$array0[$id]=$href_div->attr['data-pkey'];
			if($array0[$id]!='')
			{
			    $contact_number=0;
		    	foreach($href_div->find('a.snippet-link') as $href_to_check)
	    	    {$contact_number=$contact_number+1;  }	
		         if($contact_number==1)
		        {
                   fwrite($write_id_file_2, $id.",");
		    	    fwrite($mistakes, $id);
		        }
			}
	}
	fclose($write_id_file_2);
	//////////////////////////////////////////////
	fwrite($mistakes, $url);
	fwrite($mistakes, $url_2);
	//////////////////////////////////////////////
    $id_arrayall=htmlentities(file_get_contents("id_array_1.txt"));
    $id_array_1=preg_split("/[\s,]+/",$id_arrayall);
	$max_id_1=count($id_array_1);
    
    $id_arrayall=htmlentities(file_get_contents("id_array_2.txt"));
    $id_array_2=preg_split("/[\s,]+/",$id_arrayall);
	$max_id_2=count($id_array_2);
	
	///////////////////////////////////////////////////
	//Первый поток 2 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    fwrite($mistakes, 'perviy cicl');
	$time_sleep=rand(2,3);
	$html=Curl_avito1($url,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	
    check_signal();
    
	for($id_numer=0;$id_numer<$max_id_1; $id_numer++)
	{
		$id=$id_array_2[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		        if(isset($href_div)){$array1[$id]=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array1[$id]."<br>");}
		}
	}
	//Второй поток 2 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    fwrite($mistakes, 'perviy cicl');
	$time_sleep=rand(2,3);
	$html=Curl_avito2($url_2,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	
    check_signal();
    
	for($id_numer=0;$id_numer<$max_id_2; $id_numer++)
	{
		$id=$id_array_2[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		        if(isset($href_div)){$array1[$id]=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array1[$id]."<br>");}
		}
	}
    //Первый поток 3 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$checked_id=1;
	$time_sleep=rand(2,3);
	$html=Curl_avito1($url,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	for($id_numer=0;$id_numer<$max_id_1; $id_numer++)
	{
        check_signal();
		$id=$id_array_1[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		if(isset($href_div))
		{$array2[$id]=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array2[$id]."<br>");}
		if($array0[$id]!='' && $array1[$id]!='' && $array2[$id]!='')
		 {           
				    //echo "<br>Номер )",$id,"<br>";
	            	fwrite($mistakes, $checked_id."  Внутри ласт цикла   ".$phone_number.") ".$array0[$id]."   ".$array1[$id]."    ".$array2[$id]."    ");
            		$phone_item_only0=$array0[$id];
            		$phone_item_only1=$array1[$id];
            		$phone_item_only2=$array2[$id];
              		if($id%2!=0)
              		{ $url=find_phone_url($id, $phone_item_only0, $phone_item_only1, $phone_item_only2); }
              		else 
              		{ $url=find_phone_url_2($id, $phone_item_only0, $phone_item_only1, $phone_item_only2); }
                    $sel = $connect->query("SELECT id FROM phones_url WHERE item_url='$url'");
	            	$numer = $sel->num_rows; //считаем, сколько с таким же логином
	            	if($numer == 0)
	            	{
              		    $add = $connect->query("INSERT INTO phones_url (id, item_url) VALUES (NULL,'$url')");
              		}
              		$phone_number++;
              		if($phone_number>=$number_of_phones) { 
              		$sell = exec("mysql -u root -p12345 phones -sse 'SELECT COUNT(*) FROM phones'");
              		$phone_number = $sell;
              		if($phone_number>=$number_of_phones) 
              		{ 
              		    $php_status = fopen('phpstatus.txt', 'w+'); fwrite($php_status, "done"); fclose($php_status); 
              		    fwrite($time, "Конец"); fwrite($time, date('l jS \of F Y h:i:s A')); fclose($time); 
              		    exit; 
              		}
		 }
		 }
		}
	unset($array0[$id]); unset($array1[$id]); unset($array2[$id]);
	}
	//Второй поток 3 цикл////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $checked_id=1;
	$time_sleep=rand(2,3);
	$html=Curl_avito2($url_2,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	for($id_numer=0;$id_numer<$max_id_2; $id_numer++)
	{
        check_signal();
		$id=$id_array_2[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		if(isset($href_div))
		{$array2[$id]=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array2[$id]."<br>");}
		if($array0[$id]!='' && $array1[$id]!='' && $array2[$id]!='')
		 {           
				    //echo "<br>Номер )",$id,"<br>";
	            	fwrite($mistakes, $checked_id."  Внутри ласт цикла   ".$phone_number.") ".$array0[$id]."   ".$array1[$id]."    ".$array2[$id]."    ");
            		$phone_item_only0=$array0[$id];
            		$phone_item_only1=$array1[$id];
            		$phone_item_only2=$array2[$id];
            		if($id%2!=0)
            		{ $url=find_phone_url($id, $phone_item_only0, $phone_item_only1, $phone_item_only2); }
              		else 
              		{ $url=find_phone_url_2($id, $phone_item_only0, $phone_item_only1, $phone_item_only2); }
                    $sel = $connect->query("SELECT id FROM phones_url WHERE item_url='$url'");
	            	$numer = $sel->num_rows; //считаем, сколько с таким же логином
	            	if($numer == 0)
	            	{
              		    $add = $connect->query("INSERT INTO phones_url (id, item_url) VALUES (NULL,'$url')");
              		}
              		$phone_number++;
              		if($phone_number>=$number_of_phones) { 
              		$sell = exec("mysql -u root -p12345 phones -sse 'SELECT COUNT(*) FROM phones'");
              		$phone_number = $sell;
              		if($phone_number>=$number_of_phones) 
              		{ 
              		    $php_status = fopen('phpstatus.txt', 'w+'); fwrite($php_status, "done"); fclose($php_status); 
              		    fwrite($time, "Конец"); fwrite($time, date('l jS \of F Y h:i:s A')); fclose($time); 
              		    exit; 
              		}
		 }
		 }
		}
	unset($array0[$id]); unset($array1[$id]); unset($array2[$id]);
	}


////////////////
unset($html);
fwrite($mistakes, date('l jS \of F Y h:i:s A'));
fwrite($mistakes, "Страница номер $page_number");
}
?>