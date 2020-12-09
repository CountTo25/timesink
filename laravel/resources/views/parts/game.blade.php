<style>
  body {
    margin: 0px;
  }

  .content {
    background-color: var(--text);
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }
</style>
<div class='maxwrapper'>
  <div class='gameheader'>
    @if ($game->banner)

    @endif
    <span class='gametitle'>{{$game->name}}</span>
    <span> made by {{$game->owner()->name}}</span>
    <div class='overlay-toggle'>Overlay <i class="fas fa-bars"></i></div>
  </div>
  <div class='gamecontainer'>
    <!--Game goes here-->
    <div class='gameinner'>
      <!-- sandbox later-->
      <iframe id='gameframe'>
        <?php $game->view(); ?>
      </iframe>
    </div>
  </div>
</div>

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
