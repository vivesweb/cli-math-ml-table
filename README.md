# View data structure in table format with features oriented to detect wrong data in datasets before deep learning process.

Anyway you can to use this class to visualize any type of data. It cannot do any calc, only visualize the data in an understandable way.

You need always clean your data in the dataset before process of deep learning. If you have wrong, empty data, values that can be splitted (as datetimes fields), the model will not work properly. You have to pass quality data. Knowing what structure the data of the dataset has to be able to 'fix' it before the process is essential, and the cli-math-ml-table helps to visualize this data in a simple way to identify it.

To pass the data to this class, you have to use dataset techniques and Data engineering processes before. The class that performs them will be published soon.

## Data engineering support Class in PHP that visualize data in table format to detect errors in input datasets. It will help you visually to get a consistent datasets.

The table incorporates useful elements focused on achieving optimal dataset quality, so certain values have to be analyzed and presented in a specific way.
- Detects types. Align & Color them automatically to get a better visualization values.
- Detects Boolean in cols with only ['1'|'0'|'-'|empty|null] values
- Detects Yes/No in cols with only ['y'|'n'|'yes'|'no'|'-'|empty|null] values
- Detects True/False in cols with only ['t'|'f'|'true'|'false'|'1'|'0'|'-'|empty|null] values
- Detects Ok/Ko in cols with only ['ok'|'ko'|'-'|empty|null] values
- Detects Numeric in cols with only [Number|'na'|'nan'|'-'|empty|null] values
- Autoadjust cols width
- Repeat Header every 20 rows for better visualization
- Color automatically negative numbers in red
- Color automatically Boolean 1, ok, yes & true types in green
- Color automatically Boolean 0, ko, no & false types in red
- Color automatically Top row & first col in Bold for identify headers col & row.
- Color automatically in Bold Ok/Ko type (is important in ML cols datasets to know if the col is Ok or KO).
- Customizable Inverse Headers
- Customizable Text Color in all 1 row, all 1 col or a single cell
- Customizable Background Color in all 1 row, all 1 col or a single cell
- Customizable Text Decoration [bold, underline, strikethrought, ...] in all 1 row, all 1 col or a single cell
- Customizable output format for Yes/No or True/False cols with Raw data, boolean, short or long formats


# Example in real use of the visualization data in format table, in Machine Learning Cleaning data process:
![Screenshot of the Real use of simple table for ML created in Pure PHP](https://github.com/vivesweb/cli-math-ml-table/blob/main/example_cli_math_ml_table_1.png?raw=true)
Important Note: You need to calc the values before the output. The values represented in the image are calculated in other class (soon be published). The table only shows in correct visual format the values given.
 
 # REQUERIMENTS:
 
 - A minimum (minimum, minimum, minimum requeriments is needed). Tested on:
 		
    - Simple Raspberry pi (B +	512MB	700 MHz ARM11) with Raspbian Lite PHP7.3 (i love this gadgets)  :heart_eyes:
   
    - VirtualBox Ubuntu Server 20.04.2 LTS (Focal Fossa) with PHP7.4.3
 
 
  # FILES:
 There are 3 files:
 
 *cli-math-ml-table.class.php* -> **Master class**. This file is the main file that you need to include in your code.
 
 *cli-math-ml-table-cell.class.php* -> **Child class**. This file has operations on individual cells. Is included in cli-math-ml-table.class.php.
 
 *example.php* -> **Code with example use of the class**
 
 
 # INSTALLATION:
 A lot of easy :smiley:. It is written in PURE PHP. Only need to include the files. Tested on basic PHP installation
 
         require_once( 'cli-math-ml-table.class.php' );
 
 # BASIC USAGE:

 - When you have the class created, you can to reuse it changing the format or the values with:
 
         $cli_table->set_table_format( $table_format, $Values );
 
 - 1.- Create an array with the values. First Row & Col will be Headers. cli-math-ml-table will detect the format of each col :smiley::
 
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

 - 2.- Create the Class with the Values and draw the table:

        // simple table
        echo "Default simple table:".PHP_EOL;
        $cli_table = new cli_math_ml_table( $Values );
        $cli_table->draw();

- Result Default simple table:
![Screenshot of the Default simple table for ML created in Pure PHP](https://github.com/vivesweb/cli-math-ml-table/blob/main/example_cli_math_ml_table_2.png?raw=true)
 
 - 3.- Draw the table with Raw input data. This code takes advantage of the previously created class. If it has not been created before, it must be created with $cli_table = new cli_math_ml_table($Values);:

        // simple table with raw values
        $table_format=[];
        $table_format['yes_no_col']['output_format'] = 'raw';
        $table_format['ok_ko_col']['output_format'] = 'raw';
        $table_format['true_false_col']['output_format'] = 'raw';

        echo "Default simple table with RAW values:".PHP_EOL;
        $cli_table->set_table_format( $table_format, $Values );
        $cli_table->draw();

- Result simple table with RAW values:
![Screenshot of the table with Raw Values for ML created in Pure PHP](https://github.com/vivesweb/cli-math-ml-table/blob/main/example_cli_math_ml_table_3.png?raw=true)
 
 - 4.- Draw the table with CUSTOM format. This code takes advantage of the previously created class. If it has not been created before, it must be created with $cli_table = new cli_math_ml_table($Values);:

        // Cols with special format
        $col_special_format = [
            [ 'col_name' => 'Boolean Col', 'text_color' => 'lightwhite', 'text_decoration' => 'bold' , 'background_color' => 'blue']
        ];
        
        // rows with special format
        $row_special_format = [
            [ 'row_name' => '[7]Test Name8', 'text_color' => 'lightyellow', 'text_decoration' => 'double_underline', 'background_color' => 'red']
        ];
        
        // Cell Fields with Special Format
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

- Result Customized table:
![Screenshot of the CUSTOMIZED table for ML created in Pure PHP](https://github.com/vivesweb/cli-math-ml-table/blob/main/example_cli_math_ml_table_4.png?raw=true)

**CONFIG VALUES:**

- indiv_formats: [Array] of individual formats (each cell)

    - row_name: [string] Name of the row to be formatted
    - col_name: [string] Name of the column to be formatted
    - text_color: [string] Colour of the text
    - text_decoration: [string] Decoration of text ['bold', 'italic', 'underline', 'strikethrough', 'double_underline', 'curly_underline', 'blink', 'reverse', 'invisible']. Some values maybe they won't work.
    - background_color: [string] Colour of the background


- col_formats: [Array] of whole cols format

    - col_name: [string] Name of the column to be formatted
    - text_color: [string] Colour of the text
    - text_decoration: [string] Decoration of text ['bold', 'italic', 'underline', 'strikethrough', 'double_underline', 'curly_underline', 'blink', 'reverse', 'invisible']. Some values maybe they won't work.
    - background_color: [string] Colour of the background

- row_formats: [Array] of whole rows format

    - row_name: [string] Name of the row to be formatted
    - text_color: [string] Colour of the text
    - text_decoration: [string] Decoration of text ['bold', 'italic', 'underline', 'strikethrough', 'double_underline', 'curly_underline', 'blink', 'reverse', 'invisible']. Some values maybe they won't work.
    - background_color: [string] Colour of the background


- padding_cells_left: [int] Num of Chars to padd left each cell
- padding_cells_right: [int] Num of Chars to padd right each cell
- margin_left: [int] Num of Chars of margin left table
- margin_right: [int] um of Chars of margin right table
- margin_top: [int] Num of Chars of margin top table
- margin_bottom: [int] Num of Chars of margin bottom table
- reverse_headers: [Boolean] Change inverse colours of headers
- border_style: [string] ['simple'|'single'|'double'|'dobule_single'] Type of borders. ! NOT working for now. Use only 'simple'
- negative_numeric_in_red: [Boolean] Echo negative numeric cols in red if the number is negative
- yes_no_col: [Array] of col yes_no

    - bold: [Boolean] If the column yes_no is Bold or not
    - colored: [Array] of Colours
    
        - color_positive: [string] Colour of 'yes' values
        - color_negative: [string] Colour of 'no' values
        
     - output_format: [string] ['boolean'|'short'|'long'|'raw'|null] will transform in ['1', 'y', 'yes', 'orinal_value_without_formatting']
            ],

- true_false_col: [Array] of col true_false

    - bold: [Boolean] If the column yes_no is Bold or not
    - colored: [Array] of Colours
    
        - color_positive: [string] Colour of 'true' values
        - color_negative: [string] Colour of 'false' values
        
     - output_format: [string] ['boolean'|'short'|'long'|'raw'|null] will transform in ['1', 't', 'true', 'orinal_value_without_formatting']

- booelan_col: [Array] of col boolean

    - bold: [Boolean] If the column yes_no is Bold or not
    - colored: [Array] of Colours
    
        - color_positive: [string] Colour of '1' values
        - color_negative: [string] Colour of '0' values
        
     - output_format: [string] ['raw'|null]

- ok_ko_col: [Array] of col ok_ko

    - bold: [Boolean] If the column ok_ko is Bold or not
    - colored: [Array] of Colours
    
        - color_positive: [string] Colour of 'ok' values
        - color_negative: [string] Colour of 'ko' values
        
     - output_format: [string] ['raw'|null]

**TEXT COLORS**
- 'lightblue', 'lightred', 'lightgreen', 'lightyellow', 'lightblack', 'lightmagenta', 'lightcyan', 'lightwhite', 'blue', 'red', 'green', 'yellow', 'black', 'magenta', 'cyan', 'white', 'orange', 'reset'
* Colour 'orange' if supported by the terminal

**BACKGROUND COLORS**
- 'black', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'light_gray'

**TEXT DECORATION**
- ['bold', 'italic', 'underline', 'strikethrough', 'double_underline', 'curly_underline', 'blink', 'reverse', 'invisible']
* Some values maybe they won't work.
* If 'bold' doesn't work. Tray to use 'light' colours
 
 **Of course. You can use it freely :vulcan_salute::alien:**
 
 By Rafa.
 
 
 @author Rafael Martin Soto
 
 @author {@link http://www.inatica.com/ Inatica}
 
 @blog {@link https://rafamartin10.blogspot.com/ Rafael Martin's Blog}
 
 @since September 2021
 
 @version 1.0.0
 
 @license GNU General Public License v3.0
