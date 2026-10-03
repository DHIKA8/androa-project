<?php 

include '../../db_connect.php'; 
include '../../components/header.php'; 
?>

<link rel="stylesheet" href="guest.css">

<body class="home-body-guest">
    
<header class="landing-header">
    <section class="header-banner">
        <h1>WELCOME TO ANDROA</h1>
        <p>Explore gender-free fashion, stories, and inspirations.</p>
    </section>
</header>

<main>

    <section class="infografis-container">
        
        <div class="infografis-box">
            <h3>Sejarah Fashion Unisex</h3>
            <p>
                Fashion unisex berkembang sebagai respons terhadap perubahan sosial dan perlawanan terhadap batasan gender dalam berpakaian. Akar gerakannya muncul sejak akhir abad ke-19 ketika perempuan mulai mengenakan celana longgar demi mobilitas.
            </p>
            
            <div id="text-sejarah" class="full-text">
                <p>
                    Gerakan ini semakin kuat pada 1920-an lewat gaya Flapper yang mengaburkan batas maskulin–feminin. Setelah Perang Dunia II, perempuan yang bekerja memakai pakaian kerja yang sama dengan pria. Momentum besar hadir pada 1960–1970an lewat figur seperti David Bowie.
                </p>
                <p>
                    Memasuki era 1980–2000an, pergerakan ini semakin menguat lewat desain longgar ala desainer Jepang serta streetwear. Saat ini, koleksi berlabel “gender-neutral” umum ditemui dengan siluet oversized dan warna netral. Fashion unisex bukan sekadar tren, tetapi evolusi budaya yang menghapus batasan gender tradisional.
                </p>
            </div>
            
            <button class="toggle-btn" onclick="toggleText('text-sejarah', this)">Baca Selengkapnya</button>
        </div>

        <div class="infografis-box">
            <h3>Tokoh Perempuan Perintis</h3>
            <p>
                <b>1. Coco Chanel</b><br>
                Tokoh paling berpengaruh dalam menciptakan dasar fashion unisex modern. Ia memperkenalkan pakaian perempuan yang terinspirasi dari busana pria, seperti celana longgar dan blazer.
            </p>

            <div id="text-tokoh" class="full-text">
                <p>
                    Gerakannya menjadi tonggak awal netralisasi gender dalam busana, mempopulerkan ide bahwa perempuan boleh memakai pakaian praktis.
                </p>
                <p>
                    <b>2. Marlene Dietrich</b><br>
                    Aktris Hollywood ikon androgini 1930-an. Berani memakai setelan tuksedo dan topi fedora di saat perempuan “wajib” tampil feminin.
                </p>
                <p>
                    <b>3. Katharine Hepburn</b><br>
                    Dikenal sering memakai celana panjang dan kemeja longgar bahkan saat dilarang studio film. Ia menjadi simbol perempuan yang menolak aturan gender kaku.
                </p>
            </div>

            <button class="toggle-btn" onclick="toggleText('text-tokoh', this)">Baca Selengkapnya</button>
        </div>

    </section>

    <section class="highlight-container">
        <h2 class="section-title">Highlight Artikel</h2>

        <div class="highlight-content">
            <?php
            $sql_artikel = "SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT 3";
            $result_artikel = $conn->query($sql_artikel);

            if ($result_artikel->num_rows > 0) {
                while ($row = $result_artikel->fetch_assoc()) {
                    $judul = htmlspecialchars($row['title']);
                    $excerpt = htmlspecialchars(substr(strip_tags($row['content']), 0, 80)) . '...';
                    $tanggal = date('d M Y', strtotime($row['created_at']));
                    $gambar = !empty($row['image_path']) ? "../../Asset/" . $row['image_path'] : "../../Asset/default.jpg";
            ?>
                    <div class="artikel-card">
                        <img src="<?= $gambar; ?>" alt="<?= $judul; ?>">
                        
                        <div class="artikel-info">
                            <h4><?= $judul; ?></h4>
                            <p><?= $excerpt; ?></p>

                            <div class="artikel-footer">
                                <span class="date-tag"><?= $tanggal; ?></span>
                                <a href="detail-artikel.php?id=<?= $row['article_id']; ?>" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
            <?php 
                }
            } else { 
            ?>
                <div class="empty-state" style="grid-column: 1/-1; text-align:center; padding:20px;">
                    <p>Belum ada artikel highlight saat ini.</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="gallery-preview">
        <h2 class="section-title">Gallery Komunitas</h2>

        <div class="gallery-grid">
            <?php
            $sql_gallery = "SELECT gp.*, u.username 
                            FROM gallery_posts gp 
                            JOIN users u ON gp.user_id = u.user_id 
                            ORDER BY gp.created_at DESC LIMIT 6";
            $result_gallery = $conn->query($sql_gallery);

            if ($result_gallery->num_rows > 0) {
                while ($row = $result_gallery->fetch_assoc()) {
                    $username = htmlspecialchars($row['username']);
                    $caption  = htmlspecialchars($row['caption']);
                    $category = htmlspecialchars($row['style_category']);
                    $gambar_galeri = "../../Asset/" . $row['image_path'];
            ?>
                    <div class="gallery-item">
                        <a href="detail-gallery.php?post_id=<?= $row['post_id']; ?>">
                            <img src="<?= $gambar_galeri; ?>" alt="Post by <?= $username; ?>">
                        </a>
                        
                        <div class="gallery-info">
                            <p>
                                <b>@<?= $username; ?></b> <?= $caption; ?>
                            </p>
                            
                            <?php if(!empty($category)) : ?>
                                <span class="style-tag"><?= $category; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php 
                }
            } else { 
            ?>
                <div style="grid-column: 1 / -1; text-align: center; color: #666; padding: 20px;">
                    <p>Belum ada postingan galeri. Jadilah yang pertama posting!</p>
                </div>
            <?php } ?>
        </div>

        <a href="gallery.php" class="more-btn">Lihat Semua</a>
    </section>

</main>
</body>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>

<script>
function toggleText(id, btn) {
    var content = document.getElementById(id);
    
    var currentStyle = window.getComputedStyle(content).display;

    if (currentStyle === "none") {
        content.style.display = "block";
        btn.innerHTML = "Tutup";
    } else {
        content.style.display = "none";
        btn.innerHTML = "Baca Selengkapnya";
    }
}
</script>
