<?php
/* 
Plugin Name: Automate Slack Invite Gravityforms
Plugin URI: http://www.rtcamp.com/
Description: GravityForms addon to automatically invite a user to your Slack, using email address form field.
Version: 1.0.0
Author: rtCamp
Author URI: http://rtcamp.com/
Text Domain: automate-slack-invite-gravityforms
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

add_action('gform_loaded', array('GF_Slack_Invitation', 'load'), 5);

define ( 'GF_SLACK_INVITE_VERSION', '0.1' );
class GF_Slack_Invitation {

    public static function load(){
        require_once('public/class-automate-slack-invite-gravityforms.php');
        GFAddOn::register('automate_slack_invite_gravityforms_public');
    }

}

function gf_slack() {
    return automate_slack_invite_gravityforms_public::get_instance();
}