<?php
// Load the database configuration file
include_once 'dbConfig.php';

// Get status message
if (!empty($_GET['status'])) {
    switch ($_GET['status']) {
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Members data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>CSV file data to insert Database</title>
</head>

<body>


    <div class="container">
        <h1>Location</h1>
        <!-- Display status message -->
        <?php if (!empty($statusMsg)) { ?>
            <div class="col-xs-12">
                <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
            </div>
        <?php } ?>

        <div class="row">
            <!-- Import link -->
            <div class="col-md-12 head">
                <div class="float-right">
                    <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
                </div>
            </div>
            <!-- CSV file upload form -->
            <div class="col-md-12" id="importFrm" style="display: none;">
                <form action="importData.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" />
                    <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
                </form>
            </div>

            <!-- Data list table -->
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#ID</th>
                        <th>STORE_NBR</th>
                        <th>STREET_TXT</th>
                        <th>CITY_NAME</th>
                        <th>STATE_CD</th>
                        <th>ZIP_CD</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get member rows
                    $result = $db->query("SELECT * FROM location ORDER BY id DESC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['STORE_NBR']; ?></td>
                                <td><?php echo $row['STREET_TXT']; ?></td>
                                <td><?php echo $row['CITY_NAME']; ?></td>
                                <td><?php echo $row['STATE_CD']; ?></td>
                                <td><?php echo $row['ZIP_CD']; ?></td>
                                <td><?php echo $row['Latitude']; ?></td>
                                <td><?php echo $row['Longitude']; ?></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5">No member(s) found...</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Show/hide CSV upload form -->
        <script>
            function formToggle(ID) {
                var element = document.getElementById(ID);
                if (element.style.display === "none") {
                    element.style.display = "block";
                } else {
                    element.style.display = "none";
                }
            }
        </script>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
</body>

</html>