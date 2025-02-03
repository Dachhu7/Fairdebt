document.addEventListener("DOMContentLoaded", function () {
  // Carousel functionality
  const carousel = document.querySelector("#homeCarousel");
  if (carousel) {
    let slideIndex = 0;
    const slides = document.querySelectorAll(".carousel-item");

    // Ensure the first slide is active
    if (slides.length > 0 && !document.querySelector(".carousel-item.active")) {
      slides[0].classList.add("active");
    }

    function autoSlide() {
      if (slides.length > 0) {
        slides[slideIndex].classList.remove("active");
        slideIndex = (slideIndex + 1) % slides.length;
        slides[slideIndex].classList.add("active");
      }
    }

    setInterval(autoSlide, 5000);
  }

  // jQuery filter functionality
  if (typeof $ !== "undefined") {
    $(document).ready(function () {
      $(".filter-item").click(function () {
        const value = $(this).attr("data-filter");
        if (value === "all") {
          $(".post").show(1000);
        } else {
          $(".post").not("." + value).hide(1000);
          $(".post").filter("." + value).show(1000);
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
        document.body.classList.add("fixed-navbar");
      } else {
        navbar.classList.remove("fixed-top");
        document.body.classList.remove("fixed-navbar");
      }
    });
  }

  // Back to Top Button
  let backToTopButton = document.getElementById("btn-back-to-top");
  if (backToTopButton) {
    window.addEventListener("scroll", function () {
      backToTopButton.style.display = window.scrollY > 20 ? "block" : "none";
    });

    backToTopButton.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
});
