<?
function update_time_entry( $data )
{
    $query = "UPDATE tb_time_entry";
    $params = [];

    if( $data['unit'] )
    {
        $query .= " SET unit = $1, station = 'Final Assembly'";
        array_push( $params, $data['unit'] );
    }
    else if( $data['employee_name'] )
    {
        $query .= " SET employee_name = $1";
        array_push( $params, $data['employee_name'] );
    }

    array_push( $params, $data['time_entry'] );
    $query .= " WHERE time_entry = $2 RETURNING *";

    $result = pg_query_params( $query, $params );
    $time_entry = pg_fetch_assoc( $result );
    return $time_entry;
}
?>
