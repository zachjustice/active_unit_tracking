<?
function update_time_entry( $data )
{
    $query = "UPDATE tb_time_entry";
    $params = [];

    if( $data['unit'] )
    {
        $query .= " SET unit = $1, station = 1";
        array_push( $params, $data['unit'] );
    }
    else if( $data['entity_name'] )
    {
        $query .= <<<SQL
            SET entity = (
                SELECT entity 
                FROM tb_entity
                WHERE name = $1 )
SQL;
        array_push( $params, $data['entity_name'] );
    }

    array_push( $params, $data['time_entry'] );
    $query .= " WHERE time_entry = $2 RETURNING *";

    $result = pg_query_params( $query, $params );
    $time_entry = pg_fetch_assoc( $result );
    return $time_entry;
}
?>
