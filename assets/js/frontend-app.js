var slideIndex = 1;


function plusSlides(n,cls) {
  showSlides(slideIndex += n,cls);
}

function currentSlide(n,cls) {
  showSlides(slideIndex = n,cls);
}

/*function initSlides(){
  var containers=document.getElementsByClassName("slideshow-container");
  if(!containers || !containers[0])
    return;
  var slides=[];
  var dots=[];
  for(var i=0;i<containers.length;i++){
    if(containers[i].childNodes){
      var children=containers[i].childNodes;
      for(var j=0;j<children.length;j++){
        if(children[j].className=='mySlides')
          slides.push(children[j]);
        if(children[j].nodeName=='a' && children[j].className=='prev'){
          children[j].onclick=function(){
            plusSlides(-1,j);
          };
          slides.push(children[j]);
        }
      }
    }
  }
}*/

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