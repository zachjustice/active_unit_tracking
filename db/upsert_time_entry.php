<? 

require_once( '../functions/common.php' );
require_once( 'get_time_entry_by_barcode_id.php' );
require_once( 'create_time_entry.php' );
require_once( 'update_time_entry.php' );

function upsert_time_entry( $data )
{
    $time_entry = get_time_entry_by_barcode_id($data['barcode_scanner_id']);

    if( !$time_entry )
    {
        $time_entry = create_time_entry($data);
    }
    else
    {
        $data['time_entry'] = $time_entry['time_entry'];
        $time_entry = update_time_entry($data);
    }

    return $time_entry;
}

?>
