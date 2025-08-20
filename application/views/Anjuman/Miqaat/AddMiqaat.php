<div class="container d-flex justify-content-center" style="margin-top:7rem;">
    <div class="card" style="width:100%">
        <div class="card-header text-center">
            New Miqaat
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('anjuman/submitmiqaat') ?>">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Name</label>
                            <input required type="text" class="form-control" name="miqaatname"
                                placeholder="Enter Miqaat Name">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input required type="text" class="form-control" name="miqaatdesc"
                                placeholder="Enter Miqaat Description">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Date</label>
                            <input required type="date" class="form-control" name="miqaatdate">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Miqaat Time</label>
                            <input required type="time" class="form-control" name="miqaattime">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hijri Date</label>
                            <input required type="text" class="form-control" name="miqaathijridate"
                                placeholder="Enter Hiijri Date">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Last Submission Date</label>
                            <input required type="date" class="form-control" name="miqaatexpired">
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="mb-3 form-check">
                        <input type="checkbox" required class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">All Fields Are Correct</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>