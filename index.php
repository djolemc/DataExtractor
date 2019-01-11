<?php

header('Content-type: text/plain; charset=utf-8');


ini_set('xdebug.var_display_max_depth', '-1');
ini_set('xdebug.var_display_max_children', '-1');
ini_set('xdebug.var_display_max_data', '-1');

set_time_limit(0); 
ignore_user_abort(true);
ini_set('max_execution_time', 0);
 ini_set('display_errors', '0');
//ini_set('default_socket_timeout', 15);



//fajl iz kog cita adrese

 //Ako se pokrece iz cmd, otkomenarisat 21,22,23 i pokrenuti sa php index.php ime_ulaznog_fajla.txt , zakomantarisati linije 27,28, 32
//$file_lines = file($argv['1']); 
//$no_of_lines =count(file($argv['1']));
//$new_file = fopen('done_'.$argv['1'], 'a');


 //Ukoliko se pokrece iz browsera, ovde upisati ime ulaznog fajla
 $file_lines = file('OJSsvi.txt');
$no_of_lines =count (file('OJSsvi.txt'));


//fajl u koji se snima rezultat csv
$new_file = fopen('done_'.$fajl, 'a');

//$z="<title>Svet Kompjutera</title>";




var_dump($argv);



$brojac=1;

foreach ($file_lines as $line) {
  echo $brojac ." od ". $no_of_lines;
  $line = str_replace(array("\n", "\r"), '', $line);
   
  $x=get_web_page($line);
  
  $error= $x['errmsg'];
    
   $text= $x['content'];
   $text_issn= strip_tags($x['content']);
   
    $y= getISSN($text_issn);
   // var_dump($y);
   
  
        $z= getOnlyISSN($text);
     //   var_dump($z);
   
   echo "\n";
    
    $title= getTitle($text);
    //echo $line." ------ ". $title."\n";
    $ver=getVersion($text);

      if (isset($z[0][0])) $issn1=  $z[0][0];
      if (isset($z[0][1])) $issn2=  $z[0][1];
      if (isset($z[0][2])) $issn3=  $z[0][2];
  
    
   if (isset($y[0][0])) $pissn=  $y[1][0];
   if (isset ($y[0][1])) $eissn= $y[1][1];
   if (isset ($y[0][2])) $eissn2= $y[1][2];
   
   $pissn = preg_replace('~[\r\n]+~', '', $pissn);
   $eissn = preg_replace('~[\r\n]+~', '', $eissn);
   $eissn2 = preg_replace('~[\r\n]+~', '', $eissn2);
   $issn1 = preg_replace('~[\r\n]+~', '', $issn1);
   $issn2 = preg_replace('~[\r\n]+~', '', $issn2);
   $issn3 = preg_replace('~[\r\n]+~', '', $issn3);
    
   $title = preg_replace('~[\r\n]+~', '', $title);
   $ver = preg_replace('~[\r\n]+~', '', $ver);
   
   
   
   $pissn= str_replace(",", "ZAREZ", $pissn);
   $eissn= str_replace(",", "ZAREZ", $eissn);
   $eissn2= str_replace(",", "ZAREZ", $eissn2);
    $issn1= str_replace(",", "ZAREZ", $issn1);
    $issn2= str_replace(",", "ZAREZ", $issn2);
    $issn3= str_replace(",", "ZAREZ", $issn3);
   $title= str_replace(",", "ZAREZ", $title);
   $ver= str_replace(",", "ZAREZ", $ver);
   
    
  fwrite ($new_file,$line.", ".$pissn.", ".$eissn.", ".$eissn2.", ".$issn1.", ".$issn2.", ".$issn3.", ".$title." ,".$ver.", ".$error."\n"); 
  unset($line,$pissn,$eissn,$eissn2,$title,$ver,$error,$issn1,$issn2,$issn3);    
  $brojac++;
}
   

fclose($new_file);


/**
     * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
     * array containing the HTTP server response header fields and content.
     */
    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 20,      // timeout on connect
            CURLOPT_TIMEOUT        => 20,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }




function getISSN ($x) {

    //ovaj radi ok
//preg_match_all('/(ISSN|issn|Issn)(\D|\s|\D\s)(\d{4}\D\d+\D|(\d+\D))/', $x,$y);

    
    //za test:
      //preg_match_all('/(ISSN|issn|Issn)(\D|\s|\D+\s+|)(\d{4}(\D|)\d{4}|(\d{4}(\D|)\d{3}\D{1}))/', $x,$y);
      // preg_match_all('/((.{0,20})(ISSN)(.{0,40}))|((.{0,20})(issn)(.{0,40}))|((.{0,20})(Issn)(.{0,40}))/', $x,$y);
     
    
    preg_match_all('/(?=(.{20}(ISSN).{0,40}))/ims', $x,$y);  
       
      
return($y);

}

function getOnlyISSN ($x) {
     preg_match_all('/(\D|:|\/|\\|>| |\()\d{4}((-)|( - )|(–)|( – )|( )|())(\d{3}(\d|X))\D/m', $x,$y);  
    return($y);
}



function getTitle($z) {
       
  //  preg_match_all('/<title>(.*?)<\/title>/', $z,$y);
            
    preg_match_all('/((<title>(\s+.*?\s+)<\/title>)|(<title>(.*?)<\/title>))/', $z,$y);
    
    $title='null';
    
    if ($y[3][0]!='') {
        $title = $y[3][0];
    } 
    if ($y[5][0]!='') {
        $title = $y[5][0];
    }
    $title=preg_replace('/\s{2,}/', ' ', $title);
    return $title;
}

function getVersion($x) {
    
  //  preg_match_all('/(Open Journal Systems)(.{8})/', $x,$y);
    
     preg_match_all('/(<meta name="generator" content=")(.+)(")/', $x,$y);
    
    // var_dump($y);
    
    $ver=$y[2][0];
    return $ver;
    
}

