<?php

/** cli-math-ml-table-cell.class.php
 * 
 * Class of individual cell
 * 
 * @author Rafael Martin Soto
 * @author {@link https://www.inatica.com/ Inatica}
 * @blog {@link https://rafamartin10.blogspot.com/ Blog Rafael Martin Soto}
 * @since September 2021
 * @version 1.0.1
 * @license GNU General Public License v3.0
*/

class cli_math_ml_table_cell {
    /**
     * arr_alignments
     *
     * @var    array
     * @access private
     *
     **/
    private $arr_alignments = [ 'left'=>STR_PAD_RIGHT, 'right'=>STR_PAD_LEFT, 'center'=>STR_PAD_BOTH ];

    /**
     * Cell Value
     *
     * @var    object
     * @access private
     *
     **/
    private $value = null;

    /**
     * Cell Raw Value
     *
     * @var    object
     * @access private
     *
     **/
    private $raw_value = null;

    /**
     * Cell Formatted Value
     *
     * @var    string
     * @access private
     *
     **/
    private $formatted_value = '';

    /**
     * Cell Width
     *
     * @var    int
     * @access private
     *
     **/
    private $width = null;

    /**
     * Text Color
     *
     * @var    string
     * @access private
     *
     **/
    private $text_color = null;

    /**
     * Background Color
     *
     * @var    string
     * @access private
     *
     **/
    private $background_color = null;

    /**
     * TextDecoration
     *
     * @var    string
     * @access private
     *
     **/
    private $text_decoration = null;

    /**
     * Cell Padding Left
     *
     * @var    int
     * @access private
     *
     **/
    private $padding_left = 1;

    /**
     * Cell Padding Right
     *
     * @var    int
     * @access private
     *
     **/
    private $padding_right = 1;

    /**
     * Alignment of cell
     *
     * @var    string
     * @access private
     *
     **/
    private $align = 'left'; // ['left'|'right'|'center']

    /**
     * Value is OK_KO
     *
     * @var    object
     * @access private
     *
     **/
    private $is_ok_ko = null;

    /**
     * Value is BLANK ('')
     *
     * @var    object
     * @access private
     *
     **/
    private $is_blank = null;

    /**
     * Value is DASH ('-')
     *
     * @var    object
     * @access private
     *
     **/
    private $is_dash = null;

    /**
     * Value is YES_NO
     *
     * @var    object
     * @access private
     *
     **/
    private $is_yes_no = null;

    /**
     * Value is TRUE_FALSE
     *
     * @var    object
     * @access private
     *
     **/
    private $is_true_false = null;

    /**
     * Format type for TRUE_FALSE
     * 
     * Possible values: ['boolean'|'short'|'long'|'raw'|null]
     * - 'boolean': 1|0
     * - 'short': 't'|'f'
     * - 'long': 'true'|'false'
     * - 'raw'|null: original value
     * 
     * @var    object
     * @access private
     *
     **/
    private $format_true_false = null;

    /**
     * Format type for YES_NO
     * 
     * Possible values: ['boolean'|'short'|'long'|'raw'|null]
     * - 'boolean': 1|0
     * - 'short': 'y'|'n'
     * - 'long': 'yes'|'no'
     * - 'raw'|null: original value
     * 
     * @var    object
     * @access private
     *
     **/
    private $format_yes_no = null;

    /**
     * Value is NUMBER
     *
     * @var    object
     * @access private
     *
     **/
    private $is_numeric = null;

    /**
     * Format Color
     *
     * @var    string
     * @access private
     *
     **/
    private $color = null;

   
    /**
     * Constructor
     *
     * @access public
     * @param object $value
     */
    public function __construct( $value ) {
        $this->set_value($value);
    } // /__construct()


    /**
     * Get Cell width
     *
     * @access public
     * @return int $width_plus_padding
     */
    public function width(  ) {
        return $this->width + $this->padding_left + $this->padding_right;
    } // /width()


    /**
     * Set padding_left
     *
     * @access public
     * @param int $padding_left
     */
    public function set_padding_left( $padding_left ) {
        $this->padding_left = $padding_left;
    } // /set_padding_left()


    /**
     * Set padding_right
     *
     * @access public
     * @paramn int $padding_right
     */
    public function set_padding_right( $padding_right ) {
        $this->padding_right = $padding_right;
    } // /set_padding_right()

    
    /**
     * Set TYPE of Value
     *
     * @access public
     */
    public function set_type( ) {
        $this->is_blank( true );
        $this->is_dash( true );
        $this->is_yes_no( true );
        $this->is_true_false( true );
        $this->is_ok_ko( true );
        $this->is_numeric( true );
    } // /set_type()
    

    /**
     * Format raw value to desire format
     * yes_no:
     * - raw: original value
     * - boolean:   ['1'|'0']
     * - short:     ['y'|'n']
     * - long:      ['yes'|'no']
     * true_false:
     * - raw: original value
     * - boolean:   ['1'|'0']
     * - short:     ['t'|'f']
     * - long:      ['true'|'false']
     *
     * @access public
     * @param object $value
     * @return string $transform_value
     */
    public function formatted_raw_value_output( ) {
        $transform_value = $this->raw_value;
        
        if( $this->is_true_false() && !is_null($this->format_true_false) && $this->format_true_false != 'raw' ||
            $this->is_yes_no() && !is_null($this->format_yes_no) && $this->format_yes_no != 'raw' ){
                $type = ((!is_null($this->format_true_false))?$this->format_true_false:$this->format_yes_no);
                switch( $type ){
                    case 'boolean': if( $this->is_yes() || $this->is_true() ){
                                        $transform_value = '1';
                                    } else if( $this->is_no() || $this->is_false() ){
                                        $transform_value = '0';
                                    }
                                    break;
                    case 'short': if( $this->is_yes() ){
                                        $transform_value = 'y';
                                    } else if( $this->is_no() ){
                                        $transform_value = 'n';
                                    }if( $this->is_true() ){
                                        $transform_value = 't';
                                    } else if( $this->is_false() ){
                                        $transform_value = 'f';
                                    }
                                    break;
                    case 'long': if( $this->is_yes() ){
                                        $transform_value = 'yes';
                                    } else if( $this->is_no() ){
                                        $transform_value = 'no';
                                    }if( $this->is_true() ){
                                        $transform_value = 'true';
                                    } else if( $this->is_false() ){
                                        $transform_value = 'false';
                                    }
                                    break;
                } // /switch $type
        } // if need to transform

        // Freemem()
        unset( $type );

        return $transform_value;
    } // formatted_raw_value_output()


    /**
     * Set Value of Cell
     *
     * @access public
     * @param object $value
     */
    public function set_value( $value = null ) {
        if( !is_null($value) ){
            $this->raw_value = $value;
        }

        // Transform

        $this->value = $this->formatted_raw_value_output( );

        if( !is_null($value) ){
            $this->set_type( );

            if( $this->is_ok_ko_blank_dash() || $this->is_yes_no() || $this->is_true_false() ){
                $this->align = 'center'; // Default alignment for ok_ko, yes_no, true_false
            } else{
                $cleaned_value = $this->cleaned_value( );
                if( is_numeric( $cleaned_value ) || in_array($cleaned_value, array('na', 'nan')) ){
                    $this->align = 'right'; // Default alignment for numbers
                }
            } 
        }

        if( is_null($value) ){
            $this->width =  strlen($this->cleaned_value());
        }

        $this->set_formatted_value( );

        // Freemem()
        unset( $cleaned_value );
    } // /set_value()


    /**
     * Set Format text color
     *
     * @access public
     * @param string $text_color
     */
    public function set_color( $color = null ) {
        $this->color = $color;
    } // /set_color()
    

    /**
     * Set Format format yes no
     *
     * @access public
     * @param string $format_yes_no
     */
    public function set_format_yes_no( $format_yes_no = null ) {
        $this->format_yes_no = $format_yes_no;
        $this->set_value( );
    } // /set_format_yes_no()
    

    /**
     * Set Format format true false
     *
     * @access public
     * @param string $format_true_false
     */
    public function set_format_true_false( $format_true_false = null ) {
        $this->format_true_false = $format_true_false;
        $this->set_value( );
    } // /set_format_true_false()

    
    /**
    * Set Format background color
    *
    * @access public
    * @param string $background_color
    */
   public function set_background_color( $background_color = null ) {
       $this->background_color = $background_color;
   } // /set_background_color(9)


    /**
    * Set Format text decoration
    *
    * @access public
    * @param string $text_decoration
    */
    public function set_text_decoration( $text_decoration = null ) {
        $this->text_decoration = $text_decoration;
    } // /set_text_decoration()


    /**
     * Set Formatted Value of Cell
     *
     * @access private
     */
    private function set_formatted_value( ) {
        $this->formatted_value = $this->formatted_value( );
    } // /set_formatted_value()


    /**
     * Set alignment of Cell
     *
     * @access public
     * @param string $align
     */
    public function align( $align='left' ) {
        $this->align = $align;
        $this->formatted_value = $this->set_formatted_value( );
    } // /align()


    /**
     * Format Color Value of Cell
     *
     * @access public
     * @return string $formatted_value
     */
    public function formatted_color_value( ) {
        $str_value = $this->formatted_value( );

        $StrColor = null;

        if( !is_null($this->color) || !is_null($this->text_decoration) || !is_null($this->background_color) ){
            //$StrColor = chr(27);
        }

        if( !is_null($this->color) ){
            $StrColor .= chr(27).$this->color;
        }

        if( !is_null($this->text_decoration) ){
            $StrColor .=  chr(27).$this->text_decoration;
        }

        if( !is_null($this->background_color) ){
            $StrColor .= chr(27).$this->background_color;            
        }

        if( !is_null( $StrColor) ){
            $str_value = $StrColor.$str_value.chr(27).'[0m';
        }

        // Freemem()
        unset( $StrColor );

        return $str_value;
    } // /formatted_color_value()


    /**
     * Format Value of Cell
     *
     * @access public
     * @return string $formatted_value
     */
    public function formatted_value(  ) {
        $str_value = $this->cleaned_value( );

        if( is_null($this->width) ){
            $this->set_width( strlen( $str_value )+$this->padding_left+$this->padding_right );
        }

        $str_value      = str_pad($str_value, $this->width, ' ', $this->arr_alignments[$this->align]);
        $str_pad_left   = str_pad('', $this->padding_left);
        $str_pad_right  = str_pad('', $this->padding_right);

        $str_value      = $str_pad_left.$str_value.$str_pad_right;

        // Freemem()
        unset( $str_pad_left );
        unset( $str_pad_right );
        
        return $str_value;
    } // /formatted_value


    /**
     * Set Width of Cell
     *
     * @access public
     * @param int $width
     */
    public function set_width( $width=null ) {
        if( is_null($this->width) && is_null($width) ){
            $this->set_width( strlen( $this->cleaned_value( ) )+$this->padding_left+$this->padding_right );
        }

        $this->width = $width-$this->padding_left-$this->padding_right;
        $this->formatted_value = $this->formatted_value($this->value);
    } // /set_width()


    /**
     * Get if Value is Number
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_numeric
     */
    public function is_numeric( $set_variable=false ) {
        if( $set_variable ){
            $cleaned_value = $this->cleaned_value( );
			
            $this->is_numeric = ( is_numeric( $cleaned_value ) || $cleaned_value == 'na' || $cleaned_value == 'nan' ) ||
								( ($cleaned_value[0] == '<' || $cleaned_value[0] == '>') && is_numeric( substr($cleaned_value, 1 ))	);
            
            // Freemem()
            unset( $cleaned_value );
        }

        return $this->is_numeric;
    } // /is_numeric()


    /**
     * Get if Value is blank
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_blank
     */
    public function is_blank( $set_variable=false ) {
        if( $set_variable ){
            $cleaned_value = $this->cleaned_value( );
            $this->is_blank = ( $cleaned_value == '' || $cleaned_value == 'null' );
            
            // Freemem()
            unset( $cleaned_value );
        }
        return $this->is_blank;
    } // /is_blank()


    /**
     * Get if Value is dash
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_dash
     */
    public function is_dash( $set_variable=false ) {
        if( $set_variable ){
            $this->is_dash = ( $this->cleaned_value( ) == '-' );
        }

        return $this->is_dash;
    } // /is_dash()    
    

    /**
     * Get if Value is is_numeric()|''
     *
     * @access public
     * @return boolean $is_numeric_blank
     */
    public function is_numeric_blank_dash(  ) {
        return ( $this->is_numeric() || $this->is_blank() || $this->is_dash() );
    } // /is_numeric_blank_dash()


    /**
     * Get if Value is OK|KO|''
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_ok_ko
     */
    public function is_ok_ko_blank_dash( $set_variable=false ) {
        return ( $this->is_ok_ko() || $this->is_blank() || $this->is_dash() );
    } // /is_ok_ko_blank_dash()


    /**
     * Get if Value is OK_KO
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_ok_ko
     */
    public function is_ok_ko( $set_variable=false ) {
        if( $set_variable ){
            $this->is_ok_ko = ( $this->is_ok( ) || $this->is_ko( ) );
        }
        return $this->is_ok_ko;
    } // /is_ok_ko()


    /**
     * Get if Value is OK
     *
     * @access public
     * @return boolean $is_ok
     */
    public function is_ok( ) {
        return ( $this->cleaned_value( ) == 'ok' );
    } // /is_ok()


    /**
     * Get if Value is KO
     *
     * @access public
     * @return boolean $is_ko
     */
    public function is_ko( ) {
        return ( $this->cleaned_value( ) == 'ko' );
    } // /is_ko()

    
    /**
    * Get if Value is YES|NO|''
    *
    * @access public
    * @return boolean $is_yes_no
    */
    public function is_yes_no_blank_dash(  ) {
       return ( $this->is_yes_no() || $this->is_blank() || $this->is_dash() );
    } // /is_yes_no_blank_dash()


    /**
    * Get if Value is YES_NO
    *
    * @access public
    * @param boolean $set_variable
    * @return boolean $is_yes_no
    */
    public function is_yes_no( $set_variable=false ) {
        if( $set_variable ){
            $this->is_yes_no = ( $this->is_yes( ) || $this->is_no( ) );
        }

        return $this->is_yes_no;
    } // /is_yes_no()


    /**
     * Get if Value is YES
     *
     * @access public
     * @return boolean $is_yes
     */
    public function is_yes( ) {
        $cleaned_value =$this->cleaned_value( );
        return ( $cleaned_value == 'yes' || $cleaned_value == 'y' );
    } // /is_yes()


    /**
     * Get if Value is NO
     *
     * @access public
     * @return boolean $is_no
     */
    public function is_no( ) {
        $cleaned_value =$this->cleaned_value( );
        return ( $cleaned_value == 'no' || $cleaned_value == 'n' );
    } // /is_no()

    
    /**
     * Get if Value is TRUE|FALSE|''
     *
     * @access public
     * @return boolean $is_true_false
     */
    public function is_true_false_blank_dash(  ) {
        return ( $this->is_true_false() || $this->is_blank() || $this->is_blank() );
    } // /is_true_false_blank_dash()


    /**
     * Get if Value is TRUE_FALSE
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_true_false
     */
    public function is_true_false( $set_variable=false ) {
        if( $set_variable ){
            $this->is_true_false = ( $this->is_true( ) || $this->is_false( ) );
        }
        
        return $this->is_true_false;
    } // /is_true_false()


    /**
     * Get if Value is TRUE
     *
     * @access public
     * @return boolean $is_true
     */
    public function is_true( ) {
        $tr_true_false =$this->cleaned_value( );

        return ( $tr_true_false == 'true'  || $tr_true_false == 't' || $tr_true_false == '1' );
    } // /is_true()


    /**
     * Get if Value is FALSE
     *
     * @access public
     * @param boolean $set_variable
     * @return boolean $is_false
     */
    public function is_false( ) {
        $tr_true_false =$this->cleaned_value( );

        return ( $tr_true_false == 'false' || $tr_true_false == 'f' || $tr_true_false == '0' );
    } // /is_false()


    /**
     * Clean Value
     *
     * @access public
     * @param mixed $value
     * @return boolean $cleaned_value
     */
    public function cleaned_value( $value = null ) {
        $ValueToClean = ( is_null($value)?$this->value:$value );

        return trim( strtolower( (string)$ValueToClean ) );
    } // /cleaned_value()
} // /cli_math_ml_table_cell class
?>