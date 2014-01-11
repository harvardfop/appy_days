<?php
    require("fpdf.php");
    
    // name of XML file
    if ($argc != 2 && $argc != 3) {
        exit("usage: php fop_eval_2_pdf.php evals.xml [ResponseID]\n");
    }
    $file = $argv[1];
    
    // open the file
    if (file_exists($file))
        $xml = simplexml_load_file($file);
    else
        exit("Failed to open " . $file . ".\n");
    
    // set the header and footer
    class PDF extends FPDF {
        function Header() {
            $this->SetTextColor(2, 78, 23);
            $this->SetFont("Arial", "I", 8);
            $this->Cell(80);
            $this->Cell(30, 6, "Harvard FOP Application 2013-14", 0, 0, "C");
            $this->SetTextColor(0, 0, 0);
            $this->Ln(12);
        }
        function Footer() {
            $this->SetY(-15);
            $this->SetTextColor(2, 78, 23);
            $this->SetFont("Arial", "I" ,8);
            $this->Cell(0, 6, "Page " . $this->PageNo(), 0, 0, "C");
            $this->SetTextColor(0, 0, 0);
        }
    }

    foreach($xml as $response) {
        // only create one PDF if optional ResponseID argument is given
        if ($argc == 3 && strcmp($response->{"ResponseID"}, $argv[2]) != 0) {
            continue;
        }
        
        // create a new PDF
        $filename = trim($response->{"Q2.2_1_TEXT"}) . " " . trim($response->{"Q2.2_2_TEXT"}) . " (Eval) - " . 
                    preg_replace("/[^a-z\d ]/i", "", trim($response->{"Q2.1_1_TEXT"})) . ".pdf";
        $pdf = new PDF();
        $pdf->AddPage();

        // applicant's name
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "FOP Leader Applicant's name:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $applicant = trim($response->{"Q2.2_1_TEXT"}) . " " . trim($response->{"Q2.2_2_TEXT"});
        $pdf->MultiCell(0, 6, $applicant, 0, 1);
        $pdf->Ln();

        // evaluator's name
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Evaluator's name:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $evaluator = trim($response->{"Q2.1_1_TEXT"}) . " " . trim($response->{"Q2.1_2_TEXT"});
        $pdf->MultiCell(0, 6, $evaluator, 0, 1);
        $pdf->Ln();

        // question 1
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Describe a specific incident you have witnessed in which the applicant has clearly demonstrated a capacity for leadership.", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q2.4"}), 0, 1);
        $pdf->Ln();

        // question 2
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Comment on the applicant's character in a group setting. Describe the applicant in a specific group. What was the applicant's role in the group? How did the applicant interact with others in the group?", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q2.5"}), 0, 1);
        $pdf->Ln();

        // question 3
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Relate a specific incident in which you saw the applicant deal with a difficult situation - be it a disagreement, a difficult assignment, a tough decision, or a personal hardship. How did the applicant react to the situation? How did the applicant get past the situation?", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q2.6"}), 0, 1);
        $pdf->Ln();

        // question 4
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "As described on the previous page, leading a FOP trip requires a number of technical, interpersonal and judgmental skills. How might leading a trip of 8 - 12 students in the backcountry be difficult for the applicant? Where would the applicant excel?", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q2.7"}), 0, 1);
        $pdf->Ln();

        // question 5
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "If you have first hand knowledge, address the applicant's qualifications in endurance and outdoor skills for hiking, canoeing, camping, and service.", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q2.8"}), 0, 1);
        $pdf->Ln();

        // done!
        $pdf->Output($filename, "F");
        echo $filename . " has been created!\n";
    }
?>
