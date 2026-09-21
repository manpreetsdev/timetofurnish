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
            ? 'New Career Application'
            : 'Thank You for Your Career Application' }}
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

                                New Career Application

                            </h2>


                            <p style="
                                margin:0 0 20px 0;
                                color:#555555;
                                font-size:14px;
                            ">

                                A new career application has been
                                submitted through your website.

                            </p>


                            <!-- APPLICANT DETAILS -->

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


                                <!-- NAME -->

                                <tr style="background-color:#f9f9f9;">

                                    <td width="30%"
                                        style="
                                            font-weight:bold;
                                            color:#555;
                                            border-bottom:1px solid #eee;
                                        ">

                                        Name:

                                    </td>

                                    <td style="
                                        border-bottom:1px solid #eee;
                                        color:#222;
                                    ">

                                        {{ $data['name'] ?? '' }}

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


                                <!-- PHONE -->

                                <tr style="background-color:#f9f9f9;">

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        border-bottom:1px solid #eee;
                                    ">

                                        Phone:

                                    </td>

                                    <td style="
                                        border-bottom:1px solid #eee;
                                    ">

                                        <a href="tel:{{ $data['phone'] ?? '' }}"
                                           style="
                                               color:#671919;
                                               text-decoration:none;
                                               font-weight:bold;
                                           ">

                                            {{ $data['phone'] ?? '' }}

                                        </a>

                                    </td>

                                </tr>


                                <!-- ROLE -->

                                <tr>

                                    <td style="
                                        font-weight:bold;
                                        color:#555;
                                        border-bottom:1px solid #eee;
                                    ">

                                        Role:

                                    </td>

                                    <td style="
                                        border-bottom:1px solid #eee;
                                        color:#222;
                                    ">

                                        {{ $data['role'] ?? '' }}

                                    </td>

                                </tr>


                                <!-- MESSAGE -->

                                @if(!empty($data['message']))

                                    <tr style="background-color:#f9f9f9;">

                                        <td style="
                                            font-weight:bold;
                                            color:#555;
                                            vertical-align:top;
                                        ">

                                            Message:

                                        </td>

                                        <td style="
                                            white-space:pre-wrap;
                                            color:#222;
                                        ">

                                            {{ $data['message'] }}

                                        </td>

                                    </tr>

                                @endif


                            </table>


                            <p style="
                                margin:0;
                                font-size:13px;
                                color:#666;
                            ">

                                Please review the application and
                                contact the applicant using the details
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

                                Thank You for Applying

                            </h2>


                            <p style="
                                margin:0 0 15px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Hi
                                <strong>
                                    {{ $data['name'] ?? 'Applicant' }}
                                </strong>,

                            </p>


                            <p style="
                                margin:0 0 20px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Thank you for submitting your career
                                application to
                                <strong>Time To Furnish</strong>.

                                We have received your application
                                successfully.

                            </p>


                            <p style="
                                margin:0 0 20px 0;
                                font-size:14px;
                                color:#444444;
                            ">

                                Our team will review your profile and
                                contact you if your qualifications
                                match our requirements.

                            </p>


                            <!-- APPLICATION SUMMARY -->

                            <table width="100%"
                                   cellpadding="10"
                                   cellspacing="0"
                                   border="0"
                                   style="
                                       border:1px solid #e0e0e0;
                                       border-radius:6px;
                                       font-size:14px;
                                   ">

                                <tr style="background-color:#f9f9f9;">

                                    <td width="35%"
                                        style="
                                            font-weight:bold;
                                            color:#555;
                                        ">

                                        Position:

                                    </td>

                                    <td style="color:#222;">

                                        {{ $data['role'] ?? '' }}

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