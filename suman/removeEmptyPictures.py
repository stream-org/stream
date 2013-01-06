import _mysql
import sys
import string
import re
import MySQLdb as mdb
from urllib2 import urlopen

# Connect to Database
con = mdb.connect('174.120.60.130', 'suman', 'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")

# Get list of all pictures
cur.execute(

"SELECT PicURL FROM StreamActivity"

)

pictures = cur.fetchall()

for picture in pictures:
	picURL = picture[0]
	try:
		code = urlopen(picURL).code
	except Exception, e:
		print e
		print picURL
		if (str(e) == "HTTP Error 404: Not Found"):
			print "not found"
			cur.execute(
				"""DELETE FROM StreamActivity WHERE PicURL = %s""", (picURL)

				)

