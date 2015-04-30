'''
Created on Apr 30, 2015

@author: selly
'''

import ConfigParser

class GreenbaseConfig(object):
    '''
    Pulls all the configuratoin data for Greenbase TwitterPuller
    '''

    def __init__(self):
        '''
        Pull in the config file or set defaults and throw a warning
        '''

        config = ConfigParser.RawConfigParser()
        config.read('greenbase.cfg')
        
        self.mysqlHost = config.get("mysql", "host")
        self.mysqlDb = config.get("mysql", "db")
        self.mysqlUser = config.get("mysql", "user")
        self.mysqlPassword = config.get("mysql", "password")