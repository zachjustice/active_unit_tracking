"use strict";
var EMPLOYEE_BARCODE_PREFIX  = 'e'
var UNIT_BARCODE_PREFIX  = 'u'
var BARCODE_SCANNER_ID_PREFIX  = 'b'

var time_entry_state  = '';
var TIME_ENTRY_STATES = {
    READY:       0, // have not scanned a barcode and ready to accept time entries
    INITIALIZE:  1, // we have scanned a barcode and are starting a new time entry
    IN_PROGRESS: 2, // necessary barcodes have been scanned and timing has started
    PAUSED:      3, // no longer working on this time entry (e.g. workday ended)
    DONE:        4  // we have finished this time entry
}

$( init );

function init()
{
    time_entry_state = TIME_ENTRY_STATES.READY;
    init_barcode_handler( receive_barcode_input );             
}

// takes in a raw barcode input and returns a map like
// { unit => unit id, employee => employee id, etc }
function parse_barcode( barcode )
{
    // barcodes are encoded by identifier value pairs
    // in the form of value_identifier_value_identifier
    // ex: u_RGLMP4300J_e_123 designates the unit RGLMP4300J
    // and employee 123 where 123 is a primary key in the db
    var barcode_array = barcode.split( '_' );
    var barcode_data = {
        unit : '',
        barcode_scanner_id: '',
        employee_name : ''
    }

    // interpret barcode array into map
    for( var i = 0; i < barcode_array.length; i += 2 )
    {
        var barcode_subject = barcode_array[i];
        var barcode_value = barcode_array[i+1];

        if( barcode_subject == UNIT_BARCODE_PREFIX )
        {
            barcode_data.unit = barcode_value
        }
        else if( barcode_subject == EMPLOYEE_BARCODE_PREFIX )
        {
            barcode_data.employee_name = barcode_value
        }
        else if( barcode_subject == BARCODE_SCANNER_ID_PREFIX )
        {
            barcode_data.barcode_scanner_id = barcode_value
        }
    }

    return barcode_data
}

function receive_barcode_input( barcode )
{
    console.log("RECEIVED INPUT: " + barcode);
    // if time_entry_state == TIME_ENTRY_STATES["IN_PROGRESS"]
    // {
    //     finish_recording_time( barcode );
    // }

    // parse raw barcode string into a map
    var barcode_data = parse_barcode( barcode );

    $.ajax({
        url:      '/ajax/get_time_entry.php',
        dataType: 'json',
        data:     barcode_data
    }).done( receive_get_time_entry_response )
    .fail( /* TODO ajax error handling */ );
}

function receive_get_time_entry_response( time_entry_record )
{
    if( time_entry_state == TIME_ENTRY_STATES.READY )
    {
        // if this is the first barcode scanned for a new time entry,
        // create new row in time entrytable
        // use barcode data to create partial time entry
        time_entry_state = TIME_ENTRY_STATES.INITIALIZE;
        create_time_entry_row( time_entry_record);
    }
    else
    {
        // otherwise, time entry row exists and we update partially filled
        // out time entry with new data
        var is_row_filled_out = update_time_entry_row( time_entry_record );
    }

    // We have the necessary data to begin tracking time
    if( time_entry_record.unit &&
        time_entry_record.employee_name &&
        time_entry_record.station
      )
    {
        time_entry_state = TIME_ENTRY_STATES.IN_PROGRESS;
        begin_time_entry_timer( time_entry_record );
        $( '#time_entry_' + time_entry_record.time_entry_pk )
            .removeClass( 'table-warning' )
            .addClass( 'table-success' );
    }
}

function begin_time_entry_timer( time_entry )
{
    var now = new Date();
    var duration = now - new Date( time_entry.start_time );
    $( '#time_entry_' + time_entry_pk ).find( '.duration' ).text( duration );
}

// creates a row in the time entry table
function create_time_entry_row( time_entry_data )
{
    var time_entry_pk      = time_entry_data.time_entry_pk;
    var barcode_scanner_id = time_entry_data.barcode_scanner_id;
    var station            = time_entry_data.station;
    var unit               = time_entry_data.unit;
    var employee_name      = time_entry_data.employee_name;
    var start_time         = time_entry_data.start_time;
    var end_time           = time_entry_data.end_time;
    var duration           = time_entry_data.duration;

    if( duration == undefined )
    {
        duration = '';
    }

    var row_element = $( '<tr id="time_entry_' + time_entry_pk + '">' )
        .append( '<td class="station">'       + station       + '</td>' )
        .append( '<td class="unit">'          + unit          + '</td>' )
        .append( '<td class="employee_name">' + employee_name + '</td>' )
        .append( '<td class="start_time">'    + start_time    + '</td>' )
        .append( '<td class="end_time">'      + end_time      + '</td>' )
        .append( '<td class="duration">'      + duration      + '</td>' );

    row_element.height( '37px' )
    row_element.data( 'barcode-scanner-id', barcode_scanner_id );

    if( time_entry_state == TIME_ENTRY_STATES.INITIALIZE )
    {
        row_element.addClass( 'table-warning' );
    }

    $( '#time_entry_table' ).prepend( row_element );
}

function update_time_entry_row( time_entry_record )
{
    var time_entry_pk      = time_entry_record.time_entry_pk;
    var barcode_scanner_id = time_entry_record.barcode_scanner_id;
    var station            = time_entry_record.station;
    var unit               = time_entry_record.unit;
    var employee_name      = time_entry_record.employee_name;
    var start_time         = time_entry_record.start_time;
    var end_time           = time_entry_record.end_time;
    var duration           = time_entry_record.duration;

    if( duration == undefined )
    {
        duration = '';
    }

    var time_entry_row = $( '#time_entry_' + time_entry_pk );
        time_entry_row.find( '.station'       ).text( station       );
        time_entry_row.find( '.unit'          ).text( unit          );
        time_entry_row.find( '.employee_name' ).text( employee_name );
        time_entry_row.find( '.start_time'    ).text( start_time    );
        time_entry_row.find( '.end_time'      ).text( end_time      );
        time_entry_row.find( '.duration'      ).text( duration      );
}
