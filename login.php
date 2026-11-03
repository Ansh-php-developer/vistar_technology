<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $error = 'Session expired. Reload and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Check username and password (same logic as before)
        $s = $pdo->prepare('SELECT * FROM admins WHERE username = ? AND password_hash = ?');
        $s->execute([$username, $password]);
        $u = $s->fetch();

        if ($u) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $u['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Wrong username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --a:#dbe4ff; --a2:#8ea6e6; --on:#0d1424; --glow:219,228,255;
  --bg:#07090d;
}
[data-theme="amber"]{--a:#f7a936;--a2:#e8901a;--on:#1a1206;--glow:247,169,54}
[data-theme="cyan"]{--a:#22d3ee;--a2:#0ea5c9;--on:#041a20;--glow:34,211,238}
[data-theme="pink"]{--a:#ff2d78;--a2:#d4125c;--on:#fff;--glow:255,45,120}
[data-theme="green"]{--a:#00d46a;--a2:#0aa655;--on:#02170b;--glow:0,212,106}
[data-theme="white"]{--a:#dbe4ff;--a2:#8ea6e6;--on:#0d1424;--glow:219,228,255}

*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:"Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;background:var(--bg);color:#fff;overflow-x:hidden;-webkit-font-smoothing:antialiased}
button,input{font:inherit;color:inherit}
button{cursor:pointer;border:0;background:none}

/* ambient light + stars */
.ambient{position:fixed;inset:0;pointer-events:none;background:radial-gradient(ellipse 60% 70% at 28% 42%,rgba(var(--glow),.16),transparent 70%),radial-gradient(ellipse 80% 80% at 50% 50%,rgba(var(--glow),.05),transparent 75%);opacity:0;transition:opacity .8s ease,background .6s}
.on .ambient{opacity:1}
.stars{position:fixed;inset:0;pointer-events:none}
.stars i{position:absolute;width:2px;height:2px;border-radius:50%;background:#fff;opacity:.35;animation:tw 4s ease-in-out infinite}
@keyframes tw{50%{opacity:.08}}

/* theme dots */
.dots{position:fixed;top:14px;right:16px;display:flex;gap:8px;z-index:5;padding:6px 8px;border-radius:99px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);backdrop-filter:blur(6px)}
.dots button{width:14px;height:14px;border-radius:50%;transition:transform .2s,box-shadow .2s}
.dots button:hover{transform:scale(1.25)}
.dots button.sel{transform:scale(1.35);box-shadow:0 0 0 2px var(--bg),0 0 0 3.5px rgba(255,255,255,.7)}

/* layout */
.stage{position:relative;min-height:100vh;display:flex;align-items:center;justify-content:center;gap:clamp(40px,8vw,110px);padding:40px 24px}

/* lamp */
.lamp-wrap{position:relative;width:300px;flex:none}
.lamp{display:block;width:100%;height:auto;overflow:visible;cursor:pointer}
.lamp:focus-visible{outline:2px solid var(--a);outline-offset:6px;border-radius:12px}
.cone{opacity:0;transition:opacity .6s ease}
.on .cone{opacity:1}
.bulb{fill:#2b2f36;transition:fill .4s}
.on .bulb{fill:var(--a)}
.bulb-glow{opacity:0;transition:opacity .5s}
.on .bulb-glow{opacity:1}
.shade-glow{opacity:0;transition:opacity .5s}
.on .shade-glow{opacity:.9}
.rim{stroke:#c99a2e;transition:stroke .4s}
.on .rim{stroke:var(--a)}
.rim-in{fill:#2a2412;transition:fill .5s}
.on .rim-in{fill:var(--a);opacity:.9}
.string-g{transition:transform .15s}
.pull .string-g{animation:pull .45s ease}
@keyframes pull{0%{transform:translateY(0)}35%{transform:translateY(16px)}100%{transform:translateY(0)}}
.bead{fill:#c99a2e;transition:fill .4s}
.on .bead{fill:var(--a)}
.hint{position:absolute;left:50%;top:79%;transform:translateX(-30%);background:rgba(20,24,32,.92);border:1px solid rgba(255,255,255,.12);padding:6px 10px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap;pointer-events:none;transition:opacity .3s;box-shadow:0 6px 20px rgba(0,0,0,.5)}
.on .hint{opacity:0}

/* card */
.card{width:min(380px,100%);border-radius:18px;padding:26px 24px 22px;background:linear-gradient(180deg,rgba(24,29,38,.92),rgba(15,19,26,.92));border:1px solid rgba(255,255,255,.08);box-shadow:0 30px 80px rgba(0,0,0,.6),0 0 0 1px rgba(var(--glow),.04),0 0 60px rgba(var(--glow),.08);opacity:0;transform:translateY(14px) scale(.98);visibility:hidden;transition:opacity .55s ease .1s,transform .55s ease .1s,visibility 0s linear .65s}
.on .card,.nojs .card{opacity:1;transform:none;visibility:visible;transition:opacity .55s ease .15s,transform .55s ease .15s,visibility 0s}
.card h1{font-size:24px;font-weight:800;letter-spacing:-.3px}
.sub{font-size:12px;color:#8b94a3;margin:4px 0 16px}
.tabs{display:grid;grid-template-columns:1fr 1fr;padding:3px;border-radius:10px;background:#0a0d12;border:1px solid rgba(255,255,255,.05);margin-bottom:16px}
.tabs button{padding:8px 0;border-radius:8px;font-size:12px;font-weight:600;color:#8b94a3;transition:.2s}
.tabs button.act{background:#1b212b;color:#fff;box-shadow:0 1px 3px rgba(0,0,0,.4)}
.row{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
label,.lbl{font-size:11.5px;font-weight:600;color:#c3cad6}
.forgot{font-size:11px;font-weight:700;color:var(--a);background:none}
.field{position:relative;margin-bottom:14px}
.field input{width:100%;height:44px;border-radius:10px;padding:0 40px 0 38px;background:#0d1117;border:1px solid rgba(255,255,255,.07);font-size:13px;outline:0;transition:border-color .2s,box-shadow .2s}
.field input::placeholder{color:#4b5463}
.field input:focus{border-color:var(--a);box-shadow:0 0 0 3px rgba(var(--glow),.15)}
.field svg{position:absolute;left:13px;top:50%;margin-top:-7px;width:14px;height:14px;stroke:#5a6372;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;pointer-events:none}
.field .eye{position:absolute;right:6px;top:50%;margin-top:-16px;width:32px;height:32px;display:grid;place-items:center;border-radius:8px}
.field .eye svg{position:static;margin:0;stroke:#5a6372}
.field .eye:hover svg{stroke:#fff}
.rem{display:flex;align-items:center;gap:8px;font-size:12px;color:#aab2bf;margin:2px 0 16px;cursor:pointer;user-select:none}
.rem input{position:absolute;opacity:0}
.rem .box{width:15px;height:15px;border-radius:4px;border:1.5px solid #3a4250;display:grid;place-items:center;transition:.2s}
.rem .box svg{width:10px;height:10px;stroke:var(--on);stroke-width:3;fill:none;opacity:0;stroke-linecap:round;stroke-linejoin:round}
.rem input:checked+.box{background:var(--a);border-color:var(--a)}
.rem input:checked+.box svg{opacity:1}
.rem input:focus-visible+.box{box-shadow:0 0 0 3px rgba(var(--glow),.25)}
.submit{width:100%;height:44px;border-radius:10px;font-weight:700;font-size:13px;color:var(--on);background:linear-gradient(180deg,var(--a),var(--a2));box-shadow:0 8px 24px rgba(var(--glow),.28),inset 0 1px 0 rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;gap:8px;transition:transform .15s,box-shadow .2s,filter .2s}
.submit:hover{transform:translateY(-1px);filter:brightness(1.06)}
.submit:active{transform:translateY(0)}
.submit svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.or{display:flex;align-items:center;gap:10px;margin:16px 0 12px;font-size:9.5px;letter-spacing:.14em;color:#586070;font-weight:600}
.or::before,.or::after{content:"";flex:1;height:1px;background:rgba(255,255,255,.06)}
.social{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.social button{height:40px;border-radius:10px;display:grid;place-items:center;background:#10151c;border:1px solid rgba(255,255,255,.07);transition:.2s}
.social button:hover{background:#171d26;border-color:rgba(255,255,255,.14)}
.social svg{width:16px;height:16px}
.err{background:rgba(255,61,90,.1);border:1px solid rgba(255,61,90,.35);color:#ff8fa3;padding:9px 12px;border-radius:10px;font-size:12px;font-weight:600;margin-bottom:14px}
.toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,20px);opacity:0;background:#151a22;border:1px solid rgba(255,255,255,.12);padding:10px 16px;border-radius:10px;font-size:12.5px;font-weight:600;box-shadow:0 10px 30px rgba(0,0,0,.6);transition:.3s;pointer-events:none;z-index:9}
.toast.show{opacity:1;transform:translate(-50%,0)}

@media(max-width:820px){
  .stage{flex-direction:column;gap:0;padding:56px 18px 32px;justify-content:flex-start}
  .lamp-wrap{width:210px;margin-bottom:-6px}
  .hint{font-size:10px}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition-duration:.01s!important}}
</style>
</head>
<body data-theme="white">
<div class="stage <?= $error ? 'on' : '' ?>" id="stage">
  <div class="ambient"></div>
  <div class="stars" id="stars" aria-hidden="true"></div>

  <div class="dots" id="dots" role="group" aria-label="Theme colour">
    <button type="button" data-t="amber" style="background:#f7a936" aria-label="Amber"></button>
    <button type="button" data-t="cyan" style="background:#22d3ee" aria-label="Cyan"></button>
    <button type="button" data-t="pink" style="background:#ff2d78" aria-label="Pink"></button>
    <button type="button" data-t="green" style="background:#00d46a" aria-label="Green"></button>
    <button type="button" data-t="white" style="background:#e8eeff" aria-label="White" class="sel"></button>
  </div>

  <div class="lamp-wrap">
    <svg class="lamp" id="lamp" viewBox="0 0 260 430" role="button" tabindex="0" aria-label="Pull the string to turn the lamp on or off" aria-pressed="false">
      <defs>
        <linearGradient id="coneG" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0" stop-color="var(--a)" stop-opacity=".62"/>
          <stop offset=".55" stop-color="var(--a)" stop-opacity=".18"/>
          <stop offset="1" stop-color="var(--a)" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="shadeG" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stop-color="#2a2f38"/><stop offset="1" stop-color="#0c0e12"/>
        </linearGradient>
        <linearGradient id="armG" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" stop-color="#22262d"/><stop offset=".5" stop-color="#0f1115"/><stop offset="1" stop-color="#1b1e24"/>
        </linearGradient>
        <radialGradient id="bulbG"><stop offset="0" stop-color="#fff"/><stop offset=".35" stop-color="var(--a)"/><stop offset="1" stop-color="var(--a)" stop-opacity="0"/></radialGradient>
        <filter id="blur14" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="14"/></filter>
        <filter id="blur6" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="6"/></filter>
      </defs>

      <!-- light cone -->
      <g class="cone">
        <polygon points="100,128 204,128 392,404 -62,404" fill="url(#coneG)" filter="url(#blur6)"/>
        <ellipse cx="150" cy="404" rx="150" ry="12" fill="var(--a)" opacity=".10" filter="url(#blur6)"/>
      </g>

      <!-- base -->
      <ellipse cx="125" cy="400" rx="76" ry="14" fill="#07080a"/>
      <ellipse cx="125" cy="396" rx="76" ry="13" fill="#15181d"/>
      <ellipse cx="125" cy="393" rx="60" ry="9" fill="#0b0d10"/>

      <!-- arms -->
      <path d="M120 376 L92 252" stroke="url(#armG)" stroke-width="13" stroke-linecap="round" fill="none"/>
      <path d="M92 252 L142 150" stroke="url(#armG)" stroke-width="11" stroke-linecap="round" fill="none"/>
      <circle cx="120" cy="368" r="9" fill="#14171c" stroke="#c99a2e" stroke-width="3"/>
      <circle cx="92" cy="252" r="9" fill="#14171c" stroke="#c99a2e" stroke-width="3"/>
      <circle cx="142" cy="150" r="7" fill="#14171c" stroke="#c99a2e" stroke-width="3"/>

      <!-- shade glow -->
      <ellipse class="shade-glow" cx="150" cy="96" rx="82" ry="64" fill="var(--a)" opacity=".5" filter="url(#blur14)"/>

      <!-- shade -->
      <path d="M92 128 C88 70 112 34 152 34 C192 34 212 72 208 128 Z" fill="url(#shadeG)" stroke="rgba(255,255,255,.08)" stroke-width="1"/>
      <path d="M118 52 C108 66 104 90 106 118" stroke="rgba(255,255,255,.12)" stroke-width="3" fill="none" stroke-linecap="round"/>
      <ellipse class="rim-in" cx="150" cy="128" rx="58" ry="11"/>
      <ellipse class="rim" cx="150" cy="128" rx="58" ry="11" fill="none" stroke-width="3"/>
      <ellipse class="bulb-glow" cx="150" cy="130" rx="46" ry="14" fill="url(#bulbG)"/>
      <circle class="bulb" cx="150" cy="130" r="9"/>

      <!-- pull string -->
      <g class="string-g" id="stringG">
        <line x1="178" y1="136" x2="178" y2="226" stroke="#c99a2e" stroke-width="2" id="stringL"/>
        <circle class="bead" cx="178" cy="230" r="6"/>
        <circle cx="178" cy="230" r="14" fill="transparent"/>
      </g>
    </svg>
    <div class="hint">Pull string to turn on</div>
  </div>

  <form class="card" method="post" autocomplete="on" id="form">
    <h1>Welcome</h1>
    <p class="sub">Sign in to continue to your account</p>

    <div class="tabs" role="tablist">
      <button type="button" class="act" role="tab" aria-selected="true">Sign In</button>
      <button type="button" role="tab" aria-selected="false" id="tabUp">Sign Up</button>
    </div>

    <?php if ($error): ?>
      <p class="err" role="alert"><?= e($error) ?></p>
    <?php endif; ?>

    <?= csrf_field() ?>

    <div class="row"><label for="u">Username or Email</label></div>
    <div class="field">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
      <input id="u" type="text" name="username" placeholder="Enter username or email" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
    </div>

    <div class="row"><label for="p">Password</label><button type="button" class="forgot" id="forgot">Forgot?</button></div>
    <div class="field">
      <svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
      <input id="p" type="password" name="password" placeholder="Enter password" required autocomplete="current-password">
      <button type="button" class="eye" id="eye" aria-label="Show password">
        <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
    </div>

    <label class="rem">
      <input type="checkbox" name="remember" value="1" checked>
      <span class="box"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span>
      Remember me
    </label>

    <button class="submit" type="submit">Sign In
      <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>

    <div class="or">OR CONTINUE WITH</div>
    <div class="social">
      <button type="button" aria-label="Continue with Google" data-s="Google">
        <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.05H12v3.88h5.38a4.6 4.6 0 01-2 3.02v2.5h3.24c1.9-1.75 2.98-4.32 2.98-7.35z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.42l-3.24-2.5c-.9.6-2.04.95-3.38.95-2.6 0-4.8-1.76-5.58-4.12H3.07v2.58A10 10 0 0012 22z"/><path fill="#FBBC05" d="M6.42 13.9a6 6 0 010-3.8V7.52H3.07a10 10 0 000 8.96l3.35-2.58z"/><path fill="#EA4335" d="M12 5.98c1.47 0 2.79.5 3.83 1.5l2.87-2.87C16.96 2.99 14.7 2 12 2A10 10 0 003.07 7.52l3.35 2.58C7.2 7.74 9.4 5.98 12 5.98z"/></svg>
      </button>
      <button type="button" aria-label="Continue with GitHub" data-s="GitHub">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M12 2a10 10 0 00-3.16 19.5c.5.1.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.15-1.1-1.46-1.1-1.46-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.08 2.9.83.09-.65.35-1.08.63-1.33-2.22-.25-4.55-1.1-4.55-4.93 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02a9.5 9.5 0 015 0c1.9-1.29 2.74-1.02 2.74-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0012 2z"/></svg>
      </button>
      <button type="button" aria-label="Continue with Apple" data-s="Apple">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M16.37 12.6c-.03-2.5 2.04-3.7 2.13-3.76-1.16-1.7-2.97-1.93-3.62-1.96-1.54-.16-3 .9-3.78.9-.78 0-1.98-.88-3.26-.86-1.68.03-3.22.97-4.09 2.47-1.74 3.02-.45 7.5 1.25 9.95.83 1.2 1.82 2.55 3.12 2.5 1.25-.05 1.73-.81 3.25-.81 1.51 0 1.94.81 3.27.78 1.35-.02 2.2-1.22 3.02-2.43.95-1.39 1.34-2.74 1.37-2.81-.03-.01-2.63-1-2.66-3.97zM13.9 5.2c.69-.84 1.16-2 1.03-3.16-1 .04-2.2.66-2.92 1.5-.64.74-1.2 1.92-1.05 3.05 1.11.09 2.24-.56 2.94-1.39z"/></svg>
      </button>
    </div>
  </form>
</div>

<div class="toast" id="toast" role="status"></div>

<script>
(function(){
  var stage=document.getElementById('stage'), lamp=document.getElementById('lamp');
  var toast=document.getElementById('toast'), tt;

  /* stars */
  var st=document.getElementById('stars'), html='';
  for(var i=0;i<70;i++){
    html+='<i style="left:'+(Math.random()*100).toFixed(1)+'%;top:'+(Math.random()*100).toFixed(1)+'%;animation-delay:'+(Math.random()*4).toFixed(1)+'s;opacity:'+(.15+Math.random()*.4).toFixed(2)+'"></i>';
  }
  st.innerHTML=html;

  function say(m){toast.textContent=m;toast.classList.add('show');clearTimeout(tt);tt=setTimeout(function(){toast.classList.remove('show')},2400)}

  /* lamp on/off */
  function setOn(on){
    stage.classList.toggle('on',on);
    lamp.setAttribute('aria-pressed',on?'true':'false');
    if(on){setTimeout(function(){var u=document.getElementById('u');if(u&&!u.value)u.focus({preventScroll:true})},700)}
  }
  function toggle(){
    stage.classList.remove('pull'); void stage.offsetWidth; stage.classList.add('pull');
    setOn(!stage.classList.contains('on'));
  }
  lamp.addEventListener('click',toggle);
  lamp.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();toggle()}});

  /* theme dots */
  var dots=document.querySelectorAll('#dots button');
  function theme(t){
    document.body.setAttribute('data-theme',t);
    dots.forEach(function(b){b.classList.toggle('sel',b.dataset.t===t)});
    try{localStorage.setItem('lamp-theme',t)}catch(e){}
  }
  dots.forEach(function(b){b.addEventListener('click',function(){theme(b.dataset.t)})});
  try{var s=localStorage.getItem('lamp-theme'); if(s)theme(s)}catch(e){}

  /* password eye */
  var p=document.getElementById('p');
  document.getElementById('eye').addEventListener('click',function(){
    var show=p.type==='password'; p.type=show?'text':'password';
    this.setAttribute('aria-label',show?'Hide password':'Show password');
  });

  /* items that are not enabled on this site */
  document.getElementById('tabUp').addEventListener('click',function(){say('Sign up is disabled. This login is for admins only.')});
  document.getElementById('forgot').addEventListener('click',function(){say('Please contact the site owner to reset your password.')});
  document.querySelectorAll('.social button').forEach(function(b){b.addEventListener('click',function(){say(b.dataset.s+' login is not enabled.')})});
})();
</script>
<noscript><style>.card{opacity:1!important;transform:none!important;visibility:visible!important}</style></noscript>
</body>
</html>
