# encoding: utf-8
'''
greenbase.BackgroundDaemon -- Runs the background thread to update the database with required external data

greenbase.BackgroundDaemon is a Daemon thread running separately of the core web site, calls Twitter API's on each of 
organizations iteratively. This has to be done carefully to avoid Twitter usage throttling

It defines classes_and_methods

@author:     selly

@copyright:  2015 organization_name. All rights reserved.

@license:    MIT License

@deffield    updated: 2015-04-30
'''

import sys
import os
import MySQLdb
import threading
import time
import twitter

import GreenbaseConfig
from argparse import ArgumentParser
from argparse import RawDescriptionHelpFormatter

__all__ = []
__version__ = 0.1
__date__ = '2015-04-29'
__updated__ = '2015-04-29'

DEBUG = 1
TESTRUN = 0
PROFILE = 0

class CLIError(Exception):
    '''Generic exception to raise and log different fatal errors.'''
    def __init__(self, msg):
        super(CLIError).__init__(type(self))
        self.msg = "E: %s" % msg
    def __str__(self):
        return self.msg
    def __unicode__(self):
        return self.msg

class BackgroundDaemon (object):
    '''
    Stores the data for the daemon including state, etc. 
    '''

    def __init__(self):
        '''
        '''
        self.config=GreenbaseConfig.GreenbaseConfig()
        self.verbose = 0
        print "Configuring host as {}".format(self.config.mysqlHost)
    
    def getConnection(self):
        for i in [1,2,3,4]:
            try:
                return MySQLdb.connect(host=self.config.mysqlHost,user=self.config.mysqlUser,
                                  passwd=self.config.mysqlPassword,db=self.config.mysqlDb)
            except Exception as e:
                print e
                print "Exception getting connection, retry {}".format(i)
        
        print "Exiting because database acquisition failed"
        exit -1
    
    def runProgram(self):
        try:
            while(True): # Loop forever, and ever, and ever.
                try:
                    db=self.getConnection()
                    cursor=db.cursor()
                    orgcount = cursor.execute ("SELECT id, twitter_handle FROM orgs WHERE twitter_handle IS NOT NULL ORDER BY id DESC")
                    orgs = cursor.fetchall()
                    cursor.close()
                    db.close()
    
                    for org in orgs:
                        if (self.verbose > 0): print "Processing org {} with Twitter Handle {}".format(org[0], org[1])
                        user = None
                        for i in [1, 2, 3, 4]:
                            try:
                                api = twitter.Api(consumer_key = 'iHRUdbaEW0gXB4ZNAB9nr0nky', consumer_secret = 'wpLUqW1NZ6F1InXlNlscTNpSn2ZWQ0nUL35FhX31uvczfJLLPF', access_token_key='22303636-hvyrAmD8ttzxXTGd5rGtV6LBT0hCz3AyPgQ9KkbOs', access_token_secret='YTvoTwkHYS8rpueoXybTZG5YL6IucfRcaVkUwKdAiUPeY')
                                user = api.VerifyCredentials()
                                if user: break
                            except Exception:
                                print "Error validating Twitter credentials, trying again, attempt {}".format(i)
                        if not user:
                            print "NO USER, exiting"
                            exit -1
                            
                        try:
                            statuses = api.GetUserTimeline (screen_name = org[1])
                            if (self.verbose >0):
                                print "Statuses returned {}".format(len(statuses))
                        except Exception as e:
                            print "Error retrieving timeline for user {}".format(org[1])
                            print e
                            print e.args                       
                        if (statuses):
                            try:
                                statuses_list = []
                                for status in statuses:
                                    if (self.verbose > 0): print "{} tweeted {} on date {}".format(org[1], status.text.encode('utf-8'), status.created_at)
                                    statuses_list.append([org[0], status.created_at, status.text, status.user.profile_image_url, status.user.description,
                                                   status.user.url])
                                if self.verbose > 0: print "Values pulled from API. Length: {}".format(len(statuses_list))
                                if len(statuses_list) > 0: 
                                    db = self.getConnection()
                                    cursor = db.cursor()
                                    cursor.execute("DELETE FROM twitter_feed WHERE org_id = {}".format(org[0]))
                                    cursor.executemany("""INSERT INTO twitter_feed (org_id, created_at, text, user_profile_image_url, 
                                    user_description, user_url)
                                    VALUES (%s, STR_TO_DATE(%s, '%%a %%b %%d %%k:%%i:%%s +0000 %%Y'), %s, %s, %s, %s)""", statuses_list)
                                    cursor.close()
                                    db.close()
                                '''
                                # in case we have trouble with the executemany version...
                                db = self.getConnection()
                                cursor = db.cursor()
                                cursor.execute("DELETE FROM twitter_feed WHERE org_id = {}".format(org[0]))
                                for status in statuses:
                                    if (self.verbose > 0): print "{} tweeted {} on date {}".format(org[1], status.text, status.created_at)
                                    cursor.execute("""INSERT INTO twitter_feed (org_id, created_at, text, user_profile_image_url, 
                                    user_description, user_url)
                                    VALUES (%s, STR_TO_DATE(%s, '%%a %%b %%d %%k:%%i:%%s +0000 %%Y'), %s, %s, %s, %s)""", 
                                    [org[0], status.created_at, status.text, status.user.profile_image_url, status.user.description,
                                                   status.user.url])
                                cursor.close()
                                db.close()
                                '''
                            except Exception as e:
                                print "Exception while storing statuses in db."
                                print e
                        time.sleep(65)
                    
                except KeyboardInterrupt:
                    return 0
                except Exception:
                    return -1
        except Exception as e:
            print "Exception caught in main daemon thread, exiting hard"
            print e
            exit -1

def main(argv=None): # IGNORE:C0111
    '''Command line options.'''

    if argv is None:
        argv = sys.argv
    else:
        sys.argv.extend(argv)

    program_name = os.path.basename(sys.argv[0])
    program_version = "v%s" % __version__
    program_build_date = str(__updated__)
    program_version_message = '%%(prog)s %s (%s)' % (program_version, program_build_date)
    program_shortdesc = __import__('__main__').__doc__.split("\n")[1]
    program_license = '''%s

  Created by selly on %s.
  Copyright 2015 Climate Collaborative. All rights reserved.

  Licensed under the MIT Open Source License
  
  Distributed on an "AS IS" basis without warranties
  or conditions of any kind, either express or implied.

USAGE
''' % (program_shortdesc, str(__date__))

    daemon = BackgroundDaemon()
    try:
        # Setup argument parser
        parser = ArgumentParser(description=program_license, formatter_class=RawDescriptionHelpFormatter)
        parser.add_argument("-v", "--verbose", dest="verbose", action="count", help="set verbosity level [default: %(default)s]")

        # Process arguments
        args = parser.parse_args()

        daemon.verbose = args.verbose

        if daemon.verbose > 0:
            print("Verbose mode on")

        t = threading.Thread(target=daemon.runProgram)
        # TODO: Change this?
        t.daemon = False
        t.start()

        return 0
    except KeyboardInterrupt:
        ### handle keyboard interrupt ###
        return 0
    except Exception, e:
        if DEBUG or TESTRUN:
            raise(e)
        indent = len(program_name) * " "
        sys.stderr.write(program_name + ": " + repr(e) + "\n")
        sys.stderr.write(indent + "  for help use --help")
        return 2

if __name__ == "__main__":
    if DEBUG:
        sys.argv.append("-v")
    if TESTRUN:
        import doctest
        doctest.testmod()
    if PROFILE:
        import cProfile
        import pstats
        profile_filename = 'greenbase.BackgroundDaemon_profile.txt'
        cProfile.run('main()', profile_filename)
        statsfile = open("profile_stats.txt", "wb")
        p = pstats.Stats(profile_filename, stream=statsfile)
        stats = p.strip_dirs().sort_stats('cumulative')
        stats.print_stats()
        statsfile.close()
        sys.exit(0)
    sys.exit(main())

