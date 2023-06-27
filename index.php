<?php
require('./fpdf/fpdf.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Process the uploaded CSV file
    if ($_FILES['csv']['error'] == UPLOAD_ERR_OK && $_FILES['csv']['tmp_name'] != '') {
        $csvFile = $_FILES['csv']['tmp_name'];

          // Read the CSV file and convert it to an array
          $data = [];
          if (($handle = fopen($csvFile, "r")) !== FALSE) {
              $skipFirstLine = true; // Flag to skip the first line
              while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                  if ($skipFirstLine) {
                      $skipFirstLine = false;
                      continue; // Skip the first line
                  }
                  $selectedData = array($row[0], $row[1], $row[2]);
                  $data[] = $selectedData;
              }
              fclose($handle);
          }

        // Generate PDF
        $pdf = new FPDF('L', 'mm', array(101.6 ,50.8));
        $pdf->AddPage();
      
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetCompression(true); // Enable compression
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetXY(5, 5);
        
        // Output CSV data in the PDF
        $pdf->SetFont('Arial', '', 8);
        foreach ($data as $row) {
       
            $pdf->Image('./img.jpg', 80, 5, 20); 

            $pdf->Cell(60, 3, 'Return Address', 0, 1); 
            $pdf->Cell(60, 3, 'Gruum Europe Ltd', 0, 1);
            $pdf->Cell(80, 3, 'Unit 3 Brookside Ind. esate', 0, 1);
            $pdf->Cell(60, 3, 'Stockport', 0, 1);
            $pdf->Cell(60, 3, 'SK1 3BJ', 0, 1);
            // foreach ($row as $cell) {
            //   $pdf->Cell(0, 5, $cell, 0, 1);
            // }

            $pdf->Cell(60, 3, $row[1], 0, 1);
            $pdf->Cell(60, 3, $row[2], 0, 1);
            $pdf->Cell(60, 3, $row[3], 0, 0);
            $pdf->Cell(20, 3, $row[0], 0, 1,'R');
        }

        // Output the PDF file
        $pdf->Output('output.pdf', 'D'); 
        // unlink($csvFile);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSV to PDF Converter</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="csv" accept=".csv">
        <input type="submit" name="submit" value="Upload CSV">
    </form>
</body>
</html>
