var slideIndex = 1;


function plusSlides(n,cls) {
  showSlides(slideIndex += n,cls);
}

function currentSlide(n,cls) {
  showSlides(slideIndex = n,cls);
}

function showSlides(n,cls) {
  var i;
  var slides = document.getElementsByClassName("mySlides_"+cls);
  var dots = document.getElementsByClassName("dot_"+cls);
  if (n > slides.length) {slideIndex = 1} 
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none"; 
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block"; 
  dots[slideIndex-1].className += " active";

  // setTimeout(function(){showSlides(slideIndex++);}, 2000);
}