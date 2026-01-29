<?php
// Initial Hijri month and year (fallback to today's Hijri date)
$ci = get_instance();
$ci->load->model('HijriCalendar');

$today_greg = date('Y-m-d');
$today_hijri = $ci->HijriCalendar->get_hijri_parts_by_greg_date($today_greg);

$hijri_month = isset($_GET['hijri_month']) ? intval($_GET['hijri_month']) : (int)($today_hijri['hijri_month'] ?? 0);
$hijri_year = isset($_GET['hijri_year']) ? intval($_GET['hijri_year']) : (int)($today_hijri['hijri_year'] ?? 0);

// Safety fallback if Hijri lookup missing
if (empty($hijri_month) || empty($hijri_year)) {
  $hijri_month = 1;
  $hijri_year = 1440;
}

// Function to generate the calendar HTML (Hijri month view)
function generateCalendar($hijri_month, $hijri_year, $user_appointments)
{
  $ci = get_instance();
  $ci->load->model('HijriCalendar');
  $hijri_month_padded = str_pad((string)(int)$hijri_month, 2, '0', STR_PAD_LEFT);

  $days = $ci->HijriCalendar->get_hijri_days_for_month_year($hijri_month_padded, (string)(int)$hijri_year);

  $month_row = $ci->HijriCalendar->hijri_month_name((int)$hijri_month);
  $hijri_month_name = $month_row ? $month_row['hijri_month'] : $hijri_month_padded;

  // Get current date for highlighting (greg)
  $today = new DateTime();

  ob_start(); // Start output buffering
?>
  <div class="container pt-5">
    <div class="mt-5 mt-md-0 pt-5">
      <a href="<?php echo base_url('accounts/home') ?>" class="btn btn-secondary inline-block text-blue-600">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <div class="container mt-4 mt-md-0 mb-4 mb-md-0">
      <h1 class="text-center heading mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
      <hr>
    </div>

    <div class="calendar-container mt-4">
      <h4 class="text-center mb-4">Amil Saheb's Appointment Calendar</h4>

      <!-- Navigation for Months -->
      <div class="calendar-navigation text-center mb-4" role="navigation" aria-label="Calendar navigation">
        <button type="button" onclick="changeMonth(-1)" class="nav-btn" aria-label="Previous month" title="Previous month">
          <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
        <div
          id="month-year"
          class="month-pill"
          aria-live="polite"
          data-hijri-month="<?php echo (int)$hijri_month; ?>"
          data-hijri-year="<?php echo (int)$hijri_year; ?>"
        ><?php echo htmlspecialchars($hijri_month_name . ' ' . $hijri_year, ENT_QUOTES); ?></div>
        <button type="button" onclick="changeMonth(1)" class="nav-btn" aria-label="Next month" title="Next month">
          <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
      </div>

      <table class="calendar-table">
        <thead>
          <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
          </tr>
        </thead>
        <tbody>
          <?php
          echo '<tr>';
          if (empty($days)) {
            echo '<td colspan="7" class="text-muted">Hijri calendar data not available for this month.</td>';
          } else {
            $firstGreg = new DateTime($days[0]['greg_date']);
            $startWeekday = (int)$firstGreg->format('w');
            for ($i = 0; $i < $startWeekday; $i++) {
              echo '<td></td>';
            }

            foreach ($days as $d) {
              $greg = $d['greg_date'];
              $isToday = ($greg === $today->format('Y-m-d')) ? 'today' : '';
              $gregDt = new DateTime($greg);
              $hijriDay = ltrim((string)($d['day'] ?? ''), '0');
              if ($hijriDay === '') $hijriDay = (string)($d['day'] ?? '');

              echo '<td class="' . $isToday . '">';
              echo '<a href="' . site_url('accounts/time_slots?date=' . $greg) . '" class="date-link">'
                . '<div class="hijri-day">' . htmlspecialchars($hijriDay, ENT_QUOTES) . '</div>'
                . '<div class="greg-day">' . htmlspecialchars($gregDt->format('j M Y'), ENT_QUOTES) . '</div>'
                . '</a>';
              echo '</td>';

              if ((int)$gregDt->format('w') === 6) {
                echo '</tr><tr>';
              }
            }

            // Pad last week
            $lastGreg = new DateTime(end($days)['greg_date']);
            $endWeekday = (int)$lastGreg->format('w');
            if ($endWeekday !== 6) {
              for ($i = $endWeekday + 1; $i < 7; $i++) {
                echo '<td></td>';
              }
            }
          }
          echo '</tr>';
          ?>
        </tbody>
      </table>
    </div>

    <div class="container" style="margin-top:50px;">
      <h4 class="text-center mb-4">Your Appointments</h4>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Purpose</th>
              <th>Details</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Ensure $user_appointments is an array before sorting
            if (is_array($user_appointments)) {
              usort($user_appointments, function ($a, $b) {
                return strtotime($a->date) - strtotime($b->date);
              });
            } else {
              $user_appointments = []; // Default to empty array if not an array
            }
            ?>
            <?php foreach ($user_appointments as $appointment): ?>
              <tr>
                <td class="td">
                  <?= isset($appointment->purpose) ? htmlspecialchars($appointment->purpose) : '' ?>
                </td>
                <td class="td">
                  <?= isset($appointment->other_details) ? nl2br(htmlspecialchars($appointment->other_details)) : '' ?>
                </td>
                <td class="td">
                  <?php echo date('D, d M Y', strtotime($appointment->date)) ?>
                </td>
                <td class="td">
                  <?= htmlspecialchars($appointment->time) ?>
                </td>
                <td class="<?= ($appointment->status == 0) ? 'status-not-attended' : 'status-attended'; ?>">
                  <?= ($appointment->status == 0) ? 'Not Attended' : 'Attended'; ?>
                </td>
                <td>
                  <?php if ($appointment->status == 0): ?>
                    <a href="<?= site_url('accounts/delete_appointment/' . $appointment->id) ?>"
                      class="btn btn-danger btn-sm"
                      onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                  <?php else: ?>
                    <span data-toggle="tooltip" data-placement="top" title="Appointment Attended">
                      <svg fill="#68d241" width="25px" height="25px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path
                            d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z">
                          </path>
                        </g>
                      </svg>
                    </span>
                  <?php endif; ?>
                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  return ob_get_clean(); // Return the buffer content and clean the buffer
}
  ?>

<?php
// Support month navigation AJAX: return only the calendar HTML when partial=1
// (Controller should skip rendering the header in this mode.)
if (isset($_GET['partial']) && $_GET['partial'] == '1') {
  echo generateCalendar($hijri_month, $hijri_year, $user_appointments);
  return;
}
?>

  </div>


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

      /* .calendar-container {
            max-width: 800px;
            margin: auto;
            background-size: cover;
            background-position: center;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        } */

      .calendar-header {
        text-align: center;
        margin-bottom: 20px;
      }

      .calendar-table {
        width: 100%;
        border-collapse: collapse;
        background-color: rgba(255, 255, 255, 0.8);
        /* Add a semi-transparent white background for better readability */
        border-radius: 8px;
        overflow: hidden;
        /* Ensure rounded corners are applied to the background */
      }

      .calendar-table th,
      .calendar-table td {
        text-align: center;
        border: 1px solid #ddd;

      }

      @media screen and (min-width:540px) {

        .calendar-table th,
        .calendar-table td {
          padding: 1rem;
        }
      }

      .date-link {
        display: block;
        color: #007bff;
        text-decoration: none;
        transition: background-color 0.3s ease;
        padding: 8px;
        border-radius: 4px;
        font-weight: bold;
        line-height: 1.1;
      }

      .date-link .hijri-day {
        font-size: 16px;
        font-weight: 800;
      }

      .date-link .greg-day {
        font-size: 12px;
        font-weight: 600;
        opacity: 0.75;
        margin-top: 2px;
      }

      .date-link:hover {
        background-color: #007bff;
        color: #fff;
      }

      /* Colorful styles for different date ranges */
      .date-past {
        background-color: #f8f9fa;
        color: #6c757d;
      }

      .date-current {
        background-color: #007bff;
        color: #fff;
      }

      .date-link {
        display: block;
        color: #007bff;
      }

      /* Responsive styles for smaller screens */
      @media (max-width: 300px) {

        .calendar-table th,
        .calendar-table td {
          font-size: 12px;
          /* Adjust font size for better visibility on smaller screens */
          padding: 0px;

        }

        .date-link {
          font-size: 12px;
          /* Adjust font size for better visibility on smaller screens */
          padding: 2px;
        }
      }



      /* Styles for buttons */
      .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
      }

      /* Styles for delete button */
      .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
      }

      /* Styles for success button */
      .btn-success {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
      }

      /* Styles for 'Not Attended' status */
      .status-not-attended {
        color: #d9534f;
        font-weight: bold;
      }

      /* Styles for 'Attended' status */
      .status-attended {
        color: #5cb85c;
        font-weight: bold;
      }

      .heading {
        color: #ad7e05;
        font-family: 'Amita', cursive;
      }

      .td {
        min-width: 100px;
      }

      /* Calendar Navigation */
      .calendar-navigation {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        /* Space between buttons and text */
      }

      .calendar-navigation .nav-btn {
        background-color: #007bff;
        border: none;
        color: #fff;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        cursor: pointer;
        transition: background-color 0.15s ease, transform 0.08s ease;
        font-size: 1rem;
      }

      .calendar-navigation .nav-btn:active { transform: translateY(1px); }

      .calendar-navigation .nav-btn:hover {
        background-color: #0056b3;
      }

      .calendar-navigation .month-pill {
        display: inline-block;
        background: #ffffff;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
        font-size: 1.15rem;
        font-weight: 700;
        color: #222;
        min-width: 160px;
        text-align: center;
      }

      /* Calendar Table */
      .calendar-table {
        width: 100%;
        border-collapse: collapse;
      }

      .calendar-table th,
      .calendar-table td {
        border: 1px solid #ddd;
        /* Border color */
        padding: 0.5rem;
        /* Padding */
        text-align: center;
        /* Center text */
      }

      .calendar-table th {
        background-color: #f4f4f4;
        /* Header background color */
      }

      /* Highlight Current Date */
      .today a {
        color: white;

        /* Padding */
        background-color: goldenrod;
        /* Background color */
      }
    </style>

  </head>

  <body>

      <div id="calendar-content">
      <?php echo generateCalendar($hijri_month, $hijri_year, $user_appointments); ?>
    </div>

    <script>
      function changeMonth(offset) {
        const el = document.getElementById('month-year');
        if (!el) return;
        let month = parseInt(el.dataset.hijriMonth || '0', 10);
        let year = parseInt(el.dataset.hijriYear || '0', 10);
        if (!month || !year) return;

        month += offset;
        if (month < 1) {
          month = 12;
          year -= 1;
        } else if (month > 12) {
          month = 1;
          year += 1;
        }

        // Fetch new calendar HTML (partial)
        fetch(`?hijri_month=${month}&hijri_year=${year}&partial=1`)
          .then(response => response.text())
          .then(html => {
            document.getElementById('calendar-content').innerHTML = html;
          })
          .catch(error => console.error('Error fetching calendar data:', error));
      }
    </script>







    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  </html>