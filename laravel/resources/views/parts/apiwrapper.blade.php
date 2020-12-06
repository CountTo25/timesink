<script>
class TSAPI {
  consctructor() {
    console.log('API ready');
  }
  cloudSave(data) {
    let request = new XMLHttpRequest();
    request.open('POST', '/clientapi/cloudsave', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    let toSend = {
      'savedata': data,
    };
    request.send('savedata='+JSON.stringify(data));
  }

  cloudLoad() {
    let request = new XMLHttpRequest();
    request.open('GET', '/clientapi/cloudsave', true);
    request.onload = function() {
      if (this.status >= 200 && this.status < 400) {
        let r = JSON.parse(this.response);
        if (r.success) {
          return r.cloudsave;
        } else {
          console.log('error: '+r.error);
        }
      } else {
        console.log('error');
      }
    };
    request.send();
  }
}

var timesinkAPI = new TSAPI();
</script>
