To get this image working on OS X:

1. Install Homebrew (brew.sh)
2. Install mysql ('brew install mysql')
3. Install php54 ('brew install php54 --with-mysql')
4. Install Imagick ('brew install php54-imagick');
5. Load the database into mysql
- Create a database
- Create a user with access
- Load the schema and data into the database
6. Update "config.php" to include the correct username/password/database information
- Remember if you use "Localhost" php will look for a UNIX socket--so using 127.0.0.1 frequently has better luck
- The file should look something like this (or heck could look exactly like that if you'd like :)  to do that, follow directions at the bottom)


$db_url='127.0.0.1';
$db_username='greenbase';
$db_password='abc';
$db_name='greenbase';

7. Start up apache.
8. Install WordPress
9. Call Greenbase from wordPress

----

For problems with permissions with the apache server:

http://support.apple.com/kb/TA25038
http://superuser.com/questions/158792/403-forbidden-error-on-mac-os-x-localhost

After chmodding things per these, I simply had to add the file:
/etc/apache2/users/USERNAME.conf

With the following in it:
<Directory "/Users/USERNAME/Sites/">
Options Indexes MultiViews
AllowOverride None
Order allow,deny
Allow from all
</Directory>


----

To set up a dev machine which has an unchanged config.php:

After installing MySQL,

mysql -u root
CREATE DATABASE greenbase;
GRANT ALL ON greenbase.* to 'greenbase'@'localhost' IDENTIFIED BY 'abc';
GRANT ALL ON greenbase.* to 'greenbase' IDENTIFIED BY 'abc';



-----
Integration with Wordpress:
- Install the plugin "Shortcode Exec PHP"
- pull the git repository into the "wp-content" directory. i.e., WP_CONTENT_DIR/greenbase/...
- Put the config.php into wp-content/ and edit it to work with this database.

- Log into wp-admin and create a shortcode using shortcode exec php.
- The shortcode *MUST* change directories first, so here's the twitter-feed shortcode:

------

$pre_working_directory = getcwd();

if (chdir( WP_CONTENT_DIR . '/greenbase/')) {
	include "Tweet.php";
}
chdir ($pre_working_directory);

---------

You'll see the github root referenced in the 3rd line "WP_CONTENT_DIR . '/greenbase/'". This location is critical for making things work

For the visual snapshot:
x 1. Add the AJAX root directory to the config file. Use this config parameter in the snapshot.
x 2. Update the Organization.php to work.
3. Call brad to get most recent snapshot.
4. Process URL's in tweets to highlight them and turn them to links.
5.
