<?php
/* 
Plugin Name: gravityforms slack invitation
Plugin URI: http://www.rtcamp.com
Description: Automatic invite member to slack team using gravity form.
Version: 0.1
Author: rtCamp- Jignesh
Author URI: https://www.rtcamp.com
Text Domain: gravityformsslackinvite
*/

add_action('gform_loaded', array('GF_Slack_Invitation', 'load'), 5);

define ( 'GF_SLACK_INVITE_VERSION', '0.1' );
class GF_Slack_Invitation {

    public static function load(){
        require_once('class-gf-slack-invite.php');
        GFAddOn::register('GFSlackInvite');
    }

}

function gf_slack() {
    return GFSlackInvite::get_instance();
}