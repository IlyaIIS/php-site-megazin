let closePopupButtons = document.querySelectorAll('.popup__close-button');
let popupBg = document.querySelector('.popup-bg');
let popup = document.querySelector('.popup');

closePopupButtons.forEach(button => {
    button.addEventListener('click',() => {
        popupBg.classList.remove('active');
        popupBg.classList.add('closed');
        popup.classList.remove('active');
    });
});
