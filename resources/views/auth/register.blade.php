<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <link rel="shortcut icon" href="https://uin-alauddin.ac.id/themes//images/favicon.png" />
    <title>{{ config('app.name') }} | Register</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Si Peka (Sistem Informasi Pengetesan Kemiskinan) Adalah Wadah Perencanaan, Monitoring Pelakasanaan dan Evaluasi Kinerja Program Pengetesan Kemiskinan Terintegrasi Dengan Konsep Kolaborasi Program dan Anggaran." />
    <meta name="keywords" content="Kemiskinan, perencanaan,monitoring, evaluasi" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body data-kt-name="metronic" id="kt_body"
    class="app-blank app-blank bgi-size-cover bgi-position-bottom bgi-no-repeat">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root background-overlay" id="kt_app_root"
        data-vide-bg="https://uin-alauddin.ac.id/themes/video/uin5/uin5" style="height: 620px;">
        <div
            style="position: absolute; z-index: -1; inset: 0px; overflow: hidden; background-size: cover; background-color: transparent; background-repeat: no-repeat; background-position: 50% 50%; background-image: none;">
            <video autoplay="" loop="" muted=""
                style="margin: auto; position: absolute; z-index: -1; top: 50%; left: 50%; transform: translate(-50%, -50%); visibility: visible; opacity: 1; width: 100%; height: auto;">
                <source src="https://uin-alauddin.ac.id/themes/video/uin5/uin5.mp4" type="video/mp4">
                <source src="https://uin-alauddin.ac.id/themes/video/uin5/uin5.webm" type="video/webm">
                <source src="https://uin-alauddin.ac.id/themes/video/uin5/uin5.ogv" type="video/ogg">
            </video>
        </div>
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex justify-content-center flex-column flex-lg-row flex-column-fluid z-index-1">
            <!--begin::Aside-->
            <div class="d-flex flex-center w-lg-50 p-10">
                <!--begin::Wrapper-->
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                    <!--begin::Body-->
                    <div class="shadow-lg"
                        style="background: rgb(110 101 101 / 50%); padding: 30px; border-radius: 10px;">
                        <!--begin::Form-->
                        <form class="form w-100" method="POST" action="{{ route('login.register-proses') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-5">
                                <!--begin::Title-->
                                <h1 class="text-white fw-bolder mb-3">Register</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-white fw-semibold fs-6">Masukkan data anda</div>
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-5">
                                <!--begin::Email-->
                                <input type="text" placeholder="Nama" name="name" autocomplete="off"
                                    class="form-control" />
                                @error('name')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-5">
                                <!--begin::Email-->
                                <input type="text" placeholder="Username" name="username" autocomplete="off"
                                    class="form-control" />
                                @error('username')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-5">
                                <!--begin::Email-->
                                <input type="text" placeholder="Nip/Nim" name="nip_nim" autocomplete="off"
                                    class="form-control" />
                                @error('nip_nim')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-5">
                                <!--begin::Email-->
                                <input type="email" placeholder="Email" name="email" autocomplete="off"
                                    class="form-control" />
                                @error('email')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-5" data-kt-password-meter="true">
                                <!--begin::Wrapper-->
                                <div class="mb-1">
                                    <!--begin::Input wrapper-->
                                    <div class="position-relative mb-3">
                                        <div class="position-relative">
                                            <input placeholder="Password" type="password" name="password"
                                                autocomplete="off" class="form-control" />
                                            <span
                                                class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                data-kt-password-meter-control="visibility">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--end::Input wrapper-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-5">
                                <!--begin::Email-->
                                <select name="role" class="form-select" data-control="select2"
                                    data-placeholder="Pilih Role">
                                    <option value="">Pilih Role</option>
                                    <option value="dosen">Dosen</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                </select>
                                @error('role')
                                    <small class="error text-danger">{{ $message }}</small>
                                @enderror
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Submit button-->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Sign Up</span>
                                    <!--end::Indicator label-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Authentication - Sign-in-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script><!--begin::Custom Javascript(used by this page)-->
    <!--end::Custom Javascript-->
    {{-- @if ($message == 'Register data success')
        <script>
            window.location.href = '/login/ogin-akun';
        </script>
    @endif --}}
</body>
<!--end::Body-->

</html>
