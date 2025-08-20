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
        }

        .hide-override-xs {
            display: none;
        }

        .btn {
            width: auto;
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
</style>
<div class="container pt-5 margintopcontainer">
    <h1 class="text-center">Hizb-e-Husaini</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mbm">
                <div class="d-flex flex-row align-items-center">
                    <h4 class=" mt-5 mb-4">
                        <div class="pull-left">Manage Miqaats&nbsp;</div>
                    </h4>
                    <div class="ml-auto">
                        <a href="<?php echo base_url('anjuman/addmiqaat') ?>"><button type="button"
                                class=" btn btn-success">Add Miqaat</button></a>
                    </div>
                </div>
                <div class="list-items">
                    <ul class="list-group">
                        <li class="list-group-item hide-override-xs">
                            <div class="row">
                                <h5 class="mt0 mb0">Miqaat</h5>
                            </div>
                        </li>
                        <?php foreach ($rsvp_list as $rv) { ?>
                            <li class="list-group-item">
                                <i class="fa-regular fa-share-from-square float-left share-button"
                                    data-share-link='<?php echo base_url('accounts/rsvp/') . $rv['id'] ?>'
                                    style="font-size:17px"></i>
                                <div class="row">
                                    <div class="col-sm-12 col-md-9">
                                        <h5 class="mt0"><a
                                                href="<?php echo base_url('anjuman/modifymiqaat/') . $rv['id'] ?>"
                                                class="fontblack">
                                                <?php echo $rv['name'] ?>
                                            </a></h5>

                                        <div class="fontcertgreen fontbold">
                                            <?php echo date('D, d M', strtotime($rv['date'])) ?> @
                                            <?php echo $rv['time'] ?>
                                        </div>
                                        <div class="font-italic font-sml-1 fontgray">
                                            <?php echo $rv['hijri_date'] ?>
                                        </div>
                                        <div class="text-info fontbold mtop-5">RSVP by
                                            <?php echo date('D, d M', strtotime($rv['expired'])) ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 rsvp-col">
                                        <hr class="mbm hide dblock-xs">
                                        <div class="font-lvl-3 dinblock-xs">
                                            <div class="mbm">
                                                <?php
                                                $currentDate = strtotime(date('Y-m-d'));
                                                $expirationDate = strtotime($rv['expired']);

                                                if ($currentDate > $expirationDate) {
                                                    echo '<strong style="color:red;">Expired</strong>';
                                                } else {
                                                    echo '<strong style="color:#799840;">Upcoming</strong>';
                                                }
                                                ?>
                                            </div>
                                            <a href="<?php echo base_url('anjuman/modifymiqaat/') . $rv['id'] ?>"
                                                class="btn btn-default btn-sm rsvp-btn">
                                                Modify</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        var shareButtons = document.querySelectorAll('.share-button');

        shareButtons.forEach(function (button) {
            var shareLink = button.getAttribute('data-share-link');

            var clipboard = new ClipboardJS(button, {
                text: function () {
                    return shareLink;
                }
            });

            clipboard.on('success', function (e) {
                alert('Link copied to clipboard!');
                e.clearSelection();
            });

            clipboard.on('error', function (e) {
                alert('Unable to copy link. Please try manually.');
            });
        });
    });

</script>