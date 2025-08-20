<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .sj-card {
            width: 400px;
            margin-top: 50px;
        }

        .pull-center {
            display: table;
            margin-left: auto;
            margin-right: auto;
        }

        @media only screen and (max-width: 767px) {
            .w100percent-xs {
                width: 100% !important;
            }
        }

    </style>
</head>
<body>
    <div class="background">
        <div class="content container margintopcontainer mb-5">
            <div class="row">
                <div class="col-12">
                    <div class="card pull-center bg-light sj-card">
                        <div class="card-header">
                            <h3 class="text-center">Add New Person</h3>
                        </div>
                        <div class="card-body">
                            <form id="addUserForm">
                                <div class="form-group">
                                    <label for="fullName" class="col-form-label requiredField">Full Name<span class="asteriskField">*</span></label>
                                    <input type="text" name="fullName" class="form-control" required id="fullName">
                                    <div id="nameHelp" class="form-text">Don't use any type of special character e.g (- ,' ,/ , ..etc)</div>
                                </div>
                                <div class="form-group">
                                    <label for="itsId" class="col-form-label requiredField">ITS Id<span class="asteriskField">*</span></label>
                                    <input type="number" name="itsId" class="form-control" required id="itsId">
                                </div>
                                <div class="form-group">
                                    <label for="contact" class="col-form-label requiredField">Contact<span class="asteriskField">*</span></label>
                                    <input type="number" name="contact" class="form-control" required id="contact">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label requiredField">Email<span class="asteriskField">*</span></label>
                                    <input type="email" name="email" class="form-control" required id="email">
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" name="isHOF" class="form-check-input" id="isHOF">
                                    <label for="isHOF" class="form-check-label">Is HOF</label>
                                </div>
                                <div class="form-group">
                                    <label for="hofItsId" class="col-form-label">HOF ITS Id</label>
                                    <input type="number" name="hofItsId" class="form-control" id="hofItsId">
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success w100percent-xs">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#isHOF').change(function() {
                if ($(this).is(':checked')) {
                    $('#hofItsId').prop('disabled', true);
                    $('#hofItsId').val($('#itsId').val());
                } else {
                    $('#hofItsId').prop('disabled', false);
                    $('#hofItsId').val('');
                }
            });

            $('#addUserForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '<?php echo base_url('/admin/submitaddmumineen') ?>', 
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success('User added successfully!');
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error adding user: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
