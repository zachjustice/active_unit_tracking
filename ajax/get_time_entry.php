<?

require_once( '../functions/common.php' );
require_once( '../db/upsert_time_entry.php' );

db_connect();

$data = [
    'unit'               => $_REQUEST['unit'],
    'employee_name'      => $_REQUEST['employee_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];

$time_entry = upsert_time_entry( $data );

foreach( $time_entry as $key => $value ) {
    if( $value == null )
    {
        $time_entry[$key] = '';
    }
}

echo json_encode($time_entry);

?>
