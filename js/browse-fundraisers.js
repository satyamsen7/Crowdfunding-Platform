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

  
// Apply filter based on dropdown selection
function applyFilter() {
  const category = document.getElementById('category-filter').value;
  window.location.href = `browse-fundraisers.php?category=${category}`;
}