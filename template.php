<?php
	/*
	 * For all PDFs' headers, enter your year.
	 * Suggested format: "2013-14"
	 */
	$year = "2013-14";
	
	/*
	 * For the application PDFs' filenames, enter the ID numbers
	 * of the "First Name" and "Last Name" questions.
	 * Enter two and only two strings.
	 *
	 * For the evaluation PDFs' filenames, enter the ID numbers
	 * of the APPLICANT'S "First Name" and "Last Name" questions AND
	 * then the ID number of the EVALUATOR's "First Name" and
	 * "Last Name" questions.
	 * Enter four and only four strings.
	 */
	$apps_names = ["Q4.1", "Q4.3"];
	$evals_names = ["Q8.2_1_TEXT", "Q8.2_2_TEXT", // applicant
					"Q8.4_1_TEXT", "Q8.4_2_TEXT"]; // evaluator

	/*
	 * To add photos to the application PDFs, enter the ID number
	 * of the "Photo" question.
	 * Format: "Q4.11"
	 * Ignore the "_FILE_ID", etc. parts of the string.
	 */
	$photo = "Q4.11";

	/*
	 * Use the array below to lay out the application PDFs. Notes:
	 * - The first level of the array is for the section headers,
	 *   each of which will start a new page.
	 * - The "key" is the question text that will appear on the PDF.
	 * - The "value" is a question ID number.
	 * - For questions with more than one answer, enter multiple
	 *   question ID numbers in an array.
	 * - Don't forget commas at the end of rows! (But no comma for the last row!)
	 */
	$apps_template = [
		"Background Information" => [
			"First Name" => "Q4.1",
			"Nickname" => "Q4.2",
			"Last Name" => "Q4.3",
			"Year" => "Q4.5",
			"Home City" => "Q4.8",
			"Home State" => "Q4.9",
			"Certifications" => ["Q6.4_1", "Q6.4_2", "Q6.4_3", "Q6.4_4", "Q6.4_5", "Q6.4_6", "Q6.4_7_TEXT"],
			"What dates are problematic for you?" => "Q5.2",
			"In which types of trips would you be interested in receiving additional training?" => ["Q5.3_1", "Q5.3_2"],
			"Are you interested in participating in Backcountry Skillz Weekend?" => "Q5.4",
			"First Evaluator" => "Q8.2_1_TEXT",
			"In what capacity you know the evaluator?" => "Q8.2_2_TEXT",
			"Second Evaluator" => "Q8.4_1_TEXT",
			"In what capacity you know the evaluator" => "Q8.4_2_TEXT"
		],
		"Previous Experiences" => [
			"Previous outdoor experiences" => "Q6.1",
			"Previous teaching experiences" => "Q6.2",
			"(Re-applicants only:) What have you done in the past year that might enhance your ability to lead a FOP trip?" => "Q6.3"
		],
		"Short Answer Questions" => [
			"Strengths" => "Q7.2",
			"Challenges" => "Q7.3",
			"Describe a situation in which you made a difficult decision." => "Q7.4",
			"Scenario Response" => "Q7.5"
		]
	];

	/*
	 * Use the array below to lay out the evaluation PDFs.
	 * Same notes as above apply.
	 */
	$evals_template = [
		"Background Information" => [
			"Applicant First Name" => "Q2.2_1_TEXT",
			"Applicant Last Name" => "Q2.2_2_TEXT",
			"Evaluator First Name" => "Q2.1_1_TEXT",
			"Evaluator Last Name" => "Q2.1_2_TEXT"
		]
	];
?>
