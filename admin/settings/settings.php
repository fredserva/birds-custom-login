<?php
/**
* Settings page
*
* @package    Birds_Custom_Login
* @subpackage birds-custom-login/settings/admin
* @since      1.0.0
*/

$text_domain = 'birds-custom-login';
$logo = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'admin/images/w-logo-blue.png';
$icon = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'admin/images/birds.png';
$blog_name = get_bloginfo('name');
$blog_url = get_bloginfo('url');

// Top level page & First Section
$birds_custom_login_top_page = create_settings_page(
    'birds_custom_login_settings',
    '<img src="'.$icon.'"> '.__('Custom Login Settings', $text_domain),
    array(
            'parent' => 'themes.php',
            'title' => __('Birds Custom Login', $text_domain),
            'icon_url' => 'dashicons-controls-play',
            'position' => '63.5'
        ),
    array(
            'bcl_elements_section' => array(
                'title' => __('Logo & Background', $text_domain),
                'description' => '',
                'fields' => array(
                    'bcl_logo_upload'  => array(
                        'type'  => 'media',
                        'label' => __( 'Logo', $text_domain ),
                        'description' => __('Upload your own logo.', $text_domain).'<br>'.__('Max width: 320px', $text_domain),
                        'default' => $logo
                    ),
                    'bcl_logo_alt_text' => array(
                        'type'  => 'text',
                        'label' => __( 'Logo Alt text', $text_domain ),
                        'description' => __( 'Default is "Powered by WordPress"', $text_domain ),
                        'default' => __( 'Powered by WordPress', $text_domain )
                    ),
                    'bcl_logo_bottom_margin' => array(
                        'type'  => 'number',
                        'label' => __( 'Logo bottom margin', $text_domain ),
                        'description' => __('Default is 25px and it\'s a good value, but if you want to increase this margin...', $text_domain),
                        'attributes' => array( 'placeholder' => '25' ),
                        'default' => '25'
                    ),
                    'bcl_bg_color'  => array(
                        'type'  => 'color',
                        'label' => __( 'Background color', $text_domain ),
                        'description' => __( 'Default color is: #f1f1f1', $text_domain ),
                        'default' => '#f1f1f1'
                    ),
                    'bcl_fullscreen_bg'  => array(
                        'type'  => 'media',
                        'label' => __( 'Background Image', $text_domain ),
                        'description' => __('You can upload a background image here.', $text_domain).'<br>'.__('The image will stretch to fit the page, and will automatically resize as the window size changes.', $text_domain).'<br><br>'.__('You\'ll have the best results by using images with a minimum width of 1024px.', $text_domain),
                        'default' => ''
                    ),
                )
            )
    ),
    array(
            'tabs' => true,
            'submit' => __('Save Settings', $text_domain),
            'reset' => __('Reset', $text_domain),
        'description' => __('Save the settings and you will be able to preview your Custom Login Page.', $text_domain),
            'updated' => __('Settings saved!', $text_domain)
    )
);

// Other Sections
$birds_custom_login_top_page->apply_settings( array(
    'bcl_form_section' => array(
        'title'  => __( 'Form', $text_domain ),
        'fields' => array(
            'bcl_rounded_form'  => array(
                'type'    => 'select',
                'label'   => __( 'Rounded Form', $text_domain ),
                'description' => __('Would you like to round the form border?', $text_domain),
                'options' => array(
                    'no'   => __( 'No', $text_domain ),
                    'yes'   => __( 'Yes', $text_domain )
                ),
                'default' => 'no'
            ),
            'bcl_rounded_nb' => array(
                'type'  => 'number',
                'label' => __( 'Radius', $text_domain ),
                'description' => __('How much would you like to round the border?', $text_domain),
                'attributes' => array( 'placeholder' => '20' ),
                'default' => '20'
            ),
            'bcl_label_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Label color', $text_domain ),
                'description' => __( 'Default color is: #777777', $text_domain ),
                'default' => '#777777'
            ),
            'bcl_form_bg_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Background color', $text_domain ),
                'description' => __( 'Default color is: #ffffff', $text_domain ),
                'default' => '#ffffff'
            ),
            'bcl_form_style'  => array(
                'type'    => 'radio',
                'label'   => __( 'Form Style', $text_domain ),
                'options' => array(
                    'flat'   => __( 'Flat', $text_domain),
                    'shadow' => __( 'Shadowed Box', $text_domain)
                ),
                'description' => __( 'Choose the style of the form. Default is Shadow', $text_domain ),
                'default' => 'shadow'
            ),
            'bcl_form_button_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Button color', $text_domain ),
                'description' => __( 'Default color is: #2ea2cc', $text_domain ),
                'default' => '#2ea2cc'
            ),
            'bcl_form_button_text_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Button Text color', $text_domain ),
                'description' => __( 'Default color is: #ffffff', $text_domain ),
                'default' => '#ffffff'
            ),
        )
    ),
    'bcl_below_form_section' => array(
        'title'  => __( 'Below Form Links', $text_domain ),
        'fields' => array(
            'bcl_reg' => array(
                'type'    => 'select',
                'label'   => __( '"Register<br>Lost your password?"<br>links', $text_domain ),
                'description' => __('Would you like to remove the "Register | Lost your password?" links?', $text_domain),
                'options' => array(
                    'no'   => __( 'No', $text_domain ),
                    'yes'   => __( 'Yes', $text_domain )
                ),
                'default' => 'no'
            ),
            'bcl_backto' => array(
                'type'    => 'select',
                'label'   => __( '"Back to" link', $text_domain ),
                'description' => __('Would you like to remove the "&larr; Back to" link?', $text_domain),
                'options' => array(
                    'no'   => __( 'No', $text_domain ),
                    'yes'   => __( 'Yes', $text_domain )
                ),
                'default' => 'no'
            ),
            'bcl_reg_color'  => array(
                'type'  => 'color',
                'label'   => __( '"Register<br>Lost your password?"<br>links color', $text_domain ),
                'description' => __( 'Default color is: #999999', $text_domain ),
                'default' => '#999999'
            ),
            'bcl_reg_hover_color'  => array(
                'type'  => 'color',
                'label'   => __( '"Register<br>Lost your password?"<br>link hover color', $text_domain ),
                'description' => __( 'Default color is: #2ea2cc', $text_domain ),
                'default' => '#2ea2cc'
            ),
            'bcl_back_color'  => array(
                'type'  => 'color',
                'label'   => __( '"Back to"<br>link color', $text_domain ),
                'description' => __( 'Default color is: #999999', $text_domain ),
                'default' => '#999999'
            ),
            'bcl_back_hover_color'  => array(
                'type'  => 'color',
                'label'   => __( '"Back to"<br>link hover color', $text_domain ),
                'description' => __( 'Default color is: #2ea2cc', $text_domain ),
                'default' => '#2ea2cc'
            ),
        )
    ),
    'bcl_login_messages_section' => array(
        'title'  => __( 'Message boxes', $text_domain ),
        'fields' => array(
            'bcl_error_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Error box<br>Vertical line color', $text_domain ),
                'description' => __( 'Default color is: #dd3d36', $text_domain ),
                'default' => '#dd3d36'
            ),
            'bcl_message_color'  => array(
                'type'  => 'color',
                'label'   => __( 'Message box<br>Vertical line color', $text_domain ),
                'description' => __( 'Default color is: #2ea2cc', $text_domain ),
                'default' => '#2ea2cc'
            ),
        )
    ),
    'bcl_custom_css_section' => array(
        'title'  => __( 'Custom CSS', $text_domain ),
        'fields' => array(
            'bcl_custom_css'  => array(
                'type'  => 'textarea',
                'label'   => __( 'Custom CSS', $text_domain ),
                'description' => __( 'If you want to add some extra CSS to your login page, you can do it here', $text_domain ).'<br>'.__( 'e.g.: If you want to move the login box 50px to the right, then you can type:', $text_domain ).'<span style="color:#2ea2cc;"> '.__( '#login {padding: 8% 0 0 50px;}', $text_domain ).'</span><br><br><strong>'.__( 'No preview for this Custom CSS', $text_domain ).'</strong>',
                'attributes' => array( 'style' => 'max-width: 50%;' ),
                'default' => ''
            ),
        )
    ),
    'bcl_preview_section'    => array(
        'title'  => __( 'Preview', $text_domain ),
        'fields' => array(
            'bcl_preview' => array(
                'type'    => 'preview',
                'label'   => __( 'Save the settings to update the preview.', $text_domain ).'<br><br>'.__( 'If you\'ve choosen a fullscreen image background, scroll down to make it appear...', $text_domain ),
                'description' => '

                    <div class="background-cover"></div>
                    <div id="pre_bg">
                        <div class="pre_login">
                            <h1><a href="#" tabindex="-1">...</a></h1>

                            <div name="loginform" id="pre_loginform" action="#" method="post">
                                <p>
                                    <label for="pre_user_login">'.__('Username', $text_domain ).'<br />
                                        <input type="text" name="log" id="pre_user_login" class="pre_input" value="" size="20" />
                                    </label>
                                </p>
                                <p>
                                    <label for="pre_user_pass">'.__('Password', $text_domain ).'<br />
                                        <input type="password" name="pwd" id="pre_user_pass" class="pre_input" value="" size="20" />
                                    </label>
                                </p>
                                <p class="pre_forgetmenot">
                                    <label for="pre_rememberme">
                                        <input name="rememberme" type="checkbox" id="pre_rememberme" value="forever"  /> '.__('Remember Me', $text_domain ).'
                                        </label>
                                </p>
                                <p class="pre_submit">
                                    <input type="submit" name="wp-submit" id="pre_wp-submit" class="button button-primary button-large" value="'.__('Log In', $text_domain ).'" />
                                </p>
                            </div>

                            <p id="pre_nav">
                                <a class="pre_reg" href="#">'.__('Register', $text_domain).'</a> | <a class="pre_pass" href="#" title="Password Lost and Found">'.__('Lost your password?', $text_domain ).'</a>
                            </p>
                            <p id="pre_backtoblog"><a href="#" title="Are you lost?">&larr; '.__('Back to', $text_domain ).' '.$blog_name.'</a></p>
                        </div>
                    </div>

                ', // End Preview Description
            )
        )
    ),
    'bcl_notice_section'    => array(
        'title'  => __( 'Premium Version', $text_domain ),
        'fields' => array(
            'bcl_notice' => array(
                'type'    => 'preview',
                'label'   => __( 'Go Premium', $text_domain ),
                'description' => '

                    <div class="molly postbox">
                        <div class="inside">
                        <p>'.__( 'Upgrade to the Premium Version to take advantage of new and more advanced features...', $text_domain ).'</p>
                        <p>&nbsp;</p>
                        <p>'.__( 'Although the free version is full-featured and can be used without any limits, the premium version provides more features:', $text_domain ).'
                        <ul>
                            <li style="padding-left:20px;">&bullet; '.__( 'Colors transparency', $text_domain ).'</li>
                            <li style="padding-left:20px;">&bullet; '.__( 'Links to external images', $text_domain ).'</li>
                            <li style="padding-left:20px;">&bullet; '.__( 'Fullscren background image or not. And controls over its position.', $text_domain ).'</li>
                            <li style="padding-left:20px;">&bullet; '.__( 'Instant preview (for Custom CSS too)', $text_domain ).'</li>
                            <li style="padding-left:20px;">&bullet; '.__( 'Settings Import / Export', $text_domain ).'</li>
                            <li style="padding-left:20px;">&bullet; '.__( 'Support via dedicated forum', $text_domain ).'</li>
                        </ul>
                        </p>
                        <p>'.__( 'And don\'t worry about your free version settings...', $text_domain ).'<br>'.__( 'You\'ll be able to retrieve them with a migration tool included in the premium version.', $text_domain ).'</p>
                        <p>&nbsp;</p>
                        <p>'.__( 'A one year license is included with each purchase. This license entitles you to free updates and priority support. You\'ll have the option to renew your license at a 50% discount within 60 days of it\'s expiration or are free to continue using the current Premium version at the time the license expires.', $text_domain ).'</p>
                        <p>&nbsp;</p>
                        <p><a href="http://www.tenbirdsflying.com/theme/birds-custom-login-premium/" class="button-primary" target="_blank">'.__( 'Go Premium Now!', $text_domain ).'</a></p>
                        </div>
                    </div>
                ', // End Premium Version
            )
        )
    ),
) );
