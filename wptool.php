<?php
$site=$argv[1];
########################################################################
print "|------------Wordpress Analyses Tool 1.0------------|\n";
print "|      _             _                    _            |\n";
print "|     | |           (_)                  (_)           |\n";
print "|     | | __ _ _ __  _ ___ ___  __ _ _ __ _  ___  ___  |\n";
print "| _   | |/ _` | '_ \| / __/ __|/ _` | '__| |/ _ \/ __| |\n";
print "|| |__| | (_| | | | | \\__ \\__ \\ (_| | |  | |  __/\\__ \\ |\n";
print "| \\____/ \\____|_| |_|_|___/___/\\____|_|  |_|\\___||___/ |\n";
print "|------------------------------------------------------|\n";
print "|             /\\            | |                        |\n";
print "|            /  \\   _ __ ___| | ___                    |\n";
print "|           / /\ \\ | '__/ __| |/ _ \\                   |\n";
print "|          / ____ \\| |  \\__ \\ |  __/                   |\n";
print "|         /_/    \\_\\_|  |___/_|\\___|                   |\n";
print "|-------------------Thx To:Tgrl5000--------------------|\n";
print "\n\n";
########################################################################

//Fonksiyonlar


Function curl_getSSL($url){
    $ch1=curl_init();
    $hc = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13";
    curl_setopt($ch1, CURLOPT_REFERER, 'http://www.google.com');
    curl_setopt($ch1, CURLOPT_URL,$url);
    curl_setopt($ch1, CURLOPT_USERAGENT, $hc);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER,false);
    $z = curl_exec($ch1);
    curl_close($ch1);
        return $z;
}

Function kaynakcek($hedef)
{
	$kaynak=curl_getSSL($hedef);
	
	$parcala="@/wp-content/plugins/(.*?)/@si";
	$parcala2="@/wp-content/themes/(.*?)/@si";
	$parcala3='@<meta name="generator" content="(.*?)" />@si';
	
	preg_match_all($parcala,$kaynak,$eklenti);
	preg_match($parcala2,$kaynak,$tema);
	preg_match($parcala3,$kaynak,$surum);

	stilcek($hedef,$tema[1]);
	
	$yeni=array_unique($eklenti[1]);
	print "------------------------------------------------------\n";
	print "Tema Adi\n$tema[1]\n";
	print "------------------------------------------------------\n";
	print "Wordpress Surum!\n$surum[1]\n";
	print "------------------------------------------------------\n";
	print "Eklentiler\n";
		foreach($yeni as $deger){
			print $deger."\n";
								}
	print "------------------------------------------------------\n";
	
	print "Eklenti Aciklari Araniyor...\n";
	sleep(2);
	eklentiacikara($yeni,$hedef);
	
	print "Wordpress Aciklari Araniyor...\n";
	sleep(2);
	wpacikara($surum[1]);
	
	print "Tema Aciklari Araniyor...\n";
	sleep(2);
	temacikara($tema[1]);
}


Function stilcek($hedefsite,$tema)
{
	
	$styleContent=curl_getSSL($hedefsite."/wp-content/themes/$tema/style.css");
	preg_match('@Theme Name: (.*?)\n@si',$styleContent,$themeName);
	preg_match('@Theme URI: (.*?)\n@si',$styleContent,$themeUri);
	preg_match('@Author: (.*?)\n@si',$styleContent,$themeAuthor);
	print $themeName[0];
	print $themeUri[0];
	print $themeAuthor[0];
	
	
}

Function eklentiacikara($eklenti,$site)
{
	print "------------------------------------------------------\n";
	$i=0;
	$parcala='@<td><a href="(.*?)">(.*?)</a></td>@si';
	foreach($eklenti as $eklenticik)
	{
		$i=0;
		$eklentikod=curl_getSSL("https://wpvulndb.com/plugins/$eklenticik");
		preg_match_all($parcala,$eklentikod,$parcalananeklenti);
		foreach($parcalananeklenti[1] as $eklentiacigi)
		{
			print($parcalananeklenti[2][$i])."\n";
			print("https://wpvulndb.com".$eklentiacigi."\n");
			$i++;
		}
		//print_r($parcalananeklenti);
		
	}
	
	
	print "------------------------------------------------------\n";
}

Function wpacikara($surum)
{
	
	$surum=str_replace(".","",$surum);
	$surum=str_replace("WordPress ","",$surum);
	$surumkod=curl_getSSL("https://wpvulndb.com/wordpresses/$surum");
	
	if(!strstr($surumkod,'404'))
	{
		$parcala='@<td><a href="(.*?)">(.*?)</a></td>@si';
		preg_match_all($parcala,$surumkod,$wpaciklar);
		
		//Aynı değerler var unique edilcek
		$i=0;
		
			foreach($wpaciklar[1] as $acik1)
			{
			
				print($wpaciklar[2][$i]."\n");
				print("https://wpvulndb.com".$acik1)."\n";
				$i++;
			}
		

		
		
		
		
	}
	else
	{
		print "Bulunamadı!";	
	}
	
	print "------------------------------------------------------\n";
}
Function temacikara($tema)
{
	$temakodlar=curl_getSSL("https://wpvulndb.com/themes/$tema");
	$temaparcala='@<td><a href="(.*?)">(.*?)</a></td>@si';
	preg_match_all($temaparcala,$temakodlar,$temaciklar);

		$i=0;
			foreach($temaciklar[1] as $acik1)
			{
			
				print($temaciklar[2][$i]."\n");
				print("https://wpvulndb.com".$acik1)."\n";
				$i++;
			}
	
	print "------------------------------------------------------\n";
}
//Fonksiyon Bitiş

if(!$site==0 and strstr($site,"http"))
{
	print "Hedef Site:\n$site\n";
	print "------------------------------------------------------\n";
	print "Site Analizi Yapiliyor...\n";
	sleep(2);
	kaynakcek($site);
	
	
}
else
{
	print "Kullanim:php phpadi.php http://www.siteismi.com/\n\n";
}





?>
