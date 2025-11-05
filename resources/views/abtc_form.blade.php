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

        .line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 150px;
        }

        /* ✅ Header Layout Fix */
        .header-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            height: 50px;
        }

        /* Center title properly */
        .header-title {
            flex: 1;
            text-align: center;
            margin-bottom: 3%;
        }




        */
    </style>
</head>

<body>
    <header>
        {{-- <img src="{{ asset('images\BGHMC.b1dae07e.png') }}"> --}}
        {{-- <img src="{{ public_path('images\BGHMC.b1dae07e.png') }}" alt="Image" /> --}}
        <h1 class="header-title">Animal Bite Treatment Record</h1>
        {{-- <img src="{{ asset('images\BGHMC.b1dae07e.png') }}"> --}}
        {{-- <img src="{{ public_path('images\BGHMC.b1dae07e.png') }}" alt="Image" /> --}}

    </header>

    <div class="section">
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
            <tr>
                <th>PhilHealth Identification Number (PIN):</th>
                <td></td>
                <th>[ ] Member</th>
                <th>[ ] Dependent</th>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th style="width: 17%;">Patient Name:</th>
                <td style="width: 33%;">{{ $formData->PatientName }}</td>
                <th style="width: 10%;">Age:</th>
                <td style="width: 15%;">{{ $formData->Age }}</td>
                <th style="width: 10%;">Date of Birth:</th>
                <td style="width: 15%;">{{ $formData->DateOfBirth }}</td>
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
                <th">Exposure Category</th>
                    <td>{{ $formData->BiteCategory }}</td>
                    <th">Date of Exposure:</th>
                        <td>{{ $formData->DateOfExposure }}</td>
                        <th">Treatment Started:</th>
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
                <td style="width: 3%"></td>
                <th style="width: 47%">Nibbling/Licking of Uncovered Skin</th>
                <td style="width: 3%"></td>
                <th style="width: 47%">Head and/or Neck</th>
            </tr>
            <tr>
                <td style="width: 3%"></td>
                <th style="width: 47%">Nibbling/Licking of Wounded/Broken Skin</th>
                <td style="width: 3%"></td>
                <th style="width: 47%">Other Parts of the Body</th>
            </tr>
            <tr>
                <td style="width: 3%"></td>
                <th style="width: 47%">Scatch/Abrasion</th>
                <td style="width: 3%"></td>
                <th style="width: 47%">NA (If by Ingestion Mode)</th>
            </tr>

        </table>
        <table class="table">
            <tr>
                <td style="width: 3%"></td>
                <th style="width: 47%">Transderma Bite</th>
                <th style="width: 20%">3. Type of Animal: </th>
                <td style="width: 30%">{{ $formData->TypeOfAnimal }}</td>
            </tr>
            <tr>
                <td></td>
                <th">handling/Ingestion of raw Infected Meat</th>
                    <th>4. Past History of Animal Bite</th>
                    <td>{{ $formData->PastHistory }}</td>
            </tr>
        </table>
        <table class="table">
            <tr>

                <td style="width: 3%"></td>
                <th style="width: 97%">Any Combination of the Above</th>
            </tr>
        </table>
        <table class="table">
            <tr>
                <th style="width: 50%"> 5. Based on item No. 4 was the PEP Primary Immunization Schedule Completed:
                </th>
                <th style="width: 50%"></th>
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
                            @if ($formData->firstDoseRoute === 'ID')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else 

                                <th style="width: 3%"></th>
                            @endif
                            <td style="width: 7%">ID</td>
                            @if ($formData->firstDoseRoute === 'IM')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <td style="width: 7%">IM</td>
                            <td style="width:20%">{{ $formData->firstDoseDate }}</td>
                            <td style="width:20%">{{ $formData->firstDoseBy }}</td>
                            <td style="width:20%"></td>
                        </tr>
                        <tr>
                            <th>Day 3</th>
                            @if ($formData->secondDoseRoute === 'ID')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>ID</th>
                            @if ($formData->secondDoseRoute === 'IM')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>IM</th>
                            <td>{{ $formData->secondDoseDate }}</td>
                            <td>{{ $formData->secondDoseBy }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Day 7</th>
                            @if ($formData->thirdDoseRoute === 'ID')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>ID</th>
                            @if ($formData->thirdDoseRoute === 'IM')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>IM</th>
                            <td>{{ $formData->thirdDoseDate }}</td>
                            <td>{{ $formData->thirdDoseBy }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Day 28</th>
                            @if ($formData->fourthDoseRoute === 'ID')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>ID</th>
                            @if ($formData->fourthDoseRoute === 'IM')
                                <input type="checkbox" checked style="width: 3%;margin-left:3pt; font-size: large;">
                            @else

                                <th style="width: 3%"></th>
                            @endif
                            <th>IM</th>
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
                </td>
                <td style="width: 18%; vertical-align: top;   margin-top: 3em;"><br/><br/><br/>
                    <table class="table" style="width: 100%;margin-top: .2em;font-size: 2em;">
                        <tr>
                            <th style="text-align: center;text-align: center; width: 100%; font-weight: bold; font-size: 1.4em;">
                                ICD CODE 10<br />90375
                            </th>
                        </tr>
                    </table>
                    <br/><br/><br/><br/><br/><br/><br/><br/>
                    <h5>CERTIFIED TRUE COPY: </h5>
                </td>
            </tr>
        </table>
    </div>





</body>

</html>