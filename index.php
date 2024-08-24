<?php

?>
<!DOCTYPE html><html lang="en"><head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRESTA Bank</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="images/icon.png" type="image/png">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&amp;family=Roboto:wght@700;900&amp;display=swap" rel="stylesheet">
</head>

<body id="top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header="">
    <div class="container">
      <a href="/" class="logo"><img src="images/logo.png" alt="logo" style="width:60%; margin-top: -20px; margin-bottom:-20px"></a>



      <nav class="navbar" data-navbar="">
        <ul class="navbar-list container">

          <li>
            <a href="#home" class="navbar-link active" data-nav-link="">Home</a>
          </li>

          <li>
            <a href="#features" class="navbar-link" data-nav-link="">Features</a>
          </li>

          <li>
            <a href="#about" class="navbar-link" data-nav-link="">About</a>
          </li>
          <li>
            <a href="#app" class="navbar-link" data-nav-link="">Invest</a>
          </li>
          <li>
            <a href="#blog" class="navbar-link" data-nav-link="">Testimonials</a>
          </li>

          <li>
            <a href="/crestabank/login" class="btn btn-primary">Login</a>
          </li>
          <li>
            <a href="/crestabank/signup" class="btn btn-primary">Register</a>
          </li>
          

        </ul>
      </nav>

      <button class="nav-toggle-btn" aria-label="toggle manu" data-nav-toggler="">
        <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
      </button>

    </div>
  </header>





  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="section hero" aria-label="hero" id="home">
        <div class="container">

          <div class="hero-content">

            <h1 class="h1 hero-title">Welcome to CRESTA Bank</h1>
            <h2> 
              Next generation digital banking
            </h2>

            <p class="section-text">
              Take your financial life online. Your easy bank account
              will be a one-stop-shop for spending,saving,
              budgeting,investing, and much more.
            </p>

            <ul class="btn-list">

              <li>
                  <a href="/crestabank/login" class="btn btn-primary">Login</a>
              </li>

              <li>
                <a href="/crestabank/signup" class="btn btn-primary">Register</a>
              </li>

            </ul>

          </div>

          <figure class="hero-banner">
            <img src="images/hero-banner.png" width="769" height="804" alt="hero banner" class="w-100">
          </figure>

        </div>
      </section>





      <!-- 
        - #FEATURES
      -->

      <section class="section features" id="features" aria-label="features">
        <div class="container">


          <h2 class="h2 section-title">Awesome Services</h2>

          <ul class="features-list">

            <li class="features-item">
              <div class="features-card">

                <div class="card-icon">
                  <ion-icon name="create-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h3 card-title">Online Banking</h3>

                <p class="card-text">
                  Keep track of your finances whereever you are in the world.
                </p>

              </div>
            </li>

            <li class="features-item">
              <div class="features-card">

                <div class="card-icon">
                  <ion-icon name="shield-checkmark-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h3 card-title">Fully Secure</h3>

                <p class="card-text"> 
                  Full security for every transaction you make with us
                </p>

              </div>
            </li>

            <li class="features-item">
              <div class="features-card">

                <div class="card-icon">
                  <ion-icon name="settings-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h3 card-title">Loan</h3>

                <p class="card-text">
                  Financial support you need with our loan options
                </p>

              </div>
            </li>

            <li class="features-item">
              <div class="features-card">

                <div class="card-icon">
                  <ion-icon name="cube-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h3 card-title">Fast Onboarding</h3>

                <p class="card-text">
                  Create an account in minutes. Take control of your finances.
                </p>

              </div>
            </li>

          </ul>

        </div>
      </section>





      <!-- 
        - #ABOUT
      -->

      <section class="section about" id="about" aria-label="about">
        <div class="container">

          <figure class="about-banner">
            <img src="images/about-banner.png" width="1262" height="1357" loading="lazy" alt="about banner" class="w-100">
          </figure>

          <div class="about-content">

            <h2 class="h2 section-title">We Are Trusted By Thousands Of People</h2>

            <p class="section-text">
              At CRESTA Bank, our reputation as a trusted financial institution is built upon the confidence and trust of thousands of individuals just like you. Here's why so many people choose CRESTA Bank for their financial needs:
            </p>

            <ul class="about-list">

              <li class="about-item">

                <div class="item-icon">
                  <ion-icon name="folder" aria-hidden="true"></ion-icon>
                </div>

                <div>
                  <h3 class="h3 item-title">Reliability and Security</h3>

                  <p class="item-text">
                    CRESTA Bank is known for its consistent, reliable services, and strong security measures. Customers trust us to protect their financial information and provide dependable banking solutions.
                  </p>
                </div>

              </li>

              <li class="about-item">

                <div class="item-icon">
                  <ion-icon name="pie-chart" aria-hidden="true"></ion-icon>
                </div>

                <div>
                  <h3 class="h3 item-title">Customer-Centric Approach</h3>

                  <p class="item-text">
                    We prioritize our customers' needs and provide personalized, transparent, and accountable service. Our commitment to community engagement further solidifies our customer trust.
                  </p>
                </div>

              </li>
              
              <li class="about-item">

                <div class="item-icon">
                  <ion-icon name="folder" aria-hidden="true"></ion-icon>
                </div>

                <div>
                  <h3 class="h3 item-title">Innovation and Experience</h3>

                  <p class="item-text">
                    With a blend of innovation and years of industry experience, we offer modern, convenient banking solutions while maintaining a proven track record of excellence. Customers trust our expertise to help them achieve their financial goals.
                  </p>
                </div>

              </li>

            </ul>

            <a href="/crestabank/signup" class="btn btn-secondary">Get Started</a>

          </div>

        </div>
      </section>

      <!-- 
        - #APP
      -->

      <section class="section app" aria-label="app" id="app">
        <div class="container">

          <figure class="app-banner">
            <img src="images/app.png" width="449" height="608" loading="lazy" alt="app banner" class="w-100">
          </figure>

          <div class="app-content">

            <h2 class="h2 section-title">Invest with Us: Your Path to Financial Growth</h2>

            <p class="section-text">
              Are you ready to take the next step towards securing your financial future? At CRESTA Bank, we offer a wide range of investment opportunities and services designed to help you grow your wealth and achieve your financial goals.
            </p>

            <ul class="btn-list">

              <li>
                <a href="/crestabank/signup" class="btn btn-primary">Register Now</a>
              </li>

            </ul>

          </div>

        </div>
      </section>





      <!-- 
        - #BLOG
      -->

      <section class="section blog" id="blog" aria-label="blog">
        <div class="container">

          

          <h2 class="h2 section-title">Testimonials</h2>
          <p class="section-subtitle">Hear from our clients</p>
<br><br>
          <ul class="blog-list">

            <li>
              <div class="blog-card">

                <figure class="card-banner img-holder" style="--width: 768; --height: 558;">
                  <img src="images/w1.jpg" width="768" height="558" loading="lazy" alt="Build A Full Web Chat App From Our Scratch" class="img-cover">
                </figure>

                <div class="card-content">

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <ion-icon name="calendar-outline" aria-hidden="true"></ion-icon>

                      <time class="card-meta-text" datetime="2022-05-22">May 22,2020</time>
                    </li>


                  </ul>

                  <p>
                    "CRESTA Bank not only manages my finances effectively but also supports our local community. Their commitment to charity and community development sets them apart. It's been an awesome experice. I'm proud to bank with them."
                    </p><p>
                      <br>
                </p><p>Merisa M.</p>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner img-holder" style="--width: 768; --height: 558;">
                  <img src="images/m2.jpg" width="768" height="558" loading="lazy" alt="Brush Strokes Energize Trees In Paintings" class="img-cover">
                </figure>

                <div class="card-content">

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <ion-icon name="calendar-outline" aria-hidden="true"></ion-icon>

                      <time class="card-meta-text" datetime="2022-05-22">October 19,2022</time>
                    </li>

                  </ul>

                  <p>
                    "Switching to CRESTA Bank was a smart move. Their strong security, transparency, and professional service gave me peace of mind. I trust them with my finances and recommend them to anyone seeking reliability."                  
                    </p><p>
                      <br>
                </p><p>John D.</p>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner img-holder" style="--width: 768; --height: 558;">
                  <img src="images/w2.jpg" width="768" height="558" loading="lazy" alt="Insights on How to Improve Your Teaching." class="img-cover">
                </figure>

                <div class="card-content">

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <ion-icon name="calendar-outline" aria-hidden="true"></ion-icon>

                      <time class="card-meta-text" datetime="2022-05-22">June 15,2023</time>
                    </li>

                  </ul>

                  <p>
                    "CRESTA Bank has been my trusted financial partner for over a decade. Their user-friendly online banking and expert advice helped me achieve my financial goals. I couldn't be happier with their dedication to customer satisfaction."
                  </p><p>
                      <br>
                </p><p>Sarah J.</p>

                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="section footer-top">
      <div class="container">

        <div class="footer-brand">

          <a href="#" class="logo">CRESTA BANK</a>

          <p class="section-text">
            At CRESTA Bank, we are committed to delivering exceptional service, secure transactions, and innovative solutions to meet your financial goals. Our dedication to excellence has made us a trusted partner for thousands of individuals like you.
          </p>

         </div>

        <ul class="footer-list">
            
             <li>
            <p class="footer-list-title">Company</p>
          </li>

          <li>
            <a href="#about" class="footer-link">About Us</a>
          </li>
            <li>
            <a href="#features" class="footer-link">Core Services</a>
          </li>
          
        </ul>
        <ul class="footer-list">
            
             <li>
            <p class="footer-list-title">Useful Links</p>
          </li>

          <li>
            <a href="/crestabank/signup" class="footer-link">Get Started</a>
          </li>


        </ul>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">Get In Touch</p>
          </li>

          <li class="footer-item">
            <ion-icon name="mail-outline" aria-hidden="true"></ion-icon>

            <a href="mailto:admin@CRESTAbank.site" class="item-link">admin@CRESTAbank.site</a>
          </li>

        </ul>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          All Rights Reserved by <a href="#" class="copyright-link">CRESTA Bank</a>.
        </p>

      </div>
    </div>

  </footer>





  <!-- 
    - #BACK TO TOP
  -->

  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn="">
    <ion-icon name="chevron-up" aria-hidden="true"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="js/script.js" defer=""></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="js/ionicons.esm.js"></script>
  <script nomodule="" src="js/ionicons.js"></script>



</body></html>