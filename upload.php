<?php 
 
$dbhost="localhost";
$dbuser="root";
$dbpass="x";
$dbname="demo_data";

$conn = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->exec("SET NAMES UTF8");



use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once (getcwd().'/spout-2.4.3/src/Spout/Autoloader/autoload.php');

// check file name is not empty
if (!empty($_FILES['file']['name'])) {
	  
	// Get File extension eg. 'xlsx' to check file is excel sheet
	$pathinfo = pathinfo($_FILES["file"]["name"]);
	 
	// check file has extension xlsx, xls and also check 
	// file is not empty
   if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') 
		   && $_FILES['file']['size'] > 0 ) {
		 
		// Temporary file name
		$inputFileName = $_FILES['file']['tmp_name']; 
	
		// Read excel file by using ReadFactory object.
		$reader = ReaderFactory::create(Type::XLSX);
 
		// Open file
		$reader->open($inputFileName);
		$count = 1;
 
		// Number of sheet in excel file
		foreach ($reader->getSheetIterator() as $sheet) {
			 
			// Number of Rows in Excel sheet
			foreach ($sheet->getRowIterator() as $row) {
 
				// It reads data after header. In the my excel sheet, 
				// header is in the first row. 
				if ($count > 1) { 
 
					// Data of excel sheet
					$name = $row[0];
					$email = $row[1];
					$phone = $row[2];
					$city = $row[3];
					 
					 
					$qr = $conn->prepare("INSERT INTO excel_data(name, email, phone, city) VALUES ('$name' ,'$email','$phone','$city') ");
					$qr->execute();

				}
				$count++;
			}
		}
 
		// Close excel file
		$reader->close();
 
	} else {
 
		echo "Please Select Valid Excel File";
	}
 
} else {
 
	echo "Please Select Excel File";
	 
}
