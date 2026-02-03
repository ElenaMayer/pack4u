(function() {
    'use strict';

    // DOM элементы
    const offerFormGroup = document.querySelector('.field-offer');
    const offerCheckbox = document.getElementById('offer-checkbox');
    const offerError = document.getElementById('offer-error');
    const registerButton = document.getElementById('register-button');
    const registrationForm = document.getElementById('registration-form');

    if (!offerCheckbox || !registerButton) return;

    // Состояние валидации
    let isOfferValid = false;
    let hasUserInteracted = false;

    // Функция для показа ошибки
    function showError(message) {
        offerError.textContent = message;
        offerError.classList.add('is-visible');
        offerFormGroup.classList.add('has-error');
        offerCheckbox.setAttribute('aria-invalid', 'true');
        offerCheckbox.setAttribute('aria-describedby', 'offer-error');
    }

    // Функция для скрытия ошибки
    function hideError() {
        offerError.classList.remove('is-visible');
        offerFormGroup.classList.remove('has-error');
        offerFormGroup.classList.remove('shake-animation');
        offerCheckbox.setAttribute('aria-invalid', 'false');

        // Убираем текст ошибки через короткую задержку после анимации
        setTimeout(() => {
            if (!offerError.classList.contains('is-visible')) {
                offerError.textContent = '';
            }
        }, 300);
    }

    // Функция для показа успешного состояния
    function showSuccess() {
        offerFormGroup.classList.add('is-valid');
        offerFormGroup.classList.remove('has-error');
        hideError();
    }

    // Функция для сброса успешного состояния (при снятии галочки)
    function resetSuccess() {
        offerFormGroup.classList.remove('is-valid');
    }

    // Основная функция валидации
    function validateOffer(skipErrorShow = false) {
        const isValid = offerCheckbox.checked;

        if (isValid) {
            // Успешная валидация
            showSuccess();
            isOfferValid = true;
        } else {
            // Не валидно
            resetSuccess();
            isOfferValid = false;

            // Показываем ошибку только если пользователь уже взаимодействовал
            // или если это явный запрос на показ ошибки (при отправке формы)
            if (hasUserInteracted && !skipErrorShow) {
                showError('Необходимо принять условия оферты');
            }
        }

        updateSubmitButton();
        return isValid;
    }

    // Обновление состояния кнопки
    function updateSubmitButton() {
        if (isOfferValid) {
            registerButton.disabled = false;
            registerButton.removeAttribute('aria-disabled');
            registerButton.classList.remove('btn-disabled');
        } else {
            registerButton.disabled = true;
            registerButton.setAttribute('aria-disabled', 'true');
            registerButton.classList.add('btn-disabled');
        }
    }

    // Обработчик изменения чекбокса
    offerCheckbox.addEventListener('change', function() {
        hasUserInteracted = true;
        validateOffer();
    });

    // Обработчик клика на тексте оферты
    const offerLink = document.querySelector('.offer-link');
    if (offerLink) {
        offerLink.addEventListener('click', function(e) {
            // Можно добавить логику отслеживания
            console.log('Пользователь открыл оферту');
        });
    }

    // Валидация при отправке формы
    registrationForm.addEventListener('submit', function(e) {
        // Всегда показываем ошибку при попытке отправки
        if (!validateOffer(true)) {
            e.preventDefault();
            e.stopPropagation();

            // Показываем ошибку с анимацией
            showError('Необходимо принять условия оферты');
            offerFormGroup.classList.add('shake-animation');

            // Фокусируемся на чекбоксе для доступности
            offerCheckbox.focus();

            // Убираем анимацию тряски после завершения
            setTimeout(() => {
                offerFormGroup.classList.remove('shake-animation');
            }, 500);

            // Вибрация для мобильных устройств
            if (navigator.vibrate) {
                navigator.vibrate(200);
            }

            return false;
        }

        // Если валидация прошла успешно
        return true;
    });

    // Валидация при потере фокуса
    offerCheckbox.addEventListener('blur', function() {
        if (hasUserInteracted) {
            validateOffer();
        }
    });

    // Клавиатурная навигация
    offerCheckbox.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.checked = !this.checked;

            // Триггерим событие change вручную
            const event = new Event('change');
            this.dispatchEvent(event);
        }
    });

    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        // Изначально валидируем без показа ошибки
        validateOffer(true);

        // Делаем фокус на чекбоксе более заметным
        offerCheckbox.addEventListener('focus', function() {
            offerFormGroup.style.boxShadow = '0 0 0 0.2rem rgba(0,123,255,.25)';
        });

        offerCheckbox.addEventListener('blur', function() {
            offerFormGroup.style.boxShadow = 'none';
        });
    });

    // Для отладки (можно удалить в production)
    console.log('Offer validation initialized');
})();