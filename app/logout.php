<?php
session_start();
session_destroy();
?>
<script type="text/javascript">
    alert('Selamat anda berhasil logout sampai jumpa admin.');
    location.href = "../sign/login_akun.php";
</script>