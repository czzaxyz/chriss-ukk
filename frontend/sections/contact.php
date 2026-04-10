<?php
include "../partials/header.php";
include "../partials/navbar.php";
include "../../config/koneksi.php";

// Proses simpan pesan ke database
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($connect, $_POST['phone']) : '';
    $subject = mysqli_real_escape_string($connect, $_POST['subject']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    
    $query = "INSERT INTO contacts (name, email, phone, subject, message, status, created_at) 
              VALUES ('$name', '$email', '$phone', '$subject', '$message', 'unread', NOW())";
}
?>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <h1>Get In Touch</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Contact</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOP -->

<!-- START ADDRESS -->
<section class="address_area section-padding">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-4 col-sm-4 col-xs-12 no-padding wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s" data-wow-offset="0">
                <div class="single_address sa_one">
                    <i class="ti-map"></i>
                    <h4>Our Location</h4>
                    <p>Jl. Motor Raya No. 123, Jakarta Selatan<br />Indonesia</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-xs-12 no-padding wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                <div class="single_address sa_two">
                    <i class="ti-mobile"></i>
                    <h4>Telephone</h4>
                    <p>(+62) 812 3456 7890</p>
                    <p>(+62) 811 2345 6789</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-xs-12 no-padding wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0">
                <div class="single_address sa_three">
                    <i class="ti-email"></i>
                    <h4>Send email</h4>
                    <p>info@rentalmotor.com</p>
                    <p>cs@rentalmotor.com</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END ADDRESS -->

<!-- CONTACT -->
<div id="contact" class="contact_area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                <div class="contact">
                    
                    <form class="form" name="enq" method="post" action="" onsubmit="return validation();">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone">No. Telepon (Opsional)</label>
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Contoh: 08123456789">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="subject">Subjek <span class="required">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="message">Pesan <span class="required">*</span></label>
                                <textarea rows="6" name="message" id="message" class="form-control" required></textarea>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" value="Send message" name="submit" id="submitButton" class="btn_one" title="Submit Your Message!">
                                    <i class="fa fa-paper-plane"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5211773276665!2d106.828717!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f391c209f6f9%3A0x8c2c2b2b2b2b2b2b!2sJakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional CSS for contact form */
.required {
    color: #e53e3e;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert .close {
    float: right;
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1;
    color: inherit;
    opacity: 0.5;
    cursor: pointer;
    background: none;
    border: none;
}

.alert .close:hover {
    opacity: 0.8;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #4a5568;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 10px 15px;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    outline: none;
}

.btn_one {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn_one:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102,126,234,0.3);
}

.single_address {
    padding: 30px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.single_address i {
    font-size: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 15px;
    display: inline-block;
}

.single_address h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.single_address p {
    color: #718096;
    margin-bottom: 5px;
}

.map iframe {
    width: 100%;
    height: 400px;
    border-radius: 16px;
}

@media (max-width: 768px) {
    .single_address {
        margin-bottom: 15px;
    }
    .map iframe {
        height: 300px;
        margin-top: 30px;
    }
}
</style>

<script>
function validation() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var subject = document.getElementById('subject').value;
    var message = document.getElementById('message').value;
    
    if (name == '') {
        alert('Nama tidak boleh kosong!');
        return false;
    }
    if (email == '') {
        alert('Email tidak boleh kosong!');
        return false;
    }
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Email tidak valid!');
        return false;
    }
    if (subject == '') {
        alert('Subjek tidak boleh kosong!');
        return false;
    }
    if (message == '') {
        alert('Pesan tidak boleh kosong!');
        return false;
    }
    return true;
}
</script>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>