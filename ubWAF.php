<?

class ubWAF{
    
    /**
     * 
     **/

    function sanitize_array($val){
        if (is_array($val)){
            foreach ($val as $k => $v){
                $val[$k] = htmlentities(strip_tags($v), ENT_QUOTES, 'UTF-8');
            }
        } else {
            $val = htmlentities(strip_tags($val),ENT_QUOTES, 'UTF-8');
        }
        return $val;
    }
	
	
    function get_ip_addr(){
            //check ip from share internet
            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
              $ip=$_SERVER['HTTP_CLIENT_IP'];
            } //to check ip is pass from proxy
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
              $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
              $ip=$_SERVER['REMOTE_ADDR'];
            }
            $ip = explode(",", $ip);
            return $ip[0];
    }

	
    function session_identifier(){
        global $core;
	$userAgent = getenv('HTTP_USER_AGENT');
        $ipAddress = $this->get_ip_addr();
        return $core->B64encode($ipAddress.'|'.$userAgent);
    }
	
	
    /********** SANITIZE FUNCTION **********/
    function sanitizeHard($data, $type = '0') {

        // remove whitespaces (not a must though)
        $data = trim($data);
        // apply stripslashes if magic_quotes_gpc is enabled
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        // a mySQL connection is required before using this function
        //$data = mysql_real_escape_string($data);

        if (($type == '1') || ($type == 1)) {
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            //elimina tutto tranne i numeri
            $data = preg_replace("/[^0-9]/", "", $data);
        }

        // hard coding of STRING type
        if (($type == '2') || ($type == 2)) {
            $data = strip_tags($data);
            // trova ed elimina la parola http da $pagina
            $data = str_replace("http", "", $data);
            // trova ed elimina la parola https da $pagina
            $data = str_replace("https", "", $data);
            // trova ed elimina la parola ftp da $pag_def
            $data = str_replace("ftp", "", $data);

            // solo in casi eccezionali
            $dirtystuff = array("\\", "*", "=", "#", ";", "<", ">", "+", "%", "(", ")", "}", "{", "//", "--");

            $data = str_replace($dirtystuff, "", $data);

            $data = str_replace("\"", "˝", $data);
            $data = str_replace("'", "ʼ", $data);
        }


        $data = $this->strip_tags_attributes($data, '<div>', '');

        return $data;
    }


    /**
     *  strip_tags_attributes: Eliminates all unnecesary tags and attributes not specified in the parameters
     *  Example: $data = strip_tags_attributes($data,'<strong><em><a><br><ul><li><b><p>','href,rel');
     *
     **/
    function strip_tags_attributes($string, $allowtags = NULL, $allowattributes = NULL) {
        $string = strip_tags($string, $allowtags);
        if (!is_null($allowattributes)) {
            if (!is_array($allowattributes))
                $allowattributes = explode(",", $allowattributes);
            if (is_array($allowattributes))
                $allowattributes = implode(")(?<!", $allowattributes);
            if (strlen($allowattributes) > 0)
                $allowattributes = "(?<!" . $allowattributes . ")";
            $string = preg_replace_callback("/<[^>]*>/i", create_function(
                '$matches', 'return preg_replace("/ [^ =]*' . $allowattributes . '=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'
            ), $string);
        }
        return $string;
    }

    function sanitizeInteger($var){
        // To SANITIZE Integer value use
        $var=(filter_var($var, FILTER_SANITIZE_NUMBER_INT));
    }

    function sanitizeEmail($var){
        //To SANITIZE email query value use
        $var=(filter_var($var,  FILTER_SANITIZE_EMAIL));
    }

//To SANITIZE String value use
    function sanitizeString($data){
        //remove space bfore and after
        $data = trim($data);
        //remove slashes
        $data = stripslashes($data);
        $data=(filter_var($data, FILTER_SANITIZE_STRING));
        return $data;
    }

//To SANITIZE Sql query value use
    function sanitizeSQL($data){
        //$data= mysql_real_escape_string($data);
        $data= stripslashes($data);
        return $data;
        //or in one line code
        //return(stripslashes(mysql_real_escape_string($data)));
    }
	
	

}
