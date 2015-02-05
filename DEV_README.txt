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
- Remember if you use "Localhost" php will look for a UNIX socket--so using 127.0.0.1 has better luck
7. Start up apache.

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
