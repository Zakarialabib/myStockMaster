
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>{{__('Customer')}}  : {{$client['client_name']}}</title>
      <link rel="stylesheet" href="{{asset('/css/pdf_style.css')}}" media="all" />
   </head>

   <body>
      <header class="clearfix">
         <div id="logo">
         <img src="{{asset('/images/'.$setting['logo'])}}">
         </div>
        
         <div id="Title-heading">
               {{__('Customer')}}  : {{$client['client_name']}}
         </div>
         </div>
      </header>
      <main>
         <div id="details" class="clearfix">
            <div id="client">
               <table class="table-sm">
                  <thead>
                     <tr>
                        <th class="desc">{{__('Customer Details')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div><strong>{{__('Name')}}:</strong> {{$client['client_name']}}</div>
                           <div><strong>{{__('Tax_number')}}:</strong> {{$client['client_ice']}}</div>
                           <div><strong>{{__('Phone')}}:</strong> {{$client['phone']}}</div>
                           <div><strong>{{__('Total Sales')}}:</strong> {{$client['total_sales']}}</div>
                           <div><strong>{{__('Total Amount')}}:</strong> {{$symbol}} {{$client['total_amount']}}</div>
                           <div><strong>{{__('Total Paid')}}:</strong> {{$symbol}} {{$client['total_paid']}}</div>
                           <div><strong>{{__('Due')}}:</strong> {{$symbol}} {{$client['due']}}</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div id="invoice">
               <table class="table-sm">
                  <thead>
                     <tr>
                        <th class="desc">{{__('Company')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div id="comp">{{$setting['CompanyName']}}</div>
                           <div><strong>{{__('Tax Number')}}:</strong>  {{$setting['CompanyTaxNumber']}}</div>
                           <div><strong>{{__('Address')}}:</strong>  {{$setting['CompanyAdress']}}</div>
                           <div><strong>{{__('Phone')}}:</strong>  {{$setting['CompanyPhone']}}</div>
                           <div><strong>{{__('Email')}}:</strong>  {{$setting['email']}}</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div id="details_inv">
            <h3 style="margin-bottom:10px">
            Toutes les ventes ( Non payé/Partiel )
            </h3>
            <table  class="table-sm">
               <thead>
                  <tr>
                     <th>{{__('Date')}}</th>
                     <th>REF</th>
                     <th>PAYE</th>
                     <th>DÛ</th>
                     <th>ETAT DE PAIEMENT</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($sales as $sale)
                  <tr>
                     <td>{{$sale['date']}} </td>
                     <td>{{$sale['Ref']}}</td>
                     <td>{{$symbol}} {{$sale['paid_amount']}} </td>
                     <td>{{$symbol}} {{$sale['due']}} </td>
                     <td>{{$sale['payment_status']}} </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </main>
   </body>
</html>
