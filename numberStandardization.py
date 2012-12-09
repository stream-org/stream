# Suman Venkataswamy
# This script removes all non numeric characters from phone numbers as well as
# adds the US international area code (1) to the beginning of a phone number

import _mysql
import sys
import string
import re
import MySQLdb as mdb

# Connect to Database
con = mdb.connect('174.120.60.130', 'suman', 'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")


# Get list of all Tables
cur.execute(

"SHOW TABLES FROM suman_Stream"

)

tables = cur.fetchall()

# Applies script to every Phone Number is every table

for table in tables:

	print table[0]
	cur.execute(

	"""SELECT Phone FROM %s""" % table[0]

	)

	phoneNumbers = cur.fetchall()
	
	# Removes and non numeric characters and checks length of phone number
	# If length is 10, add US international area code
	# If not length of 11 leave blank

	for phone in phoneNumbers:

		oldPhone = phone[0]
		
		newPhone = re.sub(r'\D', '', oldPhone)
		

		if (len(newPhone) == 10):
			newPhone = "1" +newPhone

		elif (len(newPhone) != 11):
			newPhone = ""

		print newPhone

		# Update the record

		cur.execute(

		"""UPDATE %s SET Phone = %%s WHERE Phone = %%s""" % table[0], (newPhone, oldPhone)

		)




