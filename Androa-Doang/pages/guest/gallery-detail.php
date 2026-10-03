<?php include '../../components/header.php'; ?>

<header class="gallery-header">
    <section class="header-banner">
        <h1>Photo Detail</h1>
        <p>See how the community expresses gender-free fashion.</p>
    </section>
</header>

<main class="gallery-detail-main">

    <section class="photo-section">
        <img src="/assets/gallery1.jpg" alt="Gallery Photo" class="photo-detail-img">

        <div class="photo-info">
            <h2 class="photo-title">Neutral Casual Look</h2>
            <p class="photo-desc">
                Outfit casual yang nyaman untuk siapa saja. Oversized shirt dan loose pants membuat tampilan simple namun stylish.
            </p>

            <div class="photo-meta">
                <p>Uploaded by: <strong>@user1</strong></p>
                <p>Style: <span class="category">Casual</span></p>
            </div>

            <div class="photo-likes">
                <button class="like-btn">❤️ Like</button>
                <span class="like-count">89 likes</span>
            </div>
        </div>
    </section>

    <section class="comment-section">

        <h3>Comments</h3>

        <!-- Jika user belum login -->
        <p class="login-reminder">
            Want to comment? <a href="login.php">Log in</a> first.
        </p>

        <!-- Daftar Komentar -->
        <div class="comment-list">

            <div class="comment-item">
                <p class="comment-author">Naya</p>
                <p class="comment-text">Love this look! Simple but very clean.</p>
            </div>

            <div class="comment-item">
                <p class="comment-author">Reza</p>
                <p class="comment-text">Ini inspirasiku buat outfit besok 😆🔥</p>
            </div>

        </div>

    </section>

</main>

<?php include '../../components/footer.php'; ?>
