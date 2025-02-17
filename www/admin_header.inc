<?php

// Copyright 2020 Catchpoint Systems Inc.
// Use of this source code is governed by the Polyform Shield 1.0.0 license that can be
// found in the LICENSE.md file.

function header_link($url, $title)
{
    $url_parts = explode('?', $url);
    $class = ($url_parts[0] == $_SERVER['SCRIPT_NAME'] ? 'active' : 'inactive');

    echo '<li class="' . $class . '"><a href="' . $url . '">' . $title . '</a></li>';
}


?><!doctype html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap-3.1.1.min.css">

        <?php
        if (!empty($refresh)) { ?>
            <noscript>
                <meta http-equiv="refresh" content="<?php echo $refresh; ?>" />
            </noscript>
            <script>
                setTimeout("window.location.reload(true)", <?php echo $refresh * 1000; ?>);
            </script>
            <?php
        } ?>

    </head>
    <body>
        <div id="wrap">
            <div class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand ff-semibold" href="/">WebPageTest Admin</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <?php header_link('/getLocations.php?f=html', 'Locations');?>
                            <?php header_link('/getTesters.php?f=html', 'Testers');?>
                            <?php header_link('/usage.php', 'Usage');?>
                            <?php header_link('/checkURLs.php', 'Check URLs');?>
                            <?php header_link('/checkIPs.php', 'Check IPs');?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
