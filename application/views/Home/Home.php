<style>
  .carousel-back {
    height: 256px;
    width: 100%;
    margin: 0 auto;
    padding: 0;
    background-position: top center;
    background-repeat: no-repeat;
    background-size: cover;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    background-image: url('<?php echo base_url('assets/home_banner.jpg') ?>');
  }

  .carousel-logo {
    border-radius: 100px;
    height: 200px;
    opacity: 1;
    margin-top: 30px;
  }

  .home-content .title-base {
    text-align: left;
    position: relative;
    flex-direction: column-reverse;
    display: flex;
    margin-top: 0;
  }

  .home-content .title-base h2 {
    line-height: 35px;
    margin: 0 0 10px 0;
  }

  .home-content .title-base hr {
    border: none;
    background-color: #f3b007;
    width: 100%;
    height: 5px;
    margin: 0 auto 20px 0;
    left: 0;
    opacity: 1;
  }

  .home-content p.welcome-text {
    font-size: 14px;
    line-height: 23px;
    letter-spacing: 0.5px;
    color: #f39f07;
  }

  .boxed.golden {
    background-color: #f3b007;
  }

  .container img {
    border: 4px solid white;
  }
</style>


<div class="margintopcontainer">
  <div class="carousel-back">
    <div class="container">
      <img src="https://www.seattlejamaat.org/static/assets/images/Maula.tus.67235089a22e.jpg"
        class="carousel-logo" />
    </div>
  </div>
  <div class="container content mt-5">
    <div class="home-content">
      <div class="row">
        <div class="col-12">
          <div class="title-base text-left">
            <hr />
            <h2>Anjuman-e-Saifee Khar (Local)</h2>
            <p>Welcome to</p>
          </div>
          <p class="welcome-text">
            Welcome to Anjuman-e-Saifee Jamaat, Khar.
          </p>
          <p>
            We are the members of Dawoodi Bohra Community - Fatimid Shia
            followers of His Holiness Syedna Aali Qadr Muffaddal Saifuddin
            (TUS) residing in the Khar.
          </p>
          <p>
            The rich history of the Dawoodi Bohra community can be traced
            back to the 11th century during the Fatimid Caliphate. Islam,
            with its strict interpretation, is emphasized, particularly
            focusing on education and observance. Customs like specific
            prayers and rituals are passed down through generations. The
            religious life of the community is centered around the highest
            spiritual leader, the Dai al-Mutlaq. Strong community bonds,
            communal gatherings, and celebrations are known features. Active
            involvement in business and trade networks characterizes the
            community, and cultural practices are interwoven with religious
            beliefs.
          </p>
          <p>
            We are a non-profit organization administering and managing the
            affairs of the Dawoodi Bohra community in Khar, Mumbai, Maharashtra, India.
          </p>
        </div>

        <div class="col-12 mt-5">
          <div class="title-base title-left">
            <hr />
            <h2>Information about Dawoodi Bohras</h2>
          </div>
          <div class="list-items">
            <div class="list-item">
              <b>The Dawoodi Bohras</b> - comprehensive website about&nbsp;
              <a href="http://thedawoodibohras.com/" target="_blank">
                history and culture of the Dawoodi Bohra community
              </a>
            </div>
            <!--<div class="list-item">-->
            <!--    <b>Mumineen.org</b> - accurate and authentic content-->
            <!--    pertaining to&nbsp;-->
            <!--    <a href="http://mumineen.org/" target="_blank">-->
            <!--        Dawoodi Bohra Muslims-->
            <!--    </a>-->
            <!--</div>-->
            <!--<div class="list-item">-->
            <!--    <b>Bohra.net</b> – Chronicle of&nbsp;-->
            <!--    <a href="http://bohra.net/" target="_blank">-->
            <!--        Syedna Mufaddal Saifuddin's historic visits to the North-->
            <!--        American Jamiat-->
            <!--    </a>-->
            <!--</div>-->
            <!--<div class="list-item">-->
            <!--    <b>SBMAA</b> - The Saifee Burhani Medical Association of&nbsp;-->
            <!--    <a href="http://sbmedical.org/" target="_blank">-->
            <!--        Dawoodi Bohra physicians&nbsp;-->
            <!--    </a>-->
            <!--    in America and Canada-->
            <!--</div>-->
          </div>
        </div>
      </div>
    </div>
    <div class="home-content mt-5">
      <div class="row">
        <div class="col-12">
          <div class="title-base title-left">
            <hr />
            <h2>Contact us now</h2>
            <p>Get in touch</p>
          </div>
          <div class="col-12 boxed golden p-3 mb-5">
            <form method="post" class="contact-form form-box">
              <div class="row gy-3">
                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                  <input type="text" name="name" class="form-control" placeholder="name"
                    maxLength="255" required id="id_name" />
                </div>
                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                  <input type="text" name="email" class="form-control" placeholder="email"
                    maxLength="255" required id="id_email" />
                </div>
                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                  <input type="text" name="phone" class="form-control" placeholder="phone"
                    maxLength="255" required id="id_phone" />
                </div>
              </div>
              <hr class="space xs" />
              <div class="row">
                <div class="col-md-12">
                  <textarea name="message" cols="40" rows="3" class="form-control"
                    placeholder="Write your message/feedback" required id="id_message"></textarea>
                  <hr class="space s" />
                  <button type="submit" class="btn btn-success">
                    <i class="fa fa-envelope mr-2"></i>Send message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php
    // Provide footer defaults for unauthenticated landing page.
    $footer_defaults = array(
      'org_name' => 'Anjuman-e-Saifee Khar',
      'address_line' => '3RFP+FV4, SV Rd, Khar',
      'city_state' => 'Khar West, Mumbai, Maharashtra',
      'pincode' => '400052',
      'support_email' => 'anjuman@kharjamaat.in',
      'last_updated' => date('d M Y')
    );
    $this->load->view('Common/Footer', $footer_defaults);
    ?>
  </div>
</div>