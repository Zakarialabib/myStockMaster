
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>{{__('Client')}}  : {{$client['client_name']}}</title>
      <link rel="stylesheet" href="{{asset('/css/pdf_style.css')}}" media="all" />
   </head>

   <body>
      <header class="clearfix">
         <div id="logo">
         <img src="{{asset('/images/'.$setting['logo'])}}">
         </div>
        
         <div id="Title-heading">
               {{__('Client')}}  : {{$client['client_name']}}
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
                           <div><strong>Nom:</strong> {{$client['client_name']}}</div>
                           <div><strong>ICE:</strong> {{$client['client_ice']}}</div>
                           <div><strong>Téle:</strong> {{$client['phone']}}</div>
                           <div><strong>Ventes totales:</strong> {{$client['total_sales']}}</div>
                           <div><strong>Monatnt total:</strong> {{$symbol}} {{$client['total_amount']}}</div>
                           <div><strong>Total payé:</strong> {{$symbol}} {{$client['total_paid']}}</div>
                           <div><strong>Dû:</strong> {{$symbol}} {{$client['due']}}</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div id="invoice">
               <table class="table-sm">
                  <thead>
                     <tr>
                        <th class="desc">Infos société</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div id="comp">{{$setting['CompanyName']}}</div>
                           <div><strong>ICE:</strong>  {{$setting['CompanyTaxNumber']}}</div>
                           <div><strong>Adresse:</strong>  {{$setting['CompanyAdress']}}</div>
                           <div><strong>Téle:</strong>  {{$setting['CompanyPhone']}}</div>
                           <div><strong>Email:</strong>  {{$setting['email']}}</div>
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
                     <th>DATE</th>
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
