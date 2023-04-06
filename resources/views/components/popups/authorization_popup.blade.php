@vite('resources/js/popupCloseEvents.js')
@vite('resources/js/popupOpenEvents.js')
<article class="popup popup_authorization d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Авторизация</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        @csrf
        <label><input type="email" class="form-control" name="inputEmail" placeholder="Почта" required></label>
        <label><input type="password" class="form-control" name="inputPassword" placeholder="Пароль" required></label>
        <button type="submit" class="btn btn-warning">Войти</button>
    </form>
    <button class="btn btn-warning mt-3 open-popup-button open-popup-button_registration">Зарегистрироваться</button>
</article>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
    $(function () {
        $(".popup_authorization form").submit(function () {
            let data = $(this).serialize();
            data += '&fromUrl=' + window.location.pathname;
            $.ajax({
                type: "POST",
                url: '/authorization',
                data: data,
                success: function (data) {
                    location.reload();
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                }
            });
            return false;
        });
    });
</script>
