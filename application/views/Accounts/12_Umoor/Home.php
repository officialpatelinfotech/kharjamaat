<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
	.icon {
		font-size: 40pt;
		margin: 10px 0;
		color: #ffffff;
	}

	.title {
		font-size: 20px;
		color: goldenrod;
	}

	.title1 {
		color: black;
		color: goldenrod;
		font-size: 35px;
	}

	.heading {
		color: #ad7e05;
		font-family: 'Amita', cursive;
	}

	.card {
		height: 100%;
		background-color: #FEF7E6;
		transition: transform 0.5s ease-in-out;
		border-radius: 10px;
	}

	/* Make all cards in the grid equal height */
	#razaCardsContainer>a {
		display: flex;
	}

	#razaCardsContainer>a .card {
		width: 100%;
	}

	.card-body {
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		align-items: center;
		padding: 0.25rem;
	}

	.title {
		min-height: 52px;
		/* reserves space for 1–2 lines */
		display: flex;
		align-items: center;
		justify-content: center;
		line-height: 1.2;
	}

	.card:hover {
		box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
		transform: scale(1.05);
	}

	.row a {
		text-decoration: none;
	}

	.img-fluid {
		height: 140px;
		width: 100%;
		object-fit: contain;
		display: block;
	}

	/* Search Bar Styles */
	.search-container {
		position: relative;
		max-width: 800px;
		margin: 0 auto 30px;
	}

	.search-input {
		padding: 15px 50px 15px 20px;
		border-radius: 50px;
		border: 2px solid #AD7E05;
		font-size: 18px;
		width: 100%;
		box-shadow: 0 4px 10px rgba(173, 126, 5, 0.1);
		transition: all 0.3s ease;
	}

	.search-input:focus {
		outline: none;
		box-shadow: 0 4px 15px rgba(173, 126, 5, 0.2);
	}

	.search-icon {
		position: absolute;
		right: 20px;
		top: 50%;
		transform: translateY(-50%);
		color: #AD7E05;
		font-size: 20px;
	}

	.suggestions-dropdown {
		position: absolute;
		width: 100%;
		max-height: 300px;
		overflow-y: auto;
		background: white;
		border: 1px solid #ddd;
		border-radius: 0 0 10px 10px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
		z-index: 1000;
		display: none;
	}

	.suggestion-item {
		padding: 12px 20px;
		cursor: pointer;
		border-bottom: 1px solid #f0f0f0;
		display: flex;
		justify-content: space-between;
		align-items: center;
		transition: background-color 0.2s;
	}

	.suggestion-item:hover {
		background-color: #FEF7E6;
	}

	.suggestion-name {
		font-weight: 600;
		color: #333;
	}

	.suggestion-umoor {
		color: #AD7E05;
		font-size: 0.9em;
		background: rgba(173, 126, 5, 0.1);
		padding: 3px 8px;
		border-radius: 4px;
	}

	@media (max-width: 1176px) {
		.title1 {
			font-size: 30px;
		}
	}

	@media (max-width: 768px) {
		.search-input {
			font-size: 16px;
			padding: 12px 45px 12px 15px;
		}

		.search-icon {
			right: 15px;
			font-size: 18px;
		}
	}

	@media (max-width: 576px) {
		.title1 {
			font-size: 25px;
		}

		.suggestion-item {
			padding: 10px 15px;
			flex-direction: column;
			align-items: flex-start;
		}

		.suggestion-umoor {
			margin-top: 5px;
		}
	}
</style>

<div class="container margintopcontainer pt-5">
	<div class="text-start mb-3">
		<a href="<?= base_url('accounts') ?>" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i></a>
	</div>
	<h1 class="text-center heading mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>

	<!-- Search Bar -->
	<div class="search-container">
		<input type="text" id="razaSearch" class="form-control search-input"
			placeholder="Search for Raza categories..." autocomplete="off">
		<div class="search-icon">
			<i class="fa fa-search"></i>
		</div>
		<div id="searchSuggestions" class="suggestions-dropdown"></div>
	</div>

	<hr>
	<div class="continer d-flex justify-content-center">
		<div class="row container mt-3" id="razaCardsContainer">
			<!-- <a class="col-12 col-md-12 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=Private-Event') ?>"
				style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; height: 180px;">
				<div class="card text-center" style="width: 100%; height: 100%;">
					<div class="card-body"
						style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
						<div class="title1" style="color: goldenrod; margin: 0;"><b
								style="color:#AD7E05;">Private Event</b><br>Kaaraj Raza</div>
					</div>
				</div>
			</a> -->
			<!-- <a class="col-12 col-md-6 col-xxl-2 py-2" href="<?= base_url('Umoor12/MyRazaRequest?value=Public-Event') ?>"
				style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; height: 180px;">
				<div class="card text-center" style="width: 100%; height: 100%;">
					<div class="card-body"
						style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
						<div class="title1" style="color: goldenrod; margin: 0;"><b
								style="color:#AD7E05;">Public Event</b><br>Miqaat Raza</div>
					</div>
				</div>
			</a> -->
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorDeeniyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Deeniyah</div>
						<img src="<?php echo base_url('assets/Umoor_Deeniyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorTalimiyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Talimiyah</div>
						<img src="<?php echo base_url('assets/Umoor_Talimiyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMarafiqBurhaniyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Marafiq Burhaniyah</div>
						<img src="<?php echo base_url('assets/Umoor_Marafiq_Burhaniyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMaaliyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Maaliyah</div>
						<img src="<?php echo base_url('assets/Umoor_Maaliyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorMawaridBashariyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Mawarid Bashariyah</div>
						<img src="<?php echo base_url('assets/Umoor_Mawarid_Bashariyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorDakheliyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Dakheliya</div>
						<img src="<?php echo base_url('assets/Umoor_Dakheliyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorKharejiyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Kharejiyah</div>
						<img src="<?php echo base_url('assets/Umoor_Kharijiyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorIqtesadiyah') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Iqtesadiyah</div>
						<img src="<?php echo base_url('assets/Umoor_Iqtesadiyah.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2" href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorFMB') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor FMB</div>
						<img src="<?php echo base_url('assets/Umoor_FMB.png'); ?>" alt="Your Image" class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Qaza') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Al-Qaza</div>
						<img src="<?php echo base_url('assets/Umoor_al-Qaza.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Amlaak') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Al-Amlaak</div>
						<img src="<?php echo base_url('assets/Umoor_al-Amlaak.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
			<a class="col-12 col-md-3 col-xxl-2 py-2"
				href="<?= base_url('Umoor12/MyRazaRequest?value=UmoorAl-Sehhat') ?>">
				<div class="card text-center">
					<div class="card-body">
						<div class="title">Umoor Al-Sehhat</div>
						<img src="<?php echo base_url('assets/Umoor_al-Sehhat.png'); ?>" alt="Your Image"
							class="img-fluid">
					</div>
				</div>
			</a>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {
		// Initialize search functionality
		const razaData = <?= json_encode($raza_types) ?>;

		// Add static cards to search data
		razaData.unshift({
			name: "Private Event",
			umoor: "Private-Event",
			id: "private"
		}, {
			name: "Public Event",
			umoor: "Public-Event",
			id: "public"
		});

		// Search functionality
		$('#razaSearch').on('input', function() {
			const searchTerm = $(this).val().toLowerCase();
			const suggestions = $('#searchSuggestions');

			if (searchTerm.length === 0) {
				suggestions.hide();
				return;
			}

			const filteredResults = razaData.filter(item =>
				item.name.toLowerCase().includes(searchTerm) ||
				(item.umoor && item.umoor.toLowerCase().includes(searchTerm))
			);

			suggestions.empty();
			if (filteredResults.length > 0) {
				filteredResults.forEach(item => {
					suggestions.append(`
                        <div class="suggestion-item" 
														data-umoor="${item.umoor}" 
														data-id="${item.id || ''}">
                            <span class="suggestion-name">${item.name}</span>
                            <span class="suggestion-umoor">${item.umoor}</span>
                        </div>
                    `);
				});
				suggestions.show();
			} else {
				suggestions.hide();
			}
		});

		// Handle suggestion click (umoor in URL, razaId in POST)
		$(document).on('click', '.suggestion-item', function() {
			const umoorValue = $(this).data('umoor');
			const razaId = $(this).data('id');

			// Create hidden form
			const $form = $('<form>', {
				method: 'POST',
				action: `<?= base_url('Umoor12/NewRazaBySearch?value=') ?>${umoorValue}`
			}).append(
				$('<input>', {
					type: 'hidden',
					name: 'razaId',
					value: razaId
				})
			);

			// Submit form
			$('body').append($form);
			$form.submit();
		});

		// Hide suggestions when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('#razaSearch, #searchSuggestions').length) {
				$('#searchSuggestions').hide();
			}
		});

		// Card hover effect
		$('.raza-card').hover(
			function() {
				$(this).find('.card').css('transform', 'scale(1.05)');
			},
			function() {
				$(this).find('.card').css('transform', 'scale(1)');
			}
		);
	});
</script>