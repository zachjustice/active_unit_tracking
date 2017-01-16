"use strict";

function display_error( error_container, message )
{
    if( !element_exists( error_container ) || !is_str( message, true ) )
    {
        return null;
    }

    var context    = { message : message };
    var error_html = format_template( '#error_message_template', context );

    if( is_null( error_html ) )
    {
        return null;
    }

    $( error_container ).empty();
    $( error_container ).append( error_html );
}

function format_template( selector, context )
{
    if( !element_exists( selector ) || 
        !is_str( $( selector ).html() ) ||
        !is_object( context ) 
      )
    {
        return null;
    }

    var source   = $( selector ).html();
    var template = Handlebars.compile( source );
    var html     = template( context )

    return html;
}

function element_exists( selector )
{
    return $( selector ).length > 0;
}

function is_null( o )
{
    return o === null;
}

function is_undef( o )
{
    return o === undefined;
}

function is_null_or_undef( o )
{
    return is_null( o ) || is_undef( o ); 
}

function is_object( o )
{
    return typeof o === 'object';
}

// Validates a list of variables as strings.
// If last element is true, allows strings to be empty
// Default behaviour is to fail on empty strings since
// I want only non-empty strings.
//
// Edge case: last element is a boolean for some reason.
//    This can't happen for db results since ajax will fail
//    and db result won't reach this function.
function is_str( ...strings )
{
    // determine if last element in arguments is a boolean
    var allow_empty = false;
    var length = strings.length;

    if( typeof strings[strings.length - 1] === 'boolean' )
    {
        allow_empty = strings[strings.length - 1];
        length--; // last element is not a string
    }

    for( var i = 0; i < length; i++ )
    {
        var str = strings[i];

        // return false if its not a string
        //              if its an empty string
        //              unless we're ok with empty strings
        if( 
            typeof str !== 'string' ||
            ( str.trim().length === 0 && !allow_empty )
          )
        {
            return false;
        }
    }

    return true;
}

// returns the first argument that isn't null or undefined
// Ex: var my_val = coalesce( db_result.name, 'None' )
//     my_val is either the name currently in the db or None
function coalesce( ...args )
{
   for( var i = 0; i < args.length; i++ )
   {
       var arg = args[i];

       if( !is_null_or_undef( arg ) )
       {
           return arg;
       }
   }
}
