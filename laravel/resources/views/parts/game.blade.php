  <div class='gameheader'>
    @if ($game->banner)

    @endif
    <span>Play <span class='gametitle'>{{$game->name}}</span> @ timesink</span>
  </div>
  <div class='gamecontainer'>
    <!--Game goes here-->
    <div class='gameinner'>
      <!-- sandbox later-->
      <iframe id='gameframe'>
        <?php $game->view(); ?>
      </iframe>
    </div>
  </div><div class='sidebar'>
    info
  </div>
  <div class='under'></div>

<script>
$(document).ready(()=>{
  $('#gameframe').attr('src', '{{$game->view()}}?rand='+new Date()/1);
});

//clean up?
function unescapeHtml(safe) {
    return safe.replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#039;/g, "'");
}
</script>
