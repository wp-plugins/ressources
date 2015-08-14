=== Ressources ===
Contributors: bastho, ecolosites
Tags: server, linux, resources, memory, monitoring, load average, performances
Requires at least: 3.3
Tested up to: 4.1
Stable tag: /trunk
License: GPLv2

Monitoring the server resources with dashboard widgets

== Description ==

Not tested on non linux hosted sites !

WordPress monitoring, displays for the super admin, the server ressources on the (network) dashboard

* hostname and distro
* size of the wp-content directory
* available memory
* space used on linux partitions
* process running on the servers

== Installation ==

1. Upload `ressources` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress network admin

== Frequently asked questions ==

= Does this plugin works on a windows server ? =

No, this plugin only runs on linux servers.

= Does this plugin works on all wordpress instances ? =

Yes, both single nad multisite. But only the superadmin can view the monitors.

== Screenshots ==

1. widgets

== Changelog ==

= 0.4.1 =
* WP 4.3 compliant

= 0.4.0 =
* Add server informations: PHP verion, SQL version
* Optimize performances by introducing different refresh delays

= 0.3.1 =
* Better calculation of page loading duration
* French localization

= 0.3 =
* Add: Admin bar info: host name (useful for fallback)
* Add: CPU load average
* Add: Ajax refresh
* Fix: code cleanup

= 0.2 =
* Add process view

= 0.1 =
* Plugin creation :
* use SSH commands via php exec() function to display the server ressources.

== Upgrade notice ==

No particular informations