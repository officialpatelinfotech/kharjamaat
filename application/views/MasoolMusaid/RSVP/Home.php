<?php
$colors = [
  "#fce4ec", // light pink
  "#f8f9fa", // light gray
  "#e8f5e9", // light green
  "#e3f2fd", // light blue
  "#fff3e0", // light orange
  "#ede7f6",  // light purple
];
$colorIndex = 0;
?>

<style>
  #rsvp-dashboard .card:nth-child(6n+1) {
    background-color: #f8f9fa;
  }

  #rsvp-dashboard .card:nth-child(6n+2) {
    background-color: #e3f2fd;
  }

  #rsvp-dashboard .card:nth-child(6n+3) {
    background-color: #fce4ec;
  }

  #rsvp-dashboard .card:nth-child(6n+4) {
    background-color: #e8f5e9;
  }

  #rsvp-dashboard .card:nth-child(6n+5) {
    background-color: #fff3e0;
  }

  #rsvp-dashboard .card:nth-child(6n+6) {
    background-color: #ede7f6;
  }

  .miqaat-card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
    transition: all 0.3s ease;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-4 mb-md-0">
    <a href="<?php echo base_url('/MasoolMusaid') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>

  <h3 class="heading pb-5 text-center">RSVP <span class="text-primary">Dashboard</span></h3>
  <div class="row justify-content-center mb-4">
    <div class="col-12 col-md-8">
      <input id="miqaat-filter" type="search" class="form-control" placeholder="Search miqaat (name or date)...">
    </div>
  </div>
  <div id="rsvp-dashboard">
    <?php $this->load->view('MasoolMusaid/RSVP/_miqaat_cards', ['miqaat_rsvp_counts' => $miqaat_rsvp_counts]); ?>
  </div>
</div>
<script>
  $(function(){
    var searchTimer = null;
    $("#miqaat-filter").on('input', function(){
      clearTimeout(searchTimer);
      var q = $(this).val().trim();
      searchTimer = setTimeout(function(){
        $.getJSON("<?php echo base_url('MasoolMusaid/rsvp_search'); ?>", { q: q })
          .done(function(res){
            if(res.success){
              if($('#miqaat-cards').length){
                $('#miqaat-cards').replaceWith(res.html);
              } else {
                $('#miqaat-filter').closest('.row').after(res.html);
              }
            }
          });
      }, 300);
    });
  });
</script>