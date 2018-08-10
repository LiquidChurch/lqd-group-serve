# Liquid Group Serve

Version: 0.1 (07/19/2018)

A WordPress plugin that allows for the creation and posting of group service optunities to a WordPress site.

## Underlying Implementation

This plugin uses a custom post type (CPT), several taxonomies, and custom permalinks to store and display information relating to serving oportunities.
Sample Taxonomy Groups & Terms: 
                         Project Location:Campus/Location - Essex, Middlesex, Morris, Somerset, Union
                         Project DOW: Day of Week - Sun, Mon, Tue, Wed, Thur, Fri ,Sat, Sat-Sun
                         Project Type: Group Served - Special Needs, Hungry & Homeless, Hands Om
                         Project FFRating: All ages versus lower age limit All, 12+, 18+
                         Project Name: Project Name or Host Organisation name 
                         Project Host URL: Host Organisation Project url
                         Project Dates: Specific Date for one off projects or DOW if recurring
                         Project Teamsize: Number of volunteer openeings available at project event
                         Project Occurs: Year round, Love Week, Christmas Outreach, One Off

## Recommended Plugin

We recommend using Just Tadlock's Members plugin to restrict users to utilizing only the guides section of the administration site.

## ToDo

* Shortcodes currently use wordpress install specific term ids, need to make generic.
* Flush permalinks during activation.
* Auto-create guide types during activation.

## Authors

This plugin is a project of Liquid Church developed by Dave Mackey (<a href="https://github.com/davidshq">davidshq</a>) and Gill Crockford (<a href="https://github.com/gillcrockford">GillCrockford@mac.com</a>).
