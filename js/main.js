let navBar = document.getElementById("top-nav");
let navBarRight = document.getElementById("right");
let navLinksBar = document.getElementById("nav-links-cont");
let navCartCont = document.getElementById("nav-cart-cont")

let modalBg = document.getElementsByClassName("modal-bg")[0];

let perkCont = document.getElementById("perk-cont");
let perkList = document.getElementsByClassName("perk");
let perks = Array.from(perkList);

// modal animation handler 

modalBg.addEventListener('click', function(e) {
  if(e.target.classList.contains('modal')){
    return;
  }
  modalBg.classList.remove('appear');
});

/*make nav-bar stick*/
window.onscroll = () => {
  if(window.pageYOffset >= 0) {//height of the nav-bar plus nav-links
    navBar.classList.add('stick');
    navBarRight.style.position = "fixed";
    navCartCont.style.position = "fixed";
    navLinksBar.style.marginTop = "50px";
  } else {
    navBar.classList.remove('stick');
    navLinksBar.style.marginTop = "";
  }
}

/*scroll animation handlers*/
const observer = new IntersectionObserver(entries => {

  entries.forEach(entry => {
    if(entry.isIntersecting) {
      perks.forEach(perk => perk.classList.add('fade-up'));
      return;
    }
    //perks.forEach(perk => perk.classList.remove('fade-up'));

  });
});

observer.observe(perkCont);