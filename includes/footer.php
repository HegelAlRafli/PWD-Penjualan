<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>Sistem Pendataan Barang</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Styling Container Footer */
        .footer {
            background-color: #2c3e50; /* Warna background gelap modern */
            color: #ecf0f1;            /* Warna teks terang */
            padding: 25px 0;
            margin-top: auto;          /* Mendorong footer ke bawah */
            width: 100%;
        }

        /* Flexbox untuk menengahkan konten */
        .footer-content {
            display: flex;
            flex-direction: column;    /* Susun elemen dari atas ke bawah */
            align-items: center;       /* Rata tengah horizontal */
            justify-content: center;   /* Rata tengah vertikal */
            text-align: center;        /* Pastikan teks rata tengah */
            gap: 8px;                  /* Jarak antar baris */
        }


        /* Styling Copyright & Versi */
        .footer-text {
            font-size: 0.95rem;
            font-weight: 500;
        }

        .footer-version {
            font-size: 0.8rem;
            color: #95a5a6; /* Warna agak redup untuk versi */
        }

        Animasi Ikon Hati
        .text-danger { color: #e74c3c; }
        .fa-heart { animation: beat 1s infinite alternate; color: red; }
        
        @keyframes beat {
            to { transform: scale(2.1); }
        }
    </style>
</head>
<body>

    </div> 
        
    <footer class="footer">
        <div class="footer-content">
            
    
            <div class="footer-text">
                &copy; <?php echo date('Y'); ?> <strong>Sistem Pendataan Barang</strong>. All rights reserved.
            </div>
            <div>                Version 1.0.0 | Made</i> in kelompok 1 with love <i>   
</div>
<div class="heart-icon">
    <i class="fa-solid fa-heart"></i>
</div>



          

        </div>
    </footer>
        
    <!-- <script>
    Update waktu secara real-time
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        // Cek apakah elemen ada sebelum diisi agar tidak error
        const dateEl = document.getElementById('current-date');
        if(dateEl) {
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    
    // Update setiap detik
    setInterval(updateDateTime, 1000);
    updateDateTime();
    
    // Script notifikasi (tetap sama)
    setTimeout(function() {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            notification.style.display = 'none';
        });
    }, 5000);
    </script> -->

    </div> 
</body>
</html>