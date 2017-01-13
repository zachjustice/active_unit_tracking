<?
function debug_dump( $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );        // log contents of the result of var_dump($object)
}

function db_connect()
{
    $conn_str = "host=localhost port=5432 dbname=axolotl user=postgres password=3xc1t1ng!Rover";
    pg_connect( $conn_str );
}

?>
