<?php
    require("includes/fpdf.php");
    require("template.php");
    
    // take command line arguments
    if ($argc != 3 && $argc != 4) {
        exit("usage: php xml_2_pdf.php (apps/evals) (responses.xml) [ResponseID]\n");
    }

    // whether these are applications or evaluations
    $version = $argv[1];
    if (strcmp($version, "apps") != 0 && strcmp($version, "evals") != 0) {
        exit("usage: php xml_2_pdf.php (apps/evals) (responses.xml) [ResponseID]\n");
    } else  if (strcmp($version, "apps") == 0) {
        $template = $apps_template;
        $names = $apps_names;
    } else {
        $template = $evals_template;
        $names = $evals_names;
    }

    // name of the XML file
    $file = $argv[2];
    
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
            $this->Cell(30, 6, "Harvard FOP Application " . $GLOBALS["year"], 0, 0, "C");
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
    
    // make the application PDFs
    foreach($xml as $response) {        
        // only create one PDF if optional ResponseID argument is given
        if ($argc == 4 && strcmp($response->{"ResponseID"}, $argv[3]) != 0) {
            continue;
        }
        
        // create a new PDF
        $filename = trim($response->{$names[0]}) . " " . trim($response->{$names[1]});
        if (strcmp($version, "evals") == 0) {
            $filename .= " (Eval by " . preg_replace("/[^a-z\d ]/i", "", trim($response->{$names[2]}))
                        . " " . preg_replace("/[^a-z\d ]/i", "", trim($response->{$names[3]})) . ")";
        }
        $filename .= ".pdf";
        $pdf = new PDF();
        $start = true;
        
        foreach ($template as $page => $questions) {
            
            // print the section header
            $pdf->AddPage();
            $pdf->SetTextColor(2, 78, 23); 
            $pdf->SetFont("Arial", "BI", 16);
            $pdf->Cell(0, 6, $page, 0, 1, "C");        
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln();

            // print the photo once for applications only
            if ($start && strcmp($version, "apps") == 0) {
                $url = $response->{$photo . "_FILE_ID"};
                $url = str_replace("s.qualtrics", "az1.qualtrics", $url);
                $type = explode("/", $response->{$photo . "_FILE_TYPE"})[1];
                $type = strtoupper($type);

                if (strcmp($type, "JPEG") == 0 || strcmp($type, "JPG") == 0 || strcmp($type, "PNG") == 0) {
                    $size = getimagesize($url);
                    $width = $size[0];
                    $height = $size[1];
                    if ($width > $height) {
                        $pdf->Image($url, 150, 40, 40, 0, $type);                
                    } else {
                        $pdf->Image($url, 160, 40, 30, 0, $type);            
                    }
                }

                $start = false;
            }

            foreach ($questions as $question => $answer) {
                // get the answer text
                $text = iconv("UTF-8", "windows-1252//TRANSLIT", trim($response->{$answer}));

                // handle questions with multi-part answers
                if(is_array($answer)) {
                    $parts = [];
                    foreach ($answer as $part) {
                        if ($response->{$part} != "") {
                            array_push($parts, $response->{$part});
                        }
                    }
                    $text = join("; ", $parts);
                }

                // print the question text and answer
                if ($text) {
                    $pdf->SetFont("Arial", "B", 12);
                    $pdf->MultiCell(0, 6, $question, 0, 1);
                    $pdf->SetFont("Arial", "", 12);
                    $pdf->MultiCell(0, 6, $text, 0, 1);
                    $pdf->Ln();
                }
            }
        }

        // all done!
        $pdf->Output($version . "/" . $filename, "F");
        echo $filename . " has been created!\n";
    }
?>