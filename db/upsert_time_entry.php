<?

require_once( '../functions/common.php' );

function db_connect()
{
    $conn_str = "host=localhost port=5432 dbname=axolotl user=postgres password=3xc1t1ng!Rover";
    pg_connect( $conn_str );
}

function upsert_time_entry( $data )
{
    db_connect();
    error_log("UPSERT_TIME_ENTRY: ");
    debug_dump($data);

    $query = <<<EOF
        SELECT time_entry,
            barcode_scanner_id,
            unit,
            employee_name,
            station,
            start_time,
            end_time,
            end_time - start_time as "duration"
        FROM tb_time_entry
        WHERE barcode_scanner_id = $1
          AND (unit IS NULL OR employee_name IS NULL)
          AND end_time IS NULL
EOF;

    $params = [ $data['barcode_scanner_id'] ];

    $result = pg_query_params( $query, $params );
    // false if no time_entry, time_entry if one exists
    $time_entry = pg_fetch_assoc( $result );

    debug_dump('EXISTING TIME ENTRY');
    debug_dump( $time_entry );

    if( !$time_entry )
    {
        $query = '';
        $params = '';

        if( $data['unit'] != "" )
        {
            $query = "INSERT INTO tb_time_entry(barcode_scanner_id, unit, station)";
            // TODO dynamic station assignment
            $query .= " VALUES($1, $2, 'Final Assembly')";

            $params = [ $data['barcode_scanner_id'], $data['unit'] ];
        }
        else if( $data['employee_name'] != "" )
        {
            $query = "INSERT INTO tb_time_entry(barcode_scanner_id, employee_name)";
            $query .= " VALUES($1, $2)";

            $params = [ $data['barcode_scanner_id'], $data['employee_name'] ];
        }

        $result = pg_query_params( $query, $params );
    }
    else
    {
        // update time entry
        $query = "UPDATE tb_time_entry";
        $params = [];

        if( $data['unit'] )
        {
            $query .= " SET unit = $1";
            $query .= " ,station = 'Final Assembly'";
            array_push( $params, $time_entry['unit'] );
        }
        else if( $data['employee_name'] )
        {
            $query .= " SET employee_name = $1";
            array_push( $params, $result['employee_name'] );
        }

        array_push( $params, $result['time_entry'] );
        $query .= " WHERE time_entry = $2 RETURNING *";
        $result = pg_query_params( $query, $params );
    }

    $time_entry = pg_fetch_assoc( $result );
    return $time_entry;
}
?>
