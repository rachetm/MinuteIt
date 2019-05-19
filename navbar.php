<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php"><span class="fa fa-clock fa_cus"></span>MinuteIt</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php"><span class="fa fa-home fa_cus"></span>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#membermanage" onclick="manage_members()"><span class="fa fa-user fa_cus"></span>Manage Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#groupmanage" onclick="disp_members('nav')"><span class="fa fa-users fa_cus"></span>Manage Group Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#add_rmv_grp" onclick="manage_groups()"><span class="fa fa-users fa_cus"></span>Add/Remove Groups</a>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item" style="margin-right: 5px;">
                    <a class="nav-link" data-toggle="modal" data-target="#chgpwd"><span class="fa fa-key fa_cus"></span>Change Password</a>
                </li>
                <li class="nav-item" style="margin-right: 5px;">
                    <a class="nav-link" href="minuteit.apk" target="_blank"><span class="fab fa-google-play fa_cus"></span>Download App</a>
                </li>
                <hr>
                <a id="logout" href="logout.php" class="btn btn-danger nav-link"><span class="fa fa-sign-out-alt fa_cus mb-1"></span>Log Out</a>
            </ul>
        </div>
    </div>
</nav>