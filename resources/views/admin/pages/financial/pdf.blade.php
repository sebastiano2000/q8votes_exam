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
            <table style="padding: 5px ; text-align: center; width: 100%; border: 1px solid black; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">اسم المستأجر</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">اسم العقار</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">اسم الوحدة</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">تاريخ السداد</th>
                        <th style="width: 20% !important; border: 1px solid black; border-collapse: collapse;">لمبلغ الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($financial_transactions as $financial_transaction)
                        <tr class="compound_row mb-3 record">
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $financial_transaction->tenant->name }}
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $financial_transaction->tenancy->building->name }}
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $financial_transaction->tenancy->apartment->name }}
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $financial_transaction->paidOn }}
                            </td>
                            <td style="vertical-align: middle; width: 20% !important; border: 1px solid black; border-collapse: collapse;">
                                {{ $financial_transaction->total_amount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>  
            </table> 
        </div>
    </body>
</html>