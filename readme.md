# README #

Trainees are friends, not food! Yay for more friends!

To turn your Qualtrics form data into boatloads of FOP Leader Application and Evaluation PDFs, follow the simple steps below:

- In Qualtrics, go to the "Reporting" tab and then click on the "Download Data" option.
	- Do NOT click "Download Data" from the "View Results" tab.
- Select the appropriate survey.
- Under the "Data Representation" option, select "Choice Text". Under the "File Format" option, select "XML". No need to "Compress Data to .zip" unless you're into that kind of thing. Click the giant "Download XML Data" button.
- Unzip the file (if necessary), and then drag it into the `appy_days` folder on your Desktop.
	- You can download the `appy_days` days folder (and associated code) from its [GitHub repository](https://github.com/harvardfop/appy_days).
	- When you download it from the cloud, make sure to change the name of the folder to `appy_days` and place it on your Desktop.
- Make any necessary edits to the `template.php` file.
	- To format both the application and evaluation PDFs.
- Open the "Terminal" application on your Mac.
- Type `cd Desktop/appy_days/` then hit Enter.
- Create the PDFs by typing `php xml_2_pdf.php (apps/evals) (responses.xml) [ResponseID]` where
	- the first command line argument is either `apps` or `evals` (not in parentheses),
	- `responses.xml` (not in parentheses) is the name of the XML file you've moved to the folder,
	- and `ResponseID` is an optional argument (if you only want to create one PDF). Don't forget to hit Enter!
- Sit back and relax as PDFs magically appear in the appropriate folders.

Enjoy! FOP FOP FOP!
