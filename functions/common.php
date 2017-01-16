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

// Adds each key and value in $_REQUEST into $map.
// Each $request[key] must pass the constraints specified in $constraints[key]
// Each key of $constraints matches a potential key in $_REQUEST/$map
// $constraints[key] describes the validation for $_REQUEST[key] before
// adding the key and value to $map
// Designators
//   must_exist: key must exist in required form in $_REQUEST or we fail
//   error_message: if $request[key] doesn't pass the specified constraints
//                  return this error message. 
//   validator: if the key is present, $request[key] is passed into validator.
//              if the value fails, return the specified error message
//              if the key is not present, we skip and continue
function create_map_from_request( $map, $request, $constraints )
{
    foreach( $constraints as $request_key => $designators )
    {
        $error_message = 'There was an internal error.';
        $validator = $designators['validator'];

        if( keyExists( $designators, 'error_message' ) )
        {
            $error_message = $designators['error_message'];
        }

        if( keyExists( $designators, 'must_exist' ) &&
            $designators['must_exist'] &&
            !keyExists( $request, $request_key )
        )
        {
            return error_message;
        }

        if( keyExists( $map, $request_key ) && !$validator( $map[$request_key] ) )
        {
            return error_message;
        }

        $map[$request_key] = $request[$request_key];
    }

    return $map;
}

// I like to pass the array first
function keyExists( $data, $key )
{
    return array_key_exists( $key, $data );
}

// Sensible integer checking since is_int() can go fuck itself
// isInteger(23)   TRUE
// isInteger("23") TRUE
// isInteger(23.5) FALSE
// isInteger(NULL) FALSE
// isInteger("")   FALSE
function isInt( $val_or_arr, $key = null )
{
    $input = $val_or_arr;

    if( is_array( $val_or_arr ) )
    {
        if( keyExists( $val_or_arr, $key ) )
        {
            $input = $val_or_arr[$key];
        }
        else
        {
            return false;
        }
    }

    return ctype_digit( strval( $input ) );
}

?>
