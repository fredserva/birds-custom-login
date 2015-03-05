<?php
/**
* Custom Login Functions
*
* @package    Birds_Custom_Login
* @subpackage birds-custom-login/settings/admin
* @since      1.0.0
*/

/**
* Logo href
*/
if ( !function_exists('birds_custom_url_login')) {
    function birds_custom_url_login()  {
        return get_bloginfo( 'url' );
    }
    add_filter('login_headerurl', 'birds_custom_url_login');
}

/**
* HEX to RGBA
*/
if ( !function_exists('hex2rgba')) {
    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';
        //Return default if no color provided
        if(empty($color))
            return $default;
        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }
        //Return rgb(a) color string
        return $output;
    }
}

/**
* Darken/Lighten HEX
*/
if ( !function_exists('colourCreator')) {
    function colourCreator($colour, $per)
    {
        $colour = substr( $colour, 1 ); // Removes first character of hex string (#)
        $rgb = ''; // Empty variable
        $per = $per/100*255; // Creates a percentage to work with. Change the middle figure to control colour temperature

        if  ($per < 0 ) // Check to see if the percentage is a negative number
        {
            // DARKER
            $per =  abs($per); // Turns Neg Number to Pos Number
            for ($x=0;$x<3;$x++)
            {
                $c = hexdec(substr($colour,(2*$x),2)) - $per;
                $c = ($c < 0) ? 0 : dechex($c);
                $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
            }
        }
        else
        {
            // LIGHTER
            for ($x=0;$x<3;$x++)
            {
                $c = hexdec(substr($colour,(2*$x),2)) + $per;
                $c = ($c > 255) ? 'ff' : dechex($c);
                $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
            }
        }
        return '#'.$rgb;
    }
}

/**
* Preview
*/
add_action('admin_head', 'birds_apply_preview_css');
function birds_apply_preview_css() {

    // Logo & Background
    $logo_id = get_setting('bcl_elements_section', 'bcl_logo_upload');
    if ($logo_id != '') {
        $logo_attributes = wp_get_attachment_image_src($logo_id, 'full');
        $logo = $logo_attributes[0] ;
        $logo_width = $logo_attributes[1] ;
        $logo_height = $logo_attributes[2] ;
    } else {
        $logo = plugins_url() . '/birds-custom-login/admin/images/w-logo-blue.png' ;
        $logo_width = '84' ;
        $logo_height = '84' ;
    }
    $bcl_logo_bottom_margin = get_setting( 'bcl_elements_section', 'bcl_logo_bottom_margin' );
    $bcl_bg_color = get_setting( 'bcl_elements_section', 'bcl_bg_color' );

    // Form
    $bcl_label_color = get_setting( 'bcl_form_section', 'bcl_label_color' );
    $bcl_form_bg_color = get_setting( 'bcl_form_section', 'bcl_form_bg_color' );
    $bcl_form_style = get_setting( 'bcl_form_section', 'bcl_form_style' );
    if ($bcl_form_style == 'flat') {
        $bcl_form_style_shadow = 'none';
    }
    if ($bcl_form_style == 'shadow') {
        $bcl_form_style_shadow = '0 1px 3px rgba(0,0,0,.13)';
    }
    $bcl_form_button_color = get_setting( 'bcl_form_section', 'bcl_form_button_color' );
    $rgba_06 = hex2rgba($bcl_form_button_color, 0.6);
    $darkPercent = -13;
    $lightPercent = 90;
    $darker = colourCreator($bcl_form_button_color, $darkPercent);
    $lighter = colourCreator($bcl_form_button_color, $lightPercent);
    $rgba_015 = hex2rgba($lighter, 0.15);
    $bcl_rounded_form = get_setting( 'bcl_form_section', 'bcl_rounded_form' );
    $bcl_rounded_nb = get_setting( 'bcl_form_section', 'bcl_rounded_nb' );
    if ($bcl_rounded_form == 'yes') {
        $letsroundit = '-webkit-border-radius: '.$bcl_rounded_nb.'px;-moz-border-radius: '.$bcl_rounded_nb.'px;border-radius: '.$bcl_rounded_nb.'px;';
    }
    if ($bcl_rounded_form == 'no') {
        $letsroundit = '';
    }
    $bcl_form_text_button_color = get_setting( 'bcl_form_section', 'bcl_form_button_text_color' );

    // Below Form
    $bcl_reg = get_setting( 'bcl_below_form_section', 'bcl_reg' );
    if ($bcl_reg == 'yes') {
        $none = 'display: none';
    }
    if ($bcl_reg == 'no') {
        $none = '';
    }
    $bcl_backto = get_setting( 'bcl_below_form_section', 'bcl_backto' );
    if ($bcl_backto == 'yes') {
        $none2 = 'display: none';
    }
    if ($bcl_backto == 'no') {
        $none2 = '';
    }
    $bcl_reg_color = get_setting( 'bcl_below_form_section', 'bcl_reg_color' );
    $bcl_reg_hover_color = get_setting( 'bcl_below_form_section', 'bcl_reg_hover_color' );
    $bcl_back_color = get_setting( 'bcl_below_form_section', 'bcl_back_color' );
    $bcl_back_hover_color = get_setting( 'bcl_below_form_section', 'bcl_back_hover_color' );

    // Fullscreen Background
    $bcl_fullscreen_bg = get_setting('bcl_elements_section', 'bcl_fullscreen_bg');
    if ($bcl_fullscreen_bg != '') {
        $full_attributes = wp_get_attachment_image_src($bcl_fullscreen_bg, 'full');
        $full = $full_attributes[0] ;
        echo '
                <script>
                    jQuery(document).ready(function ($) {
                        "use strict";
                        $(window).on("load resize scroll", function(e) {
                            $("#pre_bg").backstretch("'.$full.'");
                        });
                    });
                </script>
            ';
    }
    echo '<style>
        #pre_bg {
            background: '.$bcl_bg_color.';
            height: 600px;
            border: 1px solid #ddd;
            color: #444;
            font-family: "Open Sans",sans-serif;
            font-size: 13px;
            line-height: 1.4em;
        }
        .pre_login {
            width: 320px;
            padding: 8% 0 0;
            margin: auto;
        }
        .pre_login #pre_loginform {
            margin-top: 20px;
            margin-left: 0;
            padding: 26px 24px 46px;
            font-weight: 400;
            overflow: hidden;
            background: '.$bcl_form_bg_color.' !important;
            -webkit-box-shadow: '.$bcl_form_style_shadow.' !important;
            box-shadow: '.$bcl_form_style_shadow.' !important;
            '.$letsroundit.';
        }
        input#pre_wp-submit {
            background: '.$bcl_form_button_color.' !important;
            border-color: '.$darker.' !important;
            -webkit-box-shadow: inset 0 1px 0 '.$rgba_015.' !important;
            box-shadow: inset 0 1px 0 '.$rgba_015.' !important;
            color: '.$bcl_form_text_button_color.';
        }
        .pre_login #pre_loginform p {
            margin-bottom: 0;
        }
        .pre_login #pre_loginform p.pre_submit {
            margin: 0;
            padding: 0;
        }
        .pre_login * {
            margin: 0;
            padding: 0;
        }
        .pre_login h1 {
            text-align: center;
        }
        .pre_login h1 a {
            background-image: url("'.$logo.'");
            -webkit-background-size: '.$logo_height.'px !important;
            background-size: 100% !important;
            background-position: center top;
            background-repeat: no-repeat;
            color: #999;
            height: '.$logo_height.'px !important;
            font-size: 20px;
            font-weight: 400;
            line-height: 1.3em;
            margin: 0 auto '.$bcl_logo_bottom_margin.'px;
            padding: 0;
            text-decoration: none;
            width: '.$logo_width.'px !important;
            max-width: 320px;
            text-indent: -9999px;
            outline: 0;
            overflow: hidden;
            display: block;
        }
        .pre_login #pre_backtoblog a:hover,.pre_login #pre_nav a:hover,.pre_login h1 a:hover {
            color: #2ea2cc;
        }
        .pre_login #pre_loginform .pre_forgetmenot {
            font-weight: 400;
            float: left;
            margin-bottom: 0;
        }
        .pre_login .button-primary {
            float: right;
        }
        .pre_login label {
            color: #777;
            font-size: 14px;
        }
        label[for=pre_user_login], label[for=pre_user_pass], label[for=pre_rememberme] {
            color: '.$bcl_label_color.' !important;
        }
        .pre_login #pre_loginform .pre_forgetmenot label {
            font-size: 12px;
            line-height: 19px;
        }
        .pre_login #pre_backtoblog a:hover,.pre_login #pre_nav a:hover,.pre_login h1 a:hover {
            color: #2ea2cc;
        }
        .pre_login #pre_nav {
            margin: 24px 0 0;
            '.$none.';
        }
        #pre_backtoblog {
            margin: 16px 0 0;
            padding: 0 24px;
            '.$none2.';
        }
        .pre_login #pre_backtoblog a,.pre_login #pre_nav a {
            text-decoration: none;
            color: #999;
            font-size: 13px;
        }
        a.pre_reg, a.pre_pass {
            padding: 0 0 0 24px;
            color: '.$bcl_reg_color.' !important;
        }
        a.pre_reg:hover, a.pre_pass:hover {
            padding: 0 0 0 24px;
            color: '.$bcl_reg_hover_color.' !important;
        }
        #pre_backtoblog a {
            color: '.$bcl_back_color.' !important;
        }
        #pre_backtoblog a:hover {
            color: '.$bcl_back_hover_color.' !important;
        }
        .pre_pass {
            padding: 0 !important;
        }
        .pre_login #pre_loginform .pre_input,.pre_login input[type=text] {
            font-size: 24px;
            width: 100%;
            padding: 3px;
            margin: 2px 6px 16px 0;
        }
        .pre_login #pre_loginform .pre_input,.pre_login #pre_loginform input[type=checkbox],.pre_login input[type=text] {
            background: #fbfbfb;
        }
        #pre_login#pre_loginform p.pre_submit {
            border: none;
            margin: -10px 0 20px;
        }
        #pre_loginform p.submit a.cancel:hover {
            text-decoration: none;
        }
        .pre_login #pre_loginform .pre_input,.pre_login input[type=text] {
            font-size: 24px;
            width: 100%;
            padding: 3px;
            margin: 2px 6px 16px 0;
        }
        .pre_login #pre_loginform .pre_input,.pre_login #pre_loginform input[type=checkbox],.pre_login input[type=text] {
            background: #fbfbfb;
        }
    </style>';
}

/**
* Login Screen
*/
add_action( 'login_enqueue_scripts', 'birds_custom_login_css' );
function birds_custom_login_css() {

    // Logo & Background
    $logo_id = get_setting('bcl_elements_section', 'bcl_logo_upload');
    if ($logo_id != '') {
        $logo_attributes = wp_get_attachment_image_src($logo_id, 'full');
        $logo = $logo_attributes[0] ;
        $logo_width = $logo_attributes[1] ;
        $logo_height = $logo_attributes[2] ;
    } else {
        $logo = plugins_url() . '/birds-custom-login/admin/images/w-logo-blue.png' ;
        $logo_width = '84' ;
        $logo_height = '84' ;
    }
    $bcl_logo_bottom_margin = get_setting( 'bcl_elements_section', 'bcl_logo_bottom_margin' );
    $bcl_bg_color = get_setting( 'bcl_elements_section', 'bcl_bg_color' );

    // Form
    $bcl_label_color = get_setting( 'bcl_form_section', 'bcl_label_color' );
    $bcl_form_bg_color = get_setting( 'bcl_form_section', 'bcl_form_bg_color' );
    $bcl_form_style = get_setting( 'bcl_form_section', 'bcl_form_style' );
    if ($bcl_form_style == 'flat') {
        $bcl_form_style_shadow = 'none';
    }
    if ($bcl_form_style == 'shadow') {
        $bcl_form_style_shadow = '0 1px 3px rgba(0,0,0,.13)';
    }
    $bcl_form_button_color = get_setting( 'bcl_form_section', 'bcl_form_button_color' );
    $rgba_06 = hex2rgba($bcl_form_button_color, 0.6);
    $darkPercent = -13;
    $lightPercent = 90;
    $darker = colourCreator($bcl_form_button_color, $darkPercent);
    $lighter = colourCreator($bcl_form_button_color, $lightPercent);
    $rgba_015 = hex2rgba($lighter, 0.15);
    $bcl_rounded_form = get_setting( 'bcl_form_section', 'bcl_rounded_form' );
    $bcl_rounded_nb = get_setting( 'bcl_form_section', 'bcl_rounded_nb' );
    if ($bcl_rounded_form == 'yes') {
        $letsroundit = '-webkit-border-radius: '.$bcl_rounded_nb.'px;-moz-border-radius: '.$bcl_rounded_nb.'px;border-radius: '.$bcl_rounded_nb.'px;';
    }
    if ($bcl_rounded_form == 'no') {
        $letsroundit = '';
    }
    $bcl_form_text_button_color = get_setting( 'bcl_form_section', 'bcl_form_button_text_color' );

    // Below Form
    $bcl_reg = get_setting( 'bcl_below_form_section', 'bcl_reg' );
    if ($bcl_reg == 'yes') {
        $none = 'display: none';
    }
    if ($bcl_reg == 'no') {
        $none = '';
    }
    $bcl_backto = get_setting( 'bcl_below_form_section', 'bcl_backto' );
    if ($bcl_backto == 'yes') {
        $none2 = 'display: none';
    }
    if ($bcl_backto == 'no') {
        $none2 = '';
    }
    $bcl_reg_color = get_setting( 'bcl_below_form_section', 'bcl_reg_color' );
    $bcl_reg_hover_color = get_setting( 'bcl_below_form_section', 'bcl_reg_hover_color' );
    $bcl_back_color = get_setting( 'bcl_below_form_section', 'bcl_back_color' );
    $bcl_back_hover_color = get_setting( 'bcl_below_form_section', 'bcl_back_hover_color' );

    // Fullscreen Background
    $bcl_fullscreen_bg = get_setting('bcl_elements_section', 'bcl_fullscreen_bg');
    if ($bcl_fullscreen_bg != '') {
        $full_attributes = wp_get_attachment_image_src($bcl_fullscreen_bg, 'full');
        $full = $full_attributes[0] ;
        echo '
                <div class="background-cover"></div>
                <style>
                    .background-cover {
                        background: url("'.$full.'") no-repeat center center fixed !important;
                        background-size: cover !important;
                        -moz-background-size: cover !important;
                        -o-background-size: cover !important;
                        -webkit-background-size: cover;
                        position:fixed;
                        top:0;
                        left:0;
                        z-index:10;
                        overflow: hidden;
                        width: 100%;
                        height:100%;
                    }
                    #login {
                        z-index:9999;
                        position:relative;
                    }

                </style>';
    }

    echo '

        <style type="text/css">
         body {
                background: '.$bcl_bg_color.' !important;
            }
            .login form {
                margin-top: 20px;
                margin-left: 0;
                padding: 26px 24px 46px;
                font-weight: 400;
                overflow: hidden;
                background: '.$bcl_form_bg_color.' !important;
                -webkit-box-shadow: '.$bcl_form_style_shadow.' !important;
                box-shadow: '.$bcl_form_style_shadow.' !important;
                '.$letsroundit.';
            }
            input#wp-submit {
                background: '.$bcl_form_button_color.' !important;
                border-color: '.$darker.' !important;
                -webkit-box-shadow: inset 0 1px 0 '.$rgba_015.' !important;
                box-shadow: inset 0 1px 0 '.$rgba_015.' !important;
                color: '.$bcl_form_text_button_color.' !important;
            }
            #login h1 a {
                background-image: url("'.$logo.'");
                -webkit-background-size: '.$logo_height.'px !important;
                background-size: 100% !important;
                background-position: center top;
                background-repeat: no-repeat;
                color: #999;
                height: '.$logo_height.'px !important;
                font-size: 20px;
                font-weight: 400;
                line-height: 1.3em;
                margin: 0 auto '.$bcl_logo_bottom_margin.'px;
                padding: 0;
                text-decoration: none;
                width: '.$logo_width.'px !important;
                max-width: 320px;
                text-indent: -9999px;
                outline: 0;
                overflow: hidden;
                display: block;
            }
            label[for=user_login], label[for=user_pass], label[for=rememberme] {
                color: '.$bcl_label_color.' !important;
            }
            .login #nav {
                margin: 24px 0 0;
                '.$none.';
            }
            #backtoblog {
                margin: 16px 0 0;
                padding: 0 24px;
                '.$none2.';
            }
            p#nav > a {
                padding: 0 0 0 24px;
                color: '.$bcl_reg_color.' !important;
            }
            p#nav > a:hover {
                padding: 0 0 0 24px;
                color: '.$bcl_reg_hover_color.' !important;
            }
            #backtoblog a {
                color: '.$bcl_back_color.' !important;
            }
            #backtoblog a:hover {
                color: '.$bcl_back_hover_color.' !important;
            }

        </style>
    ';
}
