<div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
  <input type="checkbox" name="{{$name}}" id="{{$id}}" @if($checked) checked @endif {{ $attributes->merge(['class'=>"toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"])}}/>
  <label for="{{$id}}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
</div>