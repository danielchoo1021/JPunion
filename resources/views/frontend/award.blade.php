@extends('layouts.app')
    <link href="{{ asset('new_layout/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
@section('content')
<div class="page-content">
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/12-1.jpg') }}); height: 500px; position: relative;">
        <div style="position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  text-align: center;
                  width: 100%;
                  z-index: 2;">
            <div class="container" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-bg" align="center">
                            <h1 style="color: white;">Awards and Certifications</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="position: absolute;
                  top:0px;
                  bottom: 0px;
                  left: 0px;
                  right: 0px;
                  background-color: rgba(0,0,0,0.3);
                  z-index: 1;">
      </div>
    </div>

    <div class="holder" align="center">
        <div class="container">
            <h1>
                Asia Honesty Product Awards 2021
            </h1>

            <p>
                Proud to announce that KIREINA have been awarded the "Asia Honesty Product Award" under the careful and rigorous review by "Asia Honesty Awards Review and Adjudication Board".
            </p>

            <p>
                This award is created to recognized those excellent products that win in good faith and is recognized by consumers and offical parties for its quality.
            </p>

            <img src="{{ asset('images/DONE-1.1-01.jpg') }}" width="100%">
            <img src="{{ asset('images/23.png') }}" width="100%">
            <p>
                International Association for the Advancement of Quantity (IAQ) (PPM-021-10-10042017) is one of the World's leading international certification organization. Through various types of certification,
                promotes the quality improvement for various industries around the world, and established more comprehensive industry standards for various industries, is one of the World's most recognized 
                certification organization.
            </p>

            <p>
                IAQ Certification are the backbone of our society, ensuring the safety and quality of products and services, facilitating international trade and improving the environment in which we live in.
            </p>
            <p>
                Kireina Triple peptide and ladyG have passed performance tests and quality assurance tests in order to meet the necessary local, national or international quality standards relevant to a particular 
                market or product set.
            </p>
            <br>
            <img src="{{ asset('images/DONE-02.jpg') }}" width="100%">
            <img src="{{ asset('images/SGS_Logo.png') }}" style="width: 10rem;">
            <h1 style="font-weight: 700 !important;">The World's Leading Testing,<br>
            Inspection and Certification Company</h1>
            <img src="{{ asset('images/DONE-03.jpg') }}" width="100%">
        </div>
    </div>
</div>
@endsection