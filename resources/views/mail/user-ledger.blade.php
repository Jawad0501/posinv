<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="http://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">p -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet"> --}}
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .container {
            padding: 40px 20px;
        }

        .w-50 {
            width: 50%;
        }

        .w-100 {
            width: 100%;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .fw-medium {
            font-weight: 500;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .fs-5{
            font-size: 1.15rem;
        }

        .border-none {
            border: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: normal;

        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .btn-place {
            padding: 8px 16px;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn-place:hover {
            color: #fff;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: auto !important;
        }
    </style>
</head>

<body style="color: #495057; max-width: 800px; width: 100%; margin: 0 auto;">

    @php
        $page_break = 1;
        $items = $orders;
        $filteredItems = [];
        $last_item = false;
        $count = 1;
        foreach($items as $item){
            $item['no'] = $count;
            $count = $count + 1;
        }
    @endphp

@for($i=0; $i<$page_break; $i++)
    @if($i==1 && $page_break==1)
     @break
    @endif
    <div class="container page-break" style="height: 1000px">
        
        <div style="position:relative; height: 100px; width: 100%">
            <div class="w-100" style="position:absolute">

                <img src="https://1.bp.blogspot.com/-JdbM280Rs2Y/XjxgQTF6HEI/AAAAAAAAAAg/YdyLp9V1tDkp2-kaFXCcgc9IeQNxt5o1gCLcBGAsYHQ/s1600/dummy.jpg" width="100px" alt="">

            </div>
            <div style="position: absolute; right: 0; text-align:right">
                <div style="width:320px; font-weight: 600; font-size: 24px; color: black">{{setting('app_title')}}</div>
                <div  style="font-size: 20px;  color: gray; font-weight: 600">{{setting('address')}}</div>
            </div>
        </div>

        <div  style="position:relative; width: 100%; height: 100px;">
            <div style="position:absolute">
                <div class="fw-semibold fs-4 text-black">Customer Name: {{$customer->full_name}}</div>
                <div class="fw-semibold fs-4 text-black">Customer Mobile: {{$customer->phone}}</div>
                <div class="fw-semibold" style="font-size: 12px;">From Date: {{ $from != null ? date('d F Y', strtotime($from)) : 'N/A'}}</div>
                <div class="fw-semibold" style="font-size: 12px;">To Date: {{ $to != null ? date('d F Y', strtotime($to)) : date('d F Y')}}</div>
                <div class="fw-semibold" style="font-size: 12px;">Ledger Creation Date: {{ date('d F Y')}}</div>
            </div>
            <div style="position:absolute; right:0; text-align: right;">
                <span class="text-uppercase fw-medium text-black" style="font-size: 30px; letter-spacing: 2px;">Ledger</span>
            </div>
        </div>

        <div style="width:100%">
            <div style="">
                <div>
                    <table  style="border-spacing: 0px !important; width:100%">
                        <thead style="background-color: #9E9E9E; ">
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">No.</td>
                            <td  style=" padding: 5px 0px; text-transform: uppercase; color: white;">Date</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Invoice</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Total</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Paid</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Change</td>
                            <td  style=" padding: 5px 0px; text-align:center; text-transform: uppercase; color: white;">Due</td>
                        </thead>

                        <tbody>
                            @php
                                $temp_item = null;
                            @endphp
                            @foreach($items as $item)
                            @if($item->no/(15*($i+1)) == 1)
                                @php
                                $page_break = $page_break + 1;
                                @endphp
                                @foreach($items as $key => $remove_item)
                                    @if ($remove_item->no >= $item->no)
                                        @php
                                            // unset($items[$key])
                                            $filteredItems[$key] = $remove_item;
                                        @endphp
                                        {{-- $filteredItems->$key = $item; --}}
                                    @endif
                                @endforeach
                                @break
                            @else
                                @php
                                    $temp_item = $item;
                                @endphp
                            @endif
                                <tr>
                                    <td style="width: 50px; text-align:center; font-size: 12px; font-weight: 600; color: black">
                                        {{$item->no}}
                                    </td>
                                    <td style="width: 200px; border-bottom: 2px solid #f2f2f3; padding-bottom: 20px;">
                                        <div style="font-size: 12px; font-weight: 600; color: black; margin-top: 10px">{{$item->date}}</div>
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->invoice }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->grand_total }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->payment->give_amount }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->payment->change_amount }}
                                    </td>
                                    <td style="width: 100px; text-align:center; font-size: 11px; font-weight: 500; border-bottom: 2px solid #f2f2f3">
                                        {{ $item->payment->due_amount }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
<!--  -->

               


            </div>
        </div>

        @php
            $items = (object) $filteredItems;
        @endphp
        </div>
    @endfor
</body>

</html>

