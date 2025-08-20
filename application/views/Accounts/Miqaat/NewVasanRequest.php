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
                    <p class="card-text"></p>
                    <form method="post" action="<?php echo base_url('accounts/submit_vasanrequest') ?>">
                        <div id="div_id_reason" class="form-group"><label for="id_reason"
                                class="col-form-label  requiredField">
                                Reason<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="reason" maxlength="255"
                                    class="textinput textInput form-control" required="" id="id_reason"></div>
                        </div>
                        <div id="div_id_from_date" class="form-group"><label for="id_from_date"
                                class="col-form-label  requiredField">
                                Date or From Date<span class="asteriskField">*</span></label>
                            <div class=""><input type="date" name="from_date" class="dateinput form-control" required=""
                                    id="id_from_date"></div>
                        </div>
                        <div id="div_id_to_date" class="form-group"><label for="id_to_date" class="col-form-label ">
                                To date
                            </label>
                            <div class=""><input type="date" name="to_date" class="dateinput form-control"
                                    id="id_to_date"><small id="hint_id_to_date" class="form-text text-muted">(if more
                                    than
                                    one day)</small></div>
                        </div>
                        <hr>
                        <h4>Utensils</h4>
                        <div class="row border p-1" id="Utensil">

                        </div>
                        <button class="btn btn-info btn-sm mt-3 mb-3" type="button" onclick="addnewutensil();"><i
                                class="fa fa-plus"></i>&nbsp;Add
                            Utensil
                        </button>

                        <div class="text-center mtop"><button type="submit"
                                class="btn btn-success w100percent-xs">Submit</button>&nbsp;<button type="button"
                                class="btn btn-danger w100percent-xs">Cancel</button>
                        </div>
                    </form>
                    <p></p>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let i = 1;
    function addnewutensil() {
        var Utensil = document.getElementById('Utensil');
        var newUtensil = document.createElement('div');
        newUtensil.className = "col-12 utensils";
        newUtensil.id = i;
        newUtensil.innerHTML = `<div class="clearfix"><div class="pull-left"><h6 class="serial-no mtop-5"># ${i}</h6></div><div class="pull-right utensil_delete"><button onclick="removeUtensil(${i})" type="button"class="btn btn-sm btn-danger remove-form-row"><i class="fa fa-trash-alt"></i></button></div></div > <div id="div_id_form-0-utensil" class="form-group"><label for="id_form-0-utensil" class="col-form-label  requiredField">Utensil<span class="asteriskField">*</span></label><div class=""><select name="form-utensil[]" class="select form-control"id="id_form-0-utensil"><option value="" selected="">---------</option>` + '<?php foreach ($vasan_type as $vt) {
            echo '<option value="' . $vt['id'] . '">' . $vt['name'] . '</option>';
        } ?>'+ `</select></div></div><div id="div_id_form-0-quantity" class="form-group"><label for="id_form-0-quantity" class="col-form-label  requiredField"> Quantity<span class="asteriskField">*</span></label><div class=""><input type="number" name="form-quantity[]" class="numberinput form-control" id="id_form-0-quantity"></div></div>`
        Utensil.appendChild(newUtensil);
        i++;
    }
    function removeUtensil(div_id) {
        console.log(div_id)
        var utensilContainer = document.getElementById('Utensil');
        var removedUtensil = document.getElementById(div_id);
        utensilContainer.removeChild(removedUtensil);
        var utensilDivs = utensilContainer.getElementsByClassName('utensils');
        let k = 0;
        for (var j = 0; j < utensilDivs.length; j++) {
            var serialNo = utensilDivs[j].querySelector('.serial-no');
            if (serialNo) {
                serialNo.innerText = `# ${j + 1}`;
                utensilDivs[j].id = j + 1;
            }
            var utensildelete = utensilDivs[j].querySelector('.utensil_delete')
            if (utensildelete) {
                utensildelete.innerHTML = `<button onclick="removeUtensil(${j + 1})" type="button"class="btn btn-sm btn-danger remove-form-row"><i class="fa fa-trash-alt"></i></button>`
            }
            k++;
        }
        i = k + 1;
    }


    addnewutensil();
</script>