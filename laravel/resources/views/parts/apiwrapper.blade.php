<script>
console.log('loading timesinkAPI');
class TSAPI {
  consctructor() {
    console.log('API ready');
  }
  cloudSave(data) {
    let request = new XMLHttpRequest();
    request.open('POST', '/clientapi/cloudsave', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.send('savedata='+JSON.stringify(data));
  }

  cloudLoad(callback) {
    let request = new XMLHttpRequest();
    request.open('GET', '/clientapi/cloudsave', true);
    request.onload = function() {
      if (this.status >= 200 && this.status < 400) {
        let r = JSON.parse(this.response);
        if (r.success) {
          callback(JSON.parse(r.cloudsave));
        } else {
          callback(r);
        }
      } else {
        console.log('error');
      }
    };
    request.send();
  }
}

var timesinkAPI = new TSAPI();
console.log('loaded timesinkAPI');
</script>
