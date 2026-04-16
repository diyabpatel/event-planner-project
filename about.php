<?php include("navbar.php"); ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f8f8f8;
}

/* ===== TOP TITLE ===== */
.about-title{
    text-align:center;
    padding:40px 20px 10px;
}

.about-title h1{
    font-size:40px;
    margin:0;
}

.about-title p{
    max-width:500px;
    margin:auto;
    font-size:14px;
    color:#666;
}

/* ===== IMAGE STRIP ===== */
.image-strip{
    display:flex;
    height:180px;
    overflow:hidden;
}

.image-strip img{
    width:25%;
    object-fit:cover;
}

/* ===== CONTENT SECTION ===== */
.content{
    max-width:1100px;
    margin:80px auto;
    display:flex;
    flex-direction:column;
    gap:80px;
}

/* ROW */
.row{
    display:flex;
    align-items:center;
    gap:50px;
}

/* IMAGE */
.row img{
    width:280px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* TEXT */
.text{
    max-width:450px;
}

.text h2{
    margin-bottom:10px;
}

.text p{
    font-size:14px;
    color:#555;
    line-height:1.7;
}

/* REVERSE */
.reverse{
    flex-direction:row-reverse;
}

/* ===== CTA SECTION ===== */
.cta{
    background:#d63384;
    color:#fff;
    padding:50px 80px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.cta h2{
    margin:0;
}

.cta button{
    background:#fff;
    color:#000;
    border:none;
    padding:10px 20px;
    cursor:pointer;
    font-weight:500;
}

</style>

<!-- ===== TITLE ===== -->
<div class="about-title">
    <h1>About.</h1>
    <p>
        We are one of the leading Event Management & Wedding Planning companies.
        EventHub simplifies event planning by offering seamless and organized
        experiences for students and organizers.
    </p>
</div>

<!-- ===== IMAGE STRIP ===== -->
<div class="image-strip">
    <img src="uploads/images/convocation/convocation1.png">
    <img src="uploads/images/annual/annualday1.png">
    <img src="uploads/images/farewell/farewell6.png">
    <img src="uploads/images/seminar/seminar5.png">
</div>

<!-- ===== CONTENT ===== -->
<div class="content">

    <!-- ROW 1 -->
    <div class="row">
        <div class="text">
            <h2>Driven By A Good Vision</h2>
            <p>
                Our vision is to simplify event management for students and colleges.
                We aim to provide a centralized platform where all events can be
                managed efficiently, reducing manual work and improving accessibility.
                EventHub ensures smooth planning, organization, and execution.
            </p>
        </div>
        <img src="uploads/images/about/about1.jpg">
    </div>

    <!-- ROW 2 -->
    <div class="row reverse">
        <div class="text">
            <h2>What We Do?</h2>
            <p>
                EventHub allows students to explore events, register online, make
                secure payments, and receive updates. Organizers can manage event
                listings, track registrations, and handle everything from one dashboard.
            </p>
        </div>
        <img src="uploads/images/about/about2.jpg">
    </div>

</div>

<?php include("footer.php"); ?>