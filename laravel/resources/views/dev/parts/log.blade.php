<div class='lastoutput'>
</div>
<div class='devlog'>
  <div class='top'><span>Result</span></div>
  <div class='fullog'>
    @if (session('messages'))
      <?php $messages = array_reverse(session('messages')); ?>
      @foreach($messages as $msg)
        <span>{{$msg}}<span>
          @endforeach
    @else
      <span>Waiting for actions</span>
    @endif
  </div>
</div>
