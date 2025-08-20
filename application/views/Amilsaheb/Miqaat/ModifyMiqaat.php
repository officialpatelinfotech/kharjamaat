<div class="container d-flex justify-content-center" style="margin-top:7rem;">
    <div class="card" style="width:100%">
        <div class="card-header text-center">
            Modify Miqaat
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('amilsaheb/submitmodifymiqaat/').$rsvp['id'] ?>">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Name</label>
                            <input required type="text" class="form-control" name="miqaatname"
                                placeholder="Enter Miqaat Name" value="<?php echo $rsvp['name'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input required type="text" class="form-control" name="miqaatdesc"
                                placeholder="Enter Miqaat Description" value="<?php echo $rsvp['description'] ?>">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Date</label>
                            <input required type="date" class="form-control" name="miqaatdate"
                                value="<?php echo $rsvp['date'] ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Time</label>
                            <input required type="time" class="form-control" name="miqaattime"
                                value="<?php echo $rsvp['time'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hijri Date</label>
                            <input required type="text" class="form-control" name="miqaathijridate"
                                value="<?php echo $rsvp['hijri_date'] ?>" placeholder="Enter Hiijri Date">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Last Submission Date</label>
                            <input required type="date" class="form-control" name="miqaatexpired"
                                value="<?php echo $rsvp['expired'] ?>">
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="mb-3 form-check">
                        <input type="checkbox" required class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">All Fields Are Correct</label>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" onclick="deletersvp()" class="btn btn-danger">Delete</button>
                    <button type="submit" class="btn btn-warning">Modify</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function deletersvp () {
        window.location = '<?php echo base_url('amilsaheb/deletemiqaat/').$rsvp['id'] ?>'
    }
</script>