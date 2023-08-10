<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Geocode find Latitude & Longitude form CSV file</title>
</head>

<body>

    <?php
    ini_set('MAX_EXECUTION_TIME', '-1');

    // Set your Google Maps API key here
    $api_key = 'AIzaSyDwYhB5FTB2GBBqQOeZ0-WnDj8K8qZuyeo';

    // Check if a CSV file was uploaded
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Check if the file is a CSV file
        if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
            // Open the file and read the data
            $handle = fopen($file['tmp_name'], 'r');
            $data = array();
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = $row;
            }
            fclose($handle);

            // Add headers for latitude and longitude columns
            array_push($data[0], 'Latitude', 'Longitude');

            // Loop through the data and geocode each address
            for ($i = 1; $i < count($data); $i++) {
                $address = $data[$i][0] . ', ' . $data[$i][1] . ', ' . $data[$i][2]; // Concatenate the address fields
                $address = urlencode($address); // Encode the address for use in the URL
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$api_key}";

                // Make a request to the Google Maps API
                $response = file_get_contents($url);
                $response = json_decode($response, true);

                // Check if the response contains a valid result
                if ($response['status'] === 'OK') {
                    $lat = $response['results'][0]['geometry']['location']['lat'];
                    $lng = $response['results'][0]['geometry']['location']['lng'];
                    array_push($data[$i], $lat, $lng); // Add the latitude and longitude to the row
                } else {
                    array_push($data[$i], 'N/A', 'N/A'); // If there is no valid result, add 'N/A' to the row
                }
            }

            // Create a new CSV file and write the data to it
            $filename = 'geocoded_addresses.csv';
            $handle = fopen($filename, 'w');
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);

            // Display a message with a link to download the new CSV file
            echo '<p>Geocoding complete. <a href="' . $filename . '">Download CSV file</a>.</p>';
        } else {
            echo '<p>Error: File must be a CSV file.</p>';
        }
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="post" enctype="multipart/form-data">
                    <p>Select a CSV file to geocode:</p>
                    <input type="file" name="file">
                    <br><br>
                    <input type="submit" value="Geocode">
                </form>
            </div>
        </div>
    </div>

</body>

</html>