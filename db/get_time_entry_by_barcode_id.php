<?

function get_time_entry_by_barcode_id( $barcode_scanner_id )
{
    $query = <<<SQL
        SELECT time_entry,
            barcode_scanner_id,
            unit,
            ( SELECT e.name
              FROM tb_entity e
              WHERE e.entity = te.entity ) as entity_name,
            station,
            start_time,
            end_time,
            end_time - start_time as "duration"
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
