import http.client, os, re, time, socket, sys, ssl, urllib.request
try:
    from bs4 import BeautifulSoup
except ImportError:
    from BeautifulSoup import BeautifulSoup

#Clears Screen
os.system('cls' if os.name == 'nt' else 'clear')

while(1):
    f = open("printers.txt", "r")

    printer_urls = []
    printer_names = []

    for line in f: #Rips printer text file for Name string and url string
        line = line.strip('\n')
        line = line.strip('\r')
        lst = line.split(',')    
        printer_names.append(lst[0])
        printer_urls.append(lst[1])

    itr = 0
    status = 0
    for url in printer_urls: 
    	#Appends path to all the links to specific status page
        printer_url = "http://" + url + "/cgi-bin/dynamic/printer/PrinterStatus.html"
        itr += 1 # I dont know why I placed the itr here...

        try: #This mess of exceptions handles timeouts, and http errors to keep the script from crashing
        	 #This is bad practice, do not replicate..
        	 #Printer could have firmware issue, be shutoff, or took to long to respond when you get this error.
            page = urllib.request.urlopen(printer_url, timeout=5)
        except urllib.error.URLError:
            print(printer_names[itr-1] + " \x1b[0;36m could have firmware issue, be shutoff, or took to long to respond.\x1b[0;30m")
            continue
        except urllib.error.HTTPError as e:
            print(printer_names[itr-1] + " \x1b[0;36m could have firmware issue, be shutoff, or took to long to respond.\x1b[0;30m")
            continue
        except socket.timeout:
            print(printer_names[itr-1] + " \x1b[0;36m could have firmware issue, be shutoff, or took to long to respond.\x1b[0;30m")
            continue
        except http.client.HTTPException as e:
            print(printer_names[itr-1] + " \x1b[0;36m could have firmware issue, be shutoff, or took to long to respond.\x1b[0;30m")
            continue

        soup = BeautifulSoup(page, "html.parser") #Opens BS4 to start parsing

        status_array = (soup.find_all(string="Empty")) #Empty is only used for paper.
        toner = soup.find_all(class_="status_table")   #Status table is a class for different sections of

        maintenancekit = ""
        tonerlevel = ""
        rollerkit = ""
        imagingunit = ""

		#I got lazy at this point and stopped using useful names.. Good luck.
        y = toner[2].get_text().strip()
        z = y.split("\n\n")

        a = toner[0].get_text().strip() #Splits the status text at the bottom of the page into an array and removes whitespace.
        c = a.strip().split("\n")
        for b in c:
            if "Black" in b: #Finds "Black" in that array and uses the number for the toner level in that string.
                tonerlevel = b

        for x in z: #Same as finding black, but is in different table so had to be split.
            if "Maintenance" in x:
                maintenancekit = x
            if "Roller" in x:
                rollerkit = x
            if "Imaging" in x:
                imagingunit = x

        
        maintenancekit = re.findall(r'\d+', maintenancekit)
        rollerkit = re.findall(r'\d+', rollerkit)
        if not rollerkit: #Some printers didn't have a roller kit for some reason. This makes their level 100 so it doesn't break.
            rollerkit.append(u'100')
        imagingunit = re.findall(r'\d+', imagingunit)
        tonerlevel = re.findall(r'\d+', tonerlevel)

        if int(maintenancekit[0]) == 0:
            status = 2
            #print("Printer " + printer_names[itr-1] + " needs maintenance kit: " + maintenancekit[0] + "%")
            print("Tier 2 or 3 make ticket for " + printer_names[itr-1] + "'s\x1b[1;31m maintenance kit\x1b[0;30m and assign to Library IT. Make Out of Order Sign")
        if int(rollerkit[0]) == 0:
            status = 3
            #print("Printer " + printer_names[itr-1] + " needs roller kit: " + rollerkit[0] + "%")
            print("Tier 3 or 4 replace\x1b[1;31m roller kit in\x1b[0;30m " + printer_names[itr-1] + "with name tag.")
        if int(imagingunit[0]) == 0:
            status = 4
            #print("Printer " + printer_names[itr-1] + " needs imagining unit: " + imagingunit[0] + "%")
            print("Tier 3 or 4 replace\x1b[1;31m imaging unit\x1b[0;30m in " + printer_names[itr-1] + " with name tag.")
        if len(tonerlevel)!=0 and int(tonerlevel[0]) == 0:
            status = 5
            #print("Printer " + printer_names[itr-1] + " needs toner: " + tonerlevel[0] + "%")
            print("Tier 3 or 4\x1b[1;31m replace toner\x1b[0;30m in " + printer_names[itr-1] + " with name tag.")
        for x in status_array: #Looks through all trays for an Empty Status. Should really only find tray 3.
            sys.stdout.flush()
            if x=="Empty":
                status = 1
                #print("Printer " + printer_names[itr-1] + " needs paper")
                print("Tier 3 or 4 needs to\x1b[1;31m refill paper\x1b[0;30m in " + printer_names[itr-1] + " with name tag.")
                
    f.close()
    if (status == 0): #Different statuses don't really have a use yet. Was going to make a switch but python is bad.
        print("\x1b[1;32mNo printers need action\x1b[0;30m")
    time.sleep(200) #Wait 200 seconds for reset.
    os.system('cls' if os.name == 'nt' else 'clear')
