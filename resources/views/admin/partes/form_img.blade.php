@if($errors->any())
  <ul>
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
  </ul>
@endif
<input type="file" name="image" id="image">
