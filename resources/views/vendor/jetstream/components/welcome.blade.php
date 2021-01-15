<div>
    <p> {{ Auth::user()->name }} </p>
    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
</div>