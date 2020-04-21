
# BuddyForms Limit User Submissions by Role

- **Original Contributors:** svenl77, buddyforms
- **Fork contributors:** MorenoLaQuatra
- Tags: limit buddyforms submissions by role. 
- Requires at least: 3.9
- Tested up to: 4.7
- Stable tag: 0.1
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Fork Specific Modifications (by MorenoLaQuatra)

- The plugin now uses form_slugs parameter to limit submissions
- The printed strings have been converted in **Italian language**
- Have been distingushed 2 cases (2 different warning):
	- No submissions possible for your role
	- No more submissions available (>0 available for your role)
- Limit on submissions are:
	- `v<0` no limit
	- `v==0` no submission available
	- `v>0` v submission max 

## Description

This plugin aim at limiting the maximum number of submissions of a BuddyForm Form for a specific user role.

## Installation 

This simple plugin extend some of BuddyForms capabilities. *It requires the original buddyform plugin.*

 1. Install <a href="http://buddyforms.com" target="_blank">BuddyForms</a>.
 2. Download this repository as zip file.
 3. Go to plugin section in Wordpress -> Add new -> Upload plugin
 4. Import the zip file.


## Frequently Asked Questions

You need the BuddyForms plugin installed for the plugin to work.

<a href="http://buddyforms.com" target="_blank">Get BuddyForms now!</a>

##  Support

For **this specific fork**, please ask in the issue section. 

