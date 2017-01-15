<?

require_once( '../functions/common.php' );
require_once( '../db/finish_time_entry.php' );
require_once( '../db/get_time_entry_by_pk.php' );

db_connect();

$barcode_data = [
    'unit'               => $_REQUEST['unit'],
    'entity_name'      => $_REQUEST['entity_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];

$time_entry = finish_time_entry( $barcode_data );
$time_entry = get_time_entry_by_pk( $time_entry['time_entry'] );

echo json_encode( $time_entry );

?>
