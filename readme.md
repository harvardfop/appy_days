# README #

Trainees are friends, not food! Yay for more friends!

To turn your Qualtrics form data into boatloads of FOP Leader Application PDFs, follow the simple steps below:

- In Qualtrics, go to the "Reporting" tab and then the "Download Data" option.
- Select the appropriate survey.
- Under the "Data Representation" option, select "Choice Text". Under the "File Format" option, select "XML". Then click the "Download XML Data" button.
- Unzip the file (if necessary), and then drag it to the `appy_days` folder on your Desktop.
	- You can download the `appy_days` days folder (and associated code) from FOP's Bitbucket account.
	- When you download it from the cloud, make sure to change the name of the folder to `appy_days` and place it on your Desktop.
- Make any necessary edits to the `template.php` file.
- Open the "Terminal" application on your Mac.
- Type `cd Desktop/fop_app_2_pdf/` then hit Enter.
- Create the PDFs by typing `php app_2_pdf.php apps.xml [ResponseID]` where `apps.xml` is the name of the XML file you've moved to the folder and `ResponseID` is an optional argument (if you only want to create one PDF). Don't forget to hit Enter!

Enjoy!