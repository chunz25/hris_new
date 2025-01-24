<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Portal Modul</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index, follow">
    <meta name="description" content="portal modul">
    <meta name="keywords" content="portal modul">
    <meta name="language" content="Indonesia">

    <link rel="shortcut icon" href="<?= config_item('url_image'); ?>old_logo.png" />
    <link rel="stylesheet" type="text/css"
        href="<?= config_item('url_template'); ?>themes_portal/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?= config_item('url_template'); ?>themes_portal/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= config_item('url_template'); ?>themes_portal/css/webstyle.css">

    <script src="<?= config_item('url_template'); ?>themes_portal/js/jquery.min.js"></script>
    <script src="<?= config_item('url_template'); ?>themes_portal/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var BASE_URL = '<?= base_url(); ?>';
        var SITE_URL = '<?= site_url(); ?>';
    </script>
</head>

<body>
    <div class="container-full">
        <header>
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="user-profile dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <?= $this->session->userdata('nama'); ?>
                                    <span class="fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="<?= site_url('app/changePassword'); ?>"><i
                                                class="fa fa-pencil pull-right"></i> Ubah Password</a></li>
                                    <?php if ($this->session->userdata('admin')): ?>
                                        <li><a href="<?= site_url('app/uploadDataPegawai'); ?>"><i
                                                    class="fa fa-file-excel-o pull-right"></i> Upload Data</a></li>
                                    <?php endif; ?>
                                    <li><a href="<?= config_item('url_logout'); ?>/logout"><i
                                                class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-envelope-o"></i>
                                    <span id="id_count_readnotif" class="badge bg-green">0</span>
                                </a>
                                <ul id="id_panelnotifikasi" class="dropdown-menu list-unstyled msg_list" role="menu">
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <aside>
            <div class="container">
                <button class="btn btn-success" style="background: #26B99A; border: 1px solid #169F85"
                    onclick="location.href='<?= site_url('app/download?filename=PP_2021-2023.pdf'); ?>'">
                    <span class="icons icons-class"><i class="fa fa-file-text-o"></i></span>
                    <span class="glyphicon-class" style="font-size:11px;"> Peraturan Perusahaan </span>
                </button>
            </div>
            <br>
            <div class="container">
                <button class="btn btn-success" style="background: #26B99A; border: 1px solid #169F85"
                    onclick="window.open('<?= site_url('app/liatprofil?filename=DIRKOM ECI.pdf'); ?>', '_blank')">
                    <span class="icons icons-class"><i class="fa fa-photo"></i></span>
                    <span class="glyphicon-class" style="font-size:11px;"> Jajaran Direksi & Komisaris </span>
                </button>
            </div>
        </aside>

        <div class="container" style="margin-top:-100px;">
            <div class="box-north">
                <div class="box-image">
                    <img alt="" src="<?= base_url('setting/eci/old_logo.png'); ?>" height="248" width="350">
                </div>
            </div>
            <div class="box-module">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <ul class="ul-box">
                            <?php foreach ($modul as $r): ?>
                                <li class="green" style="margin-top:10px">
                                    <a href="<?= config_item('base_url') . $r->name . '.php'; ?>">
                                        <span class="icons icons-class"><i class="<?= $r->icon; ?>"></i></span>
                                        <span class="glyphicon-class" style="font-size:9px;"><?= $r->deskripsi; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <footer></footer>
    </div>

    <script type="text/javascript">
        $(function () {
            var loadShortNotif = function () {
                var link = <?php echo $this->session->userdata('admin') ? "'/notifikasi/getShortNotificationHR'" : "'/notifikasi/getShortNotification'"; ?>;
                $.getJSON(SITE_URL + link)
                    .done(function (response) {
                        var html = '';
                        $.each(response.data, function (index, record) {
                            html += '<li>' +
                                '<a onclick="handleDetailApp(' + record.labelid + ');">' +
                                '<span class="message">' + record.label + '</span>' +
                                '<span class="time_notif">Jumlah: ' + record.jml + '</span>' +
                                '</a></li>';
                        });

                        html += '<li><div class="text-center"><i class="fa fa-envelope-o"></i></div></li>';
                        $('#id_panelnotifikasi').html(html);
                        $('#id_count_readnotif').text(response.count).toggle(response.count > 0);
                    })
                    .fail(function () {
                        console.error('Failed to load notifications.');
                    });
            };

            var handleDetailApp = function (labelId) {
                var pages = {
                    1: '/eservices.php/',
                    2: '/dinas.php/',
                    3: '/policies.php/',
                    4: '/contract.php/',
                    10: '/absensi.php/',
                    98: '/dailyreport.php/'
                };
                if (pages[labelId] !== undefined) {
                    window.location = BASE_URL + pages[labelId];
                } else {
                    console.warn('Invalid label ID:', labelId);
                }
            };

            loadShortNotif();

            $('#id_btnnotif').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: SITE_URL + '/notifikasi/updateReadNotif',
                    data: {},
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            loadShortNotif();
                        } else {
                            console.warn('Failed to update read notifications:', response);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error updating read notifications:', textStatus, errorThrown);
                    }
                });
            });
        });
    </script>

</body>

</html>