<html>

<head>

    <head>
        <title>Error Upload Data Excel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="index, follow">
        <meta name="description" content="portal modul">
        <meta name="keywords" content="portal modul">
        <meta http-equiv="Copyright" content="">
        <meta name="author" content="">
        <meta http-equiv="imagetoolbar" content="no">
        <meta name="language" content="Indonesia">
        <meta name="revisit-after" content="7">
        <meta name="webcrawlers" content="all">
        <meta name="rating" content="general">
        <meta name="spiders" content="all">

        <link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
        <link rel="stylesheet" type="text/css"
            href="<?php echo config_item('url_template'); ?>themes_portal/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css"
            href="<?php echo config_item('url_template'); ?>themes_portal/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css"
            href="<?php echo config_item('url_template'); ?>themes_portal/css/webstyle.css">

        <script src="<?php echo config_item('url_template'); ?>themes_portal/js/jquery.min.js"></script>
        <script src="<?php echo config_item('url_template'); ?>themes_portal/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            var BASE_URL = '<?php echo base_url(); ?>';
            var SITE_URL = '<?php echo site_url(); ?>';
        </script>
    </head>

<body>
    <div class="container-full">
        <header>
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $this->session->userdata('nama'); ?>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <aside>
            <div class="panel-body">
                <div class="col-lg-12" style="margin-top:20px">
                    <?php if (!empty($status)) {
                        echo '<div class="alert alert-danger">' . $status . '</div>';
                    } ?>
                </div>
                <div class='col-md-12'>
                    <div class='col-md-6'>
                    </div>
                    <div class='col-md-6'>
                        <input type="button" class="btn btn-success" value=" Kembali "
                            onclick="window.location.href='javascript:window.history.back();'" style="float: right;">
                    </div>
                </div>
                <div class='col-md-12' style='height:15px;'></div>
                <div class='col-md-12'>
                    <div class='col-md-6'></div>
                </div>
                <div class='col-md-12' style='height:20px;'></div>
                <div class='col-md-12'>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kolom</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($show as $row): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->nama_kolom; ?></td>
                                    <td><?php echo $row->error_message; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php echo $links; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                function myFunction() {
                    var x = document.getElementById("myFile").required;
                    document.getElementById("demo").innerHTML = x;
                }
            </script>
            <style type="text/css">
                h3 {
                    font-size: 24px;
                    color: #73879C
                }

                .readtext {
                    border: none;
                    background: none;
                    -webkit-box-shadow: none;
                }

                .btn-success {
                    background: #26B99A;
                    border: 1px solid #169F85
                }

                thead {
                    background: #26B99A;
                    border: 1px solid #F5F5F5;
                    font-size: 14px;
                    color: #F5F5F5;
                    font-family: "Arial", Times, serif;
                }

                td {
                    font-size: 14px;
                    border: none;
                    background: none;
                    -webkit-box-shadow: none;
                }
            </style>
</body>

</html>