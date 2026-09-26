@php
    $type = $type ?? 'customer';
@endphp

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $type === 'admin'
            ? 'New Delivery Partner Request'
            : 'Thank You for Your Delivery Partner Request' }}
    </title>

</head>


<body style="
    margin:0;
    padding:0;
    background-color:#f8f9fa;
    font-family:Arial, Helvetica, sans-serif;
    color:#333333;
    line-height:1.6;
">


<table width="100%"
       cellpadding="0"
       cellspacing="0"
       border="0"
       style="
           background-color:#f8f9fa;
           padding:30px 10px;
       ">

    <tr>

        <td align="center">


            <!-- MAIN CONTAINER -->

            <table width="600"
                   cellpadding="0"
                   cellspacing="0"
                   border="0"
                   style="
                       width:100%;
                       max-width:600px;
                       background-color:#ffffff;
                       border-radius:8px;
                       border:1px solid #e9ecef;
                       padding:30px;
                   ">


                <!-- LOGO -->

                <tr>

                    <td align="center"
                        style="
                            padding-bottom:20px;
                            border-bottom:1px solid #eeeeee;
                        ">

                        <img
                            src="https://timetofurnish.com/public/uploads/all/226xIlqxsV0wxtSTFRdXMVlTf2dmfBNkCOlYUnqO.jpg"
                            alt="Time To Furnish"
                            width="180"
                            style="
                                max-width:180px;
                                height:auto;
                                display:block;
                            "
                        >

                    </td>

                </tr>


                @if($type === 'admin')

                    <!-- =========================
                         ADMIN EMAIL
                    ========================== -->

                    <tr>

                        <td style="padding-top:25px;">

                            <h2 style="
                                margin:0 0 15px 0;
                                color:#671919;
                                font-size:20px;
                            ">

                                New Delivery Partner Request

                            </h2>


                            <p style="
                                margin:0 0 20px 0;
                                color:#555555;
                                font-size:14px;
                            ">

                                A new delivery partner request has been
                                submitted through your website.

                            </p>


                            <!-- PARTNER DETAILS -->

                            <table width="100%"
                                   cellpadding="10"
                                   cellspacing="0"
                                   border="0"
                                   style="
                                       border:1px solid #e0e0e0;
                                       border-radius:6px;
                                       font-size:14px;
                                       margin-bottom:20px;
                                   ">


                                <!-- COMPANY NAME -->

                                <tr style="background-color:#f9f9f9;">

                                    <td width="35%"
                                        style="
                                            font-weight:bold;
                                            color:#555;
                                            border-bottom:1px solid #eee;
                                        ">

                                        Company Name:

                                    </td>


                                    <td style="
                                        border-bottom:1px solid #eee;
                                        color:#222;
                                    ">

                                        {{ $data['company_name'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- EMAIL -->

                                <tr>

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        border-bottom:1px solid #eee;
                                    ">

                                        Email:

                                    </td>


                                    <td style="
                                        border-bottom:1px solid #eee;
                                    ">

                                        <a href="mailto:{{ $data['email'] ?? '' }}"
                                           style="
                                               color:#671919;
                                               text-decoration:none;
                                               font-weight:bold;
                                           ">

                                            {{ $data['email'] ?? '' }}

                                        </a>

                                    </td>

                                </tr>


                                <!-- CONTACT NUMBER -->

                                <tr style="background-color:#f9f9f9;">

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        border-bottom:1px solid #eee;
                                    ">

                                        Contact Number:

                                    </td>


                                    <td style="
                                        border-bottom:1px solid #eee;
                                    ">

                                        <a href="tel:{{ $data['contact_number'] ?? '' }}"
                                           style="
                                               color:#671919;
                                               text-decoration:none;
                                               font-weight:bold;
                                           ">

                                            {{ $data['contact_number'] ?? '' }}

                                        </a>

                                    </td>

                                </tr>


                                <!-- AREA COVERAGE -->

                                <tr>

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        border-bottom:1px solid #eee;
                                        vertical-align:top;
                                    ">

                                        Area Coverage:

                                    </td>


                                    <td style="
                                        border-bottom:1px solid #eee;
                                        color:#222;
                                    ">

                                        {{ $data['area_coverage'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- SERVICES PROVIDED -->

                                <tr style="background-color:#f9f9f9;">

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        vertical-align:top;
                                    ">

                                        Services Provided:

                                    </td>


                                    <td style="
                                        color:#222;
                                        white-space:pre-wrap;
                                    ">

                                        {{ $data['services_provided'] ?? '' }}

                                    </td>

                                </tr>


                            </table>


                            <p style="
                                margin:0;
                                font-size:13px;
                                color:#666;
                            ">

                                Please review the delivery partner details
                                and contact the company using the information
                                provided above.

                            </p>


                        </td>

                    </tr>


                @else


                    <!-- =========================
                         CUSTOMER EMAIL
                    ========================== -->

                    <tr>

                        <td style="padding-top:25px;">

                            <h2 style="
                                margin:0 0 15px 0;
                                color:#671919;
                                font-size:20px;
                            ">

                                Thank You for Your Request

                            </h2>


                            <p style="
                                margin:0 0 15px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Hi

                                <strong>
                                    {{ $data['company_name'] ?? 'Partner' }}
                                </strong>,

                            </p>


                            <p style="
                                margin:0 0 20px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Thank you for your interest in becoming a
                                delivery partner with
                                <strong>Time To Furnish</strong>.

                                We have received your request successfully.

                            </p>


                            <p style="
                                margin:0 0 20px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Our team will review the information provided
                                and contact you if further details are required.

                            </p>


                            <!-- REQUEST SUMMARY -->

                            <table width="100%"
                                   cellpadding="10"
                                   cellspacing="0"
                                   border="0"
                                   style="
                                       border:1px solid #e0e0e0;
                                       border-radius:6px;
                                       font-size:14px;
                                   ">


                                <!-- COMPANY -->

                                <tr style="background-color:#f9f9f9;">

                                    <td width="35%"
                                        style="
                                            font-weight:bold;
                                            color:#555;
                                        ">

                                        Company:

                                    </td>


                                    <td style="color:#222;">

                                        {{ $data['company_name'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- EMAIL -->

                                <tr>

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                    ">

                                        Email:

                                    </td>


                                    <td style="color:#222;">

                                        {{ $data['email'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- CONTACT -->

                                <tr style="background-color:#f9f9f9;">

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                    ">

                                        Contact Number:

                                    </td>


                                    <td style="color:#222;">

                                        {{ $data['contact_number'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- AREA -->

                                <tr>

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        vertical-align:top;
                                    ">

                                        Area Coverage:

                                    </td>


                                    <td style="color:#222;">

                                        {{ $data['area_coverage'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- SERVICES -->

                                <tr style="background-color:#f9f9f9;">

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        vertical-align:top;
                                    ">

                                        Services:

                                    </td>


                                    <td style="color:#222;">

                                        {{ $data['services_provided'] ?? '' }}

                                    </td>

                                </tr>


                            </table>


                        </td>

                    </tr>


                @endif


                <!-- FOOTER -->

                <tr>

                    <td align="center"
                        style="
                            padding-top:20px;
                            border-top:1px solid #eeeeee;
                            font-size:12px;
                            color:#888888;
                        ">

                        <strong style="color:#671919;">
                            Time To Furnish
                        </strong>

                        — Furniture &amp; Interior Solutions

                        <br>

                        &copy; {{ date('Y') }}
                        Time To Furnish. All rights reserved.

                    </td>

                </tr>


            </table>

        </td>

    </tr>

</table>


</body>

</html>