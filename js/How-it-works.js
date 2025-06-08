// Function to toggle between the register and login forms
function toggleForm() {
    const registerForm = document.getElementById('register-form');
    const loginForm = document.getElementById('login-form');
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    const toggleBtn = document.getElementById('toggleBtn');

    // Show popup and overlay
    overlay.style.display = 'block';
    popup.style.display = 'flex';

    // Toggle form visibility
    if (registerForm.style.display === 'none') {
      registerForm.style.display = 'block';
      loginForm.style.display = 'none';
      toggleBtn.textContent = 'Sign In';
    } else {
      registerForm.style.display = 'none';
      loginForm.style.display = 'block';
      toggleBtn.textContent = 'Sign Up';
    }
  }

  // Close the popup form
  function closeForm() {
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    overlay.style.display = 'none';
    popup.style.display = 'none';
  }


// script.js
document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.swiper-container', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
});
// script.js
document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.swiper-container', {
        loop: true, // Enables infinite looping
        pagination: {
            el: '.swiper-pagination',
            clickable: true, // Pagination dots are clickable
        },
        autoplay: {
            delay: 3000, // Swipes every 3 seconds
            disableOnInteraction: false, // Keeps autoplay even after user interaction
        },
        speed: 800, // Adjust the swipe animation speed (800ms)
    });
});
