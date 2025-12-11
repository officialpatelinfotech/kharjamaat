<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #61A0B1;
            margin: 0;
            padding: 0;
            text-align: center;
            background-image: url('<?php echo base_url('assets/background.png'); ?>');
            background-attachment: fixed;
        }

        .profile-container {
            max-width: 1100px;
            margin: 0px auto;
        }

        .profile-card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            padding: 20px;
        }

        h2 {
            color: goldenrod;
            border-bottom: 2px solid goldenrod;
            padding-bottom: 10px;
        }

        .detail-box {
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 10px;
            border: 2px solid goldenrod;
        }

        .user-details {
            background-color: #ffff;
        }

        .family-details {
            background-color: #ffff;
        }

        .family-members {
            background-color: #ffff;
        }

        .incharge-details {
            background-color: #ffff;
        }

        @media (max-width: 768px) {
            .profile-card {
                width: 80%;
                margin: 20px auto;
            }
        }

        strong {
            color: #AD7E05;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center container margintopcontainer pt-5">
        <a href="<?php echo base_url('accounts/home'); ?>" class="mt-4 btn btn-secondary me-2"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="profile-container mt-3 mb-5">
        <div class="row">
            <!-- User Profile Card (Left) -->
            <div class="col-md-6">
                <div class="profile-card user-details">
                    <h2>PERSONAL DETAILS</h2>
                    <div class="detail-box">
                        <strong>ITS ID:</strong> <?php echo $user_data['ITS_ID']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Full Name:</strong> <?php echo $user_data['Full_Name']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Full Name (Arabic):</strong> <?php echo $user_data['Full_Name_Arabic']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Vatan:</strong> <?php echo $user_data['Vatan']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Mobile:</strong> <?php echo $user_data['Mobile']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Email:</strong> <?php echo $user_data['Email']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Age:</strong> <?php echo $user_data['Age']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Gender:</strong> <?php echo $user_data['Gender']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Misaq:</strong> <?php echo $user_data['Misaq']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Marital Status:</strong> <?php echo $user_data['Marital_Status']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Blood Group:</strong> <?php echo $user_data['Blood_Group']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Organisation(s):</strong> <?php echo $user_data['Organisation']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>TanzeemFile No:</strong> <?php echo $user_data['TanzeemFile_No']; ?>
                    </div>
                </div>
                <div class="profile-card user-details">
                    <h2>RESIDENTIAL ADDRESS</h2>
                    <div class="detail-box">
                        <strong>Address:</strong> <?php echo $user_data['Address']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>City:</strong> <?php echo $user_data['City']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Pincode:</strong> <?php echo $user_data['Pincode']; ?>
                    </div>
                </div>
                <div class="profile-card user-details">
                    <h2>JAMAAT DETAILS</h2>
                    <div class="detail-box">
                        <strong>Jamaat:</strong> <?php echo $user_data['Jamaat']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Jamiaat:</strong> <?php echo $user_data['Jamiaat']; ?>
                    </div>
                </div>
            </div>

            <!-- Other Details Card (Right) -->
            <div class="col-md-6">
                <!-- Family Details Card -->
                <div class="profile-card family-details">
                    <h2>PARENTS</h2>
                    <div class="detail-box">
                        <strong>Father's ITS ID:</strong> <?php echo $father_data['ITS_ID']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Father's Name:</strong> <?php echo $father_data['Full_Name']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Mother's ITS ID:</strong> <?php echo $mother_data['ITS_ID']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Mother's Name:</strong> <?php echo $mother_data['Full_Name']; ?>
                    </div>
                </div>

                <div class="profile-card family-details">
                    <h2>HEAD OF FAMILY</h2>
                    <div class="detail-box">
                        <strong>HOF ITS ID:</strong> <?php echo $hof_data['ITS_ID']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>HOF Name:</strong> <?php echo $hof_data['Full_Name']; ?>
                    </div>
                </div>

                <!-- Family Members Card -->
                <div class="profile-card family-members">
                    <h2>FAMILY MEMBERS</h2>
                    <?php foreach ($family_members as $member): ?>
                        <div class="detail-box">
                            <strong>ITS ID:</strong> <?php echo '<span style="border-right: 2px solid goldenrod; padding-right: 5px">' . $member['ITS_ID'] . '</span>'; ?> <strong>Full Name:</strong> <?php echo $member['Full_Name']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Incharge Details Card -->
                <div class="profile-card incharge-details">
                    <h2>MASOOL / MUSAID</h2>
                    <div class="detail-box">
                        <strong>Sector:</strong> <?php echo $user_data['Sector']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Sub Sector:</strong> <?php echo $user_data['Sub_Sector']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Sector Incharge (Masool):</strong> <?php echo $incharge_data['Sector_Incharge_Name']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Sector Incharge Female (Masool):</strong> <?php echo $incharge_data['Sector_Incharge_Female_Name']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Sub Sector Incharge (Musaid):</strong> <?php echo $incharge_data['Sub_Sector_Incharge_Name']; ?>
                    </div>
                    <div class="detail-box">
                        <strong>Sub Sector Incharge Female (Musaid):</strong> <?php echo $incharge_data['Sub_Sector_Incharge_Female_Name']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>