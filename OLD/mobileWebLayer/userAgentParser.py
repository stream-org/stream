	
#!/usr/bin/env python
# -*- coding: utf-8 -*-
# Script Python Example
import time
import sys
import httpagentparser

s = sys.argv[1]

parsed = httpagentparser.detect(s)

# print parsed['browser']['version']

# print parsed['dist']['name']

if (parsed['dist']):


	if ((parsed['dist']['name'] == 'IPhone') or (parsed['dist']['name'] == 'IPod') or (parsed['dist']['name'] == 'IPad')):
		if (float(parsed['browser']['version']) >= 6.0):
			print "iOS_True"
		
		else:
			print "iOS_False"

	else (parsed['dist']['name'] == 'android'):
		print "android"

else:
	print "desktop"

