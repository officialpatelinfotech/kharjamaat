<!doctype html>
<html>

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style>
		/* Basic reset for email clients */
		body {
			margin: 0;
			padding: 0;
			background: #f4f6f8;
			font-family: Arial, Helvetica, sans-serif;
		}

		.container {
			max-width: 700px;
			margin: 18px auto;
			background: #ffffff;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 6px 18px rgba(18, 38, 63, 0.06);
		}

		.header {
			background: #0b815a;
			color: #fff;
			padding: 16px 20px;
			text-align: left;
		}

		.header h1 {
			margin: 0;
			font-size: 18px;
			font-weight: 700;
		}

		.meta {
			color: rgba(255, 255, 255, 0.9);
			font-size: 13px;
			margin-top: 4px;
		}

		.content {
			padding: 20px;
			color: #111;
			font-size: 14px;
			line-height: 1.5;
		}

		.intro {
			margin-bottom: 14px;
		}

		.card {
			background: #fafafa;
			border: 1px solid #eee;
			padding: 14px;
			border-radius: 6px;
		}

		.table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 12px;
		}

		.table th,
		.table td {
			padding: 10px 12px;
			border: 1px solid #e9e9e9;
			text-align: left;
			font-size: 14px;
		}

		.table th {
			background: #f2f6f4;
			color: #0b815a;
			font-weight: 600;
		}

		.cta {
			display: inline-block;
			margin-top: 14px;
			padding: 10px 14px;
			background: #0b815a;
			color: #fff;
			text-decoration: none;
			border-radius: 4px;
		}

		.footer {
			background: #f7faf8;
			padding: 16px 20px;
			color: #67747a;
			font-size: 13px;
			text-align: center;
		}

		@media (max-width:520px) {
			.container {
				margin: 12px
			}

			.header h1 {
				font-size: 16px
			}
		}
	</style>
</head>

<body>
	<div style="padding:10px;">
		<div class="container">
			<div class="header">
				<h1>New Raza Application</h1>
				<div class="meta">{%todayDate%}</div>
			</div>
			<div class="content">
				<div class="intro">
					<strong>Baad Afzalus Salaam,</strong><br />
					<span style="font-weight:600">{%name%}</span> — <span style="color:#5b6b6b">{%its%}</span>
				</div>

				<div class="card">
					<p style="margin:0 0 8px 0; font-weight:600;">Your Raza request has been submitted successfully.</p>

					<table class="table" role="presentation">
						<thead>
							<tr>
								<th align="center" style="border: 1px solid black;width: 50%;" colspan="2" style="width:40%">Raza Details</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center" style="border: 1px solid black;width: 50%;">
									<p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">Raza For</p>
								</td>
								<td style="border-right: #000000 1px solid; border-bottom: #000000 1px solid;">
									<p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">{%razaname%}</p>
								</td>
							</tr>
							{%table%}
						</tbody>
					</table>

					<p style="margin-top:12px;">If you did not submit this request, please contact us at <a href="mailto:kharjamaat786@gmail.com">kharjamaat786@gmail.com</a>.</p>
					<a class="cta" style="color: white;" href="https://www.kharjamaat.in/accounts">Login to your account</a>
				</div>
			</div>
			<div class="footer">
				Anjuman-e-Saifee — {%jamaat_name%} • Bohra Masjid, Khar • <a href="tel:+919372415351">+919372415351</a> • <a href="mailto:kharjamaat786@gmail.com">kharjamaat786@gmail.com</a>
			</div>
		</div>
	</div>
</body>

</html>