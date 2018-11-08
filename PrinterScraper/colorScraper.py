import http.client, os, re, time, socket, sys, ssl, urllib.request
try:
    from bs4 import BeautifulSoup
except ImportError:
    from BeautifulSoup import BeautifulSoup

#Clears Screen
os.system('cls' if os.name == 'nt' else 'clear')

while(1):
    f = open("color_printers.txt", "r")

    printer_urls = ["10.10.11.24"]
    printer_names = ["LIBRARY-0204-color-DR"]

    itr = 0
    status = 0
    for url in printer_urls: 
    	#Appends path to all the links to specific status page
        printer_url = "http://" + url
        itr += 1 # I dont know why I placed the itr here...

        page = urllib.request.urlopen(printer_url, timeout=5)

        soup = BeautifulSoup(page, "html.parser") #Opens BS4 to start parsing

        toner = soup.find(class_="mainContentArea")
        print(toner.encode('utf-8'))