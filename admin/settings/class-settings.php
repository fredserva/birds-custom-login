<?php
/**
 * Settings Class
 *
 * @package    Birds_Custom_Login
 * @subpackage birds-custom-login/settings/admin
 * @since      1.0.0
 */

if ( ! class_exists( 'Birds_Settings_Bcl' ) ) {

    class Birds_Settings_Bcl {

        private $page,
        $title,
        $menu,
        $settings = array(),
        $empty = true,
        $notices = array();
        const text_domain = 'birds-custom-login';

        public function __construct( $page = 'custom_settings', $title = null, $menu = array(), $settings = array(), $args = array() )
        {
            $this->page = $page;
            $this->title = $title ? $title : __( 'Custom Settings', self::text_domain );
            $this->menu = is_array( $menu ) ? array_merge( array(
                'parent'     => 'themes.php',
                'title'      => $this->title,
                'capability' => 'manage_options',
                'icon_url'   => null,
                'position'   => null
            ), $menu ) : false;
            $this->apply_settings( $settings );
            $this->args  = array_merge( array(
                'description' => null,
                'submit'      => __( 'Save Settings', self::text_domain ),
                'reset'       => __( 'Reset Settings', self::text_domain ),
                'tabs'        => false,
                'updated'     => null
            ), $args );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

        public function apply_settings( $settings )
        {
            if ( is_array( $settings ) ) {
                foreach ( $settings as $setting => $section ) {
                    $section = array_merge( array(
                        'title'       => null,
                        'description' => null,
                        'fields'      => array()
                    ), $section );
                    foreach ( $section['fields'] as $name => $field ) {
                        $field = array_merge( array(
                            'type'        => 'text',
                            'label'       => null,
                            'description' => null,
                            'default'     => null,
                            'sanitize'    => null,
                            'attributes'  => array(),
                            'options'     => null,
                            'action'      => null
                        ), $field );
                        $section['fields'][$name] = $field;
                    }
                    $this->settings[$setting] = $section;
                    if ( ! get_option( $setting ) ) {
                        add_option( $setting, $this->get_defaults( $setting ) );
                    }
                }
            }
        }

        public function add_notice( $message, $type = 'info' )
        {
            $this->notices[] = array(
                'message' => $message,
                'type'    => $type
            );
        }

        private function get_defaults( $setting )
        {
            $defaults = array();
            foreach ( $this->settings[$setting]['fields'] as $name => $field ) {
                if ( $field['default'] !== null ) {
                    $defaults[$name] = $field['default'];
                }
            }
            return $defaults;
        }

        private function reset()
        {
            foreach ( $this->settings as $setting => $section ) {
                $_POST[$setting] = array_merge( $_POST[$setting], $this->get_defaults( $setting ) );
            }
            add_settings_error( $this->page, 'settings_reset', __( 'Default settings have been reset.', self::text_domain ), 'updated' );
        }

        public function admin_menu()
        {
            if ( $this->menu ) {
                if ( $this->menu['parent'] ) {
                    $page = add_submenu_page( $this->menu['parent'], $this->title, $this->menu['title'], $this->menu['capability'], $this->page, array( $this, 'do_page' ) );
                } else {
                    $page = add_menu_page( $this->title, $this->menu['title'], $this->menu['capability'], $this->page, array( $this, 'do_page' ), $this->menu['icon_url'], $this->menu['position'] );
                    if ( $this->title !== $this->menu['title'] ) {
                        add_submenu_page( $this->page, $this->title, $this->title, $this->menu['capability'], $this->page );
                    }
                }
                add_action( 'load-' . $page, array( $this, 'load_page' ) );
            }
        }

        public function load_page()
        {
            global $wp_settings_errors;
            foreach ( $this->notices as $notice ) {
                $wp_settings_errors[] = array_merge( $notice, array(
                    'setting' => $this->page,
                    'code'    => $notice['type'] . '_notice'
                ) );
            }
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
                if ( $this->args['updated'] !== null && $notices = get_transient( 'settings_errors' ) ) {
                    delete_transient( 'settings_errors' );
                    foreach ( $notices as $i => $notice ) {
                        if ( $notice['setting'] === 'general' && $notice['code'] === 'settings_updated' ) {
                            if ( $this->args['updated'] ) {
                                $notice['message'] = (string) $this->args['updated'];
                            } else {
                                continue;
                            }
                        }
                        $wp_settings_errors[] = $notice;
                    }
                }
                do_action( "{$this->page}_settings_updated" );
            }
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
        }

        public static function admin_enqueue_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_script( 'birds-settings-js', plugins_url( 'settings.js' , __FILE__ ), array( 'jquery', 'wp-color-picker' ) );
            wp_localize_script( 'birds-settings-js', 'ajax', array(
                'url' => admin_url( 'admin-ajax.php' ),
                'spinner' => admin_url( 'images/spinner.gif' )
            ) );
            wp_enqueue_style( 'birds-settings-css', plugins_url( 'settings.css' , __FILE__ ) );
            wp_enqueue_style( 'wp-color-picker' );
        }

        public function do_page()
        {
        ?>
<form action="options.php" method="POST" enctype="multipart/form-data" class="wrap">
    <h1><?php echo $this->title; ?></h1>
    <?php
        settings_errors();
    if ( $text = $this->args['description'] ) { echo wpautop( $text ); }
        do_settings_sections( $this->page );
    if ( ! $this->empty ) {
        settings_fields( $this->page );
        if ( $this->args['tabs'] && count( $this->settings ) > 1 ) {
    ?>
    <div class="birds-settings-tabs"></div>
    <?php
        }
        submit_button( $this->args['submit'], 'large primary' );
        if ( $this->args['reset'] ) {
            submit_button( $this->args['reset'], 'small', "{$this->page}_reset", true, array( 'onclick' => "return confirm('" . __( 'Do you really want to reset all these settings to their default values?', self::text_domain ) . "');" ) );
        }
    }
    ?>
</form>
<?php }

        public function admin_init()
        {
            foreach ( $this->settings as $setting => $section ) {
                register_setting( $this->page, $setting, array( $this, 'sanitize_setting' ) );
                add_settings_section( $setting, $section['title'], array( $this, 'do_section' ), $this->page );
                if ( ! empty( $section['fields'] ) ) {
                    $this->empty = false;
                    $values = get_setting_bcl( $setting );
                    foreach ( $section['fields'] as $name => $field ) {
                        $id = $setting . '_' . $name;
                        $field = array_merge( array(
                            'id'    => $id,
                            'name'    => $setting . '[' . $name . ']',
                            'value'   => isset( $values[$name] ) ? $values[$name] : null,
                            'label_for' => $field['label'] === false ? 'hidden' : $id
                        ), $field );
                        add_settings_field( $name, $field['label'], array( __CLASS__, 'do_field' ), $this->page, $setting, $field );
                        if ( $field['type'] === 'action' && is_callable( $field['action'] ) ) {
                            add_action( "wp_ajax_{$setting}_{$name}", $field['action'] );
                        }
                    }
                }
            }
            if ( isset( $_POST["{$this->page}_reset"] ) ) {
                $this->reset();
            }
        }

        public function do_section( $args )
        {
            extract( $args );
            global $wp_version;
            if ($wp_version >= 4.4) {
                echo "<input name='{$id}[{$this->page}_setting]' type='hidden' value='{$id}' class='birds-settings-section' />";
            } else {
                echo "<input name='{$id}[{$this->page}_setting]' type='hidden' value='{$id}' class='birds-settings-section-ante' />";
            }
            if ( $text = $this->settings[$id]['description'] ) {
                echo wpautop( $text );
            }
        }

        public static function do_field( $args )
        {
            extract( $args );
            $attrs = "name='{$name}'";
            foreach ( $attributes as $k => $v ) {
                $k = sanitize_key( $k );
                $v = esc_attr( $v );
                $attrs .= " {$k}='{$v}'";
            }
            $desc = $description ? "<p class='description'>{$description}</p>" : '';
            switch ( $type ) {
                case 'checkbox':
                    $check = checked( 1, $value, false );
                    echo "<label><input {$attrs} id='{$id}' type='checkbox' value='1' {$check} />";
                    if ( $description ) { echo " {$description}"; }
                    echo "</label>";
                    break;

                case 'radio':
                    if ( ! $options ) { _e( 'No options defined.', self::text_domain ); }
                    echo "<fieldset id='{$id}'>";
                    foreach ( $options as $v => $label ) {
                        $check = checked( $v, $value, false );
                        $options[$v] = "<label><input {$attrs} type='radio' value='{$v}' {$check} /> {$label}</label>";
                    }
                    echo implode( '<br />', $options );
                    echo "{$desc}</fieldset>";
                    break;

                case 'select':
                    if ( ! $options ) { _e( 'No options defined.', self::text_domain ); }
                    echo "<select {$attrs} id='{$id}'>";
                    foreach ( $options as $v => $label ) {
                        $select = selected( $v, $value, false );
                        echo "<option value='{$v}' {$select} />{$label}</option>";
                    }
                    echo "</select>{$desc}";
                    break;

                case 'media':
                    echo "<fieldset class='birds-settings-media' id='{$id}'><input {$attrs} type='hidden' value='{$value}' />";
                    echo "<p><a class='button button-large birds-select-media' title='{$label}'>" . sprintf( __( 'Select %s', self::text_domain ), $label ) . "</a> ";
                    echo "<a class='button button-small birds-remove-media' title='{$label}'>" . sprintf( __( 'Remove %s', self::text_domain ), $label ) . "</a></p>";
                    if ( $value ) {
                        echo wpautop( wp_get_attachment_image( $value, 'medium' ) );
                    }
                    echo "{$desc}</fieldset>";
                break;

                case 'textarea':
                    echo "<textarea {$attrs} id='{$id}' class='large-text'>{$value}</textarea>{$desc}";
                break;

                case 'preview':
                    echo "{$desc}";
                break;

                case 'multi':
                    if ( ! $options ) { _e( 'No options defined.', self::text_domain ); }
                    echo "<fieldset id='{$id}'>";
                    foreach ( $options as $n => $label ) {
                        $a = preg_replace( "/name\=\'(.+)\'/", "name='$1[{$n}]'", $attrs );
                        $check = checked( 1, $value[$n], false );
                        $options[$n] = "<label><input {$a} type='checkbox' value='1' {$check} /> {$label}</label>";
                    }
                    echo implode( '<br />', $options );
                    echo "{$desc}</fieldset>";
                break;

                case 'action':
                    if ( ! $action ) { _e( 'No action defined.', self::text_domain ); }
                    echo "<p class='birds-settings-action'><input {$attrs} id='{$id}' type='button' class='button button-large' value='{$label}' /></p>{$desc}";
                    break;

                case 'color':
                    $v = esc_attr( $value );
                    echo "<input {$attrs} id='{$id}' type='text' value='{$v}' class='birds-settings-color' />{$desc}";
                    break;

                default:
                    $v = esc_attr( $value );
                    echo "<input {$attrs} id='{$id}' type='{$type}' value='{$v}' class='regular-text' />{$desc}";
                    break;
            }
        }

        public function sanitize_setting( $inputs ) {
            $values = array();
            if ( ! empty( $inputs["{$this->page}_setting"] ) ) {
                $setting = $inputs["{$this->page}_setting"];
                foreach ( $this->settings[$setting]['fields'] as $name => $field ) {
                    $input = array_key_exists( $name, $inputs ) ? $inputs[$name] : null;
                    if ( $field['sanitize'] ) {
                        $values[$name] = call_user_func( $field['sanitize'], $input, $name );
                    } else {
                        switch ( $field['type'] ) {
                            case 'checkbox':
                                $values[$name] = $input ? 1 : 0;
                                break;

                            case 'radio':
                            case 'select':
                                $values[$name] = sanitize_key( $input );
                                break;

                            case 'media':
                                $values[$name] = absint( $input );
                                break;

                            case 'color':
                                $values[$name] = preg_match( '/^#[a-f0-9]{6}$/i', $input ) ? $input : '#f1f1f1';
                                break;

                            case 'textarea':
                                $text = '';
                                $nl = "BIRDS-SETTINGS-NEW-LINE";
                                $tb = "BIRDS-SETTINGS-TABULATION";
                                $lines = explode( $nl, sanitize_text_field( str_replace( "\t", $tb, str_replace( "\n", $nl, $input ) ) ) );
                                foreach ( $lines as $line ) {
                                    $text .= str_replace( $tb, "\t", trim( $line ) ) . "\n";
                                }
                                $values[$name] = trim( $text );
                                break;

                            case 'multi':
                                if ( ! $input || empty( $field['options'] ) ) { break; }
                                foreach ( $field['options'] as $n => $opt ) {
                                    $input[$n] = empty( $input[$n] ) ? 0 : 1;
                                }
                                $values[$name] = json_encode( $input );
                                break;

                            case 'action':
                                break;

                            case 'email':
                                $values[$name] = sanitize_email( $input );
                                break;

                            case 'url':
                                $values[$name] = esc_url_raw( $input );
                                break;

                            case 'number':
                                $values[$name] = floatval( $input );
                                break;

                            default:
                                $values[$name] = sanitize_text_field( $input );
                                break;
                        }
                    }
                }
                return $values;
            }
            return $inputs;
        }

        public static function parse_multi( $result ) {
            // Check if the result was recorded as JSON, and if so, returns an array instead
            return ( is_string( $result ) && $array = json_decode( $result, true ) ) ? $array : $result;
        }

        public static function plugin_priority() {
            $birds_settings = plugin_basename( __FILE__ );
            $active_plugins = get_option( 'active_plugins' );
            if ( $order = array_search( $birds_settings, $active_plugins ) ) {
                array_splice( $active_plugins, $order, 1 );
                array_unshift( $active_plugins, $birds_settings );
                update_option( 'active_plugins', $active_plugins );
            }
        }
    }
    add_action( 'activated_plugin', array( 'Birds_Settings_Bcl', 'plugin_priority' ) );

    function get_setting_bcl( $setting, $option = false ) {
        $setting = get_option( $setting );
        if ( is_array( $setting ) ) {
            if ( $option ) {
                return isset( $setting[$option] ) ? Birds_Settings_Bcl::parse_multi( $setting[$option] ) : false;
            }
            foreach ( $setting as $k => $v ) {
                $setting[$k] = Birds_Settings_Bcl::parse_multi( $v );
            }
            return $setting;
        }
        return $option ? false : $setting;
    }

    function create_settings_page( $page = 'custom_settings', $title = null, $menu = array(), $settings = array(), $args = array() ) {
        return new Birds_Settings_Bcl( $page, $title, $menu, $settings, $args );
    }

}
?>
