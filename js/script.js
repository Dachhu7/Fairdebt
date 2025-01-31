document.addEventListener("DOMContentLoaded", function () {
  // htmlcss progress circular bar 
  let htmlProgress = document.querySelector(".html-css"),
    htmlValue = document.querySelector(".html-progress");

  if (htmlProgress && htmlValue) {
    let htmlStartValue = 0,
      htmlEndValue = 90,
      htmlspeed = 30;

    let progresshtml = setInterval(() => {
      htmlStartValue++;
      htmlValue.textContent = `${htmlStartValue}%`;
      htmlProgress.style.background = `conic-gradient(#fca61f ${
        htmlStartValue * 3.6
      }deg, #ededed 0deg)`;

      if (htmlStartValue == htmlEndValue) {
        clearInterval(progresshtml);
      }
    }, htmlspeed);
  }

  // JavaScript progress circular bar 
  let javascriptProgress = document.querySelector(".javascript"),
    javascriptValue = document.querySelector(".javascript-progress");

  if (javascriptProgress && javascriptValue) {
    let javascriptStartValue = 0,
      javascriptEndValue = 75,
      jsspeed = 30;

    let progressjs = setInterval(() => {
      javascriptStartValue++;
      javascriptValue.textContent = `${javascriptStartValue}%`;
      javascriptProgress.style.background = `conic-gradient(#7d2ae8 ${
        javascriptStartValue * 3.6
      }deg, #ededed 0deg)`;

      if (javascriptStartValue == javascriptEndValue) {
        clearInterval(progressjs);
      }
    }, jsspeed);
  }

  // PHP progress circular bar 
  let phpProgress = document.querySelector(".php"),
    phpValue = document.querySelector(".php-progress");

  if (phpProgress && phpValue) {
    let phpStartValue = 0,
      phpEndValue = 80,
      phpspeed = 30;

    let progressphp = setInterval(() => {
      phpStartValue++;
      phpValue.textContent = `${phpStartValue}%`;
      phpProgress.style.background = `conic-gradient(#20c997 ${
        phpStartValue * 3.6
      }deg, #ededed 0deg)`;

      if (phpStartValue == phpEndValue) {
        clearInterval(progressphp);
      }
    }, phpspeed);
  }

  // ReactJS progress circular bar 
  let reactProgress = document.querySelector(".reactjs"),
    reactValue = document.querySelector(".reactjs-progress");

  if (reactProgress && reactValue) {
    let reactStartValue = 0,
      reactEndValue = 30,
      rjsspeed = 30;

    let progressreact = setInterval(() => {
      reactStartValue++;
      reactValue.textContent = `${reactStartValue}%`;
      reactProgress.style.background = `conic-gradient(#3f396d ${
        reactStartValue * 3.6
      }deg, #ededed 0deg)`;

      if (reactStartValue == reactEndValue) {
        clearInterval(progressreact);
      }
    }, rjsspeed);
  }

  // Carousel functionality
  const carousel = document.querySelector("#homeCarousel");
  if (carousel) {
    let slideIndex = 0;

    function autoSlide() {
      const slides = document.querySelectorAll(".carousel-item");
      if (slides.length > 0) {
        slides[slideIndex].classList.remove("active");
        slideIndex = (slideIndex + 1) % slides.length;
        slides[slideIndex].classList.add("active");
      }
    }

    setInterval(autoSlide, 5000); // Auto-slide every 5 seconds
  }

  // jQuery filter
  if (typeof $ !== "undefined") {
    $(document).ready(function () {
      $(".filter-item").click(function () {
        const value = $(this).attr("data-filter");
        if (value == "all") {
          $(".post").show("1000");
        } else {
          $(".post")
            .not("." + value)
            .hide("1000");
          $(".post")
            .filter("." + value)
            .show("1000");
        }
      });
    });
  }

  // Sticky Navbar
  let navbar = document.getElementById("navbar-top");
  if (navbar) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 50) {
        navbar.classList.add("fixed-top");
        let navbarHeight = document.querySelector(".navbar").offsetHeight;
        document.body.style.paddingTop = navbarHeight + "px";
      } else {
        navbar.classList.remove("fixed-top");
        document.body.style.paddingTop = "0";
      }
    });
  }

  // Back to Top Button
  let mybutton = document.getElementById("btn-back-to-top");
  if (mybutton) {
    window.onscroll = function () {
      scrollFunction();
    };

    function scrollFunction() {
      if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
      ) {
        mybutton.style.display = "block";
      } else {
        mybutton.style.display = "none";
      }
    }

    mybutton.addEventListener("click", function () {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    });
  }
});

