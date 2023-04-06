let openPopupButtons = document.querySelectorAll('.open-popup-button');
let popupBg = document.querySelector('.popup-bg');

openPopupButtons.forEach(button => {
    button.addEventListener('click',() => {
        let buttonPopupPairs = {
            'open-popup-button_authorization': '.popup_authorization',
            'open-popup-button_registration': '.popup_registration',
            'open-popup-button_comment': '.popup_comment',
            'open-popup-button_change-avatar': '.popup_change-avatar',
            'open-popup-button_change-name': '.popup_change-name',
            'open-popup-button_change-nickname': '.popup_change-nickname',
            'open-popup-button_change-email': '.popup_change-email',
            'open-popup-button_change-password': '.popup_change-password',
            'open-popup-button_change-birthday': '.popup_change-birthday',
            'open-popup-button_change-address': '.popup_change-address',
            'open-popup-button_change-store-avatar': '.popup_change-store-avatar',
            'open-popup-button_change-store-name': '.popup_change-store-name',
            'open-popup-button_add-product': '.popup_add-product',
        }

        let targetPopup
        for (const [key, value] of Object.entries(buttonPopupPairs))
            if (button.classList.contains(key))
                targetPopup = document.querySelector(value);

        popupBg.classList.remove('closed');
        popupBg.classList.add('active');
        for(const popup of popupBg.children) {
            popup.classList.remove('active');
        }
        targetPopup.classList.add('active');
    });
});

