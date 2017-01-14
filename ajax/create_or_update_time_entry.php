<?

require_once( '../functions/common.php' );
require_once( '../db/upsert_time_entry.php' );
require_once( '../db/start_time_entry.php' );

db_connect();

$data = [
    'unit'               => $_REQUEST['unit'],
    'employee_name'      => $_REQUEST['employee_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];

$time_entry = upsert_time_entry( $data );

if( $time_entry['unit'] &&
    $time_entry['employee_name'] &&
    $time_entry['station']
  )
{
    # don't start tracking time until the employee has entered the necessary info
    $time_entry = start_time_entry( $time_entry['time_entry'] );
}

foreach( $time_entry as $key => $value ) {
    if( $value == null )
    {
        $time_entry[$key] = '';
    }
}

echo json_encode($time_entry);

?>
