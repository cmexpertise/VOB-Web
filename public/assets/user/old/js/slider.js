$('.book-slider').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots:false,
    margin: 20,
    autoplay: true,
    navigation: true,
    navText: [
      "<i class='fa-solid fa-arrow-left'></i>",
      "<i class='fa-solid fa-arrow-right'></i>",
    ],
    responsive:{
        0:{
            items:1
        },
        576:{
            items:1
        },
        600:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:4
        },
        1200:{
            items:4
        }
    }
})


// $('.stories-slider').owlCarousel({
//     loop:true,
//     margin:10,
//     nav:true,
//     dots:false,
//     margin: 20,
//     autoplay: false,
//     navigation: true,
//     navText: [
//       "<i class='fa-solid fa-arrow-left'></i>",
//       "<i class='fa-solid fa-arrow-right'></i>",
//     ],
//     responsive:{
//         0:{
//             items:1
//         },
//         576:{
//             items:1
//         },
//         600:{
//             items:2
//         },
//         768:{
//             items:3
//         },
//         1000:{
//             items:4
//         },
//         1200:{
//             items:6
//         }
//     }
// })

$(".stories-slider").owlCarousel({
    loop: true,
    margin: 10,
    items: 2,
    nav: true,
    // rtl:true,
    dots: false,
    autoplay: true,
    navigation: true,
    navText: [
      "<i class='fa-solid fa-arrow-left'></i>",
      "<i class='fa-solid fa-arrow-right'></i>",
    ],
    autoplayHoverPause: true,
    autoplaySpeed: 500,
    responsive: {
      320: {
        items: 1,
      },
      430:{
          items: 1.5,
      },
      575: {
        items: 2.5,
      },
      600: {
        items: 2.5,
      },
      615: {
        items: 2.5,
      },
      991: {
        items: 3.5,
      },
      1000: {
        items: 4.5,
      },
      1400: {
        items: 5.5,
      },
      1700: {
        items: 5.5,
      },
      2000: {
        items: 5.5,
      },
      2400: {
        items: 5.5,
      },
    },
});

$('.category-slider').owlCarousel({
  loop:true,
  margin:19,
  nav:true,
  navigation: true,
  dots:false,
  autoplay:true,
  navText: [
    "<i class='fa-solid fa-arrow-left'></i>",
    "<i class='fa-solid fa-arrow-right'></i>",
  ],
  responsive:{
    0:{
      items:2
    },
    430:{
      items:3
    },
    600:{
      items:3
    },
    991:{
      items:4
    },
    1000:{
        items:5
    },
    1200:{
      items:6
    },
    1400:{
    items:7
        }
    }
})
// $('.category-slider').owlCarousel({
//   loop:true,
//   margin:19,
//   nav:true,
//   navigation: true,
//     navText: [
//       "<i class='fa-solid fa-arrow-left'></i>",
//       "<i class='fa-solid fa-arrow-right'></i>",
//     ],
//   dots:false,
//   responsive:{
//       0:{
//           items:3
//       },
//       430:{
//           items:3
//       },
//       600:{
//           items:4
//       },
//       1000:{
//           items:5
//       },
//       1200:{
//         items:7
//       },
//       1500:{
//         items:7
//       }
//   }
// })