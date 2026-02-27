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
				<h1>{%title%}</h1>
			</div>
			<div class="content">
				<div class="intro">
					{%introHtml%}
				</div>

				<div class="card">
					{%cardTitleHtml%}
					{%cardBodyHtml%}
					{%ctaHtml%}
				</div>
			</div>
			<div class="footer">
				Anjuman-e-Saifee — {%jamaat_name%} • Bohra Masjid, Khar • <a href="tel:+919372415351">+91 9372415351</a> • <a href="mailto:kharjamaat786@gmail.com">kharjamaat786@gmail.com</a>
			</div>
		</div>
	</div>
</body>

</html>
