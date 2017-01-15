<?

require_once( '../functions/common.php' );
require_once( '../db/upsert_time_entry.php' );
require_once( '../db/start_time_entry.php' );
require_once( '../db/get_time_entry_by_pk.php' );

db_connect();

$data = [
    'unit_label'         => $_REQUEST['unit_label'],
    'entity_name'        => $_REQUEST['entity_name'],
    'barcode_scanner_id' => $_REQUEST['barcode_scanner_id']
];

$time_entry = upsert_time_entry( $data );

if( !$time_entry )
{
    fail();
}

if( $time_entry['unit'] &&
    $time_entry['entity'] &&
    $time_entry['station']
  )
{
    # don't start tracking time until the entity has entered the necessary info
    $time_entry = start_time_entry( $time_entry['time_entry'] );
}


# so that we can return the entity name and duration
$time_entry = get_time_entry_by_pk( $time_entry['time_entry'] );

echo( json_encode($time_entry) );
