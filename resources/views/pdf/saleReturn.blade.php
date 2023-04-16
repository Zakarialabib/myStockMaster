<!DOCTYPE html>
   <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{__('Return')}} _{{$return_sale->reference}}</title>
      <link rel="stylesheet" href="{{asset('/print/pdfStyle.css')}}" media="all" />
   </head>

   <body>
      <header class="clearfix">
         <div id="logo">
         <img src="{{asset('/images/'.$setting->logo )}}">
         </div>
         <div id="company">
            <div><strong> {{__('Date')}}: </strong>{{$return_sale->date}}</div>
            <div><strong> {{__('Number')}}: </strong> {{$return_sale->reference}}</div>
            <div><strong> {{__('Réf vente')}}: </strong> {{$return_sale->sale_ref}}</div>

         </div>
         <div id="Title-heading">
            {{__('Return')}}  : {{$return_sale->reference}}
         </div>
         </div>
      </header>
      <main>
         <div id="details" class="clearfix">
            <div id="client">
               <table class="table-sm">
                  <thead>
                     <tr>
                        <th class="desc">{{__('Customer Info')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div><strong>{{ __('Name') }}:</strong> {{ $returnSale->customer->name }}</div>
                                <div><strong>{{ __('Tax number') }}:</strong> {{ $returnSale->customer?->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $returnSale->customer->phone }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ $returnSale->customer->address }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $returnSale->customer->email }}</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div id="invoice">
               <table class="table-sm">
                  <thead>
                     <tr>
                        <th class="desc">{{__('Company Info')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div id="comp">{{settings()->company_name}}</div>
                           <div><strong>{{ __('Tax number') }}</strong> {{ settings()->company_tax }}</div>
                           <div><strong>{{__('Address')}}:</strong>{{settings()->company_address}}</div>
                           <div><strong>{{__('Phone')}}:</strong>{{settings()->company_phone}}</div>
                           <div><strong>{{__('Email')}}:</strong>{{settings()->company_email}}</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div id="details_inv">
            <table class="table-sm">
               <thead>
                  <tr>
                     <th>{{__('Product')}}</th>
                     <th>{{__('Unit Price')}}</th>
                     <th>{{__('Qty')}}</th>
                     <th>{{__('Discount')}}</th>
                     <th>{{__('Tax')}}</th>
                     <th>{{__('TOTAL')}}</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($details as $detail)    
                  <tr>
                     <td>
                        <span>{{$detail->code}} ({{$detail->name}})</span>
                     </td>
                     <td>{{$detail->unit_price}} </td>
                     <td>{{$detail->quantity}}/{{$detail->unit }}</td>
                     <td>{{$detail->discount }} </td>
                     <td>{{$detail->ta }} </td>
                     <td>{{$detail->total_amount}} </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         <div id="total">
            <table>
               <tr>
                  <td>{{__('Order Tax')}}</td>
                  <td>{{$return_sale->TaxNet }} </td>
               </tr>
               <tr>
                  <td>{{__('Discount')}}</td>
                  <td>{{$return_sale->discount }} </td>
               </tr>
               <tr>
                  <td>{{__('Shipping')}}</td>
                  <td>{{$return_sale->shipping }} </td>
               </tr>
               <tr>
                  <td>{{__('Total')}}</td>
                  <td>{{$symbol}} {{$return_sale->GrandTotal }} </td>
               </tr>

               <tr>
                  <td>{{__('Paid Amount')}}</td>
                  <td>{{$symbol}} {{$return_sale->paid_amount }} </td>
               </tr>

               <tr>
                  <td>{{__('Due')}}</td>
                  <td>{{$symbol}} {{$return_sale->due }} </td>
               </tr>
            </table>
         </div>
         <div>
            @if (settings()->invoice_footer_text)
                <p>{{ settings()->invoice_footer_text }}</p>
            @endif
        </div>
      </main>
   </body>
</html>