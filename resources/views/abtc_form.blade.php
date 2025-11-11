<!DOCTYPE html>
<html>

<head>
    <title>PhilHealth ABTC Form</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            line-height: 1.5;
        }

        h1 {
            text-align: center;
            font-size: 15pt;
            margin: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .head { 
            width: 100%;
            text-align: center; 
            padding: 1px;  
            height: 10%; 
        }

        .imgl{
            height: 80px; 
            margin-right:20%; 
            display: inline-block; 
            width: 80px;
            vertical-align: middle; 
        }
         .imgr {
            height: 80px; 
            margin-left: 20%; 
            display: inline-block; 
            width: 80px;
            vertical-align: middle; 
        }

        .head h1 {
            width: 35%;
            display: inline-block; 
            margin: 0; 
        }

        .table th,
        .table td {
            border: 1px solid #000;
            text-align: left;
            height: 25px;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* .upn {
          background-image:url("{{ asset('D:\xampp\htdocs\TraumaBackEnd\public\images\BGHMC.b1dae07e.png') }}");
          background-repeat:no-repeat;
          width:700px;
          height:342px;
          position:absolute;
       } */
    </style>
</head>

<body>
    <header class="head">
        <img class="imgl" src="{{ public_path('images/BGHMC.b1dae07e.png') }}" />
        <h1>Animal Bite Treatment Record</h1>
        <img class="imgr" src="{{ public_path('images/DOH.7c917786.png') }}" />
    </header>
    <div>
        {{--
        <pre>{{ print_r($formFields, true) }}</pre>
        <pre>{{ print_r($formData, true) }}</pre> --}}
        <table class="table">
            <tr>
                <th style="width: 17%;">DOH Certificate No.:</th>
                <td style="width: 33%;">2019-CAR-0020</td>
                <th style="width: 25%;">Transaction No:</th>
                <td style="width: 25%;">{{ $formData->hpercode }}</td>
            </tr>
            <tr>
                <th>PhilHealth Accreditation No.:</th>
                <td>513029720</td>
                <th>Date:</th>
                <td>{{ date('m-d-Y') }}</td>
            </tr>

        </table>
        <table class="table">
            <tr>
                <th style="width: 17%;">PhilHealth Identification Number (PIN):</th>
                <td style="width: 33%;"></td>
                <th style="width: 2%;"><input type="checkbox" {{ $formFields->membershipStatus === 'Member' ? 'checked' : '' }}></th>
                <td style="width: 23%;">Member</td>
                <th style="width: 2%;"><input type="checkbox" {{ $formFields->membershipStatus === 'Dependent' ? 'checked' : '' }}></th>
                <td style="width: 23%;">Dependent</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th style="width: 17%;">Patient Name:</th>
                <td style="width: 33%;">{{ $formData->PatientName }}</td>
                <th style="width: 12.5%;">Age:</th>
                <td style="width: 12.5%;">{{ $formData->Age }}</td>
                <th style="width: 12.5%;">Date of Birth:</th>
                <td style="width: 12.5%;">{{ $formData->DateOfBirth }}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td>{{ $formData->Address }}</td>
                <th>Sex:</th>
                <td>{{ $formData->patsex }}</td>
                <th>Weight:</th>
                <td>{{ $formData->weight }} kg</td>
            </tr>
            <tr>
                <th>Exposure Category</th>
                <td>{{ $formData->BiteCategory }}</td>
                <th>Date of Exposure:</th>
                <td>{{ $formData->DateOfExposure }}</td>
                <th>Treatment Started:</th>
                <td>{{ $formData->TreatmentStartedDate }}</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th style="width: 50%">1. Mode of Animal Exposure:</th>
                <th style="width: 50%">2. Body Part Affected/Exposed to Animal:</th>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td style="width: 3%">
                    <input type="checkbox" {{ $formFields->unCoveredSkin === 'Y' ? 'checked' : '' }}>
                </td>
                <th style="width: 47%">Nibbling/Licking of Uncovered Skin</th>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->exposedBody === 'Head and/or Neck' ? 'checked' : '' }}></td>
                <th style="width: 47%">Head and/or Neck</th>
            </tr>
            <tr>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->woundedSkin === 'Y' ? 'checked' : '' }}></td>
                <th style="width: 47%">Nibbling/Licking of Wounded/Broken Skin</th>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->exposedBody === 'Other Parts of the Body' ? 'checked' : '' }}></td>
                <th style="width: 47%">Other Parts of the Body</th>
            </tr>
            <tr>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->abrasion === 'Y' ? 'checked' : '' }}></td>
                <th style="width: 47%">Scratch/Abrasion</th>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->exposedBody === 'NA (if by Ingestion Mode)' ? 'checked' : '' }}></td>
                <th style="width: 47%">NA (If by Ingestion Mode)</th>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->transdermalBite === 'Y' ? 'checked' : '' }}>
                </td>
                <th style="width: 47%">Transdermal Bite</th>
                <th style="width: 20%">3. Type of Animal: </th>
                <td style="width: 30%">{{ $formData->TypeOfAnimal }}</td>
            </tr>
            <tr>
                <td><input type="checkbox" {{ $formFields->handlingIngestion === 'Y' ? 'checked' : '' }}></td>
                <th>Handling/Ingestion of Raw Infected Meat</th>
                <th>4. Past History of Animal Bite</th>
                <td>{{ $formFields->biteHistory }}</td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td style="width: 3%"><input type="checkbox" {{ $formFields->anyCombination === 'Y' ? 'checked' : '' }}>
                </td>
                <th style="width: 97%">Any Combination of the Above</th>
            </tr>
        </table>
        <table class="table">
            <tr>
                <th style="width: 50%">5. Based on item No. 4 was the PEP Primary Immunization Schedule Completed:</th>
                <th style="width: 50%">{{ $formFields->completeImmunization }}</th>
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 80%; vertical-align: top;">
                    <table class="table" style="width: 100%;">
                        <tr>
                            <th colspan="5">Post Exposure Vaccination Period</th>
                        </tr>
                        <tr>
                            <th style="width: 20%">Period</th>
                            <th style="width: 20%">Adm Route</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Given By</th>
                            <th style="width: 20%">Signature</th>
                        </tr>
                    </table>
                    <table class="table" style="width: 100%">
                        <tr>
                            <th style="width: 20%">Day 0</th>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->firstDoseRoute === 'ID' ? 'checked' : '' }}>
                            </td>
                            <td style="width: 7%">ID</td>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->firstDoseRoute === 'IM' ? 'checked' : '' }}>
                            </td>
                            <td style="width: 7%">IM</td>
                            <td style="width:20%">{{ $formData->firstDoseDate }}</td>
                            <td style="width:20%">{{ $formData->firstDoseBy }}</td>
                            <td style="width:20%"></td>
                        </tr>
                        <tr>
                            <th>Day 3</th>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->secondDoseRoute === 'ID' ? 'checked' : '' }}>
                            </td>
                            <td>ID</td>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->secondDoseRoute === 'IM' ? 'checked' : '' }}>
                            </td>
                            <td>IM</td>
                            <td>{{ $formData->secondDoseDate }}</td>
                            <td>{{ $formData->secondDoseBy }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Day 7</th>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->thirdDoseRoute === 'ID' ? 'checked' : '' }}>
                            </td>
                            <td>ID</td>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->thirdDoseRoute === 'IM' ? 'checked' : '' }}>
                            </td>
                            <td>IM</td>
                            <td>{{ $formData->thirdDoseDate }}</td>
                            <td>{{ $formData->thirdDoseBy }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Day 28</th>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->fourthDoseRoute === 'ID' ? 'checked' : '' }}>
                            </td>
                            <td>ID</td>
                            <td style="width: 3%">
                                <input type="checkbox" {{ $formData->fourthDoseRoute === 'IM' ? 'checked' : '' }}>
                            </td>
                            <td>IM</td>
                            <td>{{ $formData->fourthDoseDate }}</td>
                            <td>{{ $formData->fourthDoseBy }}</td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="table" style="width: 100%">
                        <tr>
                            <th style="width:40%">ERIG {{ $formData->erig }} ml</th>
                            <td style="width:20%"></td>
                            <td style="width:20%"></td>
                            <td style="width:20%"></td>
                        </tr>
                        <tr>
                            <th>Tetanus Toxoid</th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>ATS</th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 18%; vertical-align: top; margin-top: 3em;">
                    <table class="table" style="width: 100%;margin-top: .2em;font-size: 2em;">
                        <tr>
                            <th style="text-align: center; width: 100%; font-weight: bold; font-size: 1.4em;">
                                ICD CODE 10<br />90375
                            </th>
                        </tr>
                    </table>
                    <h5>CERTIFIED TRUE COPY:</h5>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>