<?

function create_time_entry($data)
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

    $query .= " RETURNING *";
    $result = pg_query_params( $query, $params );
    return $result;
}

?>
