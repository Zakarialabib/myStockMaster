<!DOCTYPE html>
   <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{__('Return')}} _{{$return_sale['Ref']}}</title>
      <link rel="stylesheet" href="{{asset('/css/pdf_style.css')}}" media="all" />
   </head>

   <body>
      <header class="clearfix">
         <div id="logo">
         <img src="{{asset('/images/'.$setting['logo'])}}">
         </div>
         <div id="company">
            <div><strong> Date: </strong>{{$return_sale['date']}}</div>
            <div><strong> Numéro: </strong> {{$return_sale['Ref']}}</div>
            <div><strong> Réf vente: </strong> {{$return_sale['sale_ref']}}</div>

         </div>
         <div id="Title-heading">
            Retour  : {{$return_sale['Ref']}}
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
                           <div><strong>Nom:</strong> {{$return_sale['client_name']}}</div>
                           <div><strong>ICE:</strong> {{$return_sale['client_ice']}}</div>
                           <div><strong>Téle:</strong> {{$return_sale['client_phone']}}</div>
                           <div><strong>Adresse:</strong>   {{$return_sale['client_adr']}}</div>
                           <div><strong>Email:</strong>  {{$return_sale['client_email']}}</div>
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
                           <div id="comp">{{$setting['CompanyName']}}</div>
                           <div><strong>ICE:</strong>{{$setting['CompanyTaxNumber']}}</div>
                           <div><strong>Adresse:</strong>{{$setting['CompanyAdress']}}</div>
                           <div><strong>Téle:</strong>{{$setting['CompanyPhone']}}</div>
                           <div><strong>Email:</strong>{{$setting['email']}}</div>
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
                     <th>PRODUIT</th>
                     <th>PRIX UNITAIRE</th>
                     <th>QUANTITE</th>
                     <th>REMISE</th>
                     <th>TAXE</th>
                     <th>TOTAL</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($details as $detail)    
                  <tr>
                     <td>
                        <span>{{$detail['code']}} ({{$detail['name']}})</span>
                           @if($detail['is_imei'] && $detail['imei_number'] !==null)
                              <p>IMEI/SN : {{$detail['imei_number']}}</p>
                           @endif
                     </td>
                     <td>{{$detail['price']}} </td>
                     <td>{{$detail['quantity']}}/{{$detail['unitSale']}}</td>
                     <td>{{$detail['DiscountNet']}} </td>
                     <td>{{$detail['taxe']}} </td>
                     <td>{{$detail['total']}} </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         <div id="total">
            <table>
               <tr>
                  <td>{{__('Order Tax')}}</td>
                  <td>{{$return_sale['TaxNet']}} </td>
               </tr>
               <tr>
                  <td>{{__('Discount')}}</td>
                  <td>{{$return_sale['discount']}} </td>
               </tr>
               <tr>
                  <td>{{__('Shipping')}}</td>
                  <td>{{$return_sale['shipping']}} </td>
               </tr>
               <tr>
                  <td>{{__('Total')}}</td>
                  <td>{{$symbol}} {{$return_sale['GrandTotal']}} </td>
               </tr>

               <tr>
                  <td>{{__('Paid Amount')}}</td>
                  <td>{{$symbol}} {{$return_sale['paid_amount']}} </td>
               </tr>

               <tr>
                  <td>{{__('Due')}}</td>
                  <td>{{$symbol}} {{$return_sale['due']}} </td>
               </tr>
            </table>
         </div>
         <div id="signature">
            @if($setting['is_invoice_footer'] && $setting['invoice_footer'] !==null)
               <p>{{$setting['invoice_footer']}}</p>
            @endif
         </div>
      </main>
   </body>
</html>