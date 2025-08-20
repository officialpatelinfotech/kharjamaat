<style>
    .rsvp-col {
        position: relative;
    }

    .hide {
        display: none;
    }

    .rsvp-btn {
        position: absolute;
        bottom: 0;
        right: 10px;
    }

    .btn-default {
        background-color: #f3b007;
        color: white;
    }

    .fontcertgreen {
        color: #799840;
    }

    .text-info {
        color: #17a2b8;
    }

    .fontbold {
        font-weight: 700;
    }

    .fontblack {
        color: black
    }

    @media only screen and (max-width: 767px) {
        .dblock-xs {
            display: block !important;
        }

        .dinblock-xs {
            display: inline-block !important;
            width: 100%;
        }

        .hide-override-xs {
            display: none;
        }

        .btn {
            width: auto;
        }

        .hide-override-xs-view {
            border-top-width: 1px !important;
        }
    }

    .seach_btn {
        display: flex;
        justify-content: center;
        justify-content: space-between;

        @media screen and (max-width:768px) {
            flex-direction: column;
            row-gap: 1rem;
        }
    }

    .w100percent-xs {
        @media screen and (max-width:548px) {
            width: 100%;
        }
    }

    .present {
        color: #a1e722;
        font-weight: bold;
        font-size: 16px;
    }

    .absent {
        color: red;
        font-weight: bold;
        font-size: 16px;
    }

    .unmarked {
        color: blue;
        font-weight: bold;
        font-size: 16px;
    }

    .hide-override-xs-view {
        border-bottom-width: 0 !important;
    }

    .detail {
        font-size: 16px;
        font-weight: bold;
    }
</style>
<div class="container mt-5">
    <div class="row">
        <div class="col-12 mbm">
            <h4 class="clearfix mt-5 mb-5 text-center">Miqaat Attendance
            </h4>
            <div class="list-items">
                <ul class="list-group">
                    <li class="list-group-item hide-override-xs">
                        <div class="row">
                            <h5 class="mt0 mb0 col-sm-9">Miqaat</h5>
                            <h5 class="mt0 mb0 col-sm-3">RSVP</h5>
                        </div>
                    </li>
                    <?php foreach ($rsvp_list as $rv) { ?>
                        <li class="list-group-item hide-override-xs-view">
                            <div class="row">
                                <div class="col-sm-12 col-md-9">
                                    <h5 class="mt0">
                                        <p class="fontblack">
                                            <?php echo $rv['name'] ?>
                                        </p>
                                    </h5>
                                    <div class="fontcertgreen fontbold">
                                        <?php echo date('D, d M', strtotime($rv['date'])) ?> @
                                        <?php echo $rv['time'] ?>
                                    </div>
                                    <div class="font-italic font-sml-1 fontgray">
                                        <?php echo $rv['hijri_date'] ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 rsvp-col">
                                    <br class="mbm hide dblock-xs">
                                    <div class="font-lvl-3 dinblock-xs">
                                        <div>
                                            <span class="present">Present</span>
                                            <span class="present pull-right">
                                                <?php echo $rv['total_present_attendance']+$rv['total_present_guest_male']+$rv['total_present_guest_female']; ?>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="absent">Absent</span>
                                            <span class="absent pull-right">
                                                <?php echo $rv['total_absent_attendance'] ?>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="unmarked">Unmarked</span>
                                            <span class="unmarked pull-right">
                                                <?php echo $rv['total_unmarked_attendance'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="mt0 mb0 detail col-sm-3">
                                    <span class="Gents">Gents</span>
                                    <span class="pull-right">
                                        <?php echo $rv['total_present_attendance_gents'] ?>
                                    </span>
                                </div>
                                <div class="mt0 mb0 detail col-sm-3">
                                    <span class="Ladies">Ladies</span>
                                    <span class="pull-right">
                                        <?php echo $rv['total_present_attendance_ladies'] ?>
                                    </span>
                                </div>
                                <div class="mt0 mb0 detail col-sm-3">
                                    <span class="Guest">Guest Mardo</span>
                                    <span class="pull-right">
                                        <?php echo $rv['total_present_guest_male'] ?>
                                    </span>
                                </div>
                                <div class="mt0 mb0 detail col-sm-3">
                                    <span class="Guest">Guest Bairao</span>
                                    <span class="pull-right">
                                        <?php echo $rv['total_present_guest_female'] ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>