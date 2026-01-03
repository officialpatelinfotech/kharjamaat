<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Admin Page</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <style>
    body {
      background-color: #f8f9fa;
      /* Set a light background color */
      margin-top: 100px;
    }

    .container {
      background-color: #ffffff;
      /* Set a white background for the container */
      padding: 20px;
      /* Add some padding for better spacing */
      border-radius: 10px;
      /* Add rounded corners */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      /* Add a subtle box shadow */
      margin-top: 100px;
      /* Adjusted margin-top for centering */
    }

    .time-slots-container {
      display: grid;
      gap: 12px;
    }

    .time-slot-card {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e1e7ea;
      border-radius: 6px;
      padding: 10px 8px;
      background: #fff;
      cursor: pointer;
      transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease;
      user-select: none;
    }

    .time-slot-card input[type="checkbox"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .time-slot-card {
      width: 100%;
      text-align: center;
      font-weight: 500;
      color: #333;
    }

    .time-slot-card.selected {
      background-color: #007bff;
      color: #fff !important;
      border-color: #007bff;
    }

    /* Grouping styles */
    .slot-group {
      margin-bottom: 18px;
      padding: 8px 0 6px 0;
      background: transparent;
    }

    .group-header {
      font-weight: 700;
      margin: 6px 0 10px 4px;
      color: #444;
    }

    .group-slots {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 12px;
    }

    /* Force 4 columns on narrow/mobile screens so slots show 4 per row */
    @media (max-width: 575px) {
      .group-slots {
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 8px;
      }
      .time-slot-card {
        padding: 8px 6px;
        font-size: 14px;
      }
      .group-slots .time-slot-card .time-label {
        display: block;
      }
    }

    .btn-submit {
      margin-top: 15px;
    }

    #selectAll {
      margin-top: 10px;
    }

    .btn-submit {
      margin-top: 15px;
    }

    #selectAll {
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <?php
    $ci = get_instance();
    $ci->load->model('HijriCalendar');
    if (!empty($_SESSION['slotdate_end'])):
        $ds = DateTime::createFromFormat('Y-m-d', $_SESSION['slotdate']);
        $de = DateTime::createFromFormat('Y-m-d', $_SESSION['slotdate_end']);
        $dispStart = $ds ? $ds->format('d-m-Y') : htmlspecialchars($_SESSION['slotdate']);
        $dispEnd = $de ? $de->format('d-m-Y') : htmlspecialchars($_SESSION['slotdate_end']);
        // Hijri parts
        $hstart = $ci->HijriCalendar->get_hijri_parts_by_greg_date($_SESSION['slotdate']);
        $hend = $ci->HijriCalendar->get_hijri_parts_by_greg_date($_SESSION['slotdate_end']);
        $hstartText = $hstart ? ($hstart['hijri_day'] . ' ' . htmlspecialchars($hstart['hijri_month_name']) . ' ' . $hstart['hijri_year']) : '';
        $hendText = $hend ? ($hend['hijri_day'] . ' ' . htmlspecialchars($hend['hijri_month_name']) . ' ' . $hend['hijri_year']) : '';
    ?>
      <div class="alert alert-info" role="alert">
        Managing slots for the week: <strong><?php echo $dispStart; ?></strong> <small class="text-muted">(Hijri: <?php echo $hstartText; ?>)</small> — <strong><?php echo $dispEnd; ?></strong> <small class="text-muted">(Hijri: <?php echo $hendText; ?>)</small>
      </div>
    <?php endif; ?>
    <div class="row mb-4 p-0">
      <div class="col-12 col-md-6">
        <a href="<?php echo base_url("amilsaheb/slots_calendar"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
      </div>
    </div>
    <form id="slotForm" action="<?= base_url('Amilsaheb/save_slots') ?>" method="post">
      <div class="row">
        <!-- Date selection part -->
        <div class="col-md-12" style="padding:0px 50px">
          <div class="row">
            <div class="col-md-6">
              <label for="selected_date">Select Date:</label>
              <input type="date" id="selected_date" value="<?php echo $_SESSION['slotdate']; ?>" name="selected_date" class="form-control" autocomplete="off">
              <?php
                // show hijri date for initial selected date
                $selDate = isset($_SESSION['slotdate']) ? $_SESSION['slotdate'] : null;
                $hselText = '';
                if ($selDate) {
                  $hparts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($selDate);
                  if ($hparts) {
                    $hselText = $hparts['hijri_day'] . ' ' . htmlspecialchars($hparts['hijri_month_name']) . ' ' . $hparts['hijri_year'];
                  }
                }
                if ($hselText !== '') {
                  echo '<div style="margin-top:6px;font-size:13px;color:#6b7280">Hijri: <strong>' . $hselText . '</strong></div>';
                }
              ?>
            </div>
            <div class="col-md-6">
              <button id="submitBtn" class="btn btn-primary mt-3 float-right">Submit</button>
            </div>
          </div>
        </div>
        <!-- Time slot selection part -->
        <div class="col-md-12" style="padding: 20px 50px">
          <label for="selected_time_slots">Select Time Slots:</label>

          <div id="selectedSlotsDisplay" class="mt-3">
            <h6>Selected Slots (<span id="selectedCount">0</span>)</h6>
            <div id="selectedList" class="d-flex flex-wrap"></div>
          </div>

          <div class="form-check mb-3" id="selectAll">
            <input class="form-check-input" type="checkbox" id="selectAllCheckbox"></input>
            <label class="form-check-label" for="selectAllCheckbox">
              Select All
            </label>
          </div>

          <div id="timeSlots" class="time-slots-container"></div>
          <!-- Selected slots display -->
        </div>
      </div>
    </form>
  </div>

  <!-- Include jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!-- Include a datepicker library -->
  <!-- For example, you can use the jQuery UI Datepicker -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Your existing HTML code ... -->

  <script>
    function formatTime(time) {
      var hours = time.getHours();
      var minutes = time.getMinutes();
      var ampm = hours >= 12 ? 'pm' : 'am';
      hours = hours % 12;
      hours = hours ? hours : 12; // Handle midnight (0 hours)
      minutes = minutes < 10 ? '0' + minutes : minutes; // Add leading zero to minutes if needed
      return hours + '_' + minutes + ampm;
    }
    $(function() {
      // Function to generate full-day time slots (15-min) grouped by time of day
      function generateTimeSlots() {
        // Helper to generate slots between two hours (start inclusive, end exclusive)
        // Accepts groupName so each checkbox can be tagged with its group
        function generateRange(startH, startM, endH, endM, groupName) {
          var arr = [];
          var cur = new Date();
          cur.setHours(startH, startM, 0, 0);
          var end = new Date();
          end.setHours(endH, endM, 0, 0);
          while (cur < end) {
            var timeText = cur.toLocaleTimeString([], {
              hour: '2-digit',
              minute: '2-digit'
            });
            var id = 'timeSlot_' + formatTime(cur);
            var slotHtml = '<label class="time-slot-card" for="' + id + '">' +
              '<input type="checkbox" class="time-checkbox" name="selected_time_slot[]" value="' + timeText + '" id="' + id + '" data-group="' + groupName + '">' +
              '<span class="time-label">' + timeText + '</span>' +
              '</label>';
            arr.push(slotHtml);
            cur = new Date(cur.getTime() + 15 * 60000);
          }
          return arr;
        }

        // Build groups in desired display order
        var groups = {
          'Night': [],
          'Morning': [],
          'Afternoon': [],
          'Evening': []
        };

        // Night: 22:00 -> 24:00, then 00:00 -> 10:00 (so display from 22:00 onwards)
        groups['Night'] = groups['Night'].concat(generateRange(22, 0, 24, 0, 'Night'));
        groups['Night'] = groups['Night'].concat(generateRange(0, 0, 10, 0, 'Night'));

        // Morning: 10:00 -> 13:00
        groups['Morning'] = generateRange(10, 0, 13, 0, 'Morning');

        // Afternoon: 13:00 -> 17:00
        groups['Afternoon'] = generateRange(13, 0, 17, 0, 'Afternoon');

        // Evening: 17:00 -> 22:00
        groups['Evening'] = generateRange(17, 0, 22, 0, 'Evening');

        // Render groups in the desired order (each group gets a group-select checkbox)
        // Show Night at the last position
        var order = ['Morning', 'Afternoon', 'Evening', 'Night'];
        var out = '';
        order.forEach(function(groupName) {
          var slots = groups[groupName];
          var groupId = 'group_' + groupName.replace(/\s+/g, '_');
          out += '<div class="slot-group">';
          out += '<div class="group-header">' +
            '<input type="checkbox" class="form-check-input group-select" id="' + groupId + '" data-group="' + groupName + '"> ' +
            '<label for="' + groupId + '" style="margin-left:6px; font-weight:700;">' + groupName + '</label>' +
            '</div>';
          out += '<div class="group-slots">' + slots.join('') + '</div>';
          out += '</div>';
        });

        $('#timeSlots').html(out);
      }

      // Event handler for date selection
      $("#selected_date").on('change', function() {
        // Fetch and display time slots for the selected date
        generateTimeSlots();

        // Fetch existing time slots for the selected date from the backend
        var selectedDate = $(this).val();
        fetchExistingTimeSlots(selectedDate);
      });

      // Event handler for time slot selection (toggle selected visual state)
      $('#timeSlots').on('change', '.time-checkbox', function() {
        var card = $(this).closest('.time-slot-card');
        card.toggleClass('selected', $(this).prop('checked'));
        updateSelectedDisplay();
        updateGroupAndGlobalState();
      });

      // Event handler for "Select All" checkbox
      $('#selectAllCheckbox').on('change', function() {
        var checked = $(this).prop('checked');
        var checkboxes = $('.time-checkbox');
        checkboxes.each(function() {
          $(this).prop('checked', checked);
          $(this).closest('.time-slot-card').toggleClass('selected', checked);
        });
        updateSelectedDisplay();
        // Update group selects to match
        $('.group-select').prop('checked', checked);
      });

      // Event handler for per-group select-all checkbox
      $('#timeSlots').on('change', '.group-select', function() {
        var groupName = $(this).data('group');
        var checked = $(this).prop('checked');
        var groupCheckboxes = $('.time-checkbox[data-group="' + groupName + '"]');
        groupCheckboxes.each(function() {
          $(this).prop('checked', checked);
          $(this).closest('.time-slot-card').toggleClass('selected', checked);
        });
        updateSelectedDisplay();
        updateGroupAndGlobalState();
      });

      // Utility: update group-select checkboxes and global select-all based on current state
      function updateGroupAndGlobalState() {
        // Update each group-select
        $('.group-select').each(function() {
          var groupName = $(this).data('group');
          var total = $('.time-checkbox[data-group="' + groupName + '"]').length;
          var checked = $('.time-checkbox[data-group="' + groupName + '"]:checked').length;
          $(this).prop('checked', total > 0 && checked === total);
        });
        // Update global select all
        var totalAll = $('.time-checkbox').length;
        var checkedAll = $('.time-checkbox:checked').length;
        $('#selectAllCheckbox').prop('checked', totalAll > 0 && checkedAll === totalAll);
      }

      // Function to fetch existing time slots for the selected date
      function fetchExistingTimeSlots(selectedDate) {
        // Use AJAX to fetch existing time slots for the selected date from the backend
        $.ajax({
          url: '<?= base_url('Amilsaheb/getExistingTimeSlots') ?>',
          method: 'GET',
          dataType: 'json',
          data: {
            date: selectedDate
          },
          success: function(response) {
            if (!response || !Array.isArray(response)) return updateSelectedDisplay();
            // Loop through existing time slots and mark corresponding checkboxes as checked
            response.forEach(function(timeSlot) {
              var checkbox = $('#timeSlot_' + getTimeAsString(timeSlot.time));
              if (checkbox.length > 0) {
                checkbox.prop('checked', true);
                checkbox.closest('.time-slot-card').addClass('selected');
              }
            });
            // Update the selected slots display after marking existing ones
            updateSelectedDisplay();
          },
          error: function() {
            console.error('Error fetching existing time slots');
          }
        });
      }

      // Function to convert DB time string (HH:MM[:SS]) to id fragment used by generated checkboxes
      function getTimeAsString(timeString) {
        // Accept formats like "HH:MM:SS", "HH:MM" or "H:MM AM/PM" and convert
        if (!timeString) return '';
        var lower = timeString.toLowerCase();
        var hasAmPm = lower.indexOf('am') !== -1 || lower.indexOf('pm') !== -1;
        var hours = 0;
        var minutes = '00';

        // Split by ':' to get hours and minutes
        var parts = timeString.split(':');
        hours = parseInt(parts[0], 10) || 0;
        minutes = (parts[1] || '00').toString().substr(0, 2);

        // Determine am/pm
        var ampm = null;
        if (hasAmPm) {
          ampm = lower.indexOf('pm') !== -1 ? 'pm' : 'am';
        } else {
          ampm = hours >= 12 ? 'pm' : 'am';
        }

        // Convert to 12-hour format for id matching
        var hours12 = hours % 12;
        if (hours12 === 0) hours12 = 12;

        if (minutes.length === 1) minutes = '0' + minutes;
        return hours12 + '_' + minutes + ampm;
      }

      // Initial generation of time slots
      generateTimeSlots();
      fetchExistingTimeSlots($("#selected_date").val());
    });

    // Update the display of selected slots
    function updateSelectedDisplay() {
      var selected = [];
      $('.time-checkbox:checked').each(function() {
        selected.push($(this).val());
      });
      $('#selectedCount').text(selected.length);
      var list = $('#selectedList');
      list.empty();
      selected.forEach(function(t) {
        var badge = $('<span class="badge badge-primary mr-2 mb-2">').text(t);
        list.append(badge);
      });
    }
  </script>

  <script>
    $(document).ready(function() {
      // Attach click event to the submit button
      $("#submitBtn").on("click", function() {
        // Display a confirmation dialog
        var confirmed = confirm("Old time slots for this date will be deleted, and new ones will be added. Are you sure you want to proceed?");

        // Check user's choice
        if (confirmed) {
          // If confirmed, submit the form
          $("#slotForm").submit();
        } else {
          // If canceled, prevent form submission
          return false;
        }
      });
    });
  </script>
</body>

</html>