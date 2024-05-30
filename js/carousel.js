let slideCont = document.querySelector(".slide-cont");
let slideShow = document.getElementById("slide-show");
let slideList = document.getElementsByClassName("slide");
let slides = Array.from(slideList);

let leftBtn = document.getElementById("move-right");
let rightBtn = document.getElementById("move-left");
let firstSlide, lastSlide;

leftBtn.addEventListener("click", moveLeft);
rightBtn.addEventListener("click", moveRight);
slideCont.addEventListener('animationend', handleAnimEnd);

//state for touch scroll
let transformObj = window.getComputedStyle(slideCont).transform;
let prevTransX = +transformObj.split(' ')[4].slice(0, -1);//unfortunately no pretty way to get curr transform val
let isDragging = false, 
    prevPos = 0,
    currPos = 0,
    animID = 0,
    posDiff = 0;

//button infinite functionality
function getNumOfSlidesToReset() {
  if(window.innerWidth > 1480) { return 5; }
  if(window.innerWidth > 850) { return 4; }
  if(window.innerWidth > 660)  { return 3; }
  //if(window.innerWidth > 455)  { return 2; }
  //return 1;
}

function moveLeft() {
  let numOfSlides = getNumOfSlidesToReset();//number of slides to reset depending on screen size

  slideCont.classList.add('moveLeft');
  firstSlide = document.getElementsByClassName("slide");
  firstSlide = [].slice.call(firstSlide);
  firstSlide = firstSlide.slice(0, numOfSlides);
}

function moveRight() {
  let numOfSlides = getNumOfSlidesToReset();
  
  slideCont.classList.add('moveRight');
  lastSlide = document.getElementsByClassName("slide");
  lastSlide = [].slice.call(lastSlide);
  lastSlide = lastSlide.slice( 25 - numOfSlides, 25);
}

function handleAnimEnd() {
  
  if(slideCont.classList.contains("moveLeft")) {
    resetSlidesLeft();
  } 
  
  if(slideCont.classList.contains("moveRight")) {
    resetSlidesRight();
  }
}

function resetSlidesLeft() {
  slideCont.classList.remove('moveLeft');
  
  firstSlide.forEach((ele) => {
    let slide = ele;
    ele.remove();
    slideCont.append(slide);
  });
  
}

function resetSlidesRight() {
  slideCont.classList.remove('moveRight');
  
  lastSlide.reverse().forEach((ele) => {
    let slide = ele;
    ele.remove();
    slideCont.prepend(slide);
  });
}

//end of button infinite functionality
//carousel touch screen functionality
if(window.innerWidth < 660) {
    slides.forEach((ele) => {
    
    //ele.addEventListener('mousedown', touchStart);
    //ele.addEventListener('mousemove', touchMove);
    //ele.addEventListener('mouseup', touchEnd);

    ele.addEventListener('touchstart', touchStart);
    ele.addEventListener('touchmove', touchMove);
    ele.addEventListener('touchend', touchEnd);

    })
}

function touchStart(ev) {

  prevPos = (ev.type == 'mousedown') ? ev.clientX : ev.touches[0].clientX;
  isDragging = true;
  currPos = prevTransX;
}

function touchMove(ev) {
  ev.preventDefault();
  let moveX = (ev.type == 'mousemove') ? ev.clientX : ev.touches[0].clientX;
  if(isDragging) posDiff = moveX - prevPos;
  currPos = prevTransX + posDiff;
  //console.log(currPos);
  animID = requestAnimationFrame(moveSlides);
}

function moveSlides() {
    slideCont.style.transform = 'translateX(' + (currPos) + 'px)';
    animID = requestAnimationFrame(moveSlides);
}

function touchEnd(ev) {
    isDragging = false;
    cancelAnimationFrame(animID);
    
    if(currPos > 0) currPos = 0
    if(slideShow.getBoundingClientRect().right > slideCont.getBoundingClientRect().right) {
        currPos = -((slideCont.clientWidth) - slideShow.clientWidth );
    }
    prevTransX = currPos;
  }

//product image cover state handlers

function moveUp(btn) {
  console.log(btn.parentNode.parentNode);
  btn.parentNode.parentNode.classList.add('show');
  btn.nextSibling.nextSibling.classList.add('move-up');
}

function handleMoveUpEnd(e) {
  this.classList.add('keep-up');
  this.classList.remove('move-up');
}