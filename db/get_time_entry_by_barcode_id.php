<?

function get_time_entry_by_barcode_id( $barcode_scanner_id )
{
    $query = <<<SQL
        SELECT time_entry,
            barcode_scanner_id,
            unit,
            entity,
            station,
            start_time,
            end_time
        FROM tb_time_entry te
        WHERE barcode_scanner_id = $1
          AND (unit IS NULL OR entity IS NULL)
          AND end_time IS NULL
SQL;

    $params = [ $barcode_scanner_id ];

    $result = pg_query_params( $query, $params );
    // false if no time_entry, time_entry if one exists
    $time_entry = pg_fetch_assoc( $result );
    return $time_entry;
}

?>
