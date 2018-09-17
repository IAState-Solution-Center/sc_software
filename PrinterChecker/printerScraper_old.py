import http.client, os, re, time, socket, sys, ssl, urllib.request
try:
    from bs4 import BeautifulSoup
except ImportError:
    from BeautifulSoup import BeautifulSoup

os.system('cls' if os.name == 'nt' else 'clear')

while(1):
    f = open("printers.txt", "r")

    printer_urls = []
    printer_names = []

    for line in f:
        line = line.strip('\n')
        line = line.strip('\r')
        lst = line.split(',')    
        printer_names.append(lst[0])
        printer_urls.append(lst[1])

    itr = 0
    status = 0
    for url in printer_urls:
        printer_url = "http://" + url + "/cgi-bin/dynamic/printer/PrinterStatus.html"
        ##print(printer_names[itr])
        itr += 1

        try:
            page = urllib.request.urlopen(printer_url, timeout=5)
        except urllib.error.URLError:
            print(printer_names[itr-1] + ": This printer is not responding")
            continue
        except urllib.error.HTTPError as e:
            print(printer_names[itr-1] + ": This printer is not responding")
            continue
        except socket.timeout:
            print(printer_names[itr-1] + ": This printer is not responding")
            continue
        except http.client.HTTPException as e:
            print(printer_names[itr-1] + ": This printer is not responding")
            continue

        soup = BeautifulSoup(page, "html.parser")

        status_array = (soup.find_all(string="Empty"))
        toner = soup.find_all(class_="status_table")

        maintenancekit = ""
        tonerlevel = ""
        rollerkit = ""
        imagingunit = ""

        y = toner[2].get_text().strip()
        z = y.split("\n\n")

        a = toner[0].get_text().strip()
        c = a.strip().split("\n")
        for b in c:
            if "Black" in b:
                tonerlevel = b

        for x in z:
            if "Maintenance" in x:
                maintenancekit = x
            if "Roller" in x:
                rollerkit = x
            if "Imaging" in x:
                imagingunit = x

        
        maintenancekit = re.findall(r'\d+', maintenancekit)
        rollerkit = re.findall(r'\d+', rollerkit)
        if not rollerkit:
            rollerkit.append(u'100')
        imagingunit = re.findall(r'\d+', imagingunit)
        tonerlevel = re.findall(r'\d+', tonerlevel)

        #print(maintenancekit[0] + "%\n" + rollerkit[0] + "%\n" + imagingunit[0] + "%\n" + tonerlevel[0] + "%\n")
        #print(printer_names[itr-1])
        
        if maintenancekit[0] == "0" or int(maintenancekit[0]) == 0:
            status = 2
            print("Printer " + printer_names[itr-1] + " needs maintenance kit: " + maintenancekit[0] + "%")
        if int(maintenancekit[0]) == 1:
            status = 2
            print("Printer " + printer_names[itr-1] + " will need maintenence kit soon: " + maintenancekit[0] + "%")
        if rollerkit[0] == "0" or int(rollerkit[0]) == 0:
            status = 3
            print("Printer " + printer_names[itr-1] + " needs roller kit: " + rollerkit[0] + "%")
        if int(rollerkit[0]) == 1:
            status = 3
            print("Printer " + printer_names[itr-1] + " will need roller kit soon: " + rollerkit[0] + "%")
        if imagingunit[0] == "0" or int(imagingunit[0]) == 0:
            status = 4
            print("Printer " + printer_names[itr-1] + " needs imagining unit: " + imagingunit[0] + "%")
        if int(imagingunit[0]) ==1:
            status = 4
            print("Printer " + printer_names[itr-1] + " will need imaging unit soon: " + imagingunit[0] + "%")
        if len(tonerlevel)!=0 and (tonerlevel[0] == "0" or int(tonerlevel[0]) == 0):
            status = 5
            print("Printer " + printer_names[itr-1] + " needs toner: " + tonerlevel[0] + "%")
        if len(tonerlevel)!=0 and int(tonerlevel[0]) == 1:
            status = 5
            print("Printer " + printer_names[itr-1] + " will need toner soon: " + tonerlevel[0] + "%")
        for x in status_array:
            sys.stdout.flush()
            if x=="Empty":
                status = 1
                print("Printer " + printer_names[itr-1] + " needs paper")

    f.close()
    if (status == 0):
        print("No printers need action")
    time.sleep(200)
    os.system('cls' if os.name == 'nt' else 'clear')
