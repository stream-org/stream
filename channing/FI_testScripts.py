
# function to insert span tags in a URL
def insertSpan(URL):
	spannedURL = ""
	for i in URL:

		if i == "." or i == ":":
			spannedURL += "<span>" + i + "</span>"
		else:
			spannedURL += i

	else:
		return spannedURL

print insertSpan('http://www.channing.com')