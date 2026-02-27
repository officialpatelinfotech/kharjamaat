<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Calendar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }


    .time-slots-container {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      /* margin: 0 -15px; */
      /* Default margin for mobile */
      justify-content: center;
    }

    .time-slot {
      width: calc(100% - 30px);
      /* One block per row on small screens */
      margin-bottom: 20px;
      padding: 0 15px;
    }

    @media (min-width: 768px) {
      .time-slots-container {
        margin: 0 100px;
        /* Margin for laptops and larger screens */
      }

      .time-slot {
        width: calc(33.3333% - 30px);
      }
    }

    @media (min-width: 992px) {
      .time-slot {
        width: calc(33.3333% - 30px);
      }
    }

    .card {
      border: 1px solid goldenrod;
      border-radius: 5px;
      padding: 15px;
      text-align: center;
      transition: box-shadow 0.3s ease;
      height: 100%;
      text-decoration: none;

    }

    /* Unique colors for each card */
    .card:nth-child(odd) {
      background-color: #FEF7E6;
      /* Yellow */
    }

    .card:nth-child(even) {
      background-color: #007bff;
      /* Blue */
      color: #fff;
      /* White text for better contrast */
    }

    .card:hover {
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .error-box {
      width: 100%;
      text-align: center;
      background-color: #ffcccc;
      border: 1px solid #ff3333;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .error-box p {
      margin: 0;
      color: #ff3333;
      font-weight: bold;
    }

    .book-link {
      color: inherit;
      /* Inherit text color from the parent element */
      text-decoration: none;
      /* Remove underline */
      cursor: pointer;
      /* Change cursor to pointer on hover for better indication */
    }

    .book-link :hover {
      color: #fff;
      text-decoration: none;
    }

    .heading {
      color: #ad7e05;
      font-family: 'Amita', cursive;
    }
  </style>




</head>

<body class="container pt-5">
  <div class="mt-5 mt-md-0 mb-4 mb-md-0 pt-5">
    <a href="<?php echo base_url('accounts/appointment') ?>" class="btn btn-secondary inline-block text-blue-600">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <div class="">
    <h1 class="text-center heading mb-4">Welcome to Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></h1>
    <hr>

  </div>
  <h4 class="text-center mb-4">Available Time Slots for
  </h4>
  <?php
  // Resolve Hijri date for the displayed Gregorian date
  $ci = get_instance();
  $ci->load->model('HijriCalendar');
  $hparts = $ci->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d', strtotime($date)));
  $hijri_label = '';
  if (!empty($hparts)) {
    $hijri_label = htmlspecialchars($hparts['hijri_day'] . ' ' . $hparts['hijri_month_name'] . ' ' . $hparts['hijri_year'], ENT_QUOTES);
  }
  ?>
  <h5 class="text-center mb-2 font-italic" style="color: #ad7e05;"><?php echo date('D, d M Y', strtotime($date)); ?>
  </h5>
  <?php if ($hijri_label !== ''): ?>
    <h5 class="text-center mb-4 font-italic" style="color:#333;font-weight:700;">Hijri: <?php echo $hijri_label; ?></h5>
  <?php endif; ?>

  <div class="time-slots-container">
    <?php if (is_array($time_slots) && !empty($time_slots['time_slots'])): ?>
      <?php foreach ($time_slots['time_slots'] as $time_slot): ?>
        <?php
        $formatted_time = date('h:i A', strtotime($time_slot->time)) . '-' . date('h:i A', strtotime($time_slot->time . ' +15 minutes'));
        ?>
        <div class="time-slot">
          <a href="#" class="book-link" data-slot-id="<?= $time_slot->slot_id ?>" data-time="<?= $time_slot->time ?>">
            <div class="card">
              <?= $formatted_time ?>
              <p>Available
                <?= $time_slot->count ?>
              </p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="error-box">
        <p>No time slots available</p>
      </div>
    <?php endif; ?>
  </div>
  <!-- Purpose selection modal -->
  <div class="modal fade" id="purposeModal" tabindex="-1" role="dialog" aria-labelledby="purposeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="purposeModalLabel">What is this appointment for?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="purposeForm">
            <input type="hidden" id="modal_slot_id" name="slot_id" />
            <input type="hidden" id="modal_time" name="time" />

            <div class="form-group">
              <label for="appointment_purpose">Select purpose</label>
              <select class="form-control" id="appointment_purpose" name="purpose" required>
                <option value="">-- Select --</option>
                <option>Wajebaat Takhmeen</option>
                <option>Miqaat Raza</option>
                <option>Kaaraj Raza</option>
                <option>Safai Chitthi</option>
                <option>Transfer Out</option>
                <option>Transfer In (New Sabeel Registration)</option>
                <option>Nikah Raza</option>
                <option>Misaaq Raza</option>
                <option>Taweez</option>
                <option>Talaq Qaziyah</option>
                <option>Wirasat Qaziyah</option>
                <option>Araz to Maula tus</option>
                <option>Others</option>
              </select>
            </div>

            <div class="form-group" id="otherTextGroup" style="display:none;">
              <label for="other_text">Please specify (required)</label>
              <input type="text" class="form-control" id="other_text" name="other_text" />
            </div>

            <div class="form-group" id="otherDetailsGroup">
              <label for="other_details">Other details (optional)</label>
              <textarea class="form-control" id="other_details" name="other_details" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="purposeConfirmBtn" class="btn btn-primary">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var bookLinks = document.querySelectorAll('.book-link');

      bookLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
          event.preventDefault();

          var slotId = this.getAttribute('data-slot-id');
          var time = this.getAttribute('data-time');

          // populate modal hidden fields
          document.getElementById('modal_slot_id').value = slotId;
          document.getElementById('modal_time').value = time;

          // reset form
          document.getElementById('appointment_purpose').value = '';
          document.getElementById('other_text').value = '';
          document.getElementById('other_details').value = '';
          document.getElementById('otherTextGroup').style.display = 'none';

          // show modal (requires jQuery + bootstrap JS loaded on page)
          if (window.jQuery && jQuery.fn.modal) {
            jQuery('#purposeModal').modal('show');
          } else {
            // fallback to confirm if modal isn't available
            var isConfirmed = confirm('Do you want to book this time slot?');
            if (isConfirmed) {
              window.location.href = "<?= site_url('accounts/book_slot') ?>?slot_id=" + slotId + "&time=" + time;
            }
          }
        });
      });

      // show/hide other textbox
      var purposeSelect = document.getElementById('appointment_purpose');
      var otherGroup = document.getElementById('otherTextGroup');
      var otherInput = document.getElementById('other_text');

      purposeSelect.addEventListener('change', function() {
        if (this.value === 'Others') {
          otherGroup.style.display = '';
          otherInput.setAttribute('required', 'required');
        } else {
          otherGroup.style.display = 'none';
          otherInput.removeAttribute('required');
        }
      });

      // confirm button
      document.getElementById('purposeConfirmBtn').addEventListener('click', function() {
        var slotId = encodeURIComponent(document.getElementById('modal_slot_id').value);
        var time = encodeURIComponent(document.getElementById('modal_time').value);
        var purpose = document.getElementById('appointment_purpose').value;
        var details = document.getElementById('other_details').value.trim();

        if (!purpose) {
          alert('Please select a purpose.');
          return;
        }

        if (purpose === 'Others') {
          var other = document.getElementById('other_text').value.trim();
          if (!other) {
            alert('Please specify the purpose for "Others".');
            return;
          }
          purpose = other;
        }

        // redirect with purpose param
        var url = "<?= site_url('accounts/book_slot') ?>?slot_id=" + slotId + "&time=" + time + "&purpose=" + encodeURIComponent(purpose) + "&details=" + encodeURIComponent(details);
        window.location.href = url;
      });
    });
  </script>






  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>