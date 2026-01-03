<div class="container margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
      <?= $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?= $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning">
      <?= $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>
  <div class="mb-4 mb-md-0 pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>

  <h3 class="heading text-center mb-4">RSVP <span class="text-primary">Dashboard</span></h3>
  <div class="row justify-content-center mb-4">
    <div class="col-12 col-md-8">
      <input id="miqaat-filter" type="search" class="form-control" placeholder="Search miqaat (name, type, date)...">
    </div>
  </div>
  <?php $this->load->view('Accounts/RSVP/_miqaat_cards', ['miqaats' => $miqaats, 'rsvp_overview' => $rsvp_overview]); ?>
</div>
<script>
  $(function(){
    var searchTimer = null;
    $("#miqaat-filter").on('input', function(){
      clearTimeout(searchTimer);
      var q = $(this).val().trim();
      searchTimer = setTimeout(function(){
        $.getJSON("<?php echo base_url('accounts/rsvp_search'); ?>", { q: q })
          .done(function(res){
            if(res.success){
              // replace cards container
              if($('#miqaat-cards').length){
                $('#miqaat-cards').replaceWith(res.html);
              } else {
                // insert after the search box
                $('#miqaat-filter').closest('.row').after(res.html);
              }
            }
          });
      }, 300);
    });

    $(".alert").delay(3000).fadeOut(500);
  });
</script>