<?php include("navbar.php"); ?>

<style>
.terms-container{
    padding:60px 80px;
    font-family:'Poppins',sans-serif;
    background:#f5f5f5;
    min-height:60vh;
}

.terms-box{
    max-width:900px;
    margin:auto;
}

.terms-title{
    font-size:32px;
    font-weight:600;
    margin-bottom:25px;
}

.terms-text{
    font-size:15px;
    color:#444;
    line-height:1.8;
    margin-bottom:15px;
}

/* MOBILE */
@media(max-width:768px){
    .terms-container{
        padding:40px 20px;
    }
}
</style>

<div class="terms-container">

    <div class="terms-box">

        <div class="terms-title">Terms & Conditions</div>

        <p class="terms-text">
            Welcome to EventHub. By accessing or using our platform, you agree to comply with the following terms and conditions.
        </p>

        <p class="terms-text">
            Users must provide accurate information during registration and are responsible for maintaining the confidentiality of their login credentials.
        </p>

        <p class="terms-text">
            EventHub is designed for managing college events. Any misuse of the platform, including fake registrations or unauthorized access, may result in account suspension.
        </p>

        <p class="terms-text">
            Event details such as time, date, and venue may change. Users are advised to stay updated through the platform.
        </p>

        <p class="terms-text">
            EventHub is not responsible for any personal loss, damage, or issues occurring during events organized through the platform.
        </p>

        <p class="terms-text">
            By continuing to use this system, you agree to abide by all college rules and regulations.
        </p>

    </div>

</div>

<?php include("footer.php"); ?>