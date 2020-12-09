<div class='lastoutput'>
</div>
@if (session('messages'))
<div class='devlog'>
  <div class='top'><span>Result</span></div>
  <div class='fullog'>
    <?php
      $messages = array_reverse(session('messages'));
    ?>
    @foreach($messages as $msg)
      <span>{{$msg}}<span>
    @endforeach
  </div>
</div>
@endif
