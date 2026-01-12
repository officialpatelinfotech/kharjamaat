<style>
    .sj-card {
        width: 400px;
        margin-top: 50px;

        @media screen and (max-width: 768px) {
            width: 98%;
        }
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

    .secondaryform {
        display: none;
    }

    .toast-message {
        position: fixed;
        top: 10;
        right: 0;
        padding: 10px 20px;
        border-radius: 4px;
        z-index: 9999;
        display: none;
        font-size: 15px;
        animation: slideIn 0.5s, slideOut 0.5s 2s;

        @media screen and (max-width:400px) {
            width: 100%;
            text-align: center;
        }
    }

    @keyframes slideIn {
        from {
            right: -100%;
        }

        to {
            right: 0;
        }
    }

    @keyframes slideOut {
        from {
            right: 0;
        }

        to {
            right: -100%;
        }
    }
</style>
<div class="margintopcontainer">
<div class="container pt-1">
    <div class="fmain">
        <div class="card pull-center bg-light sj-card ">
            <div class="card-header text-center">New Raza Request</div>
            <div class="card-body">
                <div class="card-text">
                    <form id="form-00" class="main-form">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">0%</div>
                        </div>
                        <div class="form-group">
                            <label for="id_raza-raza" class="col-form-label  requiredField">
                                Raza for<span class="asteriskField">*</span>
                            </label>
                            <select name="raza-raza"
                                class="select2widget form-control django-select2 select2-hidden-accessible" required
                                id="raza-type" data-allow-clear="false" data-minimum-input-length="0" tabindex="-1"
                                aria-hidden="true">
                                <option value="" selected="">---------</option>
                                <?php foreach ($razatype as $rt) {
                                    echo '<option value="' . $rt['view_id'] . '">' . $rt['name'] . '</option>';
                                } ?>
                                
                            </select>
                        </div>
                        <div class="form-group"><label for="id_raza-date" class="col-form-label  requiredField">
                                Date<span class="asteriskField">*</span></label>
                            <input type="date" name="raza-date" class="dateinput form-control" min="<?php echo date('Y-m-d'); ?>" required id="raza-date">
                        </div>
                        <div id="raza-time" class="form-group">
                            <label for="id_raza-time" class="col-form-label  requiredField">
                                Time<span class="asteriskField">*</span>
                            </label>
                            <select name="raza-time" class="select form-control" required id="id_raza-time">
                                <option value="" selected="">---------</option>
                                <option value="Morning">Morning</option>
                                <option value="Zawaal">Zawaal</option>
                                <option value="Maghrib">Maghrib</option>
                            </select>
                        </div>

                        <div class="row px-3 gy-1">
                            <div class=" "><a href="<?php echo base_url('accounts/MyRazaRequest') ?>"
                                    class="btn btn-danger w100percent-xs">Cancel</a></div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-00','01',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-01-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_0" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_01"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_01"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-menu" class="form-group"><label for="id_raza-fields-menu"
                                class="col-form-label  requiredField">
                                Raza Purpose<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-menu" required
                                    class="choicefield required select form-control" id="raza_purpose_01">
                                    <option value="" selected="">---------</option>
                                    <option value="Khushi nu Jaman via thaali">Khushi nu Jaman via thaali</option>
                                    <option value="Fateha Jaman via thaali">Fateha Jaman via thaali</option>
                                    <option value="Salawat item with thaali">Salawat item with thaali</option>
                                    <option value="Fateha item with thaali">Fateha item with thaali</option>
                                </select></div>
                        </div>

                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-01-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-01-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-01-02" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 50%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">50%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_0" class="col-form-label  requiredField">
                                Any Other Details</label>
                            <textarea name="raza-other-details" rows="10" class=" textarea form-control"
                                id="id_raza-other-details"></textarea>

                        </div>

                        <div class="row px-3 gy-1">
                            <div class="">
                                <button onclick="previous('form-01-02');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-01-02','form-01-03',1);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-01-03" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 75%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">75%</div>
                        </div>
                        <table id="details-table" class="table">
                        </table>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                            <label class="form-check-label" for="exampleCheck1">All Details Correct?*</label>
                        </div>
                        <div class="row px-3 gy-1">
                            <div class="">
                                <button onclick="previous('form-01-03');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="submit_raza('form-01-03');" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-13-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_13"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_13"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-menu" class="form-group"><label for="id_raza-fields-menu"
                                class="col-form-label  requiredField">
                                Raza Purpose<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-menu" required
                                    class="choicefield required select form-control" id="raza_purpose_13">
                                    <option value="" selected="">---------</option>
                                    <option value="Aqiqa">Aqiqa</option>
                                    <option value="Chatti ni rasam">Chatti ni rasam</option>
                                </select></div>
                        </div>

                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-13-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-13-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-20-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-its_number" class="form-group"><label
                                for="id_raza-fields-its_number" class="col-form-label  requiredField">
                                ITS Number<span class="asteriskField">*</span></label>
                            <div class=""><input type="number" name="raza-fields-its_number" required
                                    class="floatfield required form-control" id="transfer-its_number">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-full_name" class="form-group"><label for="id_raza-fields-full_name"
                                class="col-form-label  requiredField">
                                Full Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-full_name" maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control" id="transfer-full_name">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-current_contact_number" class="form-group"><label
                                for="id_raza-fields-current_contact_number" class="col-form-label  requiredField">
                                Current contact number<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-current_contact_number" maxlength="2000"
                                    required="" class="charfield required textinput textInput form-control"
                                    id="transfer-current_contact_number"></div>
                        </div>
                        <div id="div_id_raza-fields-transfer_out_to_which_jamaat" class="form-group"><label
                                for="id_raza-fields-transfer_out_to_which_jamaat" class="col-form-label  requiredField">
                                Transfer out to which Jamaat?<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-transfer_out_to_which_jamaat"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="transfer-out_to_which_jamaat"><small
                                    id="hint_id_raza-fields-transfer_out_to_which_jamaat"
                                    class="form-text text-muted">Please specify exact Jamaat Name as it appears on
                                    ITS</small></div>
                        </div>
                        <div id="div_id_raza-fields-relative_its_id_in_transfer_out_jamaat_if_applicable"
                            class="form-group"><label
                                for="id_raza-fields-relative_its_id_in_transfer_out_jamaat_if_applicable"
                                class="col-form-label ">
                                Relative ITS ID (in transfer out jamaat, if applicable)
                            </label>
                            <div class=""><input type="number"
                                    name="raza-fields-relative_its_id_in_transfer_out_jamaat_if_applicable"
                                    class="floatfield form-control" id="transfer-relative_its"></div>
                        </div>
                        <div id="div_id_raza-fields-additional_notes" class="form-group"><label
                                for="id_raza-fields-additional_notes" class="col-form-label  requiredField">
                                Additional Notes<span class="asteriskField">*</span></label>
                            <div class=""><textarea name="raza-fields-additional_notes" cols="40" rows="10"
                                    maxlength="2000" required="" class="charfield required textarea form-control"
                                    id="transfer-additional_notes">afdsf</textarea></div>
                        </div>

                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-20-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-20-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-18-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_18"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_18"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-its_number_of_student" class="form-group"><label
                                for="id_raza-fields-its_number_of_student" class="col-form-label  requiredField">
                                ITS Number of Student<span class="asteriskField">*</span></label>
                            <div class=""><input type="number" name="raza-fields-its_number_of_student" required
                                    class="floatfield required form-control" id="id_raza-fields-its_number_of_student">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-full_name_student" class="form-group"><label
                                for="id_raza-fields-full_name" class="col-form-label  requiredField">
                                Full Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-full_name_student" maxlength="2000"
                                    required="" class="charfield required textinput textInput form-control"
                                    id="id_raza-fields-full_name_student"></div>
                        </div>
                        <div id="div_id_raza-fields-age_of_student" class="form-group"><label
                                for="id_raza-fields-age_of_student" class="col-form-label ">
                                Age of Student
                            </label>
                            <div class=""><input type="number" name="raza-fields-age_of_student"
                                    class="floatfield form-control" id="id_raza-fields-age_of_student"></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-18-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-18-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-14-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_14"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_14"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-venue" class="form-group"><label for="id_raza-fields-venue"
                                class="col-form-label  requiredField">
                                Venue<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-venue" required=""
                                    class="choicefield required select form-control" id="14-venue">
                                    <option value=""></option>
                                    <option value="Markaz">Markaz</option>
                                    <option value="Home" selected="">Home</option>
                                    <option value="Other">Other</option>
                                </select></div>
                        </div>
                        <div id="div_id_raza-fields-address" class="form-group"><label for="id_raza-fields-address"
                                class="col-form-label ">
                                Address (Required if venue is not markaz)
                            </label>
                            <div class=""><input type="text" name="raza-fields-address" value="hkjhk" maxlength="2000"
                                    class="charfield textinput textInput form-control" id="14-address">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-name_of_person_for_whom_raza_is_being_requested" class="form-group">
                            <label for="id_raza-fields-name_of_person_for_whom_raza_is_being_requested"
                                class="col-form-label  requiredField">
                                Name of person for whom raza is being requested<span
                                    class="asteriskField">*</span></label>
                            <div class=""><input type="text"
                                    name="raza-fields-name_of_person_for_whom_raza_is_being_requested" value="fsdfd"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="14-fields-name_of_person_for_whom_raza_is_being_requested"></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-14-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-14-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-04-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_04"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_04"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Miqaat Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control" id="miqaat_name_04">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Event Time<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control" id="Event_time_04">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Additional Host Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="Additional_Host_Name_04_01"></div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Additional Host Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="Additional_Host_Name_04_02"></div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Additional Host Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="Additional_Host_Name_04_03"></div>
                        </div>

                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-04-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-04-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-09-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_09"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_09"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-miqaat_name" class="form-group"><label
                                for="id_raza-fields-miqaat_name" class="col-form-label  requiredField">
                                Miqaat Name<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-miqaat_name" value="jljl"
                                    maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control" id="miqaat_name_09">
                            </div>
                        </div>
                        <div id="div_id_raza-fields-purpose" class="form-group"><label for="id_raza-fields-purpose"
                                class="col-form-label  requiredField">
                                Purpose<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-purpose" required=""
                                    class=" required select form-control " id="purpose_09">
                                    <option value="" selected=""></option>
                                    <option value="Salawat item in thaal">Salawat item in thaal</option>
                                    <option value="Fateha item in thaal">Fateha item in thaal</option>
                                </select>
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-09-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-09-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-10-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_10"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_10"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-menu" class="form-group"><label for="id_raza-fields-menu"
                                class="col-form-label  requiredField">
                                Raza Purpose<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-menu" required
                                    class="choicefield required select form-control" id="raza_purpose_10">
                                    <option value="" selected="">---------</option>
                                    <option value="Daress">Daress</option>
                                    <option value="Majlis">Majlis</option>
                                    <option value="Mithi Shitabi">Mithi Shitabi</option>
                                </select></div>
                        </div>
                        <div id="div_id_raza-fields-venue" class="form-group"><label for="id_raza-fields-venue"
                                class="col-form-label  requiredField">
                                Venue<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-venue" required=""
                                    class="choicefield required select form-control" id="personal_daress_venue">
                                    <option value="" selected=""></option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select></div>
                        </div>
                        <div id="div_id_raza-fields-address" class="form-group"><label for="id_raza-fields-address"
                                class="col-form-label  requiredField">
                                Address (Required)<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-address" maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="personal_daress_address"></div>
                        </div>
                        <div id="div_id_raza-fields-approximate_thaal_count" class="form-group"><label
                                for="id_raza-fields-approximate_thaal_count" class="col-form-label  requiredField">
                                Approximate thaal count<span class="asteriskField">*</span></label>
                            <div class=""><input type="number" name="raza-fields-approximate_thaal_count" required=""
                                    class="floatfield required form-control" id="personal_daress_thaal_count"></div>
                        </div>
                        <div id="div_id_raza-fields-menu" class="form-group"><label for="id_raza-fields-menu"
                                class="col-form-label  requiredField">
                                Menu<span class="asteriskField">*</span></label>
                            <div class=""><textarea name="raza-fields-menu" cols="40" rows="10" maxlength="2000"
                                    required="" class="charfield required textarea form-control"
                                    id="personal_daress_menu"></textarea></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-10-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-10-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-12-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_12"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_12"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-venue" class="form-group"><label for="id_raza-fields-venue"
                                class="col-form-label  requiredField">
                                Venue<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-venue" required=""
                                    class="choicefield required select form-control" id="personal_venue_12">
                                    <option value="" selected=""></option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select></div>
                        </div>
                        <div id="div_id_raza-fields-address" class="form-group"><label for="id_raza-fields-address"
                                class="col-form-label  requiredField">
                                Address (Required)<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-address" maxlength="2000" required=""
                                    class="charfield required textinput textInput form-control"
                                    id="personal_address_12"></div>
                        </div>
                        <div id="div_id_raza-fields-approximate_thaal_count" class="form-group"><label
                                for="id_raza-fields-approximate_thaal_count" class="col-form-label  requiredField">
                                Approximate Mumineen count<span class="asteriskField">*</span></label>
                            <div class=""><input type="number" name="raza-fields-approximate_thaal_count" required=""
                                    class="floatfield required form-control" id="personal_mumineen_count_12"></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-12-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-12-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-21-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_21"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_21"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-event_type" class="form-group"><label
                                for="id_raza-fields-event_type" class="col-form-label  requiredField">
                                Event Type<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-event_type" required="" class="select form-control"
                                    id="personal_event_type">
                                    <option value="" selected=""></option>
                                    <option value="Darees">Darees</option>
                                    <option value="Majlis">Majlis</option>
                                    <option value="Misaaq">Misaaq</option>
                                    <option value="Mithi Shitabi">Mithi Shitabi</option>
                                    <option value="NIkaah">NIkaah</option>
                                    <option value="Tasbeeh">Tasbeeh</option>
                                </select>

                            </div>
                        </div>
                        <div id="div_id_raza-fields-approximate_thaal_count" class="form-group"><label
                                for="id_raza-fields-approximate_thaal_count" class="col-form-label  requiredField">
                                Approximate thaal count<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-approximate_thaal_count" maxlength="2000"
                                    required="" class="textinput textInput form-control"
                                    id="personal_event_thaal_count">

                            </div>
                        </div>
                        <div id="div_id_raza-fields-menu_if_available" class="form-group"><label
                                for="id_raza-fields-menu_if_available" class="col-form-label ">
                                Menu (if available)
                            </label>
                            <div class=""><textarea name="raza-fields-menu_if_available" cols="40" rows="10"
                                    maxlength="2000" class="charfield textarea form-control"
                                    id="personal_event_menu_if_available"></textarea></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-21-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-21-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-16-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_16"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_16"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-16-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-16-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-08-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_08"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_08"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-08-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-08-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-07-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_07"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_07"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-name_of_kitaab" class="form-group">
                            <label for="id_raza-fields-name_of_kitaab" class="col-form-label  requiredField">
                            Name of Kitaab<span class="asteriskField">*</span></label>
                            <div class="">
                                <input type="text" name="raza-fields-name_of_kitaab" maxlength="2000" required="" 
                                class="charfield required textinput textInput form-control " 
                                id="sabaq_name_of_kitaab">   
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-07-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-07-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-15-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_15"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_15"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-name_of_guzarnar" class="form-group">
                            <label for="id_raza-fields-name_of_guzarnar" class="col-form-label  requiredField">
                                Name of guzarnar<span class="asteriskField">*</span>
                            </label>
                            <div class="">
                                <input type="text" name="raza-fields-name_of_guzarnar" maxlength="2000" required="" 
                                    class="charfield required textinput textInput form-control" id="name_of_guzarnar">
                                </div>
                        </div>
                        <div id="div_id_raza-fields-venue" class="form-group">
                            <label for="id_raza-fields-venue" class="col-form-label  requiredField">
                                Venue<span class="asteriskField">*</span></label>
                                <div class="">
                                    <select name="raza-fields-venue" required="" class="choicefield required select form-control" 
                                        id="fateha_venue">
                                        <option value="" selected=""></option>
                                        <option value="Markaz">Markaz</option>
                                        <option value="Home">Home</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                        </div>
                        <div id="div_id_raza-fields-address_required_if_venue_is_not_markaz" class="form-group"><label
                                for="id_raza-fields-address_required_if_venue_is_not_markaz" class="col-form-label ">
                                Address (Required if venue is not markaz)
                            </label>
                            <div class=""><input type="text" name="raza-fields-address_required_if_venue_is_not_markaz"
                                    maxlength="2000" class="charfield textinput textInput form-control"
                                    id="address_optional"></div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-15-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-15-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-06-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_06"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_06"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-raza_purpose_if_purpose_is_other_please_specify_below" class="form-group"><label
                                for="id_raza-fields-raza_purpose_if_purpose_is_other_please_specify_below"
                                class="col-form-label  requiredField">
                                Raza Purpose (If Purpose is "other" please specify below)<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-raza_purpose_if_purpose_is_other_please_specify_below" required=""
                                    class="choicefield required select form-control "
                                    id="raza_purpose_06">
                                    <option value="" selected=""></option>
                                    <option value="Hajj">Hajj</option>
                                    <option value="Misaaq">Misaaq</option>
                                    <option value="Nikah">Nikah</option>
                                    <option value="Renewal">Renewal</option>
                                    <option value="Umrah">Umrah</option>
                                    <option value="Ziyarat">Ziyarat</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-other_description" class="form-group"><label for="other_purpose_06"
                                class="col-form-label ">
                                Other Description
                            </label>
                            <div class=""><input type="text" name="raza-fields-other_description" maxlength="2000"
                                    class="charfield textinput textInput form-control" id="other_purpose_06"></div>
                        </div>
                        <div id="div_id_raza-fields-its_numbers_type_full_family_if_you_need_to_generate_for_all" class="form-group"><label
                                for="id_raza-fields-its_numbers_type_full_family_if_you_need_to_generate_for_all"
                                class="col-form-label  requiredField">
                                ITS Number(s) (Type "Full Family" if you need to generate for all)<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="raza-fields-its_numbers_type_full_family_if_you_need_to_generate_for_all"
                                    maxlength="2000" required="" class="charfield required textinput textInput form-control "
                                    id="all_its_06">
                                
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-06-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-06-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="form-05-01" class="secondaryform">
                        <div class="progress mb-4">
                            <div role="progressbar" style="width: 25%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar bg-warning">25%</div>
                        </div>
                        <div id="div_id_raza-fields-are_you_in_riba_13" class="form-group"><label
                                for="id_raza-fields-are_you_in_riba_13" class="col-form-label  requiredField">
                                Are you in riba ?<span class="asteriskField">*</span></label>
                            <div class="">
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_1"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_05"
                                            id="id_id_raza-fields-are_you_in_riba_0_1" value="No" required="required">
                                        No
                                    </label></div>
                                <div class="form-check"><label for="id_id_raza-fields-are_you_in_riba_0_2"
                                        class="form-check-label"><input type="radio" class="form-check-input"
                                            name="raza-fields-are_you_in_riba_05"
                                            id="id_id_raza-fields-are_you_in_riba_0_2" value="Yes" required="required">
                                        Yes
                                    </label></div>
                            </div>
                        </div>
                        <div id="div_id_raza-fields-raza_purpose_if_purpose_is_other_please_specify_below" class="form-group"><label
                                for="id_raza-fields-raza_purpose_if_purpose_is_other_please_specify_below"
                                class="col-form-label  requiredField">
                                Raza Purpose<span class="asteriskField">*</span></label>
                            <div class=""><select name="raza-fields-raza_purpose_if_purpose_is_other_please_specify_below" required=""
                                    class="choicefield required select form-control "
                                    id="raza_purpose_05">
                                    <option value="" selected=""></option>
                                    <option value="Hajj">Hajj</option>
                                    <option value="Umrah">Umrah</option>
                                    <option value="Ziyarat">Ziyarat</option>
                                </select>
                            </div>
                        </div>
                        <div class="row px-3 gy-1">

                            <div class="">
                                <button onclick="previous('form-05-01');" class="btn btn-danger w100percent-xs mbm-xs">
                                    Previous
                                </button>
                            </div>
                            <div class="ml-auto ">
                                <button onclick="nextView('form-05-01','form-01-02',0);" type="submit"
                                    class="btn btn-success w100percent-xs mbm-xs">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<div id="toast-message" class="toast-message">
    done
</div>
<script>
    let prev = [];
    function nextView(current, next, last) {
        prev.push(current);
        event.preventDefault()
        var currentForm = document.getElementById(current)
        if (!currentForm.checkValidity()) {
            currentForm.reportValidity();
            return;
        }
        currentForm.style.display = "none";
        var razaTypeElement = document.getElementById('raza-type');
        var razaType = razaTypeElement.value;
        if (last == 1) {
            var razaTypeText = razaTypeElement.options[razaTypeElement.selectedIndex].text;
            switch (razaType) {
                case '01':
                    var razaDate = document.getElementById('raza-date').value;
                    var raza_purpose_01 = document.getElementById('raza_purpose_01').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_01"]:checked').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Purpose</th>
                                    <td>${raza_purpose_01}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '09':
                    var razaDate = document.getElementById('raza-date').value;
                    var miqaat_name_09 = document.getElementById('miqaat_name_09').value;
                    var purpose_09 = document.getElementById('purpose_09').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_09"]:checked').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Miqaat</th>
                                    <td>${miqaat_name_09}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Purpose</th>
                                    <td>${purpose_09}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '13':
                    var razaDate = document.getElementById('raza-date').value;
                    var raza_purpose_13 = document.getElementById('raza_purpose_13').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_13"]:checked').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Purpose</th>
                                    <td>${raza_purpose_13}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '18':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_18"]:checked').value;
                    var student_name = document.getElementById('id_raza-fields-full_name_student').value;
                    var student_its = document.getElementById('id_raza-fields-its_number_of_student').value;
                    var student_age = document.getElementById('id_raza-fields-age_of_student').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Student ITS</th>
                                    <td>${student_its}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Student Name</th>
                                    <td>${student_name}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Student Age</th>
                                    <td>${student_age}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '14':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_14"]:checked').value;
                    var venue = document.getElementById('14-venue').value;
                    var address = document.getElementById('14-address').value;
                    var person_name = document.getElementById('14-fields-name_of_person_for_whom_raza_is_being_requested').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Venue</th>
                                    <td>${venue}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>${address}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Name of Person for whom raza is being Requested</th>
                                    <td>${person_name}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '04':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_04"]:checked').value;
                    var miqaat_name_04 = document.getElementById('miqaat_name_04').value;
                    var Event_time_04 = document.getElementById('Event_time_04').value;
                    var Additional_Host_Name_04_01 = document.getElementById('Additional_Host_Name_04_01').value;
                    var Additional_Host_Name_04_02 = document.getElementById('Additional_Host_Name_04_02').value;
                    var Additional_Host_Name_04_03 = document.getElementById('Additional_Host_Name_04_03').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Miqaat</th>
                                    <td>${miqaat_name_04}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Event Time</th>
                                    <td>${Event_time_04}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Host Name 1</th>
                                    <td>${Additional_Host_Name_04_01}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Host Name 2</th>
                                    <td>${Additional_Host_Name_04_02}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Host Name 3</th>
                                    <td>${Additional_Host_Name_04_03}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '20':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var its_number = document.getElementById('transfer-its_number').value;
                    var transfer_full_name = document.getElementById('transfer-full_name').value;
                    var transfer_current_contact_number = document.getElementById('transfer-current_contact_number').value;
                    var transfer_out_to_which_jamaat = document.getElementById('transfer-out_to_which_jamaat').value;
                    var transfer_relative_its = document.getElementById('transfer-relative_its').value;
                    var transfer_additional_notes = document.getElementById('transfer-additional_notes').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">ITS Number</th>
                                    <td>${its_number}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Full Name</th>
                                    <td>${transfer_full_name}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Contact No.</th>
                                    <td>${transfer_current_contact_number}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Transfer To</th>
                                    <td>${transfer_out_to_which_jamaat}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Relative ITS</th>
                                    <td>${transfer_relative_its}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Additional Notes</th>
                                    <td>${transfer_additional_notes}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '10':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_10"]:checked').value;
                    var personal_daress_menu = document.getElementById('personal_daress_menu').value;
                    var personal_daress_thaal_count = document.getElementById('personal_daress_thaal_count').value;
                    var personal_daress_address = document.getElementById('personal_daress_address').value;
                    var personal_daress_venue = document.getElementById('personal_daress_venue').value;
                    var purpose_10 = document.getElementById('raza_purpose_10').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Purpose</th>
                                    <td>${purpose_10}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Venue</th>
                                    <td>${personal_daress_venue}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>${personal_daress_address}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Approximate Thaal count</th>
                                    <td>${personal_daress_thaal_count}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Menu</th>
                                    <td>${personal_daress_menu}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '12':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_12"]:checked').value;
                    var personal_venue_12 = document.getElementById('personal_venue_12').value;
                    var personal_address_12 = document.getElementById('personal_address_12').value;
                    var personal_mumineen_count_12 = document.getElementById('personal_mumineen_count_12').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Venue</th>
                                    <td>${personal_venue_12}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>${personal_address_12}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Approximate Mumineen count</th>
                                    <td>${personal_mumineen_count_12}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '21':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_21"]:checked').value;
                    var personal_event_type = document.getElementById('personal_event_type').value;
                    var personal_event_menu_if_available = document.getElementById('personal_event_menu_if_available').value;
                    var personal_event_thaal_count = document.getElementById('personal_event_thaal_count').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Event Type</th>
                                    <td>${personal_event_type}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Approximate Thaal count</th>
                                    <td>${personal_event_thaal_count}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Menu (if available)</th>
                                    <td>${personal_event_menu_if_available}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '08':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_08"]:checked').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '07':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_07"]:checked').value;
                    var sabaq_name_of_kitaab = document.getElementById('sabaq_name_of_kitaab').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Name Of Kitaab</th>
                                    <td>${sabaq_name_of_kitaab}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '15':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_15"]:checked').value;
                    var name_of_guzarnar = document.getElementById('name_of_guzarnar').value;
                    var address_optional = document.getElementById('address_optional').value;
                    var fateha_venue = document.getElementById('fateha_venue').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Name Of Guzarnar</th>
                                    <td>${name_of_guzarnar}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Venue</th>
                                    <td>${fateha_venue}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>${address_optional}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '06':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_06"]:checked').value;
                    var raza_purpose = document.getElementById('raza_purpose_06').value;
                    var other_purpose = document.getElementById('other_purpose_06').value;
                    var all_its = document.getElementById('all_its_06').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Raza Purpose</th>
                                    <td>${raza_purpose}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Others</th>
                                    <td>${other_purpose}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Its No.s</th>
                                    <td>${all_its}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                case '05':
                    var razaDate = document.getElementById('raza-date').value;
                    var razaTime = document.getElementById('id_raza-time').value;
                    var areYouInRiba = document.querySelector('input[name="raza-fields-are_you_in_riba_05"]:checked').value;
                    var raza_purpose_05 = document.getElementById('raza_purpose_05').value;
                    var otherDetails = document.getElementById('id_raza-other-details').value;
                    var table = document.getElementById('details-table');
                    table.innerHTML = `
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Raza For</th>
                                    <td>${razaTypeText}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>${razaDate}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time</th>
                                    <td>${razaTime}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Are You In Riba</th>
                                    <td>${areYouInRiba}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Raza Purpose</th>
                                    <td>${raza_purpose_05}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Other Details</th>
                                    <td>${otherDetails}</td>
                                </tr>
                            </tbody>
                        `;
                    var nextForm = document.getElementById(`${next}`)
                    nextForm.style.display = "block";
                    break;
                default:
                    break;
            }
        } else {
            if (current == "form-00") {
                var nextForm = document.getElementById(`form-${razaType}-01`)
                nextForm.style.display = "block";
            } else {
                var nextForm = document.getElementById(`${next}`)
                nextForm.style.display = "block";
            }
        }
    }
    function previous(current) {
        event.preventDefault()
        var current = document.getElementById(current);
        current.style.display = "none"
        let last = prev.pop();
        var prevs = document.getElementById(last);
        prevs.style.display = "block"
    }
    function submit_raza(current) {
        event.preventDefault();
        var currentForm = document.getElementById(current);
        if (!currentForm.checkValidity()) {
            currentForm.reportValidity();
            return;
        }

        let data = {};
        var razaTypeElement = document.getElementById('raza-type');
        data['razaType'] = razaTypeElement.value;
        data['razaDate'] = document.getElementById('raza-date').value;
        data['razaTime'] = document.getElementById('id_raza-time').value;
        data['otherDetails'] = document.getElementById('id_raza-other-details').value;
        data['other_information']={}
        switch (razaTypeElement.value) {
            case '01':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_01"]:checked').value;
                data['other_information']['razapurpose'] = document.getElementById('raza_purpose_01').value;
                break;
            case '13':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_13"]:checked').value;
                data['other_information']['razapurpose'] = document.getElementById('raza_purpose_13').value;
                break;
            case '18':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_18"]:checked').value;
                data['other_information']['student_name'] = document.getElementById('id_raza-fields-full_name_student').value;
                data['other_information']['student_its'] = document.getElementById('id_raza-fields-its_number_of_student').value;
                data['other_information']['student_age'] = document.getElementById('id_raza-fields-age_of_student').value;
                break;
            case '20':
                data['other_information']['its_number'] = document.getElementById('transfer-its_number').value;
                data['other_information']['transfer_full_name'] = document.getElementById('transfer-full_name').value;
                data['other_information']['transfer_current_contact_number'] = document.getElementById('transfer-current_contact_number').value;
                data['other_information']['transfer_out_to_which_jamaat'] = document.getElementById('transfer-out_to_which_jamaat').value;
                data['other_information']['transfer_relative_its'] = document.getElementById('transfer-relative_its').value;
                data['other_information']['transfer_additional_notes'] = document.getElementById('transfer-additional_notes').value;
                break;
            case '14':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_14"]:checked').value;
                data['other_information']['venue'] = document.getElementById('14-venue').value;
                data['other_information']['address'] = document.getElementById('14-address').value;
                data['other_information']['name_of_person_for_whom_raza_is_being_requeste'] = document.getElementById('14-fields-name_of_person_for_whom_raza_is_being_requested').value;
                break;
            case '04':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_04"]:checked').value;
                data['other_information']['miqaat_name'] = document.getElementById('miqaat_name_04').value;
                data['other_information']['Event_time'] = document.getElementById('Event_time_04').value;
                data['other_information']['Additional_Host_Name_01'] = document.getElementById('Additional_Host_Name_04_01').value;
                data['other_information']['Additional_Host_Name_02'] = document.getElementById('Additional_Host_Name_04_02').value;
                data['other_information']['Additional_Host_Name_03'] = document.getElementById('Additional_Host_Name_04_03').value;
                break;
            case '09':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_09"]:checked').value;
                data['other_information']['miqaat_name'] = document.getElementById('miqaat_name_09').value;
                data['other_information']['razapurpose'] = document.getElementById('purpose_09').value;
                break;
            case '10':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_10"]:checked').value;
                data['other_information']['razapurpose'] = document.getElementById('raza_purpose_10').value;
                data['other_information']['personal_menu'] = document.getElementById('personal_daress_menu').value;
                data['other_information']['personal_thaal_count'] = document.getElementById('personal_daress_thaal_count').value;
                data['other_information']['personal_address'] = document.getElementById('personal_daress_address').value;
                data['other_information']['personal_venue'] = document.getElementById('personal_daress_venue').value;
                break;
            case '12':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_12"]:checked').value;
                data['other_information']['personal_venue'] = document.getElementById('personal_venue_12').value;
                data['other_information']['personal_address'] = document.getElementById('personal_address_12').value;
                data['other_information']['tasbeeh_mumineen_count'] = document.getElementById('personal_mumineen_count_12').value;
                break;
            case '21':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_21"]:checked').value;
                data['other_information']['personal_event_type'] = document.getElementById('personal_event_type').value;
                data['other_information']['personal_menu'] = document.getElementById('personal_event_menu_if_available').value;
                data['other_information']['personal_thaal_count'] = document.getElementById('personal_event_thaal_count').value;
                break;
            case '08':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_08"]:checked').value;
                break;
            case '07':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_07"]:checked').value;
                data['other_information']['sabaq_name_of_kitaab'] = document.getElementById('sabaq_name_of_kitaab').value;
                break;
            case '15':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_15"]:checked').value;
                data['other_information']['name_of_guzarnar'] = document.getElementById('sabaq_name_of_kitaab').value;
                data['other_information']['address_optional'] = document.getElementById('address_optional').value;
                data['other_information']['fateha_venue'] = document.getElementById('fateha_venue').value;
                break;
            case '06':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_06"]:checked').value;
                data['other_information']['raza_purpose'] = document.getElementById('raza_purpose_06').value;
                data['other_information']['other_purpose'] = document.getElementById('other_purpose_06').value;
                data['other_information']['all_its'] = document.getElementById('all_its_06').value;
                break;
            case '05':
                data['other_information']['areYouInRiba'] = document.querySelector('input[name="raza-fields-are_you_in_riba_05"]:checked').value;
                data['other_information']['raza_purpose'] = document.getElementById('raza_purpose_05').value;
                break;

            default:
                break;
        }

                // Before submitting, fetch member dues and show confirmation modal
                fetch('<?php echo base_url('accounts/get_member_dues') ?>', { credentials: 'same-origin' })
                    .then(function (r) { return r.json(); })
                    .then(function (resp) {
                        if (!resp || !resp.success) {
                            // proceed directly if unable to fetch dues
                            doRazaSubmit(data);
                            return;
                        }
                        var d = resp.dues;
                        var html = '<table class="table table-sm">'
                            + '<tr><th>Category</th><th class="text-right">Due</th></tr>'
                            + '<tr><td>FMB Takhmeen</td><td class="text-right">' + formatINR(d.fmb_due) + '</td></tr>'
                            + '<tr><td>Sabeel Takhmeen</td><td class="text-right">' + formatINR(d.sabeel_due) + '</td></tr>'
                            + '<tr><td>General Contributions</td><td class="text-right">' + formatINR(d.gc_due) + '</td></tr>'
                            + '<tr><td>Miqaat Invoices</td><td class="text-right">' + formatINR(d.miqaat_due) + '</td></tr>'
                            + '<tr><td>Corpus Fund</td><td class="text-right">' + formatINR(d.corpus_due) + '</td></tr>'
                            + '<tr><td>Wajebaat</td><td class="text-right">' + formatINR(d.wajebaat_due || 0) + '</td></tr>'
                            + '<tr><td>Qardan Hasana</td><td class="text-right">' + formatINR(d.qardan_hasana_due || 0) + '</td></tr>'
                            + '<tr><th>Total</th><th class="text-right">' + formatINR(d.total_due) + '</th></tr>'
                            + '</table>';
                        if (d.total_due <= 0) html = '<div class="alert alert-success">No pending dues. You may proceed to submit.</div>' + html;
                        else html = '<div class="alert alert-warning">You have pending dues. Please review before submitting.</div>' + html;
                        showDuesModal(html);
                        // bind proceed button
                        var proceedBtn = document.getElementById('dues-confirm');
                        proceedBtn.onclick = function() {
                            // optionally notify then submit
                            fetch('<?php echo base_url('accounts/send_dues_email') ?>', { method: 'POST', credentials: 'same-origin' })
                                .finally(function(){ hideDuesModal(); doRazaSubmit(data); });
                        };
                    })
                    .catch(function() { doRazaSubmit(data); });

                function doRazaSubmit(payload) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('accounts/raza_submit') ?>",
                        dataType: "json",
                        success: function (msg) {
                                if (msg) {
                                        window.location.href = '<?php echo base_url('accounts/success/MyRazaRequest') ?>';
                                } else {
                                        window.location.href = '<?php echo base_url('accounts/error/MyRazaRequest') ?>';
                                }
                        },
                        error: function (xhr, status, error) {
                                console.log("AJAX Error: " + status + " - " + error);
                                setTimeout(function () {
                                        window.location.href = '<?php echo base_url('accounts/error/MyRazaRequest') ?>';
                                        }, 1000);
                        },
                        data: payload
                    });
                }
    }

</script>