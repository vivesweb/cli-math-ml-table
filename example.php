<?php
/** cli-math-ml-table example
 * 
 * @author Rafael Martin Soto
 * @author {@link https://www.inatica.com/ Inatica}
 * @blog {@link https://rafamartin10.blogspot.com/ Blog Rafael Martin Soto}
 * @since September 2021
 * @version 1.0.0
 * @license GNU General Public License v3.0
*/

require_once 'cli-math-ml-table.class.php';



// Table Values.
//    * First row is 'Col Names'
//    * First Col is 'Row Names'
$Values = [ 
                ['Field Name',           'Numeric Col',  'String Col',  'Yes No Col', 'True False Col',  'Ok Ko Col', 'Boolean Col'],
                ['[0]Test Name1      ',  '10',           'Test 1',      'yes',        'true',            'ok',        '1'],
                ['[1]Test Name2',        '-110',         'test 2',      'no',         'false',           'ko',        '1'],
                ['[2]Test Name3',        '-11023.2',     'null',        'y',          't',               'KO',        '0'],
                ['[3]Test Name4',        '-',            '-',           'n',          'f',               'OK',        '1'],
                ['[4]Test Name5',        '',             '',            '',           '',                '',          ''],
                ['[5]Test Name6',        '11023.2',      'null',        '-',          '0',               'oK',        '1'],
                ['[6]Test Name7',        'na',           'na',          '-',          '-',               'kO',        '0'],
                ['[7]Test Name8',        'nan',          'nan',         '-',          '1',               '-',         '-'],
                ['[8]Test Name9',        'null',         'null',        'null',       'null',            'null',      'null'],
                ['[9]Test Name10      ', '10   ',        'Test 1   ',   'yes   ',     'true   ',         'ok   ',     '1   '],
                ['[10]Test Name11',       null,           null,         null,         null,              null,        null],
            ];

            
// Cols with special format
$col_special_format = [
    [ 'col_name' => 'Boolean Col', 'text_color' => 'lightwhite', 'text_decoration' => 'bold' , 'background_color' => 'blue']
];

// rows with special format
$row_special_format = [
    [ 'row_name' => '[7]Test Name8', 'text_color' => 'lightyellow', 'text_decoration' => 'double_underline', 'background_color' => 'red']
];

// Fields with Special Format
$fields_special_format = [
    [ 'row_name' => '[2]Test Name3', 'col_name' => 'Numeric Col', 'text_color' => 'orange', 'text_decoration' => 'bold' ],
    [ 'row_name' => '[1]Test Name2', 'col_name' => 'String Col', 'text_color' => 'lightwhite', 'text_decoration' => 'bold' ],
    [ 'row_name' => '[2]Test Name3', 'col_name' => 'True False Col', 'text_color' => 'blue',
      'text_decoration' => 'strikethrough', 'background_color' => 'yellow']
];

// Table format with all possibilities example
// In this example we change:
// - 'indiv_formats'
// - 'col_formats'
// - 'row_formats'
// - 'padding_cells_left'
// - 'padding_cells_right'
// - 'margin_left'
// - 'margin_right'
// - 'margin_top'
// - 'margin_bottom'
// - 'reverse_headers'
$default_table_format = [
    'indiv_formats' => $fields_special_format,
    'col_formats' => $col_special_format,
    'row_formats' => $row_special_format,
    'padding_cells_left' => 2,
    'padding_cells_right' => 2,
    'margin_left' => 10,
    'margin_right' => 2,
    'margin_top' => 2,
    'margin_bottom' => 2,
    'reverse_headers' => true,
    'border_style' => 'simple', // Style of borders ['simple'|'single'|'double'|'dobule_single'] ! NOT working for now. Use only 'simple'
    'negative_numeric_in_red' => true,
    'yes_no_col' =>[
        'bold' => false,
        'colored' =>[
            'color_positive' => 'green',
            'color_negative' => 'red'
            ],
        'output_format' => 'short' // ['boolean'|'short'|'long'|'raw'|null] will transform in ['1', 'y', 'yes', 'orinal_value_without_formatting']
    ],
    'true_false_col' =>[
        'bold' => false,
        'colored' =>[
            'color_positive' => 'green',
            'color_negative' => 'red'
            ],
        'output_format' => 'short' // ['boolean'|'short'|'long'|'raw'|null] will transform in ['1', 't', 'true', 'orinal_value_without_formatting']
    ],
    'booelan_col' =>[
        'bold' => false,
        'colored' =>[
            'color_positive' => 'green',
            'color_negative' => 'red'
            ],
        'output_format' => 'raw' // ['raw'|null]
    ],
    'ok_ko_col' =>[
        'bold' => true,
        'colored' =>[
            'color_positive' => 'lightgreen',
            'color_negative' => 'lightred'
            ],
         'output_format' => 'raw' // ['raw'|null]
    ]
];

// simple table
echo "Default simple table:".PHP_EOL;
$cli_table = new cli_math_ml_table( $Values );
$cli_table->draw();

// simple table with raw values
$table_format=[];
$table_format['yes_no_col']['output_format'] = 'raw';
$table_format['ok_ko_col']['output_format'] = 'raw';
$table_format['true_false_col']['output_format'] = 'raw';

echo "Default simple table with RAW values:".PHP_EOL;
$cli_table->set_table_format( $table_format, $Values );
$cli_table->draw();

// Customized table
echo "Customized table:".PHP_EOL;
$cli_table->set_table_format( $default_table_format, $Values );
$cli_table->draw();

// Free mem()
unset( $cli_table );
unset( $Values );
unset( $default_table_format );
unset( $table_format );
unset( $fields_special_format );
unset( $col_special_format );
unset( $row_special_format );
?>