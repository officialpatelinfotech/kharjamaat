<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .icon {
    font-size: 40pt;
    margin: 10px 0;
    color: #ffffff;
  }

  .title {
    color: white;
  }

  .heading {
    color: #ad7e05;
    font-family: 'Amita', cursive;
  }

  .card {
    height: 153px;
  }

  .card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
  }

  .row a {
    text-decoration: none;
    color: inherit;
  }

  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }
</style>
<div class="container margintopcontainer">
  <h1 class="text-center heading pt-5 mb-4">Welcome to Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></h1>
  <hr>
      <!-- ===== Member Search Widget ===== -->
      <div class="chart-container mb-4 member-search-widget" id="member-search-block" style="padding:18px 20px;">
        <style>
          /* Override parent overflow:hidden so the dropdown can escape */
          #member-search-block {
            overflow: visible !important;
          }
          /* Member Search Widget Styles */
          .msw-label {
            font-size: .85rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 10px;
          }
          .msw-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
          }
          .msw-input-group {
            position: relative;
            flex: 1 1 250px;
            max-width: 500px;
          }
          .msw-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
            font-size: 15px;
            pointer-events: none;
          }
          #mswInput {
            width: 100%;
            padding: 11px 38px 11px 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: .97rem;
            color: #111827;
            background: #fafbff;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
          }
          #mswInput:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
            background: #fff;
          }
          .msw-clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            width: 24px;
            height: 24px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #6b7280;
            font-size: 12px;
            line-height: 1;
          }
          .msw-clear-btn.visible { display: flex; }
          .msw-spinner {
            position: absolute;
            right: 38px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(0,0,0,.08);
            border-top-color: #6366f1;
            animation: msw-spin .7s linear infinite;
            display: none;
          }
          .msw-spinner.active { display: block; }
          @keyframes msw-spin { to { transform: translateY(-50%) rotate(360deg); } }
          #mswDropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,.12);
            z-index: 1050;
            display: none;
            max-height: 300px;
            overflow-y: auto;
          }
          #mswDropdown.open { display: block; }
          .msw-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            cursor: pointer;
            transition: background .15s ease;
            border-bottom: 1px solid #f3f4f6;
          }
          .msw-result-item:last-child { border-bottom: none; }
          .msw-result-item:hover { background: #f5f7ff; }
          .msw-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0;
          }
          .msw-avatar.female { background: linear-gradient(135deg,#ec4899,#f43f5e); }
          .msw-res-name {
            font-weight: 600;
            color: #111827;
            font-size: .92rem;
          }
          .msw-res-meta {
            font-size: .78rem;
            color: #6b7280;
            margin-top: 1px;
          }
          .msw-its-badge {
            margin-left: auto;
            background: #eef2ff;
            color: #6366f1;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
          }
          .msw-no-results {
            padding: 18px 14px;
            color: #6b7280;
            text-align: center;
            font-size: .9rem;
          }

        </style>

        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:15px;">
          <!-- Left side: Label & Subtitle -->
          <div class="d-flex align-items-center" style="gap:10px;">
            <div class="msw-label m-0" style="margin-bottom:0 !important;"><i class="fa fa-search mr-1"></i> Member Search</div>
            <span class="d-none d-md-inline" style="font-size:.78rem;color:#9ca3af;margin-top:2px;">Search by name or ITS ID</span>
          </div>

          <!-- Right side: Search Input & Button -->
          <div class="msw-input-wrap m-0" style="flex: 1 1 auto; justify-content: flex-end;">
            <div class="msw-input-group">
              <i class="fa fa-search msw-icon"></i>
              <input type="text" id="mswInput" placeholder="Type name or ITS ID..." autocomplete="off" aria-label="Search members" />
              <div class="msw-spinner" id="mswSpinner"></div>
              <button class="msw-clear-btn" id="mswClear" aria-label="Clear search" title="Clear">&#x2715;</button>
              <div id="mswDropdown" role="listbox" aria-label="Member search results"></div>
            </div>
            <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-primary btn-sm" style="white-space:nowrap;border-radius:10px;">
              <i class="fa fa-users mr-1"></i>All Members
            </a>
          </div>
        </div>
      </div>
      <!-- ===== End Member Search Widget ===== -->



  <div class="continer d-flex justify-content-center">
    <div class="row container">
      <!-- <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('admin/RazaRequest'); ?>">-->
      <!--    <div class="card text-center">-->
      <!--        <div class="card-body d-flex flex-column justify-content-between">-->
      <!--            <div class="title">Raza Request</div>-->
      <!--            <i class="icon fa-solid fa-hands-holding"></i>-->
      <!--        </div>-->
      <!--    </div>-->
      <!--</a> -->
      <!-- <a href="<?php echo base_url('admin/managemiqaat'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Miqaat</div>
            <i class="icon fa-solid fa-calendar-days"></i>
          </div>
        </div>
      </a> -->
      <a href="<?php echo base_url('common/fmbcalendar?from=admin'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">FMB Calendar</div>
            <i class="icon fa-solid fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/razalist'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Raza Form</div>
            <i class="icon fa-solid fa-list-check"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managefmbsettings'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage FMB Thaali & Niyaz</div>
            <i class="icon fa-solid fa-plate-wheat"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managesabeeltakhmeen'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Sabeel Takhmeen</div>
            <i class="icon fa-solid fa-hand-holding-heart"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managemembers'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Members</div>
            <i class="icon fa-solid fa-users-cog"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('common/managemiqaat?from=admin'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Create & Manage Miqaat</div>
            <i class="icon fa-solid fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/corpusfunds'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Corpus Funds</div>
            <i class="icon fa-solid fa-donate"></i>
          </div>
        </div>
      </a>

      <a href="<?php echo base_url('admin/laagat'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Laagat / Rent Module</div>
            <i class="icon fa-solid fa-building"></i>
          </div>
        </div>
      </a>

      <a href="<?php echo base_url('admin/ekramfunds'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Ekram Funds</div>
            <i class="icon fa-solid fa-hand-holding-heart"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/qardanhasana'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Qardan Hasana</div>
            <i class="icon fa-solid fa-hand-holding-dollar"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/wajebaat'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Wajebaat</div>
            <i class="icon fa-solid fa-book"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/expense'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Expense Module</div>
            <i class="icon fa-solid fa-receipt"></i>
          </div>
        </div>
      </a>

      <a href="<?php echo base_url('admin/madresa'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Madresa Module</div>
            <i class="icon fa-solid fa-school"></i>
          </div>
        </div>
      </a>

      <a href="<?php echo base_url('admin/preferences'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Preferences</div>
            <i class="icon fa-solid fa-gear"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
  <!-- ===== Member Search Widget JavaScript ===== -->
  <script>
  (function(){
    var baseUrl = '<?php echo base_url(); ?>';
    var searchUrl = baseUrl + 'admin/searchmembers';
    var viewMemberUrl = baseUrl + 'admin/viewmember/';

    var $input   = $('#mswInput');
    var $dropdown= $('#mswDropdown');
    var $spinner = $('#mswSpinner');
    var $clear   = $('#mswClear');

    var debounceTimer = null;

    function getInitials(name){
      if(!name) return '?';
      var parts = name.trim().split(/\s+/);
      if(parts.length === 1) return parts[0].charAt(0).toUpperCase();
      return (parts[0].charAt(0) + parts[parts.length-1].charAt(0)).toUpperCase();
    }

    function closeDropdown(){
      $dropdown.removeClass('open').html('');
    }

    function showDropdown(results){
      $dropdown.html('');
      if(!results || results.length === 0){
        $dropdown.html('<div class="msw-no-results"><i class="fa fa-search mr-1"></i>No members found</div>');
        $dropdown.addClass('open');
        return;
      }
      results.forEach(function(r){
        var isF = (String(r.gender||'').toLowerCase() === 'female' || String(r.gender||'').toLowerCase() === 'f');
        var avatarCls = isF ? 'msw-avatar female' : 'msw-avatar';
        var initials  = getInitials(r.name);
        var sector = r.sector ? ('<span>'+escHtml(r.sector)+'</span>') : '';
        var hof    = r.hof_type ? (' &bull; '+escHtml(r.hof_type)) : '';
        var item = $('<div class="msw-result-item" role="option" tabindex="0"></div>');
        item.html(
          '<div class="'+avatarCls+'">'+initials+'</div>'+
          '<div style="flex:1;min-width:0;">'+
            '<div class="msw-res-name">'+escHtml(r.name)+'</div>'+
            '<div class="msw-res-meta">'+sector+hof+'</div>'+
          '</div>'+
          '<div class="msw-its-badge">'+escHtml(String(r.its_id))+'</div>'
        );
        item.on('click keydown', function(e){
          if(e.type === 'keydown' && e.key !== 'Enter') return;
          window.location.href = viewMemberUrl + r.its_id;
        });
        $dropdown.append(item);
      });
      $dropdown.addClass('open');
    }

    function escHtml(s){
      return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function doSearch(q){
      $spinner.addClass('active');
      $.getJSON(searchUrl, {q: q}, function(data){
        $spinner.removeClass('active');
        if(data && data.status === 'ok'){
          showDropdown(data.results);
        }
      }).fail(function(){
        $spinner.removeClass('active');
        $dropdown.html('<div class="msw-no-results text-danger">Search failed. Please try again.</div>').addClass('open');
      });
    }



    // Input handler
    $input.on('input', function(){
      var q = $(this).val().trim();
      if(q.length > 0) $clear.addClass('visible'); else $clear.removeClass('visible');
      clearTimeout(debounceTimer);
      if(q.length < 2){
        closeDropdown();
        return;
      }
      debounceTimer = setTimeout(function(){ doSearch(q); }, 350);
    });

    // Clear button
    $clear.on('click', function(){
      $input.val('').focus();
      $clear.removeClass('visible');
      closeDropdown();
    });

    // Close dropdown on outside click
    $(document).on('click', function(e){
      if(!$(e.target).closest('#member-search-block').length){
        closeDropdown();
      }
    });

    // Keyboard: Escape closes
    $input.on('keydown', function(e){
      if(e.key === 'Escape'){ closeDropdown(); }
    });

    // Keyboard navigation in dropdown
    $input.on('keydown', function(e){
      var $items = $dropdown.find('.msw-result-item');
      if(!$items.length) return;
      var $focused = $dropdown.find('.msw-result-item:focus');
      if(e.key === 'ArrowDown'){
        e.preventDefault();
        if(!$focused.length){ $items.first().focus(); }
        else { var next = $items.index($focused)+1; if(next < $items.length) $items.eq(next).focus(); }
      }
      if(e.key === 'ArrowUp'){
        e.preventDefault();
        if($focused.length){ var prev = $items.index($focused)-1; if(prev >= 0) $items.eq(prev).focus(); else $input.focus(); }
      }
    });
  })();
  </script>
  <!-- ===== End Member Search Widget JavaScript ===== -->



<script>
  const colors = ["rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(0, 106, 63)",
    "rgb(41, 128, 185)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
  ]
  $(document).ready(function() {
    $(".card").each(function(i, el) {
      this.style.backgroundColor = colors[i];
    });
  })
</script>