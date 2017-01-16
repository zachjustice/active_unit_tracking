<?

require_once( '../functions/common.php' );
require_once( '../db/upsert_time_entry.php' );
require_once( '../db/start_time_entry.php' );
require_once( '../db/get_time_entry_by_pk.php' );

db_connect();

$barcode_data = [];
$constraints = [
    'unit_label'         => [ 'validator' => 'is_string', 'must_exist' => true ],
    'entity_name'        => [ 'validator' => 'is_string', 'must_exist' => true ],
    'barcode_scanner_id' => [ 'validator' => 'isInt'    , 'must_exist' => true ]
];

$barcode_data = create_map_from_request( $barcode_data, $_REQUEST, $constraints );

if( is_string( $barcode_data ) )
{
    fail();
}

$time_entry = upsert_time_entry( $barcode_data );

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
