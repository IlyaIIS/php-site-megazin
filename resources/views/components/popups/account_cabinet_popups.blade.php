<article class="popup popup_change-avatar d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить аватар</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3" enctype="multipart/form-data">
        @csrf
        <div class="mb-3" data-pg-collapsed>
            <label for="inputImage" class="form-label">Добавить изображение</label>
            <input class="form-control" type="file" name="inputImage">
        </div>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-name popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить имя</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="text" class="form-control" name="inputFirstName" placeholder="Имя"></label>
        <label><input type="text" class="form-control" name="inputLastName" placeholder="Фамилия"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-nickname popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить псевданим</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="text" class="form-control" name="inputNickname" placeholder="Псевданим"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-email popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить почту</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="email" class="form-control" name="inputNickname" placeholder="Почта"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-password popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить пароль</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="password" class="form-control" name="inputPassword" placeholder="Пароль"></label>
        <label><input type="password" class="form-control" name="inputPasswordRepeat" placeholder="Повторить пароль"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-birthday popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить дату рождения</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="date" class="form-control" name="inputBirthday"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-address popup_account d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить адрес</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        <label><input type="text" class="form-control" name="inputCity" placeholder="Город"></label>
        <label><input type="text" class="form-control" name="inputStreet" placeholder="Улица"></label>
        <label><input type="text" class="form-control" name="inputHouse" placeholder="Дом"></label>
        <label><input type="number" class="form-control" name="inputApartment" placeholder="Квартира"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<script>
    $(function () {
        $(".popup_account form").submit(function () {
            let data = $(this).serialize();
            data += '&fromUrl=' + window.location.pathname;
            data += '&_token=' + '{{csrf_token()}}';
            //data += '&formClass=' + $(this).parent()[0].classList[1];
            $.ajax({
                type: "POST",
                url: '/modify-user',
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

<script>
    $(function () {
        $(".popup_change-avatar form").submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: '/modify-user-avatar',
                data: formData,
                success: function (data) {
                    location.reload();
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });
    });
</script>
