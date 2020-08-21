<html>
    <head>
    
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
    
    </head>
    <body class="py-4">
    <div class="container">
        
<?php

// PHP para conversão do Analytics User Report para PDF
$json = file_get_contents('user-report-export.json');

$json_array = json_decode($json, true);


foreach ($json_array['dates'] as $results) {
    echo '<button type="button" class="btn btn-info">' . $results['date'] . '</button><br>';
    $seconds = 0;
    echo '<div class="row position-relative"><div class="col-12"><br><div>';
    foreach ($results['sessions'] as $duration) {
        $time = $duration['duration'];
        $parsed = date_parse($time);
        $parts = explode(':', $time);
        $seconds += (($parts[0] * 60) + $parts[1]);


        foreach ($duration['activities'] as $pages) {
            echo '<div class="container"><div class="d-flex flex-row justify-content-between"><button style="text-transform: uppercase; font-size: 11px; padding: 5px 15px; max-height: 40px;" type="button" class="list-group-item list-group-item-action d-flex">' . $pages['pageTitle'] . ' <strong> -- acesso às -- ' . $pages['time'] . '</strong></button></div></div>';
        }

    }
    $output = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);
    echo '</div></div>';
    echo '<button type="button" class="btn btn-warning position-absolute" style="top: -38px; left: 125px;">';
    echo $output . '</button></div><br>';
}

?>
        </div>
    </body>
</html>