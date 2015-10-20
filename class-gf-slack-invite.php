<?php

GFForms::include_feed_addon_framework();

class GFSlackInvite extends GFFeedAddOn {

    protected $_version = GF_SLACK_INVITE_VERSION;
    protected $_min_gravityforms_version = '1.9.12';
    protected $_slug = 'gravityformsslackinvite';
    protected $_path = 'gravityforms-slack-invitation/index.php';
    protected $_full_path = __FILE__;
    protected $_url = 'http://www.rtcamp.com';
    protected $_title = 'Gravity Forms Slack Invite Add-On';
    protected $_short_title = 'Slack  Invite';
    protected $_enable_rg_autoupgrade = true;
    protected $api = null;
    private static $_instance = null;

    /* Permissions */
    protected $_capabilities_settings_page = 'gravityforms_slack_invite';
    protected $_capabilities_form_settings = 'gravityforms_slack_invite';
    protected $_capabilities_uninstall = 'gravityforms_slack_invite_uninstall';

    /* Members plugin integration */
    protected $_capabilities = array( 'gravityforms_slack_invite', 'gravityforms_slack_invite_uninstall' );

    /**
     * Get instance of this class.
     *
     * @access public
     * @static
     * @return GFSlack
     */
    public static function get_instance() {

        if ( self::$_instance == null ) {
            self::$_instance = new self;
        }

        return self::$_instance;

    }

    /**
     * @access public
     * @return void
     */
    public function init() {

        parent::init();
    }

    /**
     * Setup plugin settings fields.
     *
     * @access public
     * @return array
     */
    public function plugin_settings_fields() {

        return array(
            array(
                'title'       => '',
                'description' => $this->plugin_settings_description(),
                'fields'      => array(
                    array(
                        'name'              => 'team_domain',
                        'label'             => esc_html__( 'Team Name', 'gravityformsslackinvite' ),
                        'type'              => 'text',
                        'class'             => 'large',
                        'feedback_callback' => array( $this, 'initialize_api' )
                    ),
                    array(
                        'name'              => 'auth_token',
                        'label'             => esc_html__( 'Authentication Token', 'gravityformsslackinvite' ),
                        'type'              => 'text',
                        'class'             => 'large',
                        'feedback_callback' => array( $this, 'initialize_api' )
                    ),
                    array(
                        'type'              => 'save',
                        'messages'          => array(
                            'success' => esc_html__( 'Slack settings have been updated.', 'gravityformsslackinvite' )
                        ),
                    ),
                ),
            ),
        );

    }

    /**
     * Prepare plugin settings description.
     *
     * @access public
     * @return string $description
     */
    public function plugin_settings_description() {

        $description  = '<p>';
        $description .= sprintf(
            esc_html__( 'Slack provides simple group chat for your team. Use Gravity Forms to alert your Slack channels of a new form submission. If you don\'t have a Slack account, you can %1$s sign up for one here.%2$s', 'gravityformsslackinvite' ),
            '<a href="https://www.slack.com/" target="_blank">', '</a>'
        );
        $description .= '</p>';

        if ( ! $this->initialize_api() ) {

            $description .= '<p>';
            $description .= sprintf(
                esc_html__( 'Gravity Forms Slack Add-On requires an API authentication token. You can find your authentication token by visiting the %1$sSlack Web API page%2$s while logged into your Slack account.', 'gravityformsslackinvite' ),
                '<a href="https://api.slack.com/web" target="_blank">', '</a>'
            );
            $description .= '</p>';

        }

        return $description;

    }

    /**
     * Setup fields for feed settings.
     *
     * @access public
     * @return array $settings
     */
    public function feed_settings_fields() {
        $choices = array();
        $choices[] = array( 'label' => 'Enable','value' => 'true' );
        $choices[] = array( 'label' => 'Disable','value' => 'false' );
        $settings = array(
            array(
                'title' =>	'Invite to slack team',
                'fields' =>	array(
                    array(
                        'name'           => 'feed_name',
                        'label'          => esc_html__( 'Name', 'gravityformsslackinvite' ),
                        'type'           => 'text',
                        'class'          => 'medium',
                        'required'       => true,
                        'tooltip'        => $this->tooltip_for_feed_setting( 'feed_name' )
                    ),
                    array(
                        'name'           => 'invite',
                        'label'          => esc_html__( 'Invite user', 'gravityformsslackinvite' ),
                        'type'           => 'radio',
                        'required'       => true,
                        'choices'         => $choices,
                        'tooltip'        => $this->tooltip_for_feed_setting( 'invite' )
                    ),
                    array(
                        'name'           => 'email',
                        'label'          => esc_html__( 'Email', 'gravityformsslackinvite' ),
                        'type'           => 'text',
                        'required'       => true,
                        'class'          => 'medium merge-tag-support mt-position-right mt-hide_all_fields',
                        'tooltip'        => $this->tooltip_for_feed_setting( 'message' ),
                    ),
                )
            )
        );
        return $settings;

    }

    /**
     * Get feed tooltip.
     *
     * @access public
     * @param array $field
     * @return string
     */
    public function tooltip_for_feed_setting( $field ) {

        /* Setup tooltip array */
        $tooltips = array();
        /* Feed Name */
        $tooltips['feed_name']  = '<h6>'. esc_html__( 'Name', 'gravityformsslackinvite' ) .'</h6>';
        $tooltips['feed_name'] .= esc_html__( 'Enter a feed name to uniquely identify this setup.', 'gravityformsslackinvite' );

        /* Invite */
        $tooltips['invite']  = '<h6>'. esc_html__( 'Invite', 'gravityformsslackinvite' ) .'</h6>';
        $tooltips['invite'] .= esc_html__( 'Enable this to invite user to your team using..', 'gravityformsslackinvite' );

        $tooltips['message']  = '<h6>'. __( 'Email field to invite', 'gravityformsslackinvite' ) .'</h6>';
        $tooltips['message'] .= esc_html__( 'Select email field to send invite of slack team.', 'gravityformsslackinvite' ) . '<br /><br />';

        /* Return desired tooltip */
        return $tooltips[ $field ];

    }

    /**
     * Set feed creation control.
     *
     * @access public
     * @return bool
     */
    public function can_create_feed() {

        return $this->initialize_api();

    }

    /**
     * Setup columns for feed list table.
     *
     * @access public
     * @return array
     */
    public function feed_list_columns() {

        return array(
            'feed_name' => esc_html__( 'Name', 'gravityformsslackinvite' ),
            'enable'   => esc_html__( 'Send Invitation', 'gravityformsslackinvite' )
        );

    }
    /**
     * Get value for Invite status feed list column.
     *
     * @access public
     * @param array $feed
     * @return string
     */
    public function get_column_value_enable( $feed ) {

        /* If Slack instance is not initialized, return channel ID. */
        if ( ! $this->initialize_api() ) {
            return ucfirst( $feed['meta']['invite'] );
        }

        return ucfirst( $feed['meta']['invite'] ) ? 'Active': 'Inactive';
    }

    /**
     * Process feed.
     *
     * @access public
     * @param array $feed
     * @param array $entry
     * @param array $form
     * @return void
     */
    public function process_feed( $feed, $entry, $form ) {

        $this->log_debug( __METHOD__ . '(): Processing feed.' );

        if( ! $feed['meta']['invite'] ) {
            return;
        }
        /* If Slack instance is not initialized, exit. */
        if ( ! $this->initialize_api() ) {
            $this->add_feed_error( esc_html__( 'Feed was not processed because API was not initialized.', 'gravityformsslackinvite' ), $feed, $entry, $form );
            return;
        }

        $message = array(
            'as_user'  => false,
            'icon_url' => apply_filters( 'gform_slack_icon', $this->get_base_url() . '/images/icon.png', $feed, $entry, $form ),
            'text'     => $feed['meta']['email'],
            'username' => gf_apply_filters( 'gform_slack_username', array( $form['id'] ), 'Gravity Forms', $feed, $entry, $form ),
        );

        $message['text'] = GFCommon::replace_variables( $message['text'], $form, $entry, false, false, false, 'text' );
        if ( gf_apply_filters( 'gform_slack_process_message_shortcodes', $form['id'], false, $form, $feed ) ) {
            $message['text'] = do_shortcode( $message['text'] );
        }

        /* If message is empty, exit. */
        if ( rgblank( $message['text'] ) ) {

            $this->log_error( __METHOD__ . "(): Notification message is empty." );
            return;

        }

        $message_channel = $this->api->send_invite( $message['text'] );

        if ( rgar( $message_channel, 'ok' ) ) {
            $this->log_debug( __METHOD__ . "(): Invitation is send successfully." );
        } else {
            $this->add_feed_error( esc_html__( 'Something went wrong  while sending Invitation.', 'gravityformsslackinvite' ), $feed, $entry, $form );
        }

    }

    /**
     * Initializes Slack API if credentials are valid.
     *
     * @access public
     * @return bool
     */
    public function initialize_api() {

        if ( ! is_null( $this->api ) )
            return true;

        /* Load the API library. */
        if ( ! class_exists( 'Slack_Invite' ) ) {
            require_once( 'includes/class-slack.php' );
        }

        /* Get the OAuth token and team domain. */
        $auth_token = $this->get_plugin_setting( 'auth_token' );
        $team_domain = $this->get_plugin_setting( 'team_domain' );

        /* If the OAuth token, do not run a validation check. */
        if ( rgblank( $auth_token ) || rgblank( $team_domain ) )
            return null;

        $this->log_debug( __METHOD__ . "(): Validating API Info." );

        /* Setup a new Slack object with the API credentials. */
        $slack = new Slack_Invite( $auth_token, $team_domain );

        /* Run an authentication test. */
        $auth_test = $slack->auth_test();

        if ( rgar( $auth_test, 'ok' ) ) {

            $this->api = $slack;
            $this->log_debug( __METHOD__ . '(): API credentials are valid.' );
            return true;

        } else {

            $this->log_error( __METHOD__ . '(): API credentials are invalid; '. $auth_test['error'] );
            return false;

        }

    }

}
