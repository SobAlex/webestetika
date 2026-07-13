{{-- Блок согласия на обработку персональных данных --}}
<div class="consent-wrapper" style="margin: 15px 0;">
    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-weight: normal;">
        <input type="checkbox" class="consent-checkbox" name="consent" value="1"
            style="margin-top: 2px; flex-shrink: 0; width: 18px; height: 18px;" required>
        <span style="font-size: 14px; line-height: 1.4;">
            Я даю согласие на обработку моих персональных данных в соответствии с
            <a href="/storage/soglasie.html" target="_blank" style="text-decoration: underline; color: #007bff;">
                Согласием на обработку персональных данных
            </a>.
        </span>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обрабатываем все формы на странице
        document.querySelectorAll('form').forEach(function(form) {
            var checkbox = form.querySelector('.consent-checkbox');
            if (!checkbox) return; // если в этой форме нет чекбокса, пропускаем

            // Ищем кнопку отправки внутри формы
            var submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitButton) return; // если кнопки нет, ничего не делаем

            // Изначально кнопка неактивна
            submitButton.disabled = true;

            // При изменении состояния чекбокса переключаем активность кнопки
            checkbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
            });

            // Опционально: можно добавить обработку на случай, если пользователь кликнет по неактивной кнопке
            // Но disabled кнопка сама по себе не генерирует событий, поэтому этот блок необязателен.
        });
    });
</script>
