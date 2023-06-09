<?php
    print '
        <ul class="nav_bg_color">
            <li><a href="index.php?menu=1">Početna stranica</a></li>
            <li><a href="index.php?menu=2">Vijesti</a></li>
            <li><a href="index.php?menu=3">Kontakt</a></li>
            <li><a href="index.php?menu=4">O nama</a></li>
            <li><a href="index.php?menu=10">OMDB API</a></li>
            <li><a href="index.php?menu=11">Football (JSON)</a></li>
            <li><a href="https://www.hnb.hr/hnb-api" target="_blank">API HNB (XML)</a></li>
            <li><a href="https://github.com/public-apis/public-apis" target="_blank">Free APIs</a></li>';

            if (!isset($_SESSION['user']['valid']) || $_SESSION['user']['valid'] == 'false') {
                print '
                <li><a href="index.php?menu=5">Registriraj se</a></li>
                <li><a href="index.php?menu=6">Prijavi se</a></li>';
            }
            else if ($_SESSION['user']['valid'] == 'true') {
                if($_SESSION['user']['role'] == 'admin'){
                    print '<li><a href="index.php?menu=7">Administrator</a></li>';
                }
                else if($_SESSION['user']['role'] == 'editor'){
                    print '<li><a href="index.php?menu=8">Urednik</a></li>';
                }
                else if($_SESSION['user']['role'] == 'user'){
                    print '<li><a href="index.php?menu=9">Korisnik</a></li>';
                }
                print '
                <li><a href="signout.php">Odjavi se</a></li>
                <li><a>Dobrodošao/la ' . $_SESSION['user']['username'] . '</a></li>';
            }
        print '</ul>';
?>