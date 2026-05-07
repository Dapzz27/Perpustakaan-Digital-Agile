<?php
session_start();
session_destroy();
?>
<script type="text/javascript">
    alert('📚 Terima kasih telah menggunakan layanan perpustakaan kami. Jangan lupa kembali 👋');
    location.href = "../sign/login_akun.php";
</script>