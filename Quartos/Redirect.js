 document.querySelectorAll(".btn.book-now").forEach((button) => {
  button.addEventListener("click", function () {
    const loggedIn = this.getAttribute("data-logged-in") === "true";
    if (!loggedIn) {
      window.location.href = "../Login/Login.php";
    } else {
      
    }
  });
}); 