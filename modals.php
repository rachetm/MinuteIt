<!-- Manage Members Modal -->
<div class="modal fade" id="membermanage" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="membermanage">Manage Members</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="mem_msgmod"></span>
                <ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link active" id="pills-add-tab" data-toggle="pill" href="#pills_mem_add" role="tab">Add</a>
                    </li>
                    <li class="nav-item" onclick="manage_members()">
                        <a class="nav-link" id="pills-rmv-tab" data-toggle="pill" href="#pills_mem_rmv" role="tab">Remove</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills_mem_add" role="tabpanel">
                        <form action="" method="POST" id="mem_add_form">
                            <div class="form-row form-group">
                                <label for="fac_name">Faculty Name</label>
                                <input type="text" class="form-control field" id="fac_name" name="facname" required>
                            </div>
                            <div class="form-row form-group">
                                <label for="fac_email">Faculty Email</label>
                                <input type="text" class="form-control field" id="fac_email" name="facemail" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="mem_add_btn">Add</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="pills_mem_rmv" role="tabpanel">
                        <form action="" method="POST" id="mem_rmv_form">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Group Members Modal -->
<div class="modal fade" id="groupmanage" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupmanage">Manage Group Members</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="msgmod"></span>
                <ul class="nav nav-fill nav-pills mb-3" id="pills-tab-grp" role="tablist">
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills" role="tabpanel">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/remove Group Modal-->
<div class="modal fade" id="add_rmv_grp" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_rmv_grp">Manage Groups</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="grp_msgmod"></span>
                <ul class="nav nav-fill nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link active" id="pills-add-tab" data-toggle="pill" href="#pills_grp_add" role="tab">Add</a>
                    </li>
                    <li class="nav-item " onclick="manage_groups()">
                        <a class="nav-link" id="pills-rmv-tab" data-toggle="pill" href="#pills_grp_rmv" role="tab">Remove</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills_grp_add" role="tabpanel">
                        <form action="" method="POST" id="grp_add_form">
                            <div class="form-row form-group">
                                <label for="grp">Group Name</label>
                                <input type="text" class="form-control field" id="grp" name="group_name" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success pr-4 pl-4" id="grp_add_btn" onclick=" add_grp()">Add</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="pills_grp_rmv" role="tabpanel">
                        <form action="" method="POST" id="grp_rmv_form">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Change Password Modal -->
<div class="modal fade" id="chgpwd" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chgpwd">Change Password</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="chgpwd.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row form-row form-group">
                            <label for="currentpassword">Current Password</label>
                            <input type="password" class="form-control field" id="currpwd" name="currentpassword" required>
                        </div>

                        <div class="row form-row form-group">
                            <label for="newpassword">New Password</label>
                            <input type="password" class="form-control field" id="password" name="newpassword" required>
                        </div>

                        <div class="row form-row form-group">
                            <label for="confirmpassword">Re-enter New Password</label>
                            <input type="password" class="form-control field" id="password2" name="confirmpassword" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" value="Confirm">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmation" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chgpwd">Are you sure?</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-danger mx-2 px-4" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success mx-2 px-3" onclick="del_meeting()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Action Taken Modal -->
<div class="modal fade" id="add_action" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_action">Action Taken</h5>
                <button style="outline:none;" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="act_msgmod"></span>
                <form action="" method="post" id="action_form">
                    <input type="hidden" id="m_id" name="m_id">
                    <div class="form-row form-group">
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <label class="action_form_label" for="datepick" class="input-custom"><b>Date</b></label>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <input class="form-control" type="date" value="<?php echo date("Y-m-d"); ?>" name="datepick" id="act_date" required="">
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <label for="action_taken"><b>Action Taken</b></label>
                        <textarea type="text" class="form-control" id="action_field" name="action_taken" rows="10" required=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <form class="form-inline" action="docGenerate.php" method="POST">
                    <input type="hidden" id="mid" name="mid">
                    <input type="hidden" id="docType" name="docType" value="action">
                    <button class="btn btn-info" type="submit"><span class="fa fa-file-word fa_cus mb-1"></span>Download</button>
                </form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="action_add_btn" onclick="add_action()">Confirm</button>
            </div>
        </div>
    </div>
</div> 