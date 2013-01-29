	# Suman Venkataswamy
# This script rehashes all the commentIDs


import _mysql
import sys
import string
import re
import MySQLdb as mdb
import os
import Image
import hashlib

# Connect to Database
con = mdb.connect('174.120.60.130', 'suman', 'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")

# Get all PictureID's
cur.execute(

"SELECT * FROM Comments"

)


table = cur.fetchall()

for row in table:


	phone = str(row[0])
	pictureID = str(row[1])
	created = str(row[2])
	comment = str(row[3])
	commentID = str(row[4])

	hashValue = hashlib.sha512(str(created)).hexdigest()

	cur.execute(

	"UPDATE Comments SET CommentID = '%s' WHERE Created = '%s'" % (hashValue, created)

	)



