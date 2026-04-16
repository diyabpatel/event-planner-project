<?php include("navbar.php"); ?>

<style>
.contact-simple{
    padding:60px 80px;
    font-family:'Poppins',sans-serif;
    background:#f5f5f5;
    min-height:60vh;
}

.contact-box{
    max-width:700px;
    margin:auto;
}

.contact-title{
    font-size:32px;
    font-weight:600;
    margin-bottom:15px;
}

.contact-text{
    color:#555;
    margin-bottom:25px;
    line-height:1.7;
}

.contact-item{
    margin-bottom:15px;
    font-size:16px;
    color:#333;
}

/* MOBILE */
@media(max-width:768px){
    .contact-simple{
        padding:40px 20px;
    }
}
</style>

<div class="contact-simple">

    <div class="contact-box">

        <div class="contact-title">Contact Us</div>

        <p class="contact-text">
            Have questions or need help? Reach out to us and we will get back to you as soon as possible.
        </p>

        <div class="contact-item"><i class="fas fa-envelope"></i> eventhub@gmail.com</div>
        <div class="contact-item"><i class="fas fa-phone"></i> +91 9876543210</div>
        <div class="contact-item"><i class="fas fa-location-dot"></i> Your College Campus</div>

    </div>

</div>

<?php include("footer.php"); ?>