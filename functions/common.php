<?
$is_debug = true;

function fail()
{
    die(header("HTTP/1.0 500 Internal Server Error"));
}

function debug( $msg )
{
    if( $is_debug )
    {
        error_log( $msg );
    }
}

function debug_dump( $object=null ){
    if( $is_debug )
    {
        ob_start();
        var_dump( $object );
        $contents = ob_get_contents();
        ob_end_clean();
        error_log( $contents );
    }
}

function db_connect()
{
    $conn_str = "host=localhost port=5432 dbname=axolotl user=postgres password=3xc1t1ng!Rover";
    pg_connect( $conn_str );
}

?>
