<?

function finish_time_entry( $barcode_data )
{
    $query = <<<SQL
        UPDATE tb_time_entry
        SET end_time = NOW()
        WHERE barcode_scanner_id = $1
SQL;

    $params = [ $barcode_data['barcode_scanner_id'] ];

    if( $barcode_data['unit_label'] )
    {
        $query .= " AND unit = (SELECT unit FROM tb_unit WHERE label = $2) ";
        array_push( $params, $barcode_data['unit_label'] );
    }
    else if( $barcode_data['entity_name'] )
    {
        $query .= " AND entity = (SELECT entity FROM tb_entity WHERE name = $2) ";
        array_push( $params, $barcode_data['entity_name'] );
    }

    $query .= " RETURNING *";
    
    $result = pg_query_params( $query, $params );
    $time_entry = pg_fetch_assoc( $result );

    return $time_entry;
}

?>
