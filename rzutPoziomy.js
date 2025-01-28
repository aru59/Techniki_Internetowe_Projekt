document.getElementById('parameters').addEventListener('submit', function(event) {
  event.preventDefault();
  let initialVelocity = document.getElementById('velocity').value;
  let gravity = document.getElementById('gravity').value;
  let initheight = parseFloat(document.getElementById('height').value);
  drawProjectileMotion(initialVelocity, gravity, initheight);
});
document.getElementById('saveParameters').addEventListener('click', function() {
  if (isLoggedIn) {
      var velocity = document.getElementById('velocity').value;
      var height = document.getElementById('height').value;
      var gravity = document.getElementById('gravity').value;
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'save_parameters.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send('velocity=' + encodeURIComponent(velocity) + '&height=' + encodeURIComponent(height) + '&gravity=' + encodeURIComponent(gravity));
      xhr.addEventListener('load', function() {
          if (xhr.status >= 200 && xhr.status < 300) {
              console.log(xhr.responseText);
          } else {
              console.log('The request failed!');
          }
      });
  } else {
      alert('Musisz się zalogować, aby móc zapisywać parametry.');
  }
});
document.getElementById('loadParameters').addEventListener('click', function() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'load_parameters.php', true);
  xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
          var data = JSON.parse(xhr.responseText);

          if (data.success) {
              document.getElementById('velocity').value = data.velocity;
              document.getElementById('height').value = data.height;
              document.getElementById('gravity').value = data.gravity;

              alert('Parametry zostały wczytane.');
          } else {
              alert('Nie znaleziono zapisanych parametrów.');
          }
      } else {
          console.error('Błąd ładowania parametrów.');
      }
  };
  xhr.send();
});
document.getElementById('goToPage').addEventListener('click', function() {
  window.location.href = 'tech.html';
});
window.addEventListener('load', function() {
  let canvas = document.getElementById('canvas');
  canvas.width = 800; 
  canvas.height = 400; 
});
window.addEventListener('resize', function() {
  let canvas = document.getElementById('canvas');
  canvas.width = window.innerWidth;
});
function setGravity(value) {
  document.getElementById('gravity').value = value;
}

function drawProjectileMotion(initialVelocity, gravity, initheight) {
  let canvas = document.getElementById('canvas');
  let context = canvas.getContext('2d');
  context.clearRect(0, 0, canvas.width, canvas.height);
  context.beginPath();
  let time = 0;
  let x = 0;
  let y = initheight;
  let vx = initialVelocity;
  let vy = 0;
  let maxSpeed = Math.sqrt(vx * vx + vy * vy);
  function animate() {
    if (y >= 0) {
      
        let speed = Math.sqrt(vx * vx + vy * vy);
        

        if (speed > 0) {
            vx -= (vx / speed) * 0.01; 
            vy -= (vy / speed) * 0.01; 
        }

        vy += gravity * 0.01;

        x += vx * 0.01; 
        y -= vy * 0.01; 

        if (speed > maxSpeed) {
          maxSpeed = speed;
        }

        context.clearRect(0, 0, canvas.width, canvas.height);
        context.lineWidth = 3;
        context.lineTo(x, canvas.height - y);
        context.stroke();

        context.font = '10px Arial';
        
        context.fillText(
            `x: ${x.toFixed(2)}, y: ${y.toFixed(2)}, prędkość: ${speed.toFixed(2)} m/s`,
            x + 10,
            canvas.height - y
        );

        time += 0.01;

        window.requestAnimationFrame(animate);
    } else {
        context.font = '20px Arial';
        context.fillText(`Maksymalna prędkość: ${maxSpeed.toFixed(2)} m/s`, 10, 30);
        context.fillText(`Zasięg: ${x.toFixed(2)} m`, 10, 60);
        context.fillText(`Czas lotu: ${time.toFixed(2)} s`, 10, 90);
    }
}
  animate();
}