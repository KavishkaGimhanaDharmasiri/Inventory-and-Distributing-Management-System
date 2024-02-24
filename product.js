const imgs = document.querySelectorAll('.img-select a');

const imgBtns =[... imgs];

let imgid=1;

imgBtns.forEach((imgItem)=>{

ingItem.addEventListener('click', (event) =>{

event.preventDefault(); 
imgid = imgitem.dataset.id;

slideImage();
});
});

function slideImage(){

const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

document.querySelector('.img-showcase').style.transform = "translateX($((imgId1) displayWidth}px)";
}

