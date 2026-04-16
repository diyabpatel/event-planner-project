<?php include("navbar.php"); ?>

<style>
.privacy-container{
    padding:60px 80px;
    font-family:'Poppins',sans-serif;
    background:#f5f5f5;
    min-height:60vh;
}

.privacy-box{
    max-width:900px;
    margin:auto;
}

.privacy-title{
    font-size:32px;
    font-weight:600;
    margin-bottom:20px;
}

.privacy-text{
    font-size:15px;
    color:#444;
    line-height:1.8;
    margin-bottom:15px;
}

/* MOBILE */
@media(max-width:768px){
    .privacy-container{
        padding:40px 20px;
    }
}
</style>

<div class="privacy-container">

    <div class="privacy-box">

        <div class="privacy-title">Privacy Policy</div>

        <p class="privacy-text">
            At EventHub, we respect your privacy and are committed to protecting your personal information.
        </p>

        <p class="privacy-text">
            We collect basic user information such as name, email, and contact details only for event registration and communication purposes.
        </p>

        <p class="privacy-text">
            Your data will not be shared, sold, or disclosed to any third party without your consent.
        </p>

        <p class="privacy-text">
            We take appropriate security measures to protect your information from unauthorized access or misuse.
        </p>

        <p class="privacy-text">
            By using EventHub, you agree to our privacy practices and policies.
        </p>

    </div>

</div>

<?php include("footer.php"); ?>