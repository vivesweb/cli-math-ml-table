<?php
/** cli-math-ml-table.class.php
 * 
 * Generates a Table with a given values formatting it.
 * 
 * This table is intended for use with Machine Learning in Data Engineering to detect erroneous data,
 * reorganize it, adjust it and clean the datasets before going through the Deep Learning process.
 *
 * 2021-11-14 v.1.0.1
 * Added new col_format => 'max_chars' parameter colum to do a fixed with in column.
 *   Positive numbers larger than size are parsed for column size 5 characters, for example, as '>9999'
 *   Negative numbers larger than size are parsed for column size 5 characters, for example, as '<-999'
 *   The signs '>' & '<' followed by a number are taken into account as number format
 *   Add to data example new col with fixed width to 5 chars
 *
 * 2021-11-07 v.1.0.0
 * First release
 * 
 * @author Rafael Martin Soto
 * @author {@link https://www.inatica.com/ Inatica}
 * @blog {@link https://rafamartin10.blogspot.com/ Blog Rafael Martin Soto}
 * @since September 2021
 * @version 1.0.1
 * @license GNU General Public License v3.0
*/
    
require_once 'cli-math-ml-table-cell.class.php';

class cli_math_ml_table {

    /**
     * Border Characters
     * see: https://unicode-table.com/en/blocks/box-drawing/
     *
     * @var    array
     * @access private
     *
     **/
    private $border_chars = [
        'simple' => [
            'top'          => '-',
            'top-mid'      => '+',
            'top-left'     => '+',
            'top-right'    => '+',
            'bottom'       => '-',
            'bottom-mid'   => '+',
            'bottom-left'  => '+',
            'bottom-right' => '+',
            'left'         => '|',
            'left-mid'     => '+',
            'mid'          => '-',
            'mid-mid'      => '+',
            'right'        => '|',
            'right-mid'    => '+',
            'middle'       => '|'
            ],
        'single' => [
            'right-mid'    => '+',
            'top'          => '─',
            'top-mid'      => '┬',
            'top-left'     => '┌',
            'top-right'    => '┐',
            'bottom'       => '─',
            'bottom-mid'   => '┴',
            'bottom-left'  => '└',
            'bottom-right' => '┘',
            'left'         => '│',
            'left-mid'     => '├',
            'mid'          => '─',
            'mid-mid'      => '┼',
            'right'        => '│',
            'right-mid'    => '┤',
            'middle'       => '│'
            ],
        'double' => [
            'top'          => '═',
            'top-mid'      => '╦',
            'top-left'     => '╔',
            'top-right'    => '╗',
            'bottom'       => '═',
            'bottom-mid'   => '╩',
            'bottom-left'  => '╚',
            'bottom-right' => '╝',
            'left'         => '║',
            'left-mid'     => '╠',
            'mid'          => '═',
            'mid-mid'      => '╬',
            'right'        => '║',
            'right-mid'    => '╣',
            'middle'       => '║'
            ],
        'double_single' => [
            'top'          => '═',
            'top-mid'      => '╤',
            'top-left'     => '╔',
            'top-right'    => '╗',
            'bottom'       => '═',
            'bottom-mid'   => '╧',
            'bottom-left'  => '╚',
            'bottom-right' => '╝',
            'left'         => '║',
            'left-mid'     => '╟',
            'mid'          => '─',
            'mid-mid'      => '┼',
            'right'        => '║',
            'right-mid'    => '╢',
            'middle'       => '│'
            ]
    ]; // Border Chars

    /**
     * colors
     * chr(27).'[1;34m',
     *
     * @var    array
     * @access private
     *
     **/
    private $text_colors = [
        'lightblue'     => '[1;34m',
        'lightred'      => '[1;31m',
        'lightgreen'    => '[1;32m',
        'lightyellow'   => '[1;33m',
        'lightblack'    => '[1;30m',
        'lightmagenta'  => '[1;35m',
        'lightcyan'     => '[1;36m',
        'lightwhite'    => '[1;37m',
        'blue'          => '[0;34m',
        'red'           => '[0;31m',
        'green'         => '[0;32m',
        'yellow'        => '[0;33m',
        'black'         => '[0;30m',
        'magenta'       => '[0;35m',
        'cyan'          => '[0;36m',
        'white'         => '[0;37m',
        'orange'        => '[38;5;214m', // if supported by the terminal
        'reset'         => '[0m',
    ]; // /$text_colors

    private $backgroundColors = [
        'black'      => '[40m',
        'red'        => '[41m',
        'green'      => '[42m',
        'yellow'     => '[43m',
        'blue'       => '[44m',
        'magenta'    => '[45m',
        'cyan'       => '[46m',
        'light_gray' => '[47m',
    ]; // /$backgroundColors

    private $text_decoration = [
        'bold'                  => '[1m',
        'italic'                => '[3m',
        'underline'             => '[4m',
        'strikethrough'         => '[9m',
        'double_underline'      => '[21m',
        'curly_underline'       => '[4:3m',
        'blink'                 => '[5m',
        'reverse'               => '[7m',
        'invisible'             => '[8m'
    ]; // /$text_decoration

    /**
     * table format
     *
     * @var    array
     * @access private
     *
     **/
    private $table_format = null;

    /**
     * Definition of Defaut Table Format values
     *
     * @var    array
     * @access private
     *
     **/
    private $default_table_format = [
        'padding_cells_left' => 1,
        'padding_cells_right' => 1,
        'margin_left' => 1,
        'margin_right' => 0,
        'margin_top' => 1,
        'margin_bottom' => 1,
        'padding_cells_left' => 1,
        'padding_cells_right' => 1,
        'negative_numeric_in_red' => true,
        'border_style' => 'simple', // Style of borders ['simple'|'single'|'double'|'dobule_single']
        'reverse_headers' => false, // Style of headers
        'redraw_header_every_rows' => 20,
        'yes_no_col' =>[
            'bold' => false,
            'colored' =>[
                'color_positive' => 'green',
                'color_negative' => 'red'
                ],
            'output_format' => 'short' // ['boolean'|'short'|'long'|'raw'|null]
        ],
        'true_false_col' =>[
            'bold' => false,
            'colored' =>[
                'color_positive' => 'green',
                'color_negative' => 'red'
                ],
            'output_format' => 'short' // ['boolean'|'short'|'long'|'raw'|null]
        ],
        'boolean_col' =>[
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
        ],
        'indiv_formats' => null,
        'col_formats' => null,
        'row_formats' => null
    ]; // /$default_table_format

    /**
     * arr_alignments
     *
     * @var    array
     * @access private
     *
     **/
    private $arr_alignments = [ 'left'=>STR_PAD_RIGHT, 'right'=>STR_PAD_LEFT, 'center'=>STR_PAD_BOTH ];

    /**
     * First Row is Header
     *
     * @var    boolean
     * @access private
     *
     **/
    private $first_row_is_header = true;

    /**
     * First Col is Header
     *
     * @var    boolean
     * @access private
     *
     **/
    private $first_col_is_header = true;

    /**
     * Table Cells Rows
     *
     * @var    array
     * @access private
     *
     **/
    private $table_cells_rows = null;

    /**
     * Num Rows
     *
     * @var    int
     * @access private
     *
     **/
    private $num_rows = 0;

    /**
     * Num Cols
     *
     * @var    int
     * @access private
     *
     **/
    private $num_cols = 0;

    /**
     * padding_cells_left
     *
     * @var    int
     * @access private
     *
     **/
    private $padding_cells_left = 1;

    /**
     * padding_cells_right
     *
     * @var    int
     * @access private
     *
     **/
    private $padding_cells_right = 1;

    /**
     * Base TOP Row Line Separator
     * Will be a sring basic simple of line separator, as '+--------+---------------+--+---------+'
     *
     * @var    string
     * @access private
     *
     **/
    private $str_top_basic_rw_ln_separator = null;

    /**
     * Base Row Line Separator
     * Will be a sring basic simple of line separator, as '+--------+---------------+--+---------+'
     *
     * @var    string
     * @access private
     *
     **/
    private $str_basic_rw_ln_separator = null;

    /**
     * Base BOTTOM Row Line Separator
     * Will be a sring basic simple of line separator, as '+--------+---------------+--+---------+'
     *
     * @var    string
     * @access private
     *
     **/
    private $str_bottom_basic_rw_ln_separator = null;

    
    /**
     * Constructor
     *
     * @access public
     * @param array $values
     */
    public function __construct( $values=null, $table_format = null ) {
        $this->table_format = $this->default_table_format;
        if( isset($table_format) && !is_null($table_format) ){
            $this->set_table_format( $table_format);
        }
        
        if( !is_null($values) ){
            $this->set_values($values);
        }
    } // /__construct()
    
    /**
     * Set table format
     *
     * @access public
     * @param array $table_format
     * @param array $values
     */
    public function set_table_format( $table_format=null, $values=null ) {
        $this->table_format = $this->default_table_format;

        if( isset($table_format) ){
            if( isset($table_format['border_style']) ){
                $this->table_format['border_style'] = $table_format['border_style'];
            }
            if( isset($table_format['col_formats']) ){
                $this->table_format['col_formats'] = $table_format['col_formats'];
            }
            if( isset($table_format['row_formats']) ){
                $this->table_format['row_formats'] = $table_format['row_formats'];
            }
            if( isset($table_format['indiv_formats']) ){
                $this->table_format['indiv_formats'] = $table_format['indiv_formats'];
            }
            

            if( isset($table_format['reverse_headers']) ){
                $this->table_format['reverse_headers'] = $table_format['reverse_headers'];
            }

            if( isset($table_format['negative_numeric_in_red']) ){
                $this->table_format['negative_numeric_in_red'] = $table_format['negative_numeric_in_red'];
            }

            if( isset($table_format['margin_left']) ){
                $this->table_format['margin_left'] = $table_format['margin_left'];
            }

            if( isset($table_format['margin_right']) ){
                $this->table_format['margin_right'] = $table_format['margin_right'];
            }

            if( isset($table_format['margin_top']) ){
                $this->table_format['margin_top'] = $table_format['margin_top'];
            }

            if( isset($table_format['margin_bottom']) ){
                $this->table_format['margin_bottom'] = $table_format['margin_bottom'];
            }

            if( isset($table_format['border_style']) ){
                $this->table_format['border_style'] = $table_format['border_style'];
            }

            if( isset($table_format['yes_no_col']) ){
                $this->table_format['yes_no_col'] = $table_format['yes_no_col'];
                if( !isset($this->table_format['yes_no_col']['bold']) ){
                    $this->table_format['yes_no_col']['bold'] = $this->default_table_format['yes_no_col']['bold'];
                }
                if( !isset($this->table_format['yes_no_col']['output_format']) ){
                    $this->table_format['output_format']['output_format'] = $this->default_table_format['output_format']['output_format'];
                }
                if( !isset($this->table_format['yes_no_col']['colored']) ){
                    $this->table_format['yes_no_col']['colored'] = $this->default_table_format['yes_no_col']['colored'];
                }
                if( !isset($this->table_format['yes_no_col']['colored']['color_positive']) ){
                    $this->table_format['yes_no_col']['colored']['color_positive'] = $this->default_table_format['yes_no_col']['colored']['color_positive'];
                }
                if( !isset($this->table_format['yes_no_col']['colored']['color_negative']) ){
                    $this->table_format['yes_no_col']['colored']['color_negative'] = $this->default_table_format['yes_no_col']['colored']['color_negative'];
                }
            }

            if( isset($table_format['true_false_col']) ){
                $this->table_format['true_false_col'] = $table_format['true_false_col'];
                if( !isset($this->table_format['true_false_col']['output_format']) ){
                    $this->table_format['true_false_col']['output_format'] = $this->default_table_format['true_false_col']['output_format'];
                }
                if( !isset($this->table_format['true_false_col']['bold']) ){
                    $this->table_format['true_false_col']['bold'] = $this->default_table_format['true_false_col']['bold'];
                }
                if( !isset($this->table_format['true_false_col']['colored']) ){
                    $this->table_format['true_false_col']['colored'] = $this->default_table_format['true_false_col']['colored'];
                }
                if( !isset($this->table_format['true_false_col']['colored']['color_positive']) ){
                    $this->table_format['true_false_col']['colored']['color_positive'] = $this->default_table_format['true_false_col']['colored']['color_positive'];
                }
                if( !isset($this->table_format['true_false_col']['colored']['color_negative']) ){
                    $this->table_format['true_false_col']['colored']['color_negative'] = $this->default_table_format['true_false_col']['colored']['color_negative'];
                }
            }

            if( isset($table_format['boolean_col']) ){
                $this->table_format['boolean_col'] = $table_format['boolean_col'];
                if( !isset($this->table_format['true_false_col']['output_format']) ){
                    $this->table_format['true_false_col']['output_format'] = $this->default_table_format['true_false_col']['output_format'];
                }
                if( !isset($this->table_format['boolean_col']['bold']) ){
                    $this->table_format['boolean_col']['bold'] = $this->default_table_format['boolean_col']['bold'];
                }
                if( !isset($this->table_format['boolean_col']['colored']) ){
                    $this->table_format['boolean_col']['colored'] = $this->default_table_format['boolean_col']['colored'];
                }
                if( !isset($this->table_format['boolean_col']['colored']['color_positive']) ){
                    $this->table_format['boolean_col']['colored']['color_positive'] = $this->default_table_format['boolean_col']['colored']['color_positive'];
                }
                if( !isset($this->table_format['boolean_col']['colored']['color_negative']) ){
                    $this->table_format['boolean_col']['colored']['color_negative'] = $this->default_table_format['boolean_col']['colored']['color_negative'];
                }
            }

            if( isset($table_format['ok_ko_col']) ){
                $this->table_format['ok_ko_col'] = $table_format['ok_ko_col'];
                if( !isset($this->table_format['ok_ko_col']['bold']) ){
                    $this->table_format['ok_ko_col']['bold'] = $this->default_table_format['ok_ko_col']['bold'];
                }
                if( !isset($this->table_format['ok_ko_col']['output_format']) ){
                    $this->table_format['ok_ko_col']['output_format'] = $this->default_table_format['ok_ko_col']['output_format'];
                }
                if( !isset($this->table_format['ok_ko_col']['colored']) ){
                    $this->table_format['ok_ko_col']['colored'] = $this->default_table_format['ok_ko_col']['colored'];
                }
                if( !isset($this->table_format['ok_ko_col']['colored']['color_positive']) ){
                    $this->table_format['ok_ko_col']['colored']['color_positive'] = $this->default_table_format['ok_ko_col']['colored']['color_positive'];
                }
                if( !isset($this->table_format['ok_ko_col']['colored']['color_negative']) ){
                    $this->table_format['ok_ko_col']['colored']['color_negative'] = $this->default_table_format['ok_ko_col']['colored']['color_negative'];
                }
            }

            if( isset($table_format['padding_cells_left']) ){
                $this->table_format['padding_cells_left'] = $table_format['padding_cells_left'];
            }

            if( isset($table_format['padding_cells_right']) ){
                $this->table_format['padding_cells_right'] = $table_format['padding_cells_right'];
            }
        }
        
        if( !is_null($values) ){
            $this->set_values($values);
        }
    } // /set_table_format()

    /**
     * Get if First Row is HEADER
     *
     * @access public
     * @return boolean $first_row_is_header
     */
    public function first_row_is_header(  ) {
        return $this->first_row_is_header;
    } // /first_row_is_header()


    /**
     * Set if First Row is HEADER
     *
     * @access public
     * @param boolean $first_row_is_header
     */
    public function set_first_row_is_header( $first_row_is_header=true ) {
        $this->first_row_is_header = $first_row_is_header;
    } // /set_first_row_is_header()
    
    
    /**
     * Get if First Col is HEADER
     *
     * @access public
     * @return boolean $first_col_is_header
     */
    public function first_col_is_header(  ) {
        return $this->first_col_is_header;
    } // /first_col_is_header()


    /**
     * Set if First Col is HEADER
     *
     * @access public
     * @param boolean $first_col_is_header
     */
    public function set_first_col_is_header( $first_col_is_header=true ) {
        $this->first_col_is_header = $first_col_is_header;
    } // /set_first_col_is_header()


    /**
     * Set Value of Cells
     *
     * @access public
     * @param object $values
     */
    public function set_values( $values ) {
        // change all null values to 'null'
        $num_rows = count( $values );
        $num_cols = count( $values[0] );
        for($i=0;$i<$num_rows;$i++){
            for($j=0;$j<$num_cols;$j++){
                if( is_null($values[$i][$j]) ){
                    $values[$i][$j] = 'null';
                } else if( !is_null($this->table_format['col_formats']) ){
					// Search Col formats for resize numbers to max_size if needed
					
					foreach( $this->table_format['col_formats'] as $col_format ){
						if( trim( $col_format['col_name'] ) == trim( $values[0][$j] ) && isset($col_format['max_chars']) ){
							$values[$i][$j] = $this->fit_number_max_chars(trim($values[$i][$j]), $col_format['max_chars']);
							break;
						}
					}// /foreach $this->col_formats
            	} // /if exists col_formats
			} // /for $j
        } // /for $i

        $this->table_cells_rows = [];
        
        foreach($values as $row){
            $row_cells = [];
            foreach($row as $cell_value){
                $row_cells[] = new cli_math_ml_table_cell( $cell_value );
            }
            $this->table_cells_rows[] = $row_cells;
        }

        
        $this->num_rows = count( $this->table_cells_rows );

        $this->num_cols = count( $this->table_cells_rows[0] );

        // always after set num_rows & num_cols
        $this->set_type_cols(  );

        $this->auto_adjust_cols_width( );

        // always after auto_adjust_cols_width()
        $this->set_basic_rw_ln_separator( );

        // Freemem()
        unset( $row_cells );
        unset( $cell_value );
        unset( $row );
        unset( $i );
        unset( $j );
        unset( $num_cols );
        unset( $num_rows );
    } // /set_values()


    /**
     * Set basic row simple line separator, as '+--------+---------------+--+---------+'
     * @access private
     */
    private function set_basic_rw_ln_separator(  ) {
        $borders = $this->border_chars[ $this->table_format['border_style'] ];

        $this->str_basic_rw_ln_separator = $borders['left-mid']; // '╠'
        $this->str_top_basic_rw_ln_separator = $borders['top-left']; // '╔'
        $this->str_top_basic_rw_ln_separator = mb_convert_encoding($borders['top-left'], "UTF-8"); // '╔'
        
        $this->str_bottom_basic_rw_ln_separator = $borders['bottom-left']; // '╚'

        $first_row = $this->table_cells_rows[0];
        foreach($first_row as $cell){
            $cell_width = $cell->width();

            $this->str_basic_rw_ln_separator .= str_pad('', $cell_width, $borders['mid']); // '═'
            $this->str_top_basic_rw_ln_separator .= str_pad('', $cell_width, $borders['top']); // '═'
            $this->str_bottom_basic_rw_ln_separator .= str_pad('', $cell_width, $borders['bottom']); // '═'

            $this->str_basic_rw_ln_separator .= $borders['mid-mid']; // '╬'
            $this->str_top_basic_rw_ln_separator .= $borders['top-mid']; // '╦'
            $this->str_bottom_basic_rw_ln_separator .= $borders['bottom-mid']; // '╩'
        }

        $str_len = strlen($this->str_basic_rw_ln_separator)-1;
        
        $this->str_basic_rw_ln_separator[ $str_len ] = $borders['right-mid']; // '╣'
        $this->str_top_basic_rw_ln_separator[ $str_len ] = $borders['top-right']; // '╗'
        $this->str_bottom_basic_rw_ln_separator[ $str_len ] = $borders['bottom-right']; // '╝'

        // Freemem()
        unset( $str_len );
        unset( $cell );
        unset( $first_row );
        unset( $borders );
    } // /set_basic_rw_ln_separator()

    

    /**
     * Draw Table
     *
     * @access public
     */
    public function draw( ) {
        echo $this->get_formatted_table();
    } // /draw()

    

    /**
     * Get formatted Table
     *
     * @access public
     * @return array $output
     */
    public function get_formatted_table( ) {
        $str_margin_left = str_pad( '', $this->table_format['margin_left'], ' ');
        $str_margin_right = str_pad( '', $this->table_format['margin_right'], ' ');
        $output = [];

        for($i=0;$i<$this->table_format['margin_top'];$i++){
            $output[] = PHP_EOL;
        }

        $output[] = $str_margin_left.$this->str_top_basic_rw_ln_separator.$str_margin_right.PHP_EOL; // first line separator
        
        $i = 0;
        foreach($this->table_cells_rows as $row){
            if(!(($i++)%($this->table_format['redraw_header_every_rows']+1)) && $i>1){
                // Repeat Header every redraw_header_every_rows
                $output[] = $str_margin_left.$this->get_formatted_row( $this->table_cells_rows[0]).$str_margin_right.PHP_EOL;
                $output[] = $str_margin_left.$this->str_basic_rw_ln_separator.$str_margin_right.PHP_EOL; // underline separator
            }

            $output[] = $str_margin_left.$this->get_formatted_row( $row ).$str_margin_right.PHP_EOL;
            $output[] = $str_margin_left.$this->str_basic_rw_ln_separator.$str_margin_right.PHP_EOL; // underline separator
        }

        $output[ count($output)-1 ] = $str_margin_left.$this->str_bottom_basic_rw_ln_separator.$str_margin_right.PHP_EOL;

        for($i=0;$i<$this->table_format['margin_bottom'];$i++){
            $output[] = PHP_EOL;
        }

        unset( $str_margin_left );
        unset( $row );
        unset( $i );
        unset( $str_margin_left );
        unset( $str_margin_right );

        return implode('', $output);
    } // get_formatted_table()
    

    /**
     * Get a Formatted row
     *
     * @param array $row
     * @access private
     * @return array $str_output
     */
    private function get_formatted_row( $row ) {
        $border_chars = $this->border_chars[ $this->table_format['border_style'] ];
        $separation = $border_chars['middle'];
        $str_output = $border_chars['left'];
        
        foreach($row as $cell){
            $str_output .= $cell->formatted_color_value( );
            $str_output .= $separation; // Column Separator
        }

        $str_output[ strlen($str_output)-1 ] = $border_chars['right'];
        
        // Freemem()
        unset( $border_chars );
        unset( $separation );
        unset( $cell );

        return $str_output;
    } // /get_formatted_row()


    /**
     * Set col width
     *
     * @param int $id_col
     * @param int $width
     * @access public
     */
    public function set_col_width( $id_col, $width ) {
        foreach($this->table_cells_rows as $row){
            $row[$id_col]->set_width( $width );
        }

        // Freemem()
        unset( $row );
    } // set_col_width()


    /**
     * Set border style
     *
     * @param string $border_style
     * @access public
     */
    public function set_border_style( $border_style ) {
        $this->table_format['border_style'] =  $border_style;
    } // set_border_style()

    
    /**
     * Set col width
     *
     * @param int $id_col
     * @access public
     * @return int $max_width
     */
    public function get_max_col_width( $id_col ) {
        $max_width = 0;
        foreach($this->table_cells_rows as $row){
            if($row[$id_col]->width( ) > $max_width){
                $max_width = $row[$id_col]->width( );
            }
        }

        // Freemem()
        unset( $row );

        return $max_width;
    } // /get_max_col_width()


    /**
     * Auto Adjust cols width
     *
     * @access public
     */
    public function auto_adjust_cols_width(  ) {
        for($i=0;$i<$this->num_cols;$i++){
            $this->set_col_width( $i, $this->get_max_col_width( $i ) );
        }

        // Freemem()
        unset( $i );
    } // /auto_adjust_cols_width()


    /**
     * Set Type of Cols (string, yes_no, true_false, ok_ko, number) for alignments
     *
     * @access public
     */
    public function set_type_cols(  ) {
        for($i=0;$i<$this->num_cols;$i++){
            $type_col = $this->get_type_col( $i );
            $this->set_align_color_col( $i, $type_col );
        }

        // Freemem()
        unset( $i );
        unset( $type_col );
    } // /set_type_cols()

    

    /**
     * Set alignment of col according the type (num, text, .....)
     * Set color according headers
     *
     * @access public
     */
    private function set_align_color_col( $id_col, $type_col ) {
        $i = 0;

        // SetColNames
        $arr_col_names = [];
        foreach($this->table_cells_rows[0] as $col){
            $arr_col_names[] = $col->cleaned_value( );
        }

        // SetRowNames
        $arr_row_names = [];
        foreach($this->table_cells_rows as $row){
            $arr_row_names[] = $row[0]->cleaned_value( );
        }
        
        foreach($this->table_cells_rows as $row){
            $row[$id_col]->set_padding_left( $this->table_format['padding_cells_left'] );
            $row[$id_col]->set_padding_right( $this->table_format['padding_cells_right'] );

            if( $i++ == 0 && $this->first_row_is_header ){
                if( isset($this->table_format['reverse_headers']) && $this->table_format['reverse_headers']){
                    $row[$id_col]->set_text_decoration( $this->text_decoration['reverse'] );
                } else {
                    $row[$id_col]->set_color( $this->text_colors['lightwhite'] );
                    $row[$id_col]->set_text_decoration( $this->text_decoration['bold'] );
                }
                continue;
            }

            if( $id_col == 0 && $this->first_col_is_header ){
                if( isset($this->table_format['reverse_headers']) && $this->table_format['reverse_headers']){
                    $row[$id_col]->set_text_decoration( $this->text_decoration['reverse'] );
                } else {
                    $row[$id_col]->set_color( $this->text_colors['lightwhite']);
                }
            }

            $apply_auto_color = true;

            if( !is_null($this->table_format['col_formats']) ){
                // Search Col formats
                
                $found = false;

                foreach( $this->table_format['col_formats'] as $col_format ){
                    if( $row[0]->cleaned_value( $col_format['col_name'] ) == $arr_col_names[$id_col] ){
                        $found = true;
                        break;
                    }
                }// /foreach $this->indiv_formats

                if( $found ){
                    if( isset($col_format['text_color']) ){
                        $row[$id_col]->set_color( $this->text_colors[$col_format['text_color']] );
                        $apply_auto_color = false;
                    }
                    if( isset($col_format['text_decoration']) ){
                        $row[$id_col]->set_text_decoration( $this->text_decoration[$col_format['text_decoration']] );
                        $apply_auto_color = false;
                    }
                    if( isset($col_format['background_color']) ){
                        $row[$id_col]->set_background_color( $this->backgroundColors[$col_format['background_color']] );
                        $apply_auto_color = false;
                    }
                }
            } // if set $this->col_format

            if( !is_null($this->table_format['row_formats']) ){
                // Search Row formats
                
                $found = false;

                foreach( $this->table_format['row_formats'] as $row_format ){
                    if( $row[0]->cleaned_value( $row_format['row_name'] ) == $row[0]->cleaned_value( ) ){
                        $found = true;
                        break;
                    }
                }// /foreach $row_formats

                if( $found ){
                    if( isset($row_format['text_color']) ){
                        $row[$id_col]->set_color( $this->text_colors[$row_format['text_color']] );
                        $apply_auto_color = false;
                    }
                    if( isset($row_format['text_decoration']) ){
                        $row[$id_col]->set_text_decoration( $this->text_decoration[$row_format['text_decoration']] );
                        $apply_auto_color = false;
                    }
                    if( isset($row_format['background_color']) ){
                        $row[$id_col]->set_background_color( $this->backgroundColors[$row_format['background_color']] );
                        $apply_auto_color = false;
                    }
                }
            } // if set $row_format

            if( !is_null($this->table_format['indiv_formats']) ){
                // Search individual formats
                
                $found = false;

                foreach( $this->table_format['indiv_formats'] as $indiv_format ){
                    if( $row[0]->cleaned_value( $indiv_format['row_name'] ) == $row[0]->cleaned_value( ) && 
                        $row[0]->cleaned_value( $indiv_format['col_name'] ) == $arr_col_names[$id_col] ){
                        $found = true;
                        break;
                    }
                }// /foreach $indiv_formats

                if( $found ){
                    if( isset($indiv_format['text_color']) ){
                        $row[$id_col]->set_color( $this->text_colors[$indiv_format['text_color']] );
                        $apply_auto_color = false;
                    }
                    if( isset($indiv_format['text_decoration']) ){
                        $row[$id_col]->set_text_decoration( $this->text_decoration[$indiv_format['text_decoration']] );
                        $apply_auto_color = false;
                    }
                    if( isset($indiv_format['background_color']) ){
                        $row[$id_col]->set_background_color( $this->backgroundColors[$indiv_format['background_color']] );
                        $apply_auto_color = false;
                    }
                }
            } // if set $this->indiv_formats

            switch( $type_col ){
                case 'is_numeric':      $row[$id_col]->align( 'right' );
                                        if( isset($this->table_format['negative_numeric_in_red']) && 
                                            $this->table_format['negative_numeric_in_red'] &&
                                            (
                                                (int)$row[$id_col]->cleaned_value( ) < 0 ||
                                                (double)$row[$id_col]->cleaned_value( ) < 0

                                            )
                                        ){
                                            $row[$id_col]->set_color($this->text_colors['red']);
                                        }
                                        break;
                case 'is_boolean':      if( isset($this->table_format['boolean_col']) ){
                                            $table_col_format =$this->table_format['boolean_col'];
                                            $table_col_output_format = ((isset($table_col_format['output_format']))?$table_col_format['output_format']:'short');

                                            if( isset($table_col_format['colored'])  && $apply_auto_color){
                                                if(  (int)$row[$id_col]->cleaned_value( ) == 1 || (double)$row[$id_col]->cleaned_value( ) == 1 ){
                                                    $color = $table_col_format['colored']['color_positive'];
                                                } else if(  !($row[$id_col]->is_blank() || $row[$id_col]->is_dash()) && $row[$id_col]->is_numeric() && 
                                                            ((int)$row[$id_col]->cleaned_value( ) == 0 || (double)$row[$id_col]->cleaned_value( ) == 0)
                                                         ){
                                                    $color = $table_col_format['colored']['color_negative'];
                                                } else {
                                                    $color = null;
                                                }

                                                if( !is_null($color) ){
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                }
                                            }

                                            if( isset($table_col_format['bold']) && $table_col_format['bold'] == true && $apply_auto_color ){
                                                $row[$id_col]->set_text_decoration( $this->text_decoration['bold']);
                                            }
                                            
                                            $row[$id_col]->set_format_yes_no($table_col_output_format);
                                        }
                                        
                                        $row[$id_col]->align( 'center' );
                                        break;

                case 'is_yes_no':       if( isset($this->table_format['yes_no_col']) ){
                                            $table_col_format =$this->table_format['yes_no_col'];
                                            $table_col_output_format = ((isset($table_col_format['output_format']))?$table_col_format['output_format']:'short');

                                            if( isset($table_col_format['colored'])  && $apply_auto_color){
                                                if( $row[$id_col]->is_yes() ){
                                                    $color = $table_col_format['colored']['color_positive'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                } else if( $row[$id_col]->is_no() ){
                                                    $color = $table_col_format['colored']['color_negative'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                }
                                            }

                                            if( isset($table_col_format['bold']) && $table_col_format['bold'] == true && $apply_auto_color ){
                                                $row[$id_col]->set_text_decoration( $this->text_decoration['bold']);
                                            }
                                            
                                            $row[$id_col]->set_format_yes_no($table_col_output_format);
                                        }
                    
                                        $row[$id_col]->align( 'center' );
                                        break;
                case 'is_ok_ko':        if( isset($this->table_format['ok_ko_col']) ){
                                            $table_col_format =$this->table_format['ok_ko_col'];
                                            
                                            if( isset($table_col_format['colored']) && $apply_auto_color){
                                                if( $row[$id_col]->is_ok() ){
                                                    $color = $table_col_format['colored']['color_positive'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                } else if( $row[$id_col]->is_ko() ){
                                                    $color = $table_col_format['colored']['color_negative'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                }
                                            }

                                            if( isset($table_col_format['bold']) && $table_col_format['bold'] == true  && $apply_auto_color ){
                                                $row[$id_col]->set_text_decoration( $this->text_decoration['bold']);
                                            }
                                            
                
                                        }
                                        $row[$id_col]->align( 'center' );
                                        break;
                case 'is_true_false':   if( isset($this->table_format['true_false_col']) ){
                                            $table_col_format =$this->table_format['true_false_col'];
                                            $table_col_output_format = ((isset($table_col_format['output_format']))?$table_col_format['output_format']:'short');

                                            if( isset($table_col_format['colored'])  && $apply_auto_color ){
                                                if( $row[$id_col]->is_true() ){
                                                    $color = $table_col_format['colored']['color_positive'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                } else if( $row[$id_col]->is_false() ){
                                                    $color = $table_col_format['colored']['color_negative'];
                                                    $row[$id_col]->set_color($this->text_colors[$color]);
                                                }
                                            }

                                            if( isset($table_col_format['bold']) && $table_col_format['bold'] == true  && $apply_auto_color ){
                                                $row[$id_col]->set_text_decoration( $this->text_decoration['bold']);
                                            }
                                            
                                            $row[$id_col]->set_format_true_false($table_col_output_format);
                                        }

                                        $row[$id_col]->align( 'center' );
                                        break;

                default:                $row[$id_col]->align( 'left' );
                                        break;
            }
        }

        // Freemem()
        unset( $row );
        unset( $table_col_output_format );
        unset( $table_col_format );
        unset( $apply_auto_color );
        unset( $color );
        unset( $found );
        unset( $arr_col_names );
        unset( $arr_row_names );
        unset( $col_format );
        unset( $indiv_format );
        unset( $i );
    } // /set_align_color_col()



	/**
	 * Cut the number to fit it in max_chars
	 * 
	 * @param double $number
	 * @param int $max_chars
	 * @return string $number_pigeonholed
	 */

	private function fit_number_max_chars( $number, $max_chars ){
		$max_number_positive	= pow(10, $max_chars) - 1; // Give something like 999999
		$max_number_negative	= -(pow(10, $max_chars-1) - 1); // Give something like -99999. One char is for '-' sign
		$str_number 			= (string)$number;
		$length_number 			= strlen($str_number);

		if( $length_number <= $max_chars || !is_numeric($number) ){
			$number_pigeonholed = $str_number;
		} else if( $number < $max_number_negative ){
			$number_pigeonholed = '<'.((int)($max_number_negative/10));
		} else if( $number > $max_number_positive ){
			$number_pigeonholed = '>'.((int)($max_number_positive/10));
		} else {
			list($whole, $decimal) = explode('.', $str_number);
			$length_whole = strlen( $whole );
			if( $length_whole >= $max_chars ){
				$whole = substr($whole, -$max_chars);
				$length_whole = strlen( $whole );
			}
			$number_pigeonholed = $whole;
			if( !is_int($number) && $length_whole < $max_chars-1 ){
				$remain_chars = $max_chars - $length_whole - strlen('.');
				$cut_decimal = substr($decimal, 0, $remain_chars);
				$number_pigeonholed .= '.'.$cut_decimal;
			}
		}

		unset( $str_number );
		unset( $length_number );
		unset( $remain_chars );
		unset( $length_whole );
		unset( $cut_decimal );
		unset( $whole );
		unset( $decimal );

		return $number_pigeonholed;
	} // /fit_number_max_chars()
	
	

    /**
     * Get type of col
     *
     * @param int $id_col
     * @access private
     */
    private function get_type_col( $id_col ) {
        $is_string      = true;
        $is_ok_ko       = true;
        $is_yes_no      = true;
        $is_true_false  = true;
        $is_numeric     = true;
        $is_boolean     = false;

        $i = 0;
        foreach($this->table_cells_rows as $row){
            if( $i++ == 0 && $this->first_row_is_header ){
                continue;
            }
            if( $row[$id_col]->is_ok_ko() ){
                $is_numeric = $is_yes_no = $is_true_false = false;
            } else if( $row[$id_col]->is_yes_no() ){
                $is_numeric = $is_ok_ko = $is_true_false = false;
            } else if( $row[$id_col]->is_true_false() ){
                if( !$row[$id_col]->is_numeric() ){ // Can be 0|1
                    $is_numeric = false;
                }
                $is_yes_no = $is_ok_ko = false;
            } else if( $row[$id_col]->is_numeric() ){
                $is_true_false = $is_ok_ko = $is_yes_no = false;
            } else if( !($row[$id_col]->is_blank() || $row[$id_col]->is_dash()) ){
                // is other string
                $is_yes_no = $is_ok_ko = $is_numeric = $is_true_false = false;
            }
        }

        if( $is_numeric && $is_true_false){
            $is_true_false  = false;
            $is_numeric     = false;
            $is_boolean     = true;
        }

       
        
        if( $is_true_false ){
            $return_value = 'is_true_false';
        } else if( $is_yes_no ){
            $return_value = 'is_yes_no';
        } else if( $is_ok_ko ){
            $return_value = 'is_ok_ko';
        } else if( $is_numeric ){
            $return_value = 'is_numeric';
        } else  if( $is_boolean ){
            $return_value = 'is_boolean';
        } else {
            $return_value = 'is_string';
        }

    unset( $is_string );
    unset( $is_ok_ko );
    unset( $is_yes_no );
    unset( $is_true_false );
    unset( $is_numeric );
    unset( $is_boolean );
    unset( $row );
    unset( $i );

    return $return_value;
    } // /get_type_col()
} // /cli_math_ml_table class
?>