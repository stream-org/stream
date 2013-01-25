	# Suman Venkataswamy
# This script resizes all of the full size photos in the EC2 server to 1024
# so photos will fill the designated square in the iPhone app


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

print os.getcwd()

size = 1024,1024

# Get all PictureID's
cur.execute(

"SELECT PicURL FROM StreamActivity"

)


table = cur.fetchall()

for row in table:


# 
# CAUTION: THESE ARE SWITCHED BECAUSE ARE COLUMNS NAMES ARE ACCIDENTLY SWITCHED!!!!
	picURL = str(row[0])
	pictureID = str(row[1])

	print picURL
	print pictureID

	hashValue = hashlib.sha512(str(pictureID)).hexdigest()

	filename = str(hashValue) + '.jpg'
	tinyfilename = "tiny"+filename

	os.popen("touch "+filename)
	os.popen("chmod go+w "+filename)
	command = "wget -O "+filename+" "+picURL
	os.popen(command)

	im = Image.open(filename)
	im.thumbnail(size, Image.ANTIALIAS)
	im.save(tinyfilename, "JPEG")

	os.popen("mv "+filename+" ~/html/upload/StreamPictures/Pictures/")
	os.popen("mv "+tinyfilename+" ~/html/upload/StreamPictures/TinyPictures/")

	pictureFilePath = 'http://75.101.134.112/upload/StreamPictures/Pictures/' + filename
	tinyPictureFilePath = 'http://75.101.134.112/upload/StreamPictures/TinyPictures/' + tinyfilename

	cur.execute(

	"UPDATE StreamActivity SET PictureID = '%s' WHERE PicURL = '%s'" % (pictureFilePath, pictureID)

	)

	cur.execute(

	"UPDATE StreamActivity SET TinyPicURL = '%s' WHERE PicURL = '%s'" % (tinyPictureFilePath, pictureID)

	)

