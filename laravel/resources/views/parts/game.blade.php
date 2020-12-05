<div>
  <div class='gameheader'>
    @if ($game->banner)

    @endif
    <span>Play <span class='gametitle'>{{$game->name}}</span> @ timesink</span>
  </div>
  <div class='gamecontainer'>
    <!--Game goes here-->
    <div class='gameinner'>
      <iframe id='gameframe'>
        <?php $game->view(); ?>
      </iframe>
    </div>
  </div><div class='sidebar'>
    info
  </div>
  <div class='under'></div>
</div>

<script>
$(document).ready(()=>{
  var context = $('iframe')[0].contentWindow.document,
      $gamebody = $('body', context),
      gamestruct = '{{ $game->view() }}';
  $gamebody.html(unescapeHtml(gamestruct));
});

function unescapeHtml(safe) {
    return safe.replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#039;/g, "'");
}
</script>
