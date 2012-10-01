=== Plugin Name ===
Contributors: damiensaunders
Donate link: 
Tags: Database, Cron, Backup, Schedule, SQL
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 2.1

DBC Backup 2 is a safe & simple way to schedule regular WordPress database backups using the wp-cron batch jobs.

== Description ==

DBC Backup 2 can give you the confidence that your WordPress database is backed-up and securely stored on your server.
 
You select when and where your backup will be generated. The backup file is saved to directory on your web server which for many people is free storage and more reliable then saving to your home computer.

= Key Features =
* Secure - The file name includes some random characters which makes it impossible for someone to guess the backup name and download it.
* Safe - the backup directory is protected with a .htaccess and an empty index.html file which means no-one can browse or download the file via the web
* Storage - If your server has supports it, you can select between three different compression formats: none, Gzip and Bzip2. 
* Schedule - you can set hourly, daily, weekly or monthly backup
* Manual backup - anytime you want to save a backup before updating WordPress or installing a plugin you can.

= Additional Info =
The plugin will try to auto create the export directory

During backup, a log will be generated which includes, the generation date, file, filesize, status and the duration of the generation.

The backup files are identical to what phpmyadmin would produce because DBC Backup is using the key procedures of phpmyadmin. 

DBC Backup was built to be fast, flexible and as simple as possible.

I've used this plugin for several years and was disappointed that the author Chris T aka Tefra no longer maintained it so I have forked it. I tried to contact the author but without any luck.

= Checkout my other work =
* [Damien](http://damien.co/blog?utm_source=WordPress&utm_medium=dbc-backup&utm_campaign=WordPress-Plugin) - digital marketing strategy, technical development and digital marketing
* [Ideas for WordPress](http://wordpress.damien.co/?utm_source=WordPress&utm_medium=dbc-backup&utm_campaign=WordPress-Plugin)
* [Plugins for WordPress](http://wordpress.damien.co/plugins?utm_source=WordPress&utm_medium=dbc-backup&utm_campaign=WordPress-Plugin)


== Changelog ==

= Version 2.1 =
- New Admin Panel layout
- Moved DBC backup from top-level menu to under Tools (where it should be)
- Cleaned up the scheduling buttons to make it easier to use
- FIXED - uninstall wasn't deleting user options saved in the database.

= Version 2.0 =
- Submitted as 'fork' of the existing plugin
- Tested and confirmed working on WordPress 3.4.1

= Version 1.1 =
- Added option to specify the interval between crons. e.g 1 hour, 2 days, 3 weeks, 4 months etc etc
- Added option to remove older than x days backups after a new backup generation

= Version 1.0 =
- First Initial Release

== Installation ==

1. Upload the folder dbc-backup-2 to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can click on the Settings link from the Installed Plugins page or from the link 'DBC Backup' on the Tools Menu.
4. Configure the plugin settings and you are ready. You'll need to know your server path to a folder you want the backup saved.

* If the plugin can't create the export directory you will have to do it manually (folder needs to group read/write


== Frequently Asked Questions ==

= Why create a server based back-up =
It makes sense to me to keep the SQL database backup where you will most likely need it if something goes wrong. Many web hosts provide a large amount of free space for you to store files. So rather than having to pay someone else for storing your database backup you can use the free space you already have. 

= Aren't server based back-ups insecure? =
Not really, server based back-ups are only unsafe if your server is prone to fail or poorly protected from hacking.

= I want to make my backup more secure =
That's easy, the plugin creates a .htaccess file in the backup folder. You can open this file and add to this code. The backup folder is protected against browsing or direct file access. 

= Will you add a link to download the file from the web? =
No, as this would mean any anonymous user with the link could download your backup file. This would make things very insecure for you.

= Why doesn't any compression format options appear? =
Because Gzip and Bzip2 are not installed on your server.

= The plugin didn't make a backup when I setup a specific wp-cron job =
WP-Cron is different to server cron as it requires WordPress to trigger the batch jobs. This means, for example if you have no visitors on your site at the time of backup then the backup will run the next time someone browses your site. The same problem also happens with things like scheduled posts.

= Seriously? =
Yes, seriously. You can read up on wp-cron.

= How long have you been using this? and have you ever restored a site from the backup? =
About 2 years I've been using this and yes, a few times I've used a backups to build a fresh website.


= Does this work for multisite? =
Yes, it will create a backup of all blogs and sub-sites. In the future I hope to make it work for just selected blogs.



== Screenshots ==
1. Configuration settings and guidance notes
