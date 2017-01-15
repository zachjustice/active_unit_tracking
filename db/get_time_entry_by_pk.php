<?
function get_time_entry_by_pk( $time_entry_pk )
{
    $query = <<<SQL
        SELECT time_entry,
            barcode_scanner_id,
            unit,
            entity,
            station,
            start_time,
            end_time,
            ( SELECT u.label
              FROM tb_unit u
              WHERE u.unit = te.unit ) as unit_label,
            ( SELECT e.name
              FROM tb_entity e
              WHERE e.entity = te.entity ) as entity_name,
            ( SElECT s.name
              FROM tb_station s
              WHERE s.station = te.station ) as station_name,
            end_time - start_time as duration
        FROM tb_time_entry te
        WHERE time_entry = $1
SQL;

    $params = [ $time_entry_pk ];

    $result = pg_query_params( $query, $params );
    $time_entry = pg_fetch_assoc( $result );

    foreach( $time_entry as $key => $value ) {
        if( $value == null )
        {
            $time_entry[$key] = '';
        }
    }

    return $time_entry;
}
