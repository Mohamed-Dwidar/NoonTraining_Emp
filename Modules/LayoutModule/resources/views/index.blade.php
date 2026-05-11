@extends('layoutmodule::layouts.home_layout')

@section('content')
    <div class="content-wrapper">
        <!-- Left Section -->
        <div class="left-section">
            <div class="logo-container">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="Logo" class="logo">
                </a>
            </div>

            <h1 class="main-title">منصة الاختبارات المحوسبة</h1>
            <p class="sub-title">معهد نون للتدريب</p>

            <p class="description">
                منصة متخصصة ومنفصلة للاختبارات الإلكترونية المصممة وفق أحدث المعايير العالمية.
                نوفر لك تجربة تقييم شاملة ودقيقة تتماشى مع متطلبات السوق المهنية.
            </p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="exam-card">

                <div class="exam-icon">📋</div>

                <h2 class="card-title">ابدأ اختبارك الآن</h2>
                <p class="card-subtitle">منصة الاختبارات المحوسبة</p>

                <div class="features-grid">
                    <div class="feature-item">✅ تقييم فوري</div>
                    <div class="feature-item">🎯 دقة عالية</div>
                    <div class="feature-item">🔒 أمان تام</div>
                    <div class="feature-item">📊 تقارير مفصلة</div>
                    <div class="feature-item">⚡ سرعة في النتائج</div>
                    <div class="feature-item">🏆 معايير مهنية</div>
                </div>

                <a href="{{ route('student.login') }}" class="start-btn">
                    دخول إلى منصة الاختبارات
                </a>

                <div class="info-note">
                    <strong>💡 تنويه مهم:</strong><br>
                    للتسجيل في الاختبار الرجاء انشاء حساب في موقع المعهد الرئيسي
                    وللاستفارات التواصل على ارقامنا الخاصة بنا
                </div>

                <!-- Contacts Section -->
                <div class="info-note">
                    <!-- Social Media Icons -->
                    <div class="social-icons">
                        <a href="https://goo.gl/maps/J2LYuy8QMH5ECxLM8" target="_blank" class="social-icon"
                            title="Google Maps">
                            <i class="fas fa-map-marker-alt"></i>
                        </a>
                        <a href="https://twitter.com/noontrainingest" target="_blank" class="social-icon" title="X">
                            <i class="fab fa-x"></i>
                        </a>
                        <a href="https://www.instagram.com/noontrainingest" target="_blank" class="social-icon"
                            title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UCfE9-Yw08i4v3C7sej-Dptw" target="_blank"
                            class="social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://www.snapchat.com/add/noontrainingest" target="_blank" class="social-icon"
                            title="Snapchat">
                            <i class="fab fa-snapchat"></i>
                        </a>
                        <a href="https://t.me/noontrainingest" target="_blank" class="social-icon" title="Twitter">
                            <i class="fab fa-telegram"></i>
                        </a>
                    </div>

                    <!-- Contact Numbers -->
                    <div class="contact-numbers">
                        <div>
                            <strong>
                                <i class="fas fa-mobile-alt"></i>
                                <span>0568794480</span>
                            </strong>
                            <strong>
                                <i class="fab fa-whatsapp"></i>
                                <span>
                                    <a href="https://wa.me/+966133444480" target="_blank"
                                        style="color: inherit; text-decoration: none;">
                                        0133444480
                                    </a>
                                </span>
                            </strong>
                        </div>
                        <div>
                            <strong>
                                <i class="fab fa-whatsapp"></i>
                                <span>
                                    <a href="https://wa.me/+966565834480" target="_blank"
                                        style="color: inherit; text-decoration: none;">
                                        0565834480
                                    </a>
                                </span>
                            </strong>

                           <strong>
                                <i class="fab fa-whatsapp"></i>
                                <span>
                                    <a href="https://wa.me/+966546294480" target="_blank"
                                        style="color: inherit; text-decoration: none;">
                                        0546294480
                                    </a>
                                </span>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
