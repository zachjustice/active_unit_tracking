<?

function get_time_entry_by_barcode_id( $barcode_scanner_id )
{
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

    $params = [ $barcode_scanner_id ];

    $result = pg_query_params( $query, $params );
    // false if no time_entry, time_entry if one exists
    $time_entry = pg_fetch_assoc( $result );
    return $time_entry;
}

?>
