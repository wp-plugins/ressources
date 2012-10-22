=== Ressources ===
Contributors: 8457, ecolosites
Donate link: 
Tags: server, linux, ressources, memory, monitoring
Requires at least: 3.3
Tested up to: 3.4
Stable tag: /trunk
License: CC BY-NC 3.0
License URI: http://creativecommons.org/licenses/by-nc/3.0/

Display the server ressources on the network dashboard

== Description ==

Display the server ressources on the network dashboard
- size of the wp-content directory
- available memory
- space used on linux partitions
- process running on the servers

== Installation ==

1. Upload `eelv_ressources` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress network admin
1. Go to the network admin dashboard``

== Frequently asked questions ==

= Does this plugin works on a windows server ? =

No, this plugin only runs on linux servers. 

= Does this plugin works on all wordpress instances ? =

No, this plugin only runs on multisites wordpress instances. 

== Screenshots ==

1. http://ecolosites.eelv.fr/files/2012/10/ressources.png
2. http://ecolosites.eelv.fr/files/2012/10/process.png

== Changelog ==

v 0.2 : 
Add process view

v 0.1
Plugin creation : 
use SSH commands via php exec() function to display the server ressources.

== Upgrade notice ==

No particular informations