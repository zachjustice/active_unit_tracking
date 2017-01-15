<?

function create_time_entry($data)
{
    $query = '';
    $params = '';

    if( $data['unit'] != "" )
    {
        $query = <<<SQL
            INSERT INTO tb_time_entry(
                barcode_scanner_id,
                unit,
                station
            )
            VALUES(
                $1,
                $2,
                'Final Assembly'
            )
SQL;

        $params = [ $data['barcode_scanner_id'], $data['unit'] ];
    }
    else if( $data['entity_name'] != "" )
    {
        $query =<<<SQL
            INSERT INTO tb_time_entry(
                barcode_scanner_id,
                entity
            )
            VALUES(
                $1,
                 (SELECT entity FROM tb_entity WHERE name = $2)
            )
SQL;

        $params = [ $data['barcode_scanner_id'], $data['entity_name'] ];
    }

    $query .= " RETURNING *";
    $result = pg_query_params( $query, $params );

    if( !$result )
    {
        return false;
    }

    $time_entry = pg_fetch_assoc( $result );

    return $time_entry;
}

?>
