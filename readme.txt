=== Automate Slack Invite Gravityforms ===
Contributors: rtcamp, jignesh.nakrani
Donate link: http://rtcamp.com/donate/
Tags: GravityForms, Gravity Forms, Slack, Invite, Invitation, Automation
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: WordPress 3.6, Gravity form 1.9.14
Tested up to: 4.4
Stable tag: 1.1


== Description ==

With this plugin, you can send auto slack invitation via email to user who submitted a gravity form. 
This plugin works with Gravity form only. Once you have created a gravity form and do required settings, this plugin will start working.  Whenever new entries are submitted via gravity form, the respective user will receive an email with slack channel invitation link.


= Requirement =

1. Gravity Form plugin. 
2. Slack Team Name.
3. Slack Authentication Token.


= How this plugin works =

1. Set the "Team Name" and slack authentication token under Gravity Form -> Settings -> Slack Invite.
2. Now, create a gravity form, go to Form Setting -> Slack Invite and click on "Create one" link to set an email field.
3. Once the user submit a gravity form, he/she will receive a slack invite via email notification. 

Example: Let say you have a slack team group, in which you want users to come up and chat/discuss. However, you don’t know to whom you want to invite. 
So in this case, just create a gravity form with some fields and make it public on your website. Interested members will submit that form and they will automatically receives invitation email. 


Note: Your Gravity Form must have an email field. 

Development of this plugin is done on [GitHub](https://github.com/rtCamp/automate-slack-invite-gravityforms). You can report issues and suggest features.


== Installation ==

1. Install the plugin from the 'Plugins' section in your dashboard (Go to Plugins > Add New > Search and search for "automate slack invite gravityform").
2. Alternatively, you can download the plugin from the repository. Unzip it and upload it to the plugins folder of your WordPress installation (wp-content/plugins/ directory of your WordPress installation).

Activate it through the 'Plugins' section. 
Go to the Gravity Form settings -> Slack Invite and fill up required things.


== Frequently Asked Questions ==

= Where I can report a bug or can suggest a new feature

Please check [Github](https://github.com/rtCamp/automate-slack-invite-gravityforms) repo.


== Screenshots ==

1. Slack invite admin settings
2. Form email field settings
3. Sample gravity form in front end
4. User received invitation via email
5. User added to slack team


== Changelog ==

= 1.0 =
* Invite user to slack team using Gravity Forms.

= 1.1 =
* Removed function conflict.