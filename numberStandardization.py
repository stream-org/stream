import _mysql
import sys
import string
import re

con = mdb.connect('174.120.60.130', 'suman', 
    'ninjas1158!', 'suman_Stream')

cur = con.cursor()
cur.execute("SELECT VERSION()")


cur.execute("SELECT Phone FROM Users")

rows = cur.fetchall()

for row in rows:
    print row



cur.execute(

"select * from information_schema.tables"

)

rows = cur.fetchall()

for row in rows:

	table = row[0]

	cur.execute(

	"SELECT PHONE FROM %s", (table)

	)

    print row  


    for row in rows:

    	oldPhone = row[0]
		newPhone =  string(oldPhone)
		newPhone = filter(newPhone.isdigit, s)
		print newPhone

		if (newPhone.length() == 10):
			newPhone = "1" +newPhone

		else:
			newPhone = ""
			
		cur.execute(

		"UPDATE %s SET Phone = %s WHERE Phone = %s", (table, newPhone, oldPhone)

		)




