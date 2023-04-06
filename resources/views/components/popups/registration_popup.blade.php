<article class="popup popup_registration d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Регистрация</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        @csrf
        <label><input type="text" class="form-control" name="inputNickname" placeholder="Псевданим" required></label>
        <label><input type="email" class="form-control" name="inputEmail" placeholder="Почта" required></label>
        <label><input type="password" class="form-control" name="inputPassword" placeholder="Пароль" required></label>
        <label><input type="password" class="form-control" name="inputPasswordRepeat" placeholder="Повторить пароль" required></label>
        <label><input type="text" class="form-control" name="inputFirstName" placeholder="Имя" required></label>
        <label><input type="text" class="form-control" name="inputLastName" placeholder="Фамилия" required></label>
        <label><input type="date" class="form-control" name="inputBirthday" placeholder="Дата рождения" required></label>
        <label><input type="text" class="form-control" name="inputCity" placeholder="Город" required></label>
        <label><input type="text" class="form-control" name="inputStreet" placeholder="Улица" required></label>
        <label><input type="text" class="form-control" name="inputHouse" placeholder="Дом" required></label>
        <label><input type="number" class="form-control" name="inputApartment" placeholder="Квартира"></label>
        <button type="submit" class="btn btn-warning">Зарегистрироваться</button>
    </form>
    <button type="submit" class="btn btn-warning mt-3 open-popup-button open-popup-button_authorization">Уже есть аккаунт</button>
</article>
<script>
    $(function () {
        $(".popup_registration form").submit(function () {
            let data = $(this).serialize();
            //data['_token'] = '{{csrf_token()}}';
            $.ajax({
                type: "POST",
                url: '/registration',
                data: data,
                success: function (data) {
                    $('.open-popup-button_authorization').trigger('click');
                    alert(data['message']);
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                }
            });
            return false;
        });
    });
</script>
