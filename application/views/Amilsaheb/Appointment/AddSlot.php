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

    .time-slot {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .time-slot input {
      margin-right: 10px;
    }

    .btn-submit {
      margin-top: 15px;
    }

    #selectAll {
      margin-top: 10px;
    }
  </style>
  <style>
    .time-slot {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      border: 1px solid #ced4da;
      /* Add border */
      border-radius: 5px;
      /* Add rounded corners */
      overflow: hidden;
      /* Hide overflow for a cleaner appearance */
    }

    .time-slot input {
      margin-right: 10px;
    }

    .time-label {
      flex: 1;
      padding: 8px;
      text-align: center;
      border-radius: 5px;
      /* Add rounded corners */
      cursor: pointer;
      transition: background-color 0.3s ease;
      /* Smooth transition for background color */
    }

    .time-label.selected {
      background-color: #007bff;
      /* Change background color on selection */
      color: #fff;
      /* Change text color on selection */
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
    <div class="row mb-4 p-0">
      <div class="col-12 col-md-6">
        <a href="<?php echo base_url("amilsaheb"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
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
            </div>
            <div class="col-md-6">
              <button id="submitBtn" class="btn btn-primary mt-3 float-right">Submit</button>
            </div>
          </div>
        </div>
        <!-- Time slot selection part -->
        <div class="col-md-12" style="padding: 20px 50px">
          <label for="selected_time_slots">Select Time Slots:</label>
          <div class="form-check mb-3" id="selectAll">
            <input class="form-check-input" type="checkbox" id="selectAllCheckbox"></input>
            <label class="form-check-label" for="selectAllCheckbox">
              Select All
            </label>
          </div>

          <div id="timeSlots" class="time-slots-container"></div>
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
      // Function to generate time slots with a 10-minute gap
      function generateTimeSlots() {
        var startTime = new Date();
        startTime.setHours(6, 0, 0); // Set start time to 6:00 AM
        var endTime = new Date();
        endTime.setHours(23, 50, 0); // Set end time to 11:50 PM

        var timeSlotsHTML = '<div class="row">'; // Start the first row
        var count = 0;

        while (startTime < endTime) {
          timeSlotsHTML += '<div class="col-md-3">' +
            '<div class="time-slot">' +
            '<input type="checkbox" class="form-check-input time-checkbox" name="selected_time_slot[]" value="' +
            startTime.toLocaleTimeString([], {
              hour: '2-digit',
              minute: '2-digit'
            }) +
            '" id="timeSlot_' + formatTime(startTime) + '"> ' +
            '<label class="form-check-label time-label" for="timeSlot_' + formatTime(startTime) + '">' +
            startTime.toLocaleTimeString([], {
              hour: '2-digit',
              minute: '2-digit'
            }) +
            '</label>' +
            '</div>' +
            '</div>';

          startTime.setMinutes(startTime.getMinutes() + 15); // Increment by 10 minutes
          count++;

          if (count === 4) {
            timeSlotsHTML += '</div><div class="row">'; // Start a new row after every 4 time slots
            count = 0;
          }
        }

        timeSlotsHTML += '</div>'; // Close the last row
        $('#timeSlots').html(timeSlotsHTML);
      }

      // Event handler for date selection
      $("#selected_date").on('change', function() {
        // Fetch and display time slots for the selected date
        generateTimeSlots();

        // Fetch existing time slots for the selected date from the backend
        var selectedDate = $(this).val();
        fetchExistingTimeSlots(selectedDate);
      });

      // Event handler for time slot selection
      $('#timeSlots').on('change', '.time-checkbox', function() {
        var label = $(this).siblings('.time-label');
        label.toggleClass('selected', $(this).prop('checked'));
      });

      // Event handler for "Select All" checkbox
      $('#selectAllCheckbox').on('change', function() {
        var checkboxes = $('.time-checkbox');
        checkboxes.prop('checked', $(this).prop('checked'));
        $('.time-label').toggleClass('selected', $(this).prop('checked'));
      });

      // Function to fetch existing time slots for the selected date
      function fetchExistingTimeSlots(selectedDate) {
        // Use AJAX to fetch existing time slots for the selected date from the backend
        $.ajax({
          url: '<?= base_url('Amilsaheb/getExistingTimeSlots') ?>',
          method: 'GET',
          data: {
            date: selectedDate
          },
          success: function(response) {
            console.log(response)
            existingTimeSlots = JSON.parse(response)
            // Loop through existing time slots and mark corresponding checkboxes as checked
            existingTimeSlots.forEach(function(timeSlot) {
              var checkbox = $('#timeSlot_' + getTimeAsString(timeSlot.time));
              if (checkbox.length > 0) {
                checkbox.prop('checked', true);
                checkbox.siblings('.time-label').addClass('selected');
              }
            });
          },
          error: function() {
            console.error('Error fetching existing time slots');
          }
        });
      }

      // Function to get the time in milliseconds from a time string (HH:mm)
      function getTimeAsString(timeString) {
        var timeParts = timeString.split(':');
        var hours = parseInt(timeParts[0]);
        var minutes_ampm = timeParts[1].split(' ');
        var minutes = minutes_ampm[0];
        var ampm = minutes_ampm[1];

        console.log(hours + '_' + minutes + ampm);
        return hours + '_' + minutes + ampm.toLowerCase();
      }


      // Initial generation of time slots
      generateTimeSlots();
      fetchExistingTimeSlots($("#selected_date").val());
    });
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