<div class="comments">
  <form class="comments__form form" action="#" method="post">
    <div class="comments__my-avatar">
      <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
    </div>
    <div class="form__input-section form__input-section--error">
      <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий"></textarea>
      <label class="visually-hidden">Ваш комментарий</label>
      <button class="form__error-button button" type="button">!</button>
      <div class="form__error-text">
        <h3 class="form__error-title">Ошибка валидации</h3>
        <p class="form__error-desc">Это поле обязательно к заполнению</p>
      </div>
    </div>
    <button class="comments__submit button button--green" type="submit">Отправить</button>
  </form>
  <div class="comments__list-wrapper">
    <ul class="comments__list">
      <?php foreach ($post_comments as $comment): ?>
      <?php $comment_date = date_create($comment['dt_add']) ?>
      <li class="comments__item user">
        <div class="comments__avatar">
          <a class="user__avatar-link" href="#">
            <img class="comments__picture" src="img/<?= hsc($comment['user_picture']) ?>" alt="Аватар пользователя">
          </a>
        </div>
        <div class="comments__info">
          <div class="comments__name-wrapper">
            <a class="comments__user-name" href="#">
              <span><?= hsc($comment['username']) ?></span>
            </a>
            <time 
                class="comments__time" 
                datetime="<?= hsc($comment['dt_add']) ?>" 
                title="<?= hsc(date_format($comment_date, 'd.m.Y H:i')) ?>"
            ><?= hsc(get_date_diff_from_now($comment_date)) ?></time>
          </div>
          <p class="comments__text"><?= hsc($comment['content']) ?></p>
        </div>
      </li>
      <?php endforeach ?>
    </ul>
  </div>
</div>