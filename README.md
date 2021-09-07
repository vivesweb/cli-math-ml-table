# cli-math-ml-table
PHP-CLI math table generation for helps in Machine Learning input features cleaning process. You can use this table to visualize any type of data, but incorporates features intended for the use of dataset cleansing.

## Data engineering support Class in PHP that visualize data in table format to detect errors in input datasets. It will help you visually to get a consistent datasets.

The table incorporates useful elements focused on achieving optimal dataset quality, so certain values have to be analyzed and presented in a specific way.
- Detects types. Align & Color them automatically to get a better visualization values.
- Detects Boolean in cols with only ['1'|'0'|empty|null] values
- Detects Yes/No in cols with only ['y'|'n'|'yes'|'no'|empty|null] values
- Detects True/False in cols with only ['t'|'f'|'true'|'false'|'1'|'0'|empty|null] values
- Detects Ok/Ko in cols with only ['ok'|'ko'|empty|null] values
- Detects Numeric in cols with only [Number|'na'|'nan'|empty|null] values
- Color automatically negative numbers in red
- Color automatically Boolean 1, ok, yes & true types in green
- Color automatically Boolean 0, ko, no & false types in red
- Color automatically Top row & first col in Bold for identify headers col & row.
- Color automatically in Bold Ok/Ko type (is important in ML cols datasets to know if the col is Ok or KO).
- Customizable Inverse Headers
- Customizable Text Color in all 1 row, all 1 col or a single cell
- Customizable Background Color in all 1 row, all 1 col or a single cell
- Customizable Text Decoration [bold, underline, strikethrought, ...] in all 1 row, all 1 col or a single cell
- Customizable output format for Yes/No or True/False cols with:
-       raw => Original Value
-       boolean=> ['1'|'0']
-       short=> ['y'|'n'], ['t'|'f']
-       long=> ['yes'|'no'], ['true'|'false']
 
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
 
 - Create a array with the values. First Row & Col will be Headers:
 
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
 
 - Create the Class with the Values and draw the table:

        // simple table
        echo "Default simple table:".PHP_EOL;
        $cli_table = new cli_math_ml_table( $Values );
        $cli_table->draw();

- Result Default simple table:
![Screenshot of the Default simple table for ML created in Pure PHP](https://github.com/vivesweb/cli-math-ml-table/blob/main/example_cli_math_ml_table_2.png?raw=true)

**TEST:**

 
 **Of course. You can use it freely :vulcan_salute::alien:**
 
 By Rafa.
 
 
 @author Rafael Martin Soto
 
 @author {@link http://www.inatica.com/ Inatica}
 
 @blog {@link https://rafamartin10.blogspot.com/ Rafael Martin's Blog}
 
 @since September 2021
 
 @version 1.0.0
 
 @license GNU General Public License v3.0
