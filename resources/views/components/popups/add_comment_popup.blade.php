<article class="popup popup_comment d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Оставить комменатрий</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        @csrf
        <label><textarea class="form-control" name="inputContent" placeholder="Текст комментария" required></textarea></label>
        <label><input type="number" class="form-control" name="inputElevation" placeholder="Оценка от 1 до 5"
                      required maxlength="1"></label>
        <div class="mb-3" data-pg-collapsed>
            <label for="formFile" class="form-label">Добавить изображение</label>
            <input class="form-control" type="file" name="inputImage">
        </div>
        <button type="submit" class="btn btn-warning popup__close-button">Оставить комментарий</button>
    </form>
</article>

<script>
    $(function () {
        $(".popup_comment form").submit(function (ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            formData.append('productId', {{$product->id}});
            $.ajax({
                type: "post",
                url: '/add-comment',
                data: formData,
                success: function (data) {
                    alert("Комментарий успешно добавлен!");
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

