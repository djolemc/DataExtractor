<?php

ini_set('xdebug.var_display_max_depth', '-1');
ini_set('xdebug.var_display_max_children', '-1');
ini_set('xdebug.var_display_max_data', '-1');

set_time_limit(0); 
ignore_user_abort(true);
ini_set('max_execution_time', 0);

//ini_set('default_socket_timeout', 15);




$file_lines = file('OJS_curent_issue.txt');
$new_file = fopen('OJS_ISSN', 'a');



foreach ($file_lines as $line) {
   echo $line;
    echo "<br>";
  $line = str_replace(array("\n", "\r"), '', $line);
    $x=get_web_page($line);
    echo $x['errmsg'];
 
   $text=$x['content'];
  // var_dump($x);
   $eissn="";
   $pissn="";
    
    $y= getISSN($text);
   // var_dump($y);
    
    
   if (isset($y[0][0])) $pissn=$y[0][0];
   
    
   if (isset ($y[0][1])) $eissn=$y[0][1];
    
   

    
  fwrite ($new_file,$line.", ".$pissn.", ".$eissn."\n"); 
   unset($line);   
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
            CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
            CURLOPT_TIMEOUT        => 10,      // timeout on response
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

preg_match_all('/(ISSN|issn|Issn)(\D|\s|\D\s)(\d{4}\D\d+\D|(\d+\D))/', $x,$y);

return($y);

}