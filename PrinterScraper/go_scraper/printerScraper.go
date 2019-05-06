package main

import (
	"log"
	"net/http"
	"time"
	"html/template"
	"io/ioutil"
	
	"fmt"
	"github.com/anaskhan96/soup"
	"os"
)

const Version = 0.1

/* Web page template for template library */
type Page struct {
	Title string
	Body  string
}


/* Handles the main screen */
func mainHandler(w http.ResponseWriter, req *http.Request) {
	w.Header().Set("Cache-Control", "no-cache, private, max-age=0")
	w.Header().Set("Expires", time.Unix(0, 0).Format(http.TimeFormat))
	w.Header().Set("Pragma", "no-cache")
	w.Header().Set("X-Accel-Expires", "0")

	var page Page
	page.Title = "ISU SC Printer Scraper"
	page.Body = "This is a WIP."

	t := template.New("Main")
	file, err := ioutil.ReadFile("templates/main.html")
	if err != nil {
		log.Fatal("Error, could not read main page template: ", err)
	}
	str := string(file)
	template.Must(t.Parse(str))
	t.Execute(w, &page)
}


/* Scrapes printers for necessary maitenance/service details */
func main() {
	log.Printf("PrinterScraper v%v", Version)
	scrape()
	// Set up web handlers
	http.HandleFunc("/", mainHandler)

	// Start web server
	err := http.ListenAndServe(":8000", nil)
	if err != nil {
		log.Fatal("Error, could not init web server: ", err)
	}

	for {
		time.Sleep(10 * time.Millisecond)
	}
}

func scrape(){
	fmt.Println("Scraping...")
	resp, err := soup.Get("http://10.10.11.5")
	if err != nil {
		os.Exit(1)
	}
	doc := soup.HTMLParse(resp)
	links := doc.Find("div", "id", "status_table").FindAll("a")
	for _, link := range links {
		fmt.Println(link.Text(), "| Link :", link.Attrs()["href"])
	}
}