<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>بيانات العقار</title>
        <style>
            .header {
                height: 10px;
                background-color: #79ac69;
                color: white;
            }
            
            table{
                width: 100%;
                justify-content: center;
                color:#444;
            }
            
            table thead{
                background-color:#333;
                color:#fff;
            }
            
            .head_table {
                    background-color:#1a6296;
                    color:#fff
                }
                
            tr {
                border: none;
                text-align: center;
            }

            td{
                border: none;
            }
        </style>
    </head>
    <body>
        <div>
            <div class="col-sm-12 col-auto row">
                <h4 class="page-title col-md-11 p-3">{{ __('pages.building_name') . ': ' . $building->name }}</h4>
                <h4 class="page-title col-md-11 p-3">{{ 'مجموع ' . __('pages.cost') . ' المدفوعة: ' . $building->building_paid }}</h4>
                <h4 class="page-title col-md-11 p-3">{{ 'مصاريف العقار: ' . $building->maintenances()->whereMonth('maintenances.created_at', date('m'))->sum('cost') }}</h4>
                <h4 class="page-title col-md-11 p-3">{{ 'المبلغ المتبقي: ' . $building->building_paid - $building->maintenances()->whereMonth('maintenances.created_at', date('m'))->sum('cost') }}</h4>
            </div>
            <table style="padding: 5px ; text-align: center; width: 100%; border: 1px solid black; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 10% !important; border: 1px solid black; border-collapse: collapse;">الوحدة</th>
                        <th style="width: 30% !important; border: 1px solid black; border-collapse: collapse;">{{ __('pages.tenant') }}</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">بدء العقد</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">انتهاء العقد</th>
                        <th style="width: 10% !important; border: 1px solid black; border-collapse: collapse;">الايجار</th>
                        <th style="width: 10% !important; border: 1px solid black; border-collapse: collapse;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($building->apartments as $apartment)
                        <tr class="compound_row mb-3 record">
                            <td style="vertical-align: middle; width: 10% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $apartment->name }}
                            </td>
                            <td style="vertical-align: middle; width: 30% !important; border: 1px solid black; border-collapse: collapse;">
                                @if($apartment->tenants)
                                    @if($apartment->tenants()->latest()->first())
                                        @if($apartment->tenants()->latest()->first()->tenant)
                                            {{ $apartment->tenants()->latest()->first()->tenant->name }}
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                @if($apartment->tenants)
                                    @if($apartment->tenants()->latest()->first())
                                        {{ $apartment->tenants()->latest()->first()->start_date }}
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                @if($apartment->tenants)
                                    @if($apartment->tenants()->latest()->first())
                                        {{ $apartment->tenants()->latest()->first()->end_date }}
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align: middle; width: 10% !important; border: 1px solid black; border-collapse: collapse;">
                                @if($apartment->tenants)
                                    @if($apartment->tenants()->first())
                                        {{ $apartment->tenants()->latest()->first()->price }}
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align: middle; width: 10% !important; border: 1px solid black; border-collapse: collapse;">
                                @if($apartment->status) مستأجر @else شاغر @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>  
            </table> 
        </div>
    </body>
</html>