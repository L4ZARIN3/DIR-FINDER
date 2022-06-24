<?php

function gravar($nome, $texto){
	$arquivo = $nome.".txt";
	$fp = fopen($arquivo, "a+");
	fwrite($fp, $texto);
	fclose($fp);
}

echo "
 \e[32m########::'####:'########::::'########:'####:'##::: ##:'########::'########:'########::::::::::'##::::::'##:::'##:
 ##.... ##:. ##:: ##.... ##::: ##.....::. ##:: ###:: ##: ##.... ##: ##.....:: ##.... ##::::::::: ##:::::: ##::'##::
 ##:::: ##:: ##:: ##:::: ##::: ##:::::::: ##:: ####: ##: ##:::: ##: ##::::::: ##:::: ##::::::::: ##:::::: ##:'##:::
 ##:::: ##:: ##:: ########:::: ######:::: ##:: ## ## ##: ##:::: ##: ######::: ########:::::::::: ##:::::: #####::::
 ##:::: ##:: ##:: ##.. ##::::: ##...::::: ##:: ##. ####: ##:::: ##: ##...:::: ##.. ##:::::'##::: ##:::::: ##. ##:::
 ##:::: ##:: ##:: ##::. ##:::: ##:::::::: ##:: ##:. ###: ##:::: ##: ##::::::: ##::. ##:::: ##::: ##:'###: ##:. ##::
 ########::'####: ##:::. ##::: ##:::::::'####: ##::. ##: ########:: ########: ##:::. ##:::. ######:: ###: ##::. ##:
........:::....::..:::::..::::..::::::::....::..::::..::........:::........::..:::::..:::::......:::...::..::::..::\033[0m
::::::::::::::::::::::::::::::::::::::::::::::::BY JOHN KAI$3R:::::::::::::::::::::::::::::::::::::::::::::::::::::\n";

$site = readline("[SITE*]: ");
$gravar = readline("[GRAVAR RESULTADOS EM *]: ");
$altura = readline("[ALTURA DO WORDLIST *]: ");

if(is_numeric($altura) == false){
    echo "\e[31m A ALTURA DA WORDLIST DEVE SER UM NUMERO\033[0m";
    exit();
}

$words = explode("\n", file_get_contents('./wordlist.txt'));
for($i=$altura; $i < count($words); ) { 
    $payload = filter_var($site.'/'.$words[$i], FILTER_SANITIZE_URL);
    $ch = curl_init();
    $options = array(
        CURLOPT_URL            => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_ENCODING       => "",
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch); 
    $httpCode = curl_getinfo($ch);
    if($httpCode['http_code'] == 200){
        echo "\e[32m[$i] SIZE [".$httpCode['size_download']."] 200 OK => ".$payload."\033[0m\n";
        gravar($gravar.'[200]', '['.$i.'] SIZE ['.$httpCode['size_download'].'] 200 OK => '.$payload."\n");
    }elseif($httpCode['http_code'] == 500){
        echo "\e[33m[$i] SIZE [".$httpCode['size_download']."] 500 OK => ".$payload."\033[0m\n";
        gravar($gravar.'[500]', '['.$i.'] SIZE ['.$httpCode['size_download'].'] 500 OK => '.$payload."\n");
    }elseif($httpCode['http_code'] == 403){
        echo "\e[33m[$i] SIZE [".$httpCode['size_download']."] 403 OK => ".$payload."\033[0m\n";
        gravar($gravar.'[403]', '['.$i.'] SIZE ['.$httpCode['size_download'].'] 403 OK => '.$payload."\n");
    }else{
        echo "\e[31m[$i] SIZE [".$httpCode['size_download']."] ".$httpCode['http_code']." => ".$payload."\033[0m\n";
    }

    $i++;    
}

