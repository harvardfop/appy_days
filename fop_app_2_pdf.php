<?php
    require("fpdf.php");
    
    // name of XML file
    if ($argc != 2) {
        exit("usage: php fop_app_2_pdf.php file.xml\n");
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
        $filename = trim($response->{"Q4.1"}) . " " . trim($response->{"Q4.3"}) . ".pdf";
        $pdf = new PDF();
        
        // background information
        $pdf->AddPage();
        $pdf->SetTextColor(2, 78, 23); 
        $pdf->SetFont("Arial", "BI", 16);
        $pdf->Cell(0, 6, "Background Information", 0, 1, "C");        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        
        // name
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Name:", 0, 1);
        $name = "";
        $name .= trim($response->{"Q4.1"});
        if (trim($response->{"Q4.2"}) != false) {
            $name .= " \"" . trim($response->{"Q4.2"}) . "\"";
        }
        $name .= " " . trim($response->{"Q4.3"});
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, $name, 0, 1);
        $pdf->Ln();

        // year
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Year:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, $response->{"Q4.5"}, 0, 1);
        $pdf->Ln();
        
        // hometown
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Hometown:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $hometown = trim($response->{"Q4.8"}) . ", " . trim($response->{"Q4.9"});
        $pdf->MultiCell(0, 6, $hometown, 0, 1);
        $pdf->Ln();

        // ceritfications
        $certifications = array();
        foreach($response as $key => $value) {
            if (strncmp($key, "Q6.4", 4) == 0) {
                if (strcmp($key, "Q6.4_7") != 0 && strlen($value)) {
                    array_push($certifications, $value);
                }
            }
        }
        if (!empty($certifications)) {
            $pdf->SetFont("Arial", "B", 12);
            $pdf->MultiCell(0, 6, "Certifications:", 0, 1);
            $pdf->SetFont("Arial", "", 12);
            $pdf->MultiCell(0, 6, implode(", ", $certifications), 0, 1);
            $pdf->Ln();        
        }
        
        // training dates
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Are you able to commit to all of the training dates?", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, $response->{"Q5.1"}, 0, 1);
        $pdf->Ln();
        if (strcmp($response->{"Q5.1"}, "No") == 0) {
            $pdf->SetFont("Arial", "B", 12);
            $pdf->MultiCell(0, 6, "What dates are problematic for you?", 0, 1);
            $pdf->SetFont("Arial", "", 12);
            $pdf->MultiCell(0, 6, $response->{"Q5.2"}, 0, 1);
            $pdf->Ln();
        }

        // aditional training
        if (!empty($response->{"Q5.3_1"}) || !empty($response->{"Q5.3_2"})) {
            $pdf->SetFont("Arial", "B", 12);
            $pdf->MultiCell(0, 6, "In which types of trips would you be interested in receiving additional training?", 0, 1);
            $pdf->SetFont("Arial", "", 12);
            $additional_training = $response->{"Q5.3_1"};
            if (!empty($response->{"Q5.3_1"}) && !empty($response->{"Q5.3_2"})) {
                $additional_training .= " and ";
            }
            $additional_training .= $response->{"Q5.3_2"};
            $pdf->MultiCell(0, 6, $additional_training, 0, 1);
            $pdf->Ln();
        }
        
        // bsw
        if (!empty($response->{"Q5.4"})) {
            $pdf->SetFont("Arial", "B", 12);
            $pdf->MultiCell(0, 6, "Are you interested in participating in Backcountry Skillz Weekend?", 0, 1);
            $pdf->SetFont("Arial", "", 12);
            $pdf->MultiCell(0, 6, $response->{"Q5.4"}, 0, 1);
            $pdf->Ln();            
        }

        // photo
        $url = $response->{"Q4.11"};
        $url = str_replace("//<![CDATA[", "", $url);
        $url = str_replace("//]]>", "", $url);
        $type = explode("?", $url); // isolate the file type
        $type = $type[1];
        $type = explode("&", $type);
        $type = $type[0];       
        $type = substr($type, strrpos($type, ".") + 1);
        $type = strtoupper($type);
        if (strcmp($type, "JPG") == 0 || strcmp($type, "JPEG") == 0
            || strcmp($type, "PNG") == 0) {
            $size = getimagesize($url);
            $width = $size[0];
            $height = $size[1];
            if ($width > $height) {
                $pdf->Image($url, 150, 40, 40, 0, $type);                
            } else {
                $pdf->Image($url, 160, 40, 30, 0, $type);            
            }
        }
        
        // evaluators
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "First evaluator:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, trim($response->{"Q8.2_1_TEXT"}), 0, 1);
        $pdf->Ln();        

        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "In what capactiy you know the evaluator:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, trim($response->{"Q8.2_2_TEXT"}), 0, 1);
        $pdf->Ln(); 

        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Second evaluator:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, trim($response->{"Q8.4_1_TEXT"}), 0, 1);
        $pdf->Ln();        

        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "In what capactiy you know the evaluator:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, trim($response->{"Q8.4_2_TEXT"}), 0, 1);
        $pdf->Ln(); 

        // previous experiences
        $pdf->AddPage();
        $pdf->SetTextColor(2, 78, 23);
        $pdf->SetFont("Arial", "BI", 16);
        $pdf->Cell(0, 6, "Previous Experiences", 0, 1, "C");        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();        
        
        // previous outdoor experiences
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Previous outdoor experiences:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q6.1"}), 0, 1);
        $pdf->Ln();

        // previous teaching experiences
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Previous teaching experiences:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q6.2"}), 0, 1);
        $pdf->Ln();

        // re-applicants
        if (!empty($response->{"Q6.3"})) {
            $pdf->SetFont("Arial", "B", 12);
            $pdf->MultiCell(0, 6, "(Re-applicants only:) What have you done in the past year that might enhance your ability to lead a FOP trip?", 0, 1);
            $pdf->SetFont("Arial", "", 12);
            $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q6.3"}), 0, 1);
            $pdf->Ln();
        }
        
        // short answer questions
        $pdf->AddPage();
        $pdf->SetTextColor(2, 78, 23);
        $pdf->SetFont("Arial", "BI", 16);
        $pdf->Cell(0, 6, "Short Answer Questions", 0, 1, "C");        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();        

        // strengths
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Strengths:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q7.2"}), 0, 1);
        $pdf->Ln();

        // challenges
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Challenges:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q7.3"}), 0, 1);
        $pdf->Ln();

        // difficult decision
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Describe a situation in which you made a difficult decision:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q7.4"}), 0, 1);
        $pdf->Ln();

        // scenario response
        $pdf->SetFont("Arial", "B", 12);
        $pdf->MultiCell(0, 6, "Scenario response:", 0, 1);
        $pdf->SetFont("Arial", "", 12);
        $pdf->MultiCell(0, 6, iconv("UTF-8", "windows-1252//TRANSLIT", $response->{"Q7.5"}), 0, 1);
        $pdf->Ln();

        // done!
        $pdf->Output("../../FOP 14 Applications/" . $filename, "F");
        echo $filename . " has been created!\n";
    }
?>
