jQuery(document).ready(function(){
    createDeletes();
    document.getElementById('post').onsubmit=function(){
        removeDeletes();
        document.getElementById('content').value=document.getElementById('sortable').innerHTML.trim();
    };
});
/*window.onload=function(){
    
};*/
function createDeletes(){
  var lis=document.getElementById('sortable').childNodes;
  for(var i=0;i<lis.length;i++){
    if(lis[i].nodeName!='LI')
      continue;
    if(lis[i].childElementCount>1){
      continue;
      // lis[i].removeChild(lis[i].childNodes[1]);
    }

    let div=document.createElement('div');
    div.style.color="red";
    div.style.cursor="pointer";
    div.style.textAlign="center";
    div.style.padding="5px";
    div.style.backgroundColor="black";
    div.innerHTML="Delete";
    let temp=lis[i];
    div.onclick=function(){
      temp.parentNode.removeChild(temp);
    };
    lis[i].appendChild(div);
  }
}
function removeDeletes(){
  var lis=document.getElementById('sortable').childNodes;
  for(var i=0;i<lis.length;i++){
    if(lis[i].nodeName!='LI')
      continue;
    if(lis[i].childElementCount>1){
      // alert(lis[i].childNodes[1]);
      lis[i].removeChild(lis[i].childNodes[1]);
    }
  }
}
function openMedia(){
    var image_frame;
    if(image_frame){
        image_frame.open();
    }
    image_frame = wp.media({
        title: 'Select Media',
        multiple : true,
        library : {
            type : 'image',
        }
    });

    image_frame.on('close',function() {
        var selection =  image_frame.state().get('selection').toJSON();
        for(i=0;i<selection.length;i++){
            // let data=JSON.parse(selection[i]);
            let li=document.createElement('li');
            li.className="";
            li.style="";

            let img=document.createElement('img');
            img.src=selection[i].url;
            img.alt="Image not found!";
            img.className="alignnone size-medium wp-image-32";
            li.appendChild(img);

            document.getElementById('sortable').appendChild(li);
        }
        createDeletes();
    });

    image_frame.open();
}
jQuery( function() {
    jQuery( "#sortable" ).sortable({
        dropOnEmpty:true
    });
    jQuery( "#sortable" ).disableSelection();
} );