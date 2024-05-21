document.querySelectorAll(".book-now").forEach((button) => {
  button.addEventListener("click", function () {
    const loggedIn = this.getAttribute("data-logged-in") === "true";
    if (!loggedIn) {
      window.location.href = "../Login/Login.php";
    } else {
      window.location.href = "../Billing/Billing.php";
    }
  });
});