<?php
// Load the database configuration file
include_once 'dbConfig.php';

// if (isset($_POST['importSubmit'])) {

//     // Allowed mime types
//     $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

//     // Validate whether selected file is a CSV file
//     if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {

//         // If the file is uploaded
//         if (is_uploaded_file($_FILES['file']['tmp_name'])) {

//             // Open uploaded CSV file with read-only mode
//             $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

//             // Skip the first line
//             fgetcsv($csvFile);

//             // Parse data from CSV file line by line
//             while (($line = fgetcsv($csvFile)) !== FALSE) {
//                 // Get row data

//                 $strnmbr   = $line[0];
//                 $address  = $line[1];
//                 $city  = $line[2];
//                 $state = $line[3];
//                 $zip = $line[4];
//                 $latitude = $line[5];
//                 $longitude = $line[6];


//                 // Check whether member already exists in the database with the same email
//                 $prevQuery = "SELECT id FROM location WHERE STORE_NBR = '" . $line[0] . "'";
//                 $prevResult = $db->query($prevQuery);

//                 if ($prevResult->num_rows > 0) {
//                     // Update member data in the database
//                     $db->query("UPDATE walgreens SET STORE_NBR = '" . $strnmbr . "', STREET_TXT = '" . $address . "', CITY_NAME = '" . $city . "', STATE_CD = '" . $state . "', ZIP_CD = '" . $zip . "', Latitude = '" . $latitude . "', Longitude = '" . $longitude . "'");
//                 } else {
//                     // Insert member data in the database
//                     $db->query("INSERT INTO walgreens ( STORE_NBR, STREET_TXT, CITY_NAME, STATE_CD, ZIP_CD, Latitude, Longitude) VALUES ('" . $strnmbr . "', '" . $address . "', '" . $city . "','" . $state . "','" . $zip . "','" . $latitude . "','" . $longitude . "')");
//                 }
//             }

//             // Close opened CSV file
//             fclose($csvFile);

//             $qstring = '?status=succ';
//         } else {
//             $qstring = '?status=err';
//         }
//     } else {
//         $qstring = '?status=invalid_file';
//     }
// }

if (isset($_POST['importSubmit'])) {

    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is a CSV file
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {

        // If the file is uploaded
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Get row data
                $strnmbr   = $line[0];
                $address  = $line[1];
                $city  = $line[2];
                $state = $line[3];
                $zip = $line[4];
                $latitude = $line[5];
                $longitude = $line[6];

                // Sanitize input data to prevent SQL injection
                $strnmbr = $db->real_escape_string($strnmbr);

                // Add leading zeros to 4-digit ZIP codes
                if (strlen($zip) == 4) {
                    $zip = str_pad($zip, 5, '0', STR_PAD_LEFT);
                }

                // Check whether STORE_NBR already exists in the database
                $prevQuery = "SELECT id FROM walgreens WHERE STORE_NBR = '$strnmbr'";
                $prevResult = $db->query($prevQuery);

                if ($prevResult->num_rows > 0) {
                    // Store already exists, you can choose to update if needed
                    // Update member data in the database
                    $updateQuery = "UPDATE walgreens
                SET STREET_TXT = '$address', CITY_NAME = '$city', STATE_CD = '$state', ZIP_CD = '$zip', Latitude = '$latitude', Longitude = '$longitude'
                WHERE STORE_NBR = '$strnmbr'";

                    $db->query($updateQuery);
                } else {
                    // Insert new store data into the database
                    $insertQuery = "INSERT INTO walgreens (STORE_NBR, STREET_TXT, CITY_NAME, STATE_CD, ZIP_CD, Latitude, Longitude)
                                    VALUES ('$strnmbr', '$address', '$city', '$state', '$zip', '$latitude', '$longitude')";

                    if ($db->query($insertQuery)) {
                        // Insert successful
                        // ...
                    } else {
                        // Insert failed
                        // ...
                    }
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
        } else {
            $qstring = '?status=err';
        }
    } else {
        $qstring = '?status=invalid_file';
    }
}


// Redirect to the listing page
header("Location: file-uploder.php" . $qstring);
