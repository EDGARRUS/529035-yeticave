<main>
    <nav class="nav">
    <ul class="nav__list container">
        <?php foreach($menu as $value): ?>
        <li class="nav__item">
            <a href="index.php?cat_id=<?= $value['id']; ?>"><?php echo $value['name']; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2>404 Страница не найдена</h2>
    <p><?php echo $error;?></p>
</section>
</main>