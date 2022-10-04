<div class="relative inline-block w-60 mr-2 align-middle select-none transition duration-200 ease-in">
    <select name="{{$name}}" id="{{$id}}" @if($checked) checked @endif {{ $attributes->merge(['class'=>"form-select p-3 leading-5 bg-white dark:bg-dark-eval-2 text-zinc-700 dark:text-zinc-300 rounded border border-zinc-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"])}}/>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_PENDING}}'>{{ __('Pending') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_PROCESSING}}'>{{ __('Processing') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_COLLECTING}}'>{{ __('Collecting') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_CONFIRMED}}'>{{ __('Confirmed') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_SHIPPING}}'>{{ __('Shipping') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_CANCELED}}'>{{ __('Canceled') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_COMPLETED}}'>{{ __('Completed') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_RETURNED}}'>{{ __('Returned') }}</option>
        <option name="{{$name}}" value='{{App\Models\ORDER::STATUS_PAID}}'>{{ __('PAID') }}</option>   
        <label for="{{$id}}" class="block overflow-hidden h-6 rounded-full bg-zinc-300 cursor-pointer"></label>
    </select>
</div>