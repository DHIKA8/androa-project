<?php include '../../components/header.php'; ?>

<header class="artikel-header">
    <section class="header-banner">
        <h1>No Gender, Just Style</h1>
        <p>Mengenal lebih jauh konsep fashion bebas gender.</p>
    </section>
</header>

<main class="artikel-detail-main">

    <article class="artikel-detail">

        <img src="/assets/edustyle1.jpg" alt="artikel" class="artikel-img">

        <h2 class="artikel-title">No Gender, Just Style</h2>

        <p class="artikel-text">
            Fashion unisex tidak hanya menjadi tren tetapi juga bagian penting dalam perubahan sosial.
            Banyak generasi muda kini memilih pakaian berdasarkan kenyamanan, bukan lagi berdasarkan gender.
        </p>

        <p class="artikel-text">
            Sejak awal kemunculannya pada 1960-an, fashion genderless terus berkembang. 
            Kini, gaya bebas gender menjadi simbol ekspresi diri yang lebih inklusif dan terbuka bagi semua.
        </p>

        <p class="artikel-text">
            Banyak brand global dan lokal mulai mengembangkan koleksi gender-neutral. Tidak hanya desain, 
            tetapi juga pesan kuat tentang kebebasan identitas dan inklusivitas yang lebih luas.
        </p>

        <div class="artikel-like">
            <button class="like-btn">❤️ Like</button>
            <span class="like-count">120 likes</span>
        </div>
    </article>

    <section class="komentar-section">

        <h3>Comments</h3>

        <form class="komentar-form">
            <input type="text" placeholder="Nama" required>
            <textarea placeholder="Tulis komentar kamu..." required></textarea>
            <button type="submit" class="submit-komentar">Send</button>
        </form>

        <div class="komentar-list">

            <div class="komentar-item">
                <p class="komentar-author">Salsa</p>
                <p class="komentar-text">Artikelnya keren banget! Sangat menginspirasi.</p>
            </div>

            <div class="komentar-item">
                <p class="komentar-author">Dimas</p>
                <p class="komentar-text">Setuju sama pandangannya! Fashion itu memang bebas.</p>
            </div>

        </div>

    </section>

</main>

<?php include '../../components/footer.php'; ?>
