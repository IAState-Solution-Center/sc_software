import http.client
import re
import socket
import sys
import urllib.request
import urllib.error
from bs4 import BeautifulSoup


class Scraper(object):

    def __init__(self):
        f = open("printers.txt", "r")

        self.printer_urls = []
        self.printer_names = []
        self.printer_statuses = []

        for line in f:  # Rips printer text file for Name string and url string
            line = line.strip('\n')
            line = line.strip('\r')
            lst = line.split(',')
            self.printer_names.append(lst[0])
            self.printer_urls.append(lst[1])

        f.close()

    def load_urls(self):
        self.printer_statuses = []
        itr = 0
        for url in self.printer_urls:
            # Appends path to all the links to specific status page
            printer_url = "http://" + url + "/cgi-bin/dynamic/printer/PrinterStatus.html"
            itr += 1  # I don't know why I placed the itr here...

            # This mess of exceptions handles timeouts, and http errors to keep the script from crashing
            try:
                # This is bad practice, do not replicate..
                # Printer could have firmware issue, be shutoff, or took to long to respond when you get this error.
                page = urllib.request.urlopen(printer_url, timeout=5)
            except urllib.error.HTTPError:
                self.printer_statuses.append(self.printer_names[itr - 1] +
                                             " could have firmware issue, be shutoff, or took to long to respond.")
                continue
            except urllib.error.URLError:
                self.printer_statuses.append(self.printer_names[itr - 1] +
                                             " could have firmware issue, be shutoff, or took to long to respond.")
                continue
            except socket.timeout:
                self.printer_statuses.append(self.printer_names[itr - 1] +
                                             " could have firmware issue, be shutoff, or took to long to respond.")
                continue
            except http.client.HTTPException:
                self.printer_statuses.append(self.printer_names[itr - 1] +
                                             " could have firmware issue, be shutoff, or took to long to respond.")
                continue

            soup = BeautifulSoup(page, "html.parser")  # Opens BS4 to start parsing
            status_array = (soup.find_all(string="Empty"))  # Empty is only used for paper.
            toner = soup.find_all(class_="status_table")  # Status table is a class for different sections of

            maintenance_kit = ""
            toner_level = ""
            roller_kit = ""
            imaging_unit = ""

            # I got lazy at this point and stopped using useful names.. Good luck.
            y = toner[2].get_text().strip()
            z = y.split("\n\n")

            # Splits the status text at the bottom of the page into an array and removes whitespace.
            a = toner[0].get_text().strip()
            c = a.strip().split("\n")
            for b in c:
                if "Black" in b:  # Finds "Black" in that array and uses the number for the toner level in that string.
                    toner_level = b

            for x in z:  # Same as finding black, but is in different table so had to be split.
                if "Maintenance" in x:
                    maintenance_kit = x
                if "Roller" in x:
                    roller_kit = x
                if "Imaging" in x:
                    imaging_unit = x

            maintenance_kit = re.findall(r'\d+', maintenance_kit)
            roller_kit = re.findall(r'\d+', roller_kit)
            # Some printers didn't have a roller kit for some reason. This makes their level 100 so it doesn't break.
            if not roller_kit:
                roller_kit.append(u'100')
            imaging_unit = re.findall(r'\d+', imaging_unit)
            toner_level = re.findall(r'\d+', toner_level)

            # Check the Maintenance Kit for 0%
            # Only Kit SolCtr cannot replace, Dell has to.
            if int(maintenance_kit[0]) == 0:
                self.printer_statuses.append("Tier 2 or 3 make ticket for " + self.printer_names[itr - 1] +
                                             "'s maintenance kit and assign to Library IT. Make Out of Order Sign")
            # Check the Roller Kit for 0%
            if int(roller_kit[0]) == 0:
                self.printer_statuses.append("Tier 3 or 4 replace roller kit in " + self.printer_names[itr - 1]
                                             + "with name tag.")
            # Check the Imaging unit for 0%
            if int(imaging_unit[0]) == 0:
                self.printer_statuses.append("Tier 3 or 4 replace imaging unit in " + self.printer_names[itr - 1]
                                             + " with name tag.")
            # Checks each printer for their toner level. Only reports at 0%
            # 1% of toner lasts too long according to Library IT
            if len(toner_level) != 0 and int(toner_level[0]) == 0:
                self.printer_statuses.append("Tier 3 or 4 replace toner in " + self.printer_names[itr - 1]
                                             + " with name tag.")
            # Check for empty paper trays
            # Looks through all trays for an Empty Status. Should really only find tray 3.
            for x in status_array:
                sys.stdout.flush()
                if x == "Empty":
                    self.printer_statuses.append("Tier 3 or 4 needs to refill paper in " + self.printer_names[itr - 1]
                                                 + " with name tag.")
            print("Done Loading")


if __name__:
    s = Scraper()
    for messages in s.printer_statuses:
        print(messages)
