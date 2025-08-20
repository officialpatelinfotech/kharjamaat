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
            margin: 0 -15px;
            /* Default margin for mobile */
            justify-content: center;
        }

        .time-slot {
            width: calc(50% - 30px);
            /* Two blocks in a row for mobile with margin */
            margin-bottom: 20px;
            padding: 0 15px;
            /* Add padding to counteract the negative margin */
        }

        @media (min-width: 768px) {
            .time-slots-container {
                margin: 0 100px;
                /* Margin for laptops and larger screens */
            }

            .time-slot {
                width: calc(33.3333% - 30px);
                /* Three blocks in a row for tablets and larger with margin */
            }
        }

        @media (min-width: 992px) {
            .time-slot {
                width: calc(16.6667% - 30px);
                /* Six blocks in a row for computers with margin */
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

<body>
    <div class="container margintopcontainer">
        <h1 class="text-center heading mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
        <hr>

    </div>
    <h4 class="text-center mb-4">Available Time Slots for
    </h4>
    <h5 class="text-center mb-4 font-italic" style="color: #ad7e05;"><?php echo date('D, d M Y', strtotime($date)); ?>
    </h5>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bookLinks = document.querySelectorAll('.book-link');

            bookLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    var slotId = this.getAttribute('data-slot-id');
                    var time = this.getAttribute('data-time');

                    var isConfirmed = confirm('Do you want to book this time slot?');

                    if (isConfirmed) {
                        window.location.href = "<?= site_url('accounts/book_slot') ?>?slot_id=" + slotId + "&time=" + time;
                    }
                });
            });
        });
    </script>






    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>