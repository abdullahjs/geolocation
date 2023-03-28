<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LATITUDE & LONGITUDE</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwYhB5FTB2GBBqQOeZ0-WnDj8K8qZuyeo&libraries=places">
    </script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <center>
                    <input type="text" name="location" id="location" style="width: 50%" />
                    <input type="text" placeholder="Latitude" name="Latitude" id="lat" />
                    <input type="text" placeholder="Longitude" name="Longitude" id="long" />
                </center>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var autocomplete;
            var id = "location";

            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById(id), {
                    types: ["geocode"],
                }
            );
            google.maps.event.addListener(
                autocomplete,
                "place_changed",
                function() {
                    var place = autocomplete.getPlace();
                    jQuery("#lat").val(place.geometry.location.lat());
                    jQuery("#long").val(place.geometry.location.lng());
                }
            );
        });
    </script>
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