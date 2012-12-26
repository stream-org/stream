	# Suman Venkataswamy
# This script resizes all of the full size photos in the EC2 server to 600x600
# so we can easily load photos on the mobile site and iPhone app

import _mysql
import sys
import string
import re
import MySQLdb as mdb

# Connect to Database
con = mdb.connect('174.120.60.130', 'suman', 'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")


print "test"

# Get all PictureID's
cur.execute(

"SELECT PictureID FROM StreamActivity WHERE TinyPicURL is NULL"

)

table = cur.fetchall()

# Hashes StreamID and puts it into PicURL because we are too lazy to change the column names

for row in table:

	print row

	# hashValue = hashlib.sha224(str(row[0])).hexdigest()


	# cur.execute(

	# "UPDATE StreamActivity SET PicURL = '%s' WHERE PictureID = '%s'" % (hashValue, str(row[0]))

	)

	