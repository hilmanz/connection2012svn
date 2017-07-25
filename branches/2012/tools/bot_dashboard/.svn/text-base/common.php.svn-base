<?php

function isValidDate($y, $m, $d) {
    if (isset($y) && isset($y) && isset($d) && checkdate(intval($m), intval($d), intval($y))) {
        return true;
    }
    return false;
}

function parseArgs($argv){
    array_shift($argv);
    $out = array();
    foreach ($argv as $arg){
        if (substr($arg,0,2) == '--'){
            $eqPos = strpos($arg,'=');
            if ($eqPos === false){
                $key = substr($arg,2);
                $out[$key] = isset($out[$key]) ? $out[$key] : true;
            } else {
                $key = substr($arg,2,$eqPos-2);
                $out[$key] = substr($arg,$eqPos+1);
            }
        } else if (substr($arg,0,1) == '-'){
            if (substr($arg,2,1) == '='){
                $key = substr($arg,1,1);
                $out[$key] = substr($arg,3);
            } else {
                $chars = str_split(substr($arg,1));
                foreach ($chars as $char){
                    $key = $char;
                    $out[$key] = isset($out[$key]) ? $out[$key] : true;
                }
            }
        } else {
            $out[] = $arg;
        }
    }
    return $out;
}

function arguments ( $args )
{
    array_shift( $args );
    $endofoptions = false;

    $ret = array
    (
    'commands' => array(),
    'options' => array(),
    'flags'    => array(),
    'arguments' => array(),
    );

    while ( $arg = array_shift($args) )
    {

        // if we have reached end of options,
        //we cast all remaining argvs as arguments
        if ($endofoptions)
        {
            $ret['arguments'][] = $arg;
            continue;
        }

        // Is it a command? (prefixed with --)
        if ( substr( $arg, 0, 2 ) === '--' )
        {

            // is it the end of options flag?
            if (!isset ($arg[3]))
            {
                $endofoptions = true;; // end of options;
                continue;
            }

            $value = "";
            $com   = substr( $arg, 2 );

            // is it the syntax '--option=argument'?
            if (strpos($com,'='))
            list($com,$value) = split("=",$com,2);

            // is the option not followed by another option but by arguments
            elseif (strpos($args[0],'-') !== 0)
            {
                while (strpos($args[0],'-') !== 0)
                $value .= array_shift($args).' ';
                $value = rtrim($value,' ');
            }

            $ret['options'][$com] = !empty($value) ? $value : true;
            continue;

        }

        // Is it a flag or a serial of flags? (prefixed with -)
        if ( substr( $arg, 0, 1 ) === '-' )
        {
            for ($i = 1; isset($arg[$i]) ; $i++)
            $ret['flags'][] = $arg[$i];
            continue;
        }

        // finally, it is not option, nor flag, nor argument
        $ret['commands'][] = $arg;
        continue;
    }

    if (!count($ret['options']) && !count($ret['flags']))
    {
        $ret['arguments'] = array_merge($ret['commands'], $ret['arguments']);
        $ret['commands'] = array();
    }
    return $ret;
}

function open_db($n){
    global $DATABASE;
    $host = $DATABASE[$n]['host'];
    $user = $DATABASE[$n]['user'];
    $password = $DATABASE[$n]['password'];
    $conn = mysql_connect($host,$user,$password);
    return $conn;
}

function close_db($conn){
    mysql_close($conn);
}

?>