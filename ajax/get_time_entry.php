<?

require_once( '../functions/common.php' );
require_once( '../db/upsert_time_entry.php' );

$data = [
    'unit'               => $_REQUEST['unit'],
    'employee_name'      => $_REQUEST['employee_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];
debug_dump('AJAX RECEIVED DATA:');
debug_dump($data);

$time_entry_record = upsert_time_entry( $data );

debug_dump("UPDATED/CREATED TIME ENTRY:");
debug_dump($time_entry_record);

$time_entry_record = [
    'time_entry_pk'      => 123,
    'barcode_scanner_id' => "123",
    'unit'               => '',
    'employee_name'      => 'Zach Justice',
    'station'            => 'Final Assembly',
    'start_time'         => 'January 5, 1:00 PM',
    'end_time'           => '',
    'duration'           => '00:05:32'
];

echo json_encode($time_entry_record);

?>
