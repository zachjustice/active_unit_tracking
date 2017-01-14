<?

require_once( '../functions/common.php' );
require_once( '../db/finish_time_entry.php' );

db_connect();

$barcode_data = [
    'unit'               => $_REQUEST['unit'],
    'employee_name'      => $_REQUEST['employee_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];

$time_entry = finish_time_entry( $barcode_data );

echo json_encode( $time_entry );

?>
