Notes on installation/configuration (including configuration on Dreamhost).

Requires a newer version of Python (REALLY!! GEEZE!)
(Thanks for help here http://stackoverflow.com/questions/5506110/is-it-possible-to-install-another-version-of-python-to-virtualenv 
and http://wiki.dreamhost.com/Python)

[companion]$ mkdir src
[companion]$ cd src
[companion]$ wget https://www.python.org/ftp/python/2.7.9/Python-2.7.9.tgz

[companion]$ tar xzf Python-2.7.9.tgz 
[companion]$ cd Python-2.7.9

[companion]$ ./configure --prefix=$HOME/opt/python-2.7.9
[companion]$ make install

-- Add this line to ~/.bash_profile

export PATH=$HOME/opt/python-2.7.9/bin:$PATH

-- Log out then log back in

[companion]$ python
Python 2.7.9 (default, Apr 29 2015, 18:59:59) 
[GCC 4.6.3] on linux2
Type "help", "copyright", "credits" or "license" for more information.


-- Now need to install pip:

[companion]$ mkdir ~/tmp
[companion]$ curl https://bootstrap.pypa.io/get-pip.py > ~/tmp/get-pip.py

[companion]$ python ~/tmp/get-pip.py --user

I recommend using VIRTUALENV for each server being hosted -- e.g. in production we have staging and production greenbase pythons
(Make sure to do this after your python version is upgraded.)
To make this work on a new host (say "host.com"):

virtual env should already be installed, if not:
[companion]$ pip install virtualenv

(REPLACE "selly" with your username)
[companion]$ virtualenv $HOME/climatebase.dreamhosters.com/env --python=/home/selly/opt/python-2.7.9/bin/python

-- A lot of stuff should scroll by making an environment. Then (contrary to virtualenv documentation), use "source" on bash to read the env:

[companion]$ cd $HOME/host.com/env/
[companion]$ source bin/activate

-- Your prompt should now say "(env)" at the beginning signalling that virtualenv is running:
(env)[companion]$ 

-- Verify your python version within the virtualenv
(env)[companion]$ python 
Python 2.7.9 (default, Apr 29 2015, 18:59:59) 
[GCC 4.6.3] on linux2
Type "help", "copyright", "credits" or "license" for more information.

we can try installing python-twitter right off the bat:

(env)[companion]$ pip install python-twitter

python
import twitter
api = twitter.Api(consumer_key = 'iHRUdbaEW0gXB4ZNAB9nr0nky', consumer_secret = 'wpLUqW1NZ6F1InXlNlscTNpSn2ZWQ0nUL35FhX31uvczfJLLPF', access_token_key='22303636-hvyrAmD8ttzxXTGd5rGtV6LBT0hCz3AyPgQ9KkbOs', access_token_secret='YTvoTwkHYS8rpueoXybTZG5YL6IucfRcaVkUwKdAiUPeY')
api.VerifyCredentials()

To generate real credentials, go here:
https://apps.twitter.com/

Install mysql

(env)[companion]$ pip install mysql

Dev note for making this work on OS X -- you have to also run this as root:

sudo ln -s /usr/local/mysql/lib/libmysqlclient.18.dylib /usr/lib/libmysqlclient.18.dylib

http://stackoverflow.com/questions/6383310/python-mysqldb-library-not-loaded-libmysqlclient-18-dylib

-------

How this program works:

1. System runs as a thread which wakes up every 65 seconds (to avoid pissing off the API rate-limiter).
2. Loops through all of the users in order.
   - statuses=api.GetUserTimeline(screen_name="Greenpeace")
   - for s in statuses:
     - store created_at, text, user.profile_image_url, user.description, user.url
   - Clear the existing tweets, and rewrite by these   

After all users have been updated, the list of users is refreshed from the database (in case there are new users). The userID's are
sorted by id *descending* so if there is a new site registered, it'll be updated first.

Creating the table:
CREATE TABLE  `twitter_feed` (
`org_id` INT NOT NULL ,
`created_at` DATETIME NOT NULL ,
`text` VARCHAR( 150 ) NOT NULL ,
`user_profile_image_url` VARCHAR( 150 ) NOT NULL ,
`user_description` VARCHAR( 150 ) NOT NULL ,
`user_url` VARCHAR( 150 ) NOT NULL
) ENGINE = MYISAM ;

For the Python Mysql engine:
http://mysql-python.sourceforge.net/MySQLdb.html#mysqldb


from greenbase import GreenbaseConfig
import MySQLdb
config=GreenbaseConfig.GreenbaseConfig()
print config.mysqlHost
db=MySQLdb.connect(host=config.mysqlHost,user=config.mysqlUser,
                  passwd=config.mysqlPassword,db=config.mysqlDb)
cursor=db.cursor()
orgcount = cursor.execute ("SELECT id, twitter_handle FROM orgs WHERE twitter_handle IS NOT NULL ORDER BY id DESC")
orgs = cursor.fetchall()
for org in orgs:
  print org[0]
  
  
INSERT INTO twitter_feed (org_id, created_at, text, user_profile_image_url, 
                                user_description, user_url)
                                VALUES (%s, STR_TO_DATE('%s, '%%a %%b %%d %%k:%%i:%%s +0000 %%Y'), %s
