<?php
    function cleaning ($data){
        $result = str_replace(';','',strip_tags(trim($data)));
        return $result;
    }

    function cleaningString ($data){
        $charset = mb_detect_encoding($data, 'auto');
        $reg="";
        $regex = '/[àáâãéèêíóõôúÁÃÀÂÈÉÊÍÓÔÚçÇºª´`\(\)]/mx';
        $regex2= '/[^0-9a-zA-Z -.,;:?!àáâãéèêíóõôúÃÁÀÂÈÉÊÍÓÔÚçÇºª\/\(\)]+/m';
        preg_match_all($regex, $data, $matches, PREG_SET_ORDER, 0);
		$data2 = preg_replace($regex2, "", $data);
        $data2 = str_replace("”","",$data2);
        //$data2 = str_replace("”","",$data);
        if(count($matches)>0){
            $reg="1";
            $charsetToUse = $charset === "ASCII" ? "ISO-8859-1" : "UTF-8"; //users fica ok, protocols não
			$htmlentitiesconverted = htmlentities($data2, ENT_NOQUOTES, $charsetToUse, false);
            $charset2 = mb_detect_encoding($htmlentitiesconverted, 'auto');
            if($htmlentitiesconverted === ""){
                $htmlentitiesconverted = htmlentities($data2, ENT_NOQUOTES, "ISO-8859-1", false);
            }
            $htmlentitiesconverted = html_entity_decode($htmlentitiesconverted, ENT_NOQUOTES, "UTF-8");
            
            //echo "$charset - $charset2 - reg:$reg  - $htmlentitiesconverted \r\n";
        } else {
            $reg="0";
            $htmlentitiesconverted = htmlentities($data2, ENT_NOQUOTES, "ISO-8859-1", false);
            $charset2 = mb_detect_encoding($htmlentitiesconverted, 'auto');
            $htmlentitiesconverted = html_entity_decode($htmlentitiesconverted, ENT_NOQUOTES, "UTF-8");

            //echo "$charset - $charset2 - reg:$reg - $data - $htmlentitiesconverted \r\n";
        }

        $result = strip_tags(trim($htmlentitiesconverted));

        
        return $result;
    }

    function validateData ($data) {
        if(is_array($data) || is_object($data)) {
            $ret = [];
            foreach($data as $k => $v){
                $ret[$k] = validateData($v);
            }
            return $ret;

        } elseif(is_numeric($data)) {
            return $data;
        } else {
            //echo cleaningString($data);
            return cleaningString($data);
        }
    }


    function validateDataIn ($data) {
        if(is_array($data) || is_object($data)) {
            $ret = [];
            foreach($data as $k => $v){
                $ret[$k] = validateData($v);
            }
            return $ret;
        } elseif(is_numeric($data)) {
            return $data;
        } else {
            return htmlentities($data, ENT_NOQUOTES, 'UTF-8', false);;
        }
    }

    function validade($data){
        $data2 = explode('-',$data);
        $ano = $data2[0];
        $mes = $data2[1];
        $dia = $data2[2];
        #setando a primeira data  10/01/2008 
        $dia1= mktime(0,0,0,$mes,$dia,$ano);
        #setando segunda data 10/02/2008
        $dia2 = mktime();
        #armazenando o valor da subtracao das datas
        $d3 = ($dia1-$dia2);
        #usando o roud para arrendondar os valores
        #converter o tempo em dias
        $dias = round($d3/60/60/24);
        #exibindo  dias | repudizira na tela 31
        return $dias; 
   }
?>