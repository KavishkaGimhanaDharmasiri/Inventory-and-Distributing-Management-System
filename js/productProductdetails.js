const imgs = document.querySelectorAll('.img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = parseInt(imgItem.dataset.id); // Parse imgId to an integer
        slideImage();
    });
});

function slideImage() {
    const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;
    document.querySelector('.img-showcase').style.transform = `translateX(${-((imgId - 1) * displayWidth)}px)`;
}


// document.addEventListener("DOMContentLoaded", function() {
//     // Get all the image items
//     const imgItems = document.querySelectorAll(".img-item");

//     // Get the image showcase container
//     const imgShowcase = document.querySelector(".img-showcase");

//     // Add click event listener to each image item
//     imgItems.forEach(function(item) {
//         item.addEventListener("click", function(event) {
//             event.preventDefault();
            
//             // Get the clicked image source
//             const imgSrc = this.querySelector("img").src;
            
//             // Change the showcased image source
//             imgShowcase.querySelector("img").src = imgSrc;
//         });
//     });
// });