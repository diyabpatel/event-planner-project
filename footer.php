<?php
// footer.php
?>

<!-- FONT + ICONS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ===== FOOTER SAFE CSS ===== */
.ev-footer{
    background:#000;
    color:#ccc;
    padding:60px 40px;
    font-family:'Poppins',sans-serif;
}

.ev-footer-container{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:50px;
}

.ev-footer-section{
    min-width:180px;
}

.ev-footer-logo{
    font-size:22px;
    font-weight:600;
    color:#fff;
    margin-bottom:15px;
}

.ev-footer-section h3{
    color:#fff;
    font-size:16px;
    margin-bottom:20px;
}

.ev-footer-section ul{
    list-style:none;
    padding:0;
}

.ev-footer-section ul li{
    margin-bottom:10px;
}

.ev-footer-section ul li a{
    text-decoration:none;
    color:#aaa;
    font-size:14px;
    transition:0.3s;
}

.ev-footer-section ul li a:hover{
    color:#fff;
    padding-left:5px;
}

/* SOCIAL ICONS */
.ev-social-icons{
    margin-top:15px;
}

.ev-social-icons i{
    font-size:18px;
    margin-right:12px;
    color:#aaa;
    cursor:default;
    transition:0.3s;
}

.ev-social-icons i:hover{
    color:#fff;
    transform:scale(1.2);
}

/* LEFT TEXT */
.ev-vertical-text{
    position:fixed;
    left:0;
    bottom:100px;
    writing-mode:vertical-rl;
    transform:rotate(180deg);
    font-size:13px;
    color:#888;
}

/* RIGHT SOCIAL BAR */
.ev-social-bar{
    position:fixed;
    right:0;
    top:50%;
    transform:translateY(-50%);
}

.ev-social-bar div{
    padding:12px;
    margin-bottom:5px;
    text-align:center;
    font-size:18px;
    color:#fff;
}

/* COLORS */
.ev-linkedin{ background:#0077b5; }
.ev-snapchat{ background:#FFFC00; color:#000; }
.ev-instagram{
    background: radial-gradient(circle at 30% 30%, #feda75, #fa7e1e, #d62976, #962fbf, #4f5bd5);
}

/* MOBILE */
@media(max-width:768px){
    .ev-footer-container{
        flex-direction:column;
    }
}
</style>

<footer class="ev-footer">

    <div class="ev-footer-container">

        <!-- LOGO -->
        <div class="ev-footer-section">
            <div class="ev-footer-logo">EventHub</div>

            <div class="ev-social-icons">
                <i class="fab fa-instagram"></i>
                <i class="fab fa-snapchat"></i>
                <i class="fab fa-whatsapp"></i>
                <i class="fab fa-facebook"></i>
            </div>
        </div>

        <!-- EVENTS -->
        <div class="ev-footer-section">
            <h3>Events</h3>
            <ul>
                <li><a href="/event-planner-project/events/annualday.php">Annual Day</a></li>
                <li><a href="/event-planner-project/events/convocation.php">Convocation</a></li>
                <li><a href="/event-planner-project/events/farewell.php">Farewell</a></li>
                <li><a href="/event-planner-project/events/fresher.php">Fresher Party</a></li>
                <li><a href="/event-planner-project/events/seminar.php">Seminar</a></li>
                <li><a href="/event-planner-project/events/sportsday.php">Sports Day</a></li>
            </ul>
        </div>

        <!-- PAGES -->
        <div class="ev-footer-section">
            <h3>Pages</h3>
            <ul>
                <li><a href="/event-planner-project/index.php">Home</a></li>
                <li><a href="/event-planner-project/gallery.php">Gallery</a></li>
                <li><a href="/event-planner-project/login.php">Login</a></li>
                <li><a href="/event-planner-project/register.php">Register</a></li>
            </ul>
        </div>

        <!-- COMPANY -->
        <div class="ev-footer-section">
            <h3>Company</h3>
            <ul>
                <li><a href="terms.php">Terms & Conditions</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </div>

    </div>

</footer>
