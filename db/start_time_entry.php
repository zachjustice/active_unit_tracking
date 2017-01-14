<?
function start_time_entry( $time_entry_pk )
{
    $query = "UPDATE tb_time_entry SET start_time = NOW() WHERE time_entry = $1 RETURNING *";

    $result = pg_query_params( $query, [ $time_entry_pk ] );
    $time_entry = pg_fetch_assoc( $result );

    return $time_entry;
}
