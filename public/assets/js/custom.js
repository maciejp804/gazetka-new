const trendingProducts = {
  loop: false,
  rewind: true,
  margin: 30,
  nav: true,
  navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
  dots: true,
  responsive: {
    0: {
      items: 2,
      margin: 9,
    },
    768: {
      items: 3,
      margin: 9,
    },
    1000: {
      items: 4
    },
    1300: {
      items: 5,
      margin: 18,
    },
  }
}

const homeCategory = {
  loop: true,
  margin: 30,
  nav: true,
  navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
  dots: true,
  responsive: {
    0: {
      items: 3,
      margin: 15
    },
    479: {
      items: 3,
      margin: 15
    },
    768: {
      items: 4
    },
    979: {
      items: 6
    },
    1199: {
      items: 6
    },
    1799: {
      items: 8
    }
  }
};

const different = {
  loop: false,
  rewind: true,
  margin: 10,
  nav: true,
  navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
  dots: true,
  responsive: {
    0: {
      items: 2,
      margin: 10,
      autoWidth: true,
    },
    600: {
      items: 2
    },
    1000: {
      items: 4
    },

  }
};

const trendingProductss = {
  loop: false,
  rewind: true,
  margin: 30,
  nav: false,
  //navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
  dots: false,
  responsive: {
    0: {
      items: 1,
      margin: 15
    },
    600: {
      items: 1
    },
    1000: {
      items: 4
    },
    1400: {
      items: 5
    }
  }
};

const zsdOptions = {
  loop: false,
  rewind: true,

  margin: 30,
  nav: false,
  navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
  dots: false,
  responsive: {
    0: {
      items: 2,
      margin: 9,
    },
    768: {
      items: 3,
      margin: 20,
    },
    1200: {
      items: 5,
      margin: 20,
    },
    1800: {
      items: 5,
      margin: 20,
    }
  }
}


$(document).ready(function () {
  $('.trending-products').owlCarousel(trendingProducts);
  $('.home-category').owlCarousel(homeCategory);
  $('.different').owlCarousel(different);
  $('.trending-productss').owlCarousel(trendingProductss);
  $('.zsd').owlCarousel(zsdOptions);
});


$("button.navbar-toggler").on('click', function () {
  $(".main-menu-area").addClass("active");
  $(".mm-fullscreen-bg").addClass("active");
  $("body").addClass("hidden");
});
$(".close-box").on('click', function () {
  $(".main-menu-area").removeClass("active");
  $(".mm-fullscreen-bg").removeClass("active");
  $("body").removeClass("hidden");
});

$(".mm-fullscreen-bg").on('click', function () {
  $(".main-menu-area").removeClass("active");
  $(".mm-fullscreen-bg").removeClass("active");
  $("body").removeClass("hidden");
});

var swiper = new Swiper('.swiper-container.home-pro-tab', {
  slidesPerView: 5,
  slidesPerColumn: 3,
  spaceBetween: 30,
  observer: true,
  observeParents: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  autoplay: false,
  autoplayTimeout: 5000,
  autoplayHoverPause: true,
  pagination: {
    el: '.az'
  },
  breakpoints: {
    0: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    640: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    991: {
      slidesPerView: 3
    },
    1199: {
      slidesPerView: 4
    },
    1400: {
      slidesPerView: 5
    }
  }
});

var swiper = new Swiper('.swiper-container.home-pro-tab-2', {
  slidesPerView: 4,
  slidesPerColumn: 3,
  spaceBetween: 20,
  observer: true,
  observeParents: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  autoplay: false,
  autoplayTimeout: 5000,
  autoplayHoverPause: true,

  breakpoints: {
    0: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    640: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    991: {
      slidesPerView: 3
    },
    1199: {
      slidesPerView: 4
    },
    1400: {
      slidesPerView: 5
    }
  }
});

var swiper = new Swiper('.d_asd', {
  slidesPerView: 2,
  slidesPerColumn: 2,
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.aa'
  },
});
var swiper = new Swiper('.bsd', {
  slidesPerView: 2,
  slidesPerColumn: 2,
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.ab'
  },
});
var swiper = new Swiper('.csd', {
  slidesPerView: 2,
  slidesPerColumn: 2,
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.ac'
  },
});

var swiper = new Swiper('.dsd', {
  slidesPerView: 2,
  slidesPerColumn: 2,
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.ad'
  },
});

var swiper = new Swiper('.esd', {
  slidesPerView: 2,
  slidesPerColumn: 2,
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.ae'
  },
});
var swiper = new Swiper('.fsd', {
  slidesPerView: 1,
  slidesPerColumn: 2,
  slidesPerColumnFill: 'row',
  spaceBetween: 30,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  pagination: {
    el: '.af'
  },
});

var swiper = new Swiper('.trending-productz', {
  slidesPerView: 5,
  slidesPerColumn: 2,
  spaceBetween: 30,
  observer: true,
  observeParents: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  autoplay: false,
  autoplayTimeout: 5000,
  autoplayHoverPause: true,
  breakpoints: {
    0: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    640: {
      slidesPerView: 2,
      spaceBetween: 15
    },
    991: {
      slidesPerView: 3
    },
    1199: {
      slidesPerView: 4
    },
    1400: {
      slidesPerView: 5
    }
  }
});