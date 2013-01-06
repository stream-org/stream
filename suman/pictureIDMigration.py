# Suman Venkataswamy
# This script takes all PicturesID's, hashes them, and stores them into PicURL. This is because 
# we are too lazy to change the API to have correctly labeled column names

import _mysql
import sys
import string
import re
import MySQLdb as mdb
import hashlib

# Connect to Database
con = mdb.connect('174.120.60.130', 'suman', 'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")


print "test"

# Get all PictureID's
cur.execute(

"SELECT PictureID FROM StreamActivity"

)

table = cur.fetchall()

# Hashes StreamID and puts it into PicURL because we are too lazy to change the column names

for row in table:

	hashValue = hashlib.sha224(str(row[0])).hexdigest()


	cur.execute(

	"UPDATE StreamActivity SET PicURL = '%s' WHERE PictureID = '%s'" % (hashValue, str(row[0]))

	)

	