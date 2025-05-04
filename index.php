<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HexShop Loading</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">
  <style>
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes fadeIn {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }

    body {
      margin: 0;
      background: linear-gradient(to bottom right, #f3e8ff, #c7d2fe);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      flex-direction: column;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }

    .spin-slow {
      animation: spin 3s linear infinite;
    }

    .loading-container {
      text-align: center;
      animation: fadeIn 2s ease-out;
    }

    .loading-icon {
      animation: spin 4s linear infinite;
      margin-bottom: 20px;
      color: #7c3aed;
    }

    .loading-text {
      font-weight: 600;
      font-size: 1.5em;
      color: #4b5563;
      letter-spacing: 1px;
      animation: fadeIn 2s ease-out;
    }

    .sub-text {
      font-weight: 400;
      font-size: 1em;
      color: #6b7280;
      margin-top: 10px;
      animation: fadeIn 3s ease-out;
    }

    /* Background Particle Animation */
    .particle {
      position: absolute;
      border-radius: 50%;
      background: #7c3aed;
      animation: particleMove 5s infinite ease-in-out;
    }

    @keyframes particleMove {
      0% { transform: translate(0, 0); }
      50% { transform: translate(100px, 100px); }
      100% { transform: translate(0, 0); }
    }

    .particle:nth-child(odd) {
      animation-duration: 6s;
      animation-delay: 1s;
    }

    .particle:nth-child(even) {
      animation-duration: 7s;
      animation-delay: 2s;
    }

  </style>
  <script>
    // Redirect to main site after 4 seconds
    setTimeout(() => {
      window.location.href = "indexs.php"; // Change to your actual homepage
    }, 4000);
  </script>
</head>
<body>

  <!-- Background Particles -->
  <div class="particle" style="width: 10px; height: 10px; top: 20%; left: 30%;"></div>
  <div class="particle" style="width: 12px; height: 12px; top: 50%; left: 60%;"></div>
  <div class="particle" style="width: 8px; height: 8px; top: 70%; left: 40%;"></div>
  <div class="particle" style="width: 15px; height: 15px; top: 80%; left: 80%;"></div>

  <!-- Loading Container -->
  <div class="loading-container">
    <!-- HexShop Icon -->
    <svg class="loading-icon" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5">
      <path d="M7.5 3h9l4.5 7.8-4.5 7.8h-9L3 10.8 7.5 3z"/>
    </svg>

    <!-- Loading Text -->
    <p class="loading-text">HexShop is loading...</p>

    <!-- Sub Text -->
    <p class="sub-text">Get ready for the best shopping experience.</p>

    <!-- Background Music -->
    <audio autoplay id="loading-audio">
      <source src="your-audio-file.mp3" type="audio/mpeg">
      Your browser does not support audio.
    </audio>
  </div>

</body>
</html>
