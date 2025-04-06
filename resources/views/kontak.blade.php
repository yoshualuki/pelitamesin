@extends('customer.template')

@section('styles')
<style>
    .contact-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .contact-info {
        margin-bottom: 30px;
    }
    
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .contact-icon {
        font-size: 24px;
        color: #0d6efd;
        margin-right: 15px;
        min-width: 30px;
        text-align: center;
    }
    
    .map-responsive {
        position: relative;
        overflow: hidden;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        border-radius: 8px;
        margin-top: 30px;
        border: 1px solid #ddd;
    }
    
    .map-responsive iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    
    .whatsapp-btn {
        background-color: #25D366;
        color: white;
        font-weight: 500;
        padding: 12px 20px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    
    .whatsapp-btn:hover {
        background-color: #128C7E;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-3">Hubungi Kami</h1>
                <p class="lead text-muted">Kunjungi toko kami atau hubungi via WhatsApp</p>
            </div>
            
            <div class="contact-container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <h3 class="mb-4">Informasi Kontak</h3>
                            
                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5>Alamat</h5>
                                    <p>Jl. Bubutan No.101A, Bubutan, Kec. Bubutan, Kota Surabaya, Jawa Timur 60171</p>
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h5>Telepon</h5>
                                    <p><a href="tel:+623199250845">(031) 99250845</a></p>
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h5>Jam Operasional</h5>
                                    <p>Senin-Jumat: 08.00 - 17.00<br>Sabtu: 08.00 - 15.00</p>
                                </div>
                            </div>
                            
                            <a href="https://wa.me/0895631776702" class="whatsapp-btn" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i> Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h3 class="mb-4">Lokasi Toko</h3>
                        <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.370621220291!2d112.73597993065376!3d-7.248814478979626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f940640fa801%3A0x20f22d1808d5bf36!2sToko%20PELITA%20MESIN%20JAHIT!5e0!3m2!1sid!2sid!4v1743682575414!5m2!1sid!2sid" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection